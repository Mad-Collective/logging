<?php

namespace spec\Cmp\Logging\Monolog;

use Cmp\Logging\Monolog\Handler\HandlerBuilderInterface;
use Cmp\Logging\Monolog\Handler\RotatingFileHandlerBuilder;
use Cmp\Logging\Monolog\Handler\SyslogUdpHandlerBuilder;
use Monolog\Formatter\FormatterInterface;
use Monolog\Handler\HandlerInterface;
use Monolog\Handler\RotatingFileHandler;
use Monolog\Handler\SyslogUdpHandler;
use Monolog\Logger;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class LoggingFactorySpec extends ObjectBehavior
{
    function let(FormatterInterface $formatter)
    {
        $this->beConstructedWith('test', 'error', $formatter);
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
        $this->beConstructedWith('test', 'error', $formatter);
        $channelName = 'test';
        $handlerBuilder->build($channelName, $formatter, [])->willReturn($handler);
        $this->addHandlerBuilder($handlerBuilder);
        $this->get($channelName)->getHandlers()->shouldReturn([$handler]);
    }

    function it_should_return_many_handlers(
        RotatingFileHandlerBuilder $rotatingFileHandlerBuilder,
        SyslogUdpHandlerBuilder $syslogUdpHandlerBuilder,
        RotatingFileHandler $rotatingFileHandler,
        SyslogUdpHandler $syslogUdpHandler,
        FormatterInterface $formatter
    ) {
        $this->beConstructedWith('test', 'error', $formatter);
        $channelName = 'test';
        $rotatingFileHandlerBuilder->build($channelName, $formatter, [])->willReturn($rotatingFileHandler);
        $syslogUdpHandlerBuilder->build($channelName, $formatter, [])->willReturn($syslogUdpHandler);
        $this->addHandlerBuilder($rotatingFileHandlerBuilder);
        $this->addHandlerBuilder($syslogUdpHandlerBuilder);
        $this->get($channelName)->getHandlers()->shouldReturn([$rotatingFileHandler, $syslogUdpHandler]);
    }
    
    function it_should_build_rotating_file_handler()
    {
        $this->addRotatingFileHandlerBuilder('log', 'Y-m-d', 14, '{channel}.log', '{date}_{filename}', Logger::NOTICE);
        $this->get('test')->getHandlers()[0]->shouldBeAnInstanceOf('Monolog\Handler\RotatingFileHandler');
    }

    function it_should_build_syslog_udp_handler()
    {
        $this->addSyslogUdpHandlerBuilder('123.34.4.45', 89, Logger::NOTICE);
        $this->get('test')->getHandlers()[0]->shouldBeAnInstanceOf('Monolog\Handler\SyslogUdpHandler');
    }

    function it_should_build_stdout_handler()
    {
        $this->addStdoutHandlerBuilder(Logger::NOTICE);
        $this->get('test')->getHandlers()[0]->shouldBeAnInstanceOf('Monolog\Handler\StreamHandler');
    }

    function it_should_build_all_handlers()
    {
        $this->addSyslogUdpHandlerBuilder('123.34.4.45', 89, Logger::NOTICE);
        $this->addRotatingFileHandlerBuilder('log', 'Y-m-d', 14, '{channel}.log', '{date}_{filename}', Logger::NOTICE);
        $this->addStdoutHandlerBuilder(Logger::NOTICE);
        $this->get('test')->getHandlers()[0]->shouldBeAnInstanceOf('Monolog\Handler\SyslogUdpHandler');
        $this->get('test')->getHandlers()[1]->shouldBeAnInstanceOf('Monolog\Handler\RotatingFileHandler');
        $this->get('test')->getHandlers()[2]->shouldBeAnInstanceOf('Monolog\Handler\StreamHandler');
    }
}
