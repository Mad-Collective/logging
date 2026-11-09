<?php
namespace Cmp\Logging\Monolog;

use Cmp\Logging\LoggerFactoryInterface;
use Cmp\Logging\Monolog\Handler\HandlerBuilderInterface;
use Cmp\Logging\Monolog\Handler\RotatingFileHandlerBuilder;
use Cmp\Logging\Monolog\Handler\StdoutHandlerBuilder;
use Cmp\Logging\Monolog\Handler\SyslogUdpHandlerBuilder;
use Cmp\Logging\Monolog\Logger\SilentLogger;
use Monolog\Formatter\FormatterInterface;
use Monolog\Formatter\JsonFormatter;
use Monolog\Logger;
use Psr\Log\LoggerInterface;

class LoggingFactory implements LoggerFactoryInterface
{
    private string $defaultChannel;
    private string $errorChannel;

    /**
     * @var Logger[]
     */
    private array $loggerChannels = [];

    /**
     * @var HandlerBuilderInterface[]
     */
    private array $handlerBuilders = [];

    /**
     * @var HandlerBuilderInterface[]
     */
    private array $errorHandlerBuilders = [];
    private FormatterInterface $formatter;
    private array $processors = [];

    /**
     * LoggingFactory constructor.
     *
     * @param string $defaultChannel
     * @param string $errorChannel
     * @param FormatterInterface|null $formatter Defaults to JsonFormatter
     */
    public function __construct(string $defaultChannel, string $errorChannel = 'error', ?FormatterInterface $formatter = null)
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
    public function addRotatingFileHandlerBuilder($directoryPath, $dateFormat, $maxFiles, $fileName, $filenameFormat, $level): void
    {
        $handler = new RotatingFileHandlerBuilder($directoryPath, $dateFormat, $maxFiles, $fileName, $filenameFormat, $level);
        $this->handlerBuilders[] = $handler;
    }

    /**
     * @param $syslogUdpHost
     * @param $syslogUdpPort
     * @param $level
     */
    public function addSyslogUdpHandlerBuilder($syslogUdpHost, $syslogUdpPort, $level): void
    {
        $this->handlerBuilders[] = new SyslogUdpHandlerBuilder($syslogUdpHost, $syslogUdpPort, $level);
    }

    /**
     * @param $level
     */
    public function addStdoutHandlerBuilder($level): void
    {
        $this->handlerBuilders[] = new StdoutHandlerBuilder($level);
    }

    /**
     * @param HandlerBuilderInterface $handlerBuilder
     *
     * @return void
     */
    public function addErrorHandlerBuilder(HandlerBuilderInterface $handlerBuilder): void
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
    public function addHandlerBuilder(HandlerBuilderInterface $handlerBuilder): void
    {

        $this->handlerBuilders[] = $handlerBuilder;
    }

    /**
     * @param callable $processor
     */
    public function addProcessor(callable $processor): void
    {
        $this->processors[] = $processor;
    }

    /**
     * @inheritDoc
     */
    public function get($channel = null): Logger|LoggerInterface|SilentLogger
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
     * @return LoggerInterface|SilentLogger
     */
    private function createLogger(string $channel): LoggerInterface|SilentLogger
    {
        $handlers = [];

        foreach ($this->handlerBuilders as $handlerBuilder) {
            $handler = $handlerBuilder->build($channel, $this->formatter, $this->processors);
            $handlers[] = $handler;
        }

        return new SilentLogger($channel, $handlers);
    }
}