<?php
/**
 * Created by PhpStorm.
 * User: jaroslawgabara
 * Date: 20/09/16
 * Time: 18:00
 */

namespace Cmp\Logging\Handler;


use Monolog\Formatter\FormatterInterface;
use Monolog\Handler\HandlerInterface;

class MonologHandler implements HandlerInterface
{
    /**
     * @inheritDoc
     */
    public function isHandling(array $record)
    {
        // TODO: Implement isHandling() method.
    }

    /**
     * @inheritDoc
     */
    public function handle(array $record)
    {
        // TODO: Implement handle() method.
    }

    /**
     * @inheritDoc
     */
    public function handleBatch(array $records)
    {
        // TODO: Implement handleBatch() method.
    }

    /**
     * @inheritDoc
     */
    public function popProcessor()
    {
        // TODO: Implement popProcessor() method.
    }

    /**
     * @inheritDoc
     */
    public function getFormatter()
    {
        // TODO: Implement getFormatter() method.
    }


    /**
     * @inheritDoc
     */
    public function pushProcessor($callback)
    {
        // TODO: Implement pushProcessor() method.
    }

    /**
     * @inheritDoc
     */
    public function setFormatter(FormatterInterface $formatter)
    {
        // TODO: Implement setFormatter() method.
    }

    /**
     * @inheritDoc
     */
    public static function __callStatic($name, $arguments)
    {
        // TODO: Implement __callStatic() method.
    }


}