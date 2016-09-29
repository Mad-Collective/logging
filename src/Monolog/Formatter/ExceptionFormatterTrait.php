<?php
namespace Cmp\Logging\Monolog\Formatter;

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
    protected function normalizeException(\Exception $e)
    {
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
