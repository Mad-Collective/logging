<?php
namespace Cmp\Logging\Monolog;

use Cmp\Logging\LoggerFactoryInterface;
use Cmp\Logging\Monolog\Handler\HandlerBuilderInterface;
use Cmp\Logging\Monolog\Handler\RotatingFileHandlerBuilder;
use Cmp\Logging\Monolog\Handler\StderrHandlerBuilder;
use Cmp\Logging\Monolog\Handler\StdoutHandlerBuilder;
use Cmp\Logging\Monolog\Handler\SyslogUdpHandlerBuilder;
use Cmp\Logging\Monolog\Logger\SilentLogger;
use Monolog\Formatter\FormatterInterface;
use Monolog\Formatter\JsonFormatter;
use Monolog\Logger;
use Psr\Log\LoggerInterface;

class LoggingFactory implements LoggerFactoryInterface
{
    /**
     * @var string
     */
    private $defaultChannel;

    /**
     * @var string
     */
    private $errorChannel;

    /**
     * @var Logger[]
     */
    private $loggerChannels = [];

    /**
     * @var HandlerBuilderInterface[]
     */
    private $handlerBuilders = [];

    /**
     * @var HandlerBuilderInterface[]
     */
    private $errorHandlerBuilders = [];

    /**
     * @var FormatterInterface
     */
    private $formatter;

    /**
     * @var array
     */
    private $processors = [];

    /**
     * LoggingFactory constructor.
     *
     * @param string             $defaultChannel
     * @param string             $errorChannel
     * @param FormatterInterface $formatter      Defaults to JsonFormatter
     */
    public function __construct($defaultChannel, $errorChannel = 'error', FormatterInterface $formatter = null)
    {
        if (is_null($formatter)) {
            $formatter = new JsonFormatter();
        }

        $this->defaultChannel = $defaultChannel;
        $this->formatter = $formatter;
        $this->errorChannel = $errorChannel;
    }


    /**
     * @param $directoryPath
     * @param $dateFormat
     * @param $maxFiles
     * @param $fileName
     * @param $filenameFormat
     * @param $level
     */
    public function addRotatingFileHandlerBuilder($directoryPath, $dateFormat, $maxFiles, $fileName, $filenameFormat, $level)
    {
        $handler = new RotatingFileHandlerBuilder($directoryPath, $dateFormat, $maxFiles, $fileName, $filenameFormat, $level);
        $this->handlerBuilders[] = $handler;
    }

    /**
     * @param $syslogUdpHost
     * @param $syslogUdpPort
     * @param $level
     */
    public function addSyslogUdpHandlerBuilder($syslogUdpHost, $syslogUdpPort, $level)
    {
        $this->handlerBuilders[] = new SyslogUdpHandlerBuilder($syslogUdpHost, $syslogUdpPort, $level);
    }

    /**
     * @param $level
     */
    public function addStdoutHandlerBuilder($level)
    {
        $this->handlerBuilders[] = new StdoutHandlerBuilder($level);
    }

    /**
     * @param $level
     */
    public function addStderrHandlerBuilder($level)
    {
        $this->handlerBuilders[] = new StderrHandlerBuilder($level);
    }

    /**
     * @param HandlerBuilderInterface $handlerBuilder
     *
     * @return mixed
     */
    public function addErrorHandlerBuilder(HandlerBuilderInterface $handlerBuilder)
    {
        $handler = $handlerBuilder->build($this->errorChannel, $this->formatter, $this->processors);
        if (!isset($this->loggerChannels[$this->errorChannel])) {
            $this->loggerChannels[$this->errorChannel] = new Logger($this->errorChannel, [$handler]);
        } else {
            $this->loggerChannels[$this->errorChannel]->pushHandler($handler);
        }
    }

    /**
     * @param HandlerBuilderInterface $handlerBuilder
     */
    public function addHandlerBuilder(HandlerBuilderInterface $handlerBuilder)
    {

        $this->handlerBuilders[] = $handlerBuilder;
    }

    /**
     * @param callable $processor
     */
    public function addProcessor(callable $processor)
    {
        $this->processors[] = $processor;
    }

    /**
     * @inheritDoc
     */
    public function get($channel = null)
    {
        if (!isset($channel)) {
            $channel = $this->defaultChannel;
        }

        if (!isset($this->loggerChannels[$channel])) {
            $this->loggerChannels[$channel] = $this->createLogger($channel);
        }

        return $this->loggerChannels[$channel];
    }

    /**
     * Creates a logger with the given channel name
     *
     * @param string $channel
     *
     * @return LoggerInterface
     */
    private function createLogger($channel)
    {
        $handlers = [];

        foreach ($this->handlerBuilders as $handlerBuilder) {
            $handler = $handlerBuilder->build($channel, $this->formatter, $this->processors);
            $handlers[] = $handler;
        }

        return new SilentLogger($channel, $handlers);
    }
}