<?php
namespace Cmp\Logging\Monolog\Handler;

use Monolog\Formatter\FormatterInterface;
use Monolog\Handler\TestHandler;

class TestHandlerBuilder implements HandlerBuilderInterface
{
    /**
     * @inheritDoc
     */
    public function build($channelName, FormatterInterface $formatter, $processors = [])
    {
        return new TestHandler();
    }
}
