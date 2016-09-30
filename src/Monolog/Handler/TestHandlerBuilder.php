<?php
namespace Cmp\Logging\Monolog\Handler;

use Monolog\Formatter\FormatterInterface;
use Monolog\Handler\TestHandler;

class TestHandlerBuilder implements HandlerBuilderInterface
{
    /**
     * @var TestHandler
     */
    private $handler;

    /**
     * @inheritDoc
     */
    public function build($channelName, FormatterInterface $formatter, $processors = [])
    {
        if (is_null($this->handler)) {
            $this->handler = new TestHandler();
        }
        return $this->handler;
    }
}
