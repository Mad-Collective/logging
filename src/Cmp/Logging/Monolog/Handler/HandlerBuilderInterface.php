<?php
namespace Cmp\Logging\Monolog\Handler;

use Monolog\Formatter\FormatterInterface;
use Monolog\Handler\AbstractHandler;

interface HandlerBuilderInterface
{
    /**
     * @param string             $channelName
     * @param array              $processors
     * @param FormatterInterface $formatter
     *
     * @return AbstractHandler
     */
    public function build($channelName, $processors = [], FormatterInterface $formatter);
}