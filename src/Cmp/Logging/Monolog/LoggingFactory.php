<?php
namespace Cmp\Logging\Monolog;

use Cmp\Logging\LoggerFactoryInterface;
use Cmp\Logging\Monolog\Handler\HandlerBuilderInterface;
use Cmp\Logging\Monolog\Handler\RotatingFileHandlerBuilder;
use Cmp\Logging\Monolog\Handler\SyslogUdpHandlerBuilder;
use Cmp\Logging\Monolog\Logger\SilentLogger;
use Monolog\Formatter\FormatterInterface;
use Psr\Log\LoggerInterface;

class LoggingFactory implements LoggerFactoryInterface
{
    private $defaultChannel = 'default';

    private $loggerChannels = [];

    /**
     * @var HandlerBuilderInterface[]
     */
    private $handlers = [];

    private $handlerBuilders = [];

    private $formatter;

    private $defaults = [];

    private $processors = [];

    private $context = [];

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
     * @param $fileName
     * @param $filenameFormat
     * @param $level
     * @param $dateFormat
     */
    public function setRotatingFileHandlerConfiguration($directoryPath, $fileName, $filenameFormat, $level, $dateFormat)
    {
        $handler = new RotatingFileHandlerBuilder($directoryPath, $dateFormat, 14, $fileName, $filenameFormat);
        $this->setErrorHandler($handler);
        $this->handlerBuilders[] = $handler;
    }

    /**
     * @param $syslogUdpHandler
     * @param $syslogUdpHost
     * @param $syslogUdpPort
     */
    public function setSyslogUdpHandlerConfiguration($syslogUdpHost, $syslogUdpPort)
    {
        $this->handlerBuilders[] = new SyslogUdpHandlerBuilder($syslogUdpHost, $syslogUdpPort);
    }

    /**
     * @param HandlerBuilderInterface $handlerBuilder
     *
     * @return mixed
     */
    public function setErrorHandler(HandlerBuilderInterface $handlerBuilder)
    {
        $handler = $handlerBuilder->build('error', $this->defaultChannel, $this->processors, $this->formatter);
        $handler->setLevel('error');
        $this->handlers[] = $handler;
    }

    /**
     * @param HandlerBuilderInterface $handlerBuilder
     */
    public function addHandler(HandlerBuilderInterface $handlerBuilder)
    {

        $this->handlerBuilders[] = $handlerBuilder;
    }

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
            $this->loggerChannels[$channel]->addDefaultContext($this->context);
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
        $handlers = $this->handlers;

        foreach ($this->handlerBuilders as $handlerBuilder) {
            $handlers[] = $handlerBuilder->build($channel, $this->defaultChannel, $this->processors, $this->formatter);
        }

        $logger = new SilentLogger($channel, $handlers);

        return $logger;
    }
}