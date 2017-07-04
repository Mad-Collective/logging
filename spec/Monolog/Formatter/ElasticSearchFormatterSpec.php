<?php

namespace spec\Cmp\Logging\Monolog\Formatter;

use Monolog\Formatter\JsonFormatter;
use PhpSpec\Exception\Exception;
use PhpSpec\ObjectBehavior;

class ElasticSearchFormatterSpec extends ObjectBehavior
{
    function let()
    {
        $this->beConstructedWith(JsonFormatter::BATCH_MODE_JSON, false);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Cmp\Logging\Monolog\Formatter\ElasticSearchFormatter');
        $this->shouldHaveType('Monolog\Formatter\JsonFormatter');
    }

    function it_should_format()
    {
        $record = [
            'context' => 'my context',
        ];

        $this->format($record)->shouldReturn('{"context":"my context"}');
    }

    function it_should_format_exceptions()
    {
        $record = [
            'context' => [
                'exception' => new \Exception("asdf"),
            ]
        ];

        $this->format($record)->shouldBeString();
    }

    function it_should__format_unknown_exceptions()
    {
        $record = [
            'context' => [
                'exception' => new Exception("asdf"),
            ]
        ];

        $this->format($record)->shouldBeString();
    }
}
