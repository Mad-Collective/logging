<?php

namespace spec\Cmp\Logging\Monolog\Handler;

use Monolog\Formatter\FormatterInterface;
use Monolog\Handler\RotatingFileHandler;
use Monolog\Logger;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class RotatingFileHandlerBuilderSpec extends ObjectBehavior
{
    function let()
    {
        $this->beConstructedWith('log', 'Y-m-d', '{channel}.log', 14, '{date}_{filename}', Logger::NOTICE);
        if( ! ini_get('date.timezone') )
        {
            date_default_timezone_set('UTC');
        }
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Cmp\Logging\Monolog\Handler\RotatingFileHandlerBuilder');
    }

    function it_should_build_the_handler(FormatterInterface $formatter)
    {
        $this->build('test', $formatter, [])->shouldReturnAnInstanceOf('Monolog\Handler\RotatingFileHandler');
    }
}
