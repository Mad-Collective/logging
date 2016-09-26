<?php

namespace spec\Cmp\Logging\Monolog\Handler;

use Monolog\Formatter\FormatterInterface;
use Monolog\Handler\RotatingFileHandler;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class RotatingFileHandlerBuilderSpec extends ObjectBehavior
{
    function let()
    {
        $this->beConstructedWith('log', 'Y-m-d', '{channel}.log', 14, '{date}_{filename}');
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Cmp\Logging\Monolog\Handler\RotatingFileHandlerBuilder');
    }

    function it_should_build_the_handler(FormatterInterface $formatter)
    {
        $this->build('test', 'test', [], $formatter)->shouldReturnAnInstanceOf('Monolog\Handler\RotatingFileHandler');
    }
}
