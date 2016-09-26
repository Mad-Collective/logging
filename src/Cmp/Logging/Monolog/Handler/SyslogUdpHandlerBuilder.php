<?php
/**
 * Created by PhpStorm.
 * User: jaroslawgabara
 * Date: 22/09/16
 * Time: 16:14
 */

namespace Cmp\Logging\Monolog\Handler;


use Monolog\Formatter\FormatterInterface;
use Monolog\Handler\SyslogUdpHandler;

class SyslogUdpHandlerBuilder implements HandlerBuilderInterface
{
    private $syslogUdpHandler;

    private $syslogUdpHost;

    private $syslogUdpPort;

    private $logLevel;

    /**
     * SyslogUdpHandlerBuilder constructor.
     *
     * @param $syslogUdpHandler
     * @param $syslogUdpHost
     * @param $syslogUdpPort
     */
    public function __construct($syslogUdpHost, $syslogUdpPort)
    {
        $this->syslogUdpHost = $syslogUdpHost;
        $this->syslogUdpPort = $syslogUdpPort;
    }


    /**
     * @inheritDoc
     */
    public function build($channelName, $level, $processors = [], FormatterInterface $formatter)
    {
        if (!$this->syslogUdpHandler) {
            if (empty($this->syslogUdpHost)) {
                return false;
            }
            $this->syslogUdpHandler = new SyslogUdpHandler($this->syslogUdpHost, $this->syslogUdpPort);
            $this->syslogUdpHandler->setLevel($level);
            $this->syslogUdpHandler->setFormatter($formatter);
            array_map([$this->syslogUdpHandler, 'pushProcessor'], $processors);
        }
        return $this->syslogUdpHandler;
    }

}