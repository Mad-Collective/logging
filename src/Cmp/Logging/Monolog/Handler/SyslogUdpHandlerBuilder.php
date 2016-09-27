<?php
namespace Cmp\Logging\Monolog\Handler;

use Monolog\Formatter\FormatterInterface;
use Monolog\Handler\SyslogUdpHandler;

class SyslogUdpHandlerBuilder implements HandlerBuilderInterface
{
    private $syslogUdpHandler;

    private $syslogUdpHost;

    private $syslogUdpPort;

    private $level;

    /**
     * SyslogUdpHandlerBuilder constructor.
     *
     * @param $syslogUdpHost
     * @param $syslogUdpPort
     * @param $level
     */
    public function __construct($syslogUdpHost, $syslogUdpPort, $level)
    {
        $this->syslogUdpHost = $syslogUdpHost;
        $this->syslogUdpPort = $syslogUdpPort;
        $this->level = $level;
    }


    /**
     * @inheritDoc
     */
    public function build($channelName, $processors = [], FormatterInterface $formatter)
    {
        if (!$this->syslogUdpHandler) {
            if (empty($this->syslogUdpHost)) {
                return false;
            }
            $this->syslogUdpHandler = new SyslogUdpHandler($this->syslogUdpHost, $this->syslogUdpPort);
            $this->syslogUdpHandler->setLevel($this->level);
            $this->syslogUdpHandler->setFormatter($formatter);
            array_map([$this->syslogUdpHandler, 'pushProcessor'], $processors);
        }
        return $this->syslogUdpHandler;
    }

}