<?php
namespace Cmp\Logging\Monolog\Handler;

use Monolog\Formatter\FormatterInterface;
use Monolog\Handler\AbstractHandler;
use Monolog\Handler\HandlerInterface;

interface HandlerBuilderInterface
{
    /**
     * @param string             $channelName
     * @param FormatterInterface $formatter
     * @param array              $processors
     *
     * @return AbstractHandler
     */
    public function build($channelName, FormatterInterface $formatter, $processors = []): AbstractHandler;
}