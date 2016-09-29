<?php
namespace Cmp\Logging\Monolog\Formatter;

use Monolog\Formatter\JsonFormatter;

/**
 * Class ElasticSearchFormatter
 *
 * @package Cmp\Logging\Monolog\Formatter
 */
class ElasticSearchFormatter extends JsonFormatter
{
    use ExceptionFormatterTrait;

    /**
     * Formats a log record removing the context if empty.
     *
     * @param array $record A record to format
     *
     * @return mixed The formatted record
     */
    public function format(array $record)
    {
        $this->formatContextException($record);
        if (!empty($record['context'])) {
            $record['context'] = $this->stringify($record['context']);
        }

        return parent::format($this->removeContextFromRecordIfEmpty($record));
    }

    /**
     * Removes Context from record if empty
     *
     * @param array $record
     *
     * @return array
     */
    protected function removeContextFromRecordIfEmpty(array $record)
    {
        if (empty($record['context'])) {
            unset($record['context']);
        }

        return $record;
    }

    /**
     * Return a JSON-encoded array of records.
     *
     * @param array $records
     *
     * @return string
     */
    protected function formatBatchJson(array $records)
    {
        $cleanRecords = array_map(
            function ($record) {
                $this->formatContextException($record);
                if (!empty($record['context'])) {
                    $record['context'] = $this->stringify($record['context']);
                }

                return $this->removeContextFromRecordIfEmpty($record);
            },
            $records
        );

        return parent::formatBatchJson($cleanRecords);
    }

    /**
     * @param $value
     *
     * @return mixed String if the value could be stringified, or false
     */
    private function stringify($value)
    {
        if (is_scalar($value)) {
            if (is_bool($value)) {
                return $value ? 'true' : 'false';
            }

            return (string) $value;
        }
        if (is_array($value)) {
            foreach ($value as &$val) {
                $val = $this->stringify($val);
            }
        } elseif (is_object($value) && (!$value instanceof \JsonSerializable) && method_exists($value, '__toString')) {
            $value = (string) $value;
        }

        return $value;
    }
}