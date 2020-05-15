<?php
namespace Cmp\Logging\Monolog\Handler;

use Monolog\Formatter\FormatterInterface;
use Monolog\Handler\AbstractHandler;
use Monolog\Handler\HandlerInterface;
use Monolog\Handler\SyslogUdpHandler;

class SyslogUdpHandlerBuilder implements HandlerBuilderInterface
{
    /**
     * @var AbstractHandler
     */
    private $syslogUdpHandler;

    /**
     * @var string
     */
    private $syslogUdpHost;

    /**
     * @var integer
     */
    private $syslogUdpPort;

    /**
     * @var integer
     */
    private $level;

    /**
     * SyslogUdpHandlerBuilder constructor.
     *
     * @param string  $syslogUdpHost
     * @param integer  $syslogUdpPort
     * @param integer $level
     */
    public function __construct(string $syslogUdpHost, int $syslogUdpPort, int $level)
    {
        $this->syslogUdpHost = $syslogUdpHost;
        $this->syslogUdpPort = $syslogUdpPort;
        $this->level = $level;
    }

    /**
     * @inheritDoc
     */
    public function build($channelName, FormatterInterface $formatter, $processors = []): AbstractHandler
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