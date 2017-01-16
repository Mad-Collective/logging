<?php
namespace Cmp\Logging\Monolog\Exception;

interface LoggableException
{
    /**
     * @return array
     */
    public function getErrors();
}
