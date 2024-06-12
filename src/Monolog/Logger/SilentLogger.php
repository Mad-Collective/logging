<?php

namespace Cmp\Logging\Monolog\Logger;

use Monolog\Logger;

class SilentLogger extends Logger
{
    protected $defaultContext = [];

    /**
     * Adds a log record.
     *
     * @param int $level
     * @param string $message
     * @param array $context
     * @param \DateTimeImmutable|null $datetime
     * @return Boolean Whether the record has been processed
     */
    public function addRecord(
        int $level,
        string $message,
        array $context = array(),
        \DateTimeImmutable $datetime = null
    ): bool
    {
        try{
            $context = array_merge($this->defaultContext, $context);
            return parent::addRecord($level, $message, $context);

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
