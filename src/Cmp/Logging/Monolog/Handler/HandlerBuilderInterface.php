<?php
namespace Cmp\Logging\Monolog\Handler;

use Monolog\Formatter\FormatterInterface;

interface HandlerBuilderInterface
{
    /**
     * @param string             $channelName
     * @param string             $level
     * @param array              $processors
     * @param FormatterInterface $formatter
     *
     * @return mixed
     */
    public function build($channelName, $level, $processors = [], FormatterInterface $formatter);
}