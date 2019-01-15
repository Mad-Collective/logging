<?php
namespace spec\Cmp\Logging\Monolog\Handler;

use Monolog\Formatter\FormatterInterface;
use Monolog\Logger;
use PhpSpec\ObjectBehavior;

class StdoutHandlerBuilderSpec extends ObjectBehavior
{
    function let()
    {
        $this->beConstructedWith( Logger::NOTICE);
        if( ! ini_get('date.timezone') )
        {
            date_default_timezone_set('UTC');
        }
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Cmp\Logging\Monolog\Handler\StdoutHandlerBuilder');
    }

    function it_should_build_the_handler(FormatterInterface $formatter)
    {
        $this->build('test', $formatter, [])->shouldReturnAnInstanceOf('Monolog\Handler\StreamHandler');
    }
}