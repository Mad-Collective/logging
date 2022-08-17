<?php

namespace Cmp\Logging\Monolog\Logger;

use Monolog\DateTimeImmutable;
use Monolog\Logger;

class SilentLogger extends Logger
{
    protected $defaultContext = [];

    /**
     * Adds a log record.
     *
     * @param  integer $level   The logging level
     * @param  string  $message The log message
     * @param  array   $context The log context
     * @return Boolean Whether the record has been processed
     */
    public function addRecord(int $level, string $message, array $context = [], DateTimeImmutable $datetime = null): bool
    {
        try{
            $context = array_merge($this->defaultContext, $context);
            return parent::addRecord($level, $message, $context, $datetime);

        } catch (\Exception $e){
            trigger_error($e, E_USER_WARNING);
            return false;
        }
    }

    /**
     * Add one default context values
     *
     * @param string $key
     * @param mixed $value
     *
     * @return $this
     */
    public function addDefaultContextValue($key, $value)
    {
        $this->defaultContext[$key] = $value;

        return $this;
    }

    /**
     * Adds default context values
     *
     * @param array $defaults
     *
     * @return $this
     */
    public function addDefaultContext(array $defaults)
    {
        $this->defaultContext = array_merge($this->defaultContext, $defaults);

        return $this;
    }
}