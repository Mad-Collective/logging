<?php
namespace Cmp\Logging;

use Cmp\Logging\Exception\HandlerDoesNotExistException;
use Cmp\Logging\Logger\SilentLogger;
use Cmp\Logging\Provider\ProviderInterface;
use Monolog\Formatter\FormatterInterface;
use Monolog\Logger;
use Psr\Log\LoggerInterface;

class LoggingFactory
{
    const LOGGERS = [
        'monolog' => Logger::class
    ];

    /**
     * @var LoggerInterface;
     */
    private $logger;

    private $defaultChannel = 'default';

    private $loggerChannels = [];

    private $handlers = [];

    /**
     * LoggingFactory constructor.
     *
     * @param string $defaultChannel
     * @param null   $logger
     */
    public function __construct($defaultChannel = 'default', $logger = null)
    {
        $this->defaultChannel = $defaultChannel;
        if (!is_null($logger)) {
            $this->logger = $logger;
        }
    }

    public function setLogger(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function setFileHandler($directoryPath, $fileName)
    {

    }

    /**
     * 
     */
    public function setSyslogUdpHandler()
    {

    }

    


    public function setFormatter(FormatterInterface $formatter)
    {
        $this->handler->setFormatter($formatter);
    }

    public function get($channel = null)
    {
        if (!isset($channel)) {
            $channel = $this->defaultChannel;
        }

        if (!isset($this->loggerChannels[$channel])) {
            $this->loggerChannels[$channel] = $this->createLogger($channel);
            $this->loggerChannels[$channel]->addDefaultContext($this->defaults['context']);
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

        $syslogUdpHandler = $this->getSyslogUdpHandler();
        if ($syslogUdpHandler) {
            $handlers[] = $syslogUdpHandler;
        }

        if ($this->defaults['testHandler']) {
            $handlers[] = $this->testHandler;
        }

        $logger = new SilentLogger($channel, $handlers);

        return $logger;
    }




}