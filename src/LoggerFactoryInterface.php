<?php
namespace Cmp\Logging;

use Psr\Log\LoggerInterface;

interface LoggerFactoryInterface
{
    /**
     * @param string|null $channel
     *
     * @return LoggerInterface
     */
    public function get($channel = null);
}