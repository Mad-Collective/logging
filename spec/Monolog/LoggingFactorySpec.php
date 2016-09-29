<?php

namespace spec\Cmp\Logging\Monolog;

use Cmp\Logging\Monolog\Handler\HandlerBuilderInterface;
use Cmp\Logging\Monolog\Handler\RotatingFileHandlerBuilder;
use Cmp\Logging\Monolog\Handler\SyslogUdpHandlerBuilder;
use Monolog\Formatter\FormatterInterface;
use Monolog\Handler\HandlerInterface;
use Monolog\Handler\RotatingFileHandler;
use Monolog\Logger;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class LoggingFactorySpec extends ObjectBehavior
{
    function let(FormatterInterface $formatter)
    {
        $this->beConstructedWith('test', $formatter);
        if( ! ini_get('date.timezone') )
        {
            date_default_timezone_set('UTC');
        }
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Cmp\Logging\Monolog\LoggingFactory');
    }
    
    function it_should_return_logger_with_appropriate_channel()
    {
        $this->get()->getName()->shouldReturn('test');
        $this->get('test_a')->getName()->shouldReturn('test_a');
    }
    
    function it_should_return_handler(HandlerBuilderInterface $handlerBuilder, HandlerInterface $handler, FormatterInterface $formatter)
    {
        $this->beConstructedWith('test', $formatter);
        $channelName = 'test';
        $handlerBuilder->build($channelName, $formatter, [])->willReturn($handler);
        $this->addHandlerBuilder($handlerBuilder);
        $this->get($channelName)->getHandlers()->shouldReturn([$handler]);
    }

    function it_should_return_many_handlers(RotatingFileHandlerBuilder $rotatingFileHandlerBuilder, SyslogUdpHandlerBuilder $syslogUdpHandlerBuilder, RotatingFileHandler $rotatingFileHandler, FormatterInterface $formatter)
    {
        $this->beConstructedWith('test', $formatter);
        $channelName = 'test';
        $rotatingFileHandlerBuilder->build($channelName, $formatter, [])->willReturn($rotatingFileHandler);
        $syslogUdpHandlerBuilder->build($channelName, $formatter, [])->willReturn($syslogUdpHandlerBuilder);
        $this->addHandlerBuilder($rotatingFileHandlerBuilder);
        $this->addHandlerBuilder($syslogUdpHandlerBuilder);
        $this->get($channelName)->getHandlers()->shouldReturn([$rotatingFileHandler, $syslogUdpHandlerBuilder]);
    }
    
    function it_should_build_rotating_file_handler()
    {
        $this->addRotatingFileHandlerBuilder('log', 'Y-m-d', '{channel}.log', 14, '{date}_{filename}', Logger::NOTICE);
        $this->get('test')->getHandlers()[0]->shouldBeAnInstanceOf('Monolog\Handler\RotatingFileHandler');
    }

    function it_should_build_syslog_udp_handler()
    {
        $this->addSyslogUdpHandlerBuilder('123.34.4.45', 89, Logger::NOTICE);
        $this->get('test')->getHandlers()[0]->shouldBeAnInstanceOf('Monolog\Handler\SyslogUdpHandler');
    }

    function it_should_build_two_handlers()
    {
        $this->addSyslogUdpHandlerBuilder('123.34.4.45', 89, Logger::NOTICE);
        $this->addRotatingFileHandlerBuilder('log', 'Y-m-d', '{channel}.log', 14, '{date}_{filename}', Logger::NOTICE);
        $this->get('test')->getHandlers()[0]->shouldBeAnInstanceOf('Monolog\Handler\SyslogUdpHandler');
        $this->get('test')->getHandlers()[1]->shouldBeAnInstanceOf('Monolog\Handler\RotatingFileHandler');
    }
}
