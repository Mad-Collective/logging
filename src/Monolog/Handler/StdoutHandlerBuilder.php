<?php
namespace Cmp\Logging\Monolog\Handler;

use Monolog\Formatter\FormatterInterface;
use Monolog\Handler\AbstractHandler;
use Monolog\Handler\StreamHandler;

class StdoutHandlerBuilder implements HandlerBuilderInterface
{
    /**
     * @var StreamHandler
     */
    private $handler;

    /**
     * @var integer
     */
    private $level;

    /**
     * StdoutHandlerBuilder constructor.
     *
     * @param integer $level
     */
    public function __construct($level)
    {
        $this->level = $level;
    }

    /**
     * @param string             $channelName
     * @param FormatterInterface $formatter
     * @param array              $processors
     *
     * @return AbstractHandler|StreamHandler
     *
     * @throws \Exception
     */
    public function build($channelName, FormatterInterface $formatter, $processors = [])
    {
        if (!$this->handler) {
            $this->handler = new StreamHandler("php://stdout");
            $this->handler->setLevel($this->level);
            $this->handler->setFormatter($formatter);
            array_map([$this->handler, 'pushProcessor'], $processors);
        }
        return $this->handler;
    }
}
