<?php
namespace Cmp\Logging\Monolog;

use Cmp\Logging\LoggerFactoryInterface;
use Cmp\Logging\Monolog\Handler\HandlerBuilderInterface;
use Cmp\Logging\Monolog\Handler\RotatingFileHandlerBuilder;
use Cmp\Logging\Monolog\Handler\SyslogUdpHandlerBuilder;
use Cmp\Logging\Monolog\Logger\SilentLogger;
use Monolog\Formatter\FormatterInterface;
use Monolog\Handler\AbstractHandler;
use Monolog\Logger;
use Psr\Log\LoggerInterface;

class LoggingFactory implements LoggerFactoryInterface
{
    /**
     * @var string
     */
    private $defaultChannel = '';

    /**
     * @var array
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
     * @param FormatterInterface $formatter
     */
    public function __construct($defaultChannel = 'default', FormatterInterface $formatter)
    {
        $this->defaultChannel = $defaultChannel;
        $this->formatter = $formatter;
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
     * @param HandlerBuilderInterface $handlerBuilder
     *
     * @return mixed
     */
    public function addErrorHandlerBuilder(HandlerBuilderInterface $handlerBuilder)
    {
        $this->errorHandlerBuilders[] = $handlerBuilder;
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
        
        foreach ($this->errorHandlerBuilders as $errorHandlerBuilder)
        {
            $handler = $errorHandlerBuilder->build($channel, $this->formatter, $this->processors);
            $handler->setLevel(Logger::ERROR);
            $handlers[] = $handler;
        }

        return new Logger($channel, $handlers);
    }
}