<?php

namespace spec\Cmp\Logging\Monolog\Handler;

use Monolog\Formatter\FormatterInterface;
use Monolog\Logger;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class SyslogUdpHandlerBuilderSpec extends ObjectBehavior
{
    function let()
    {
        $this->beConstructedWith('host', '80', 100);
        if( ! ini_get('date.timezone') )
        {
            date_default_timezone_set('UTC');
        }
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Cmp\Logging\Monolog\Handler\SyslogUdpHandlerBuilder');
    }

    function it_should_build_the_handler(FormatterInterface $formatter)
    {
        $this->build('test', $formatter, [])->shouldReturnAnInstanceOf('Monolog\Handler\SyslogUdpHandler');
    }
}
