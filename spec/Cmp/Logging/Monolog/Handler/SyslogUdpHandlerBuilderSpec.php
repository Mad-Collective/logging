<?php

namespace spec\Cmp\Logging\Monolog\Handler;

use Monolog\Formatter\FormatterInterface;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class SyslogUdpHandlerBuilderSpec extends ObjectBehavior
{
    function let()
    {
        $this->beConstructedWith('log', 'Y-m-d', '{channel}.log', 14, '{date}_{filename}');
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Cmp\Logging\Monolog\Handler\SyslogUdpHandlerBuilder');
    }

    function it_should_build_the_handler(FormatterInterface $formatter)
    {
        $this->build('test', 'test', [], $formatter)->shouldReturnAnInstanceOf('Monolog\Handler\SyslogUdpHandler');
    }
}