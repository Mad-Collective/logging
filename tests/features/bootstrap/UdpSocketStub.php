<?php
namespace features\Cmp\Logging;

use Monolog\Handler\SyslogUdp\UdpSocket;

/**
 * Class UdpSocketStub
 *
 * @package features\Cmp\Logging
 */
class UdpSocketStub extends UdpSocket
{
    /**
     * @var array
     */
    protected $lines = [];

    /**
     * @param string $line
     */
    public function write($line)
    {
        $this->lines[] = $line;
    }

    /**
     * @return array
     */
    public function getLines()
    {
        return $this->lines;
    }
}