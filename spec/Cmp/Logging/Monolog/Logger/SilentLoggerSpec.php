<?php

namespace spec\Cmp\Logging\Monolog\Logger;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class SilentLoggerSpec extends ObjectBehavior
{
    function let()
    {
        $this->beConstructedWith('test', []);
    }
    function it_is_initializable()
    {
        $this->shouldHaveType('Cmp\Logging\Monolog\Logger\SilentLogger');
    }
    
    
}
