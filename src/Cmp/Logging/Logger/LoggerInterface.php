<?php 
namespace Cmp\Logging\Logger;
use Monolog\Handler\HandlerInterface;
use Psr\Log\LoggerInterface as PsrLoggerInterface;

interface LoggerInterface extends PsrLoggerInterface
{
    public function pushHandler(HandlerInterface $handler);


    /**
     * Pops a handler from the stack
     *
     * @return HandlerInterface
     */
    public function popHandler();


    /**
     * @return HandlerInterface[]
     */
    public function getHandlers();


    /**
     * Adds a processor on to the stack.
     *
     * @param callable $callback
     */
    public function pushProcessor($callback);


    /**
     * Removes the processor on top of the stack and returns it.
     *
     * @return callable
     */
    public function popProcessor();


    /**
     * @return callable[]
     */
    public function getProcessors();

}