<?php
namespace Cmp\Logging\Monolog\Formatter;

use Cmp\Logging\Monolog\Exception\LoggableException;

/**
 * Class ExceptionFormatterTrait
 *
 * @package Cmp\Logging\Monolog\Formatter
 */
trait ExceptionFormatterTrait
{
    /**
     * @param \Exception $e
     *
     * @return array
     */
    protected function normalizeException($e, int $depth = 0): array
    {
        // TODO 2.0 only check for Throwable
        if (!$e instanceof \Exception && !$e instanceof \Throwable) {
            throw new \InvalidArgumentException('Exception/Throwable expected, got '.gettype($e).' / '.get_class($e));
        }

        $data = array(
            'class' => get_class($e),
            'message' => $e->getMessage(),
            'code' => $e->getCode(),
            'file' => $e->getFile().':'.$e->getLine(),
        );

        if ($previous = $e->getPrevious()) {
            $data['previous'] = $this->normalizeException($previous);
        } else {
            $trace = $e->getTrace();
            foreach ($trace as $frame) {
                if (isset($frame['file'])) {
                    $data['trace'][] = $frame['file'].':'.$frame['line'];
                } else {
                    unset($frame['args']);
                    $data['trace'][] = json_encode($frame);
                }
            }
        }

        if ($e instanceof LoggableException) {
            $data['errors'] = (array) $e->getErrors();
        }

        return $data;
    }

    /**
     * @param array $record
     */
    protected function formatContextException(array &$record)
    {
        if (isset($record['context']['exception']) && $record['context']['exception'] instanceof \Exception) {
            $record['context']['exception'] = $this->normalizeException($record['context']['exception']);
        }
    }
}
