# Pluggit Logging

[![Build Status](https://scrutinizer-ci.com/g/CMProductions/logging/badges/build.png?b=master&s=e676eae45c0fea0a0da4827bf03eecf796ab40d7)](https://scrutinizer-ci.com/g/CMProductions/logging/build-status/master)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/CMProductions/logging/badges/quality-score.png?b=master&s=294d50bdc47ca9a1454758fda05bb5a3a19a0dbe)](https://scrutinizer-ci.com/g/CMProductions/logging/?branch=master)

_Logging_ gives you a simple abstraction compatible with [PSR-3 logger interface](http://www.php-fig.org/psr/psr-3/) that you can connect to a different backend to customize your logging needs

Logging provides a thin PSR-7 interface abstraction to connect any logging facility behind it.

## Installation

Require the library as usual:

``` bash
composer require "pluggit/logging"
```

## Monolog
This library provides [Monolog](https://github.com/Seldaek/monolog) as its main logging backend. You will need to install the library manually:

``` bash
composer require monolog/monolog ^1.2
```

To create factory, set default channel name as first parameter and Formatter as second one.
```php
<?php
use Cmp\Logging\Monolog\LoggingFactory;
use Monolog\Formatter\JsonFormatter(true);
$logger = new LoggingFactory('wellhello', 'error_channel', new JsonFormatter(true));
```
## Handlers
The library provides a factory to ease the addition of some handlers to the logger objects, 

### Built in handlers

- RotatingFileHandler  
This handler will write the log messages to a file, rotating the name depending on the given date format
```php
<?php
use Cmp\Logging\Monolog\LoggingFactory;
use Monolog\Formatter\JsonFormatter(true);
use Monolog\Logger;

$logger = new LoggingFactory('wellhello', 'error_channel', new JsonFormatter(true));

// addRotatingFileHandlerBuilder(directory path, date format, max files number, file name, file name format, level)
$logger->addRotatingFileHandlerBuilder('directory/path','Y-m-d', 14, '{channel}.log', '{date}_{filename}', Logger::ERROR);
```
- SyslogUdpHandler  
This handler will send a UDP packet with the log message, useful to send messages to 3rd party log platforms or storage servers (like ElasticSearch)
```php
<?php
use Cmp\Logging\Monolog\LoggingFactory;
use Monolog\Formatter\JsonFormatter(true);

$logger = new LoggingFactory('wellhello', 'error_channel', new JsonFormatter(true));

//addSyslogUdpHandlerBuilder(syslog UDP Host, syslog UDP Port, level)
$logger->addSyslogUdpHandlerBuilder('12.34.56.78', '90', Logger::ERROR);
```
### Adding custom handler
To add custom handler you should write handler builder implementing HandlerBuilderInterface:
```php
<?php
namespace Cmp\Logging\Monolog\Handler;

use Monolog\Formatter\FormatterInterface;
use Monolog\Handler\AbstractHandler;

interface HandlerBuilderInterface
{
    /**
     * @param string             $channelName
     * @param array              $processors
     * @param FormatterInterface $formatter
     *
     * @return AbstractHandler
     */
    public function build($channelName, FormatterInterface $formatter, $processors = []);
}
```
The handler builder should be added using addHandlerBuilder method:
```php
<?php
use Cmp\Logging\Monolog\LoggingFactory;
use Monolog\Formatter\JsonFormatter(true);
use Customer\Namespace\CustomHandlerBuilder;

$logger = new LoggingFactory('wellhello', 'error_channel', new JsonFormatter(true));
$handlerBuilder = new CustomHandlerBuilder($param1, $param2);
$logger->addHandlerBuilder($handlerBuilder);
```
### Error handler
To add default error handler use addErrorHandlerBuilder method.
```php
<?php
use Cmp\Logging\Monolog\LoggingFactory;
use Monolog\Formatter\JsonFormatter(true);
use Customer\Namespace\CustomHandlerBuilder;

$logger = new LoggingFactory('wellhello', 'error_channel', new JsonFormatter(true));
$handlerBuilder = new CustomHandlerBuilder($param1, $param2);
$logger->addErrorHandlerBuilder($handlerBuilder);
```
## Processors
```php
<?php
use Cmp\Logging\Monolog\LoggingFactory;
use Monolog\Formatter\JsonFormatter(true);
use Customer\Namespace\CustomHandlerBuilder;
use Monolog\Logger\GitProcessor;

$logger = new LoggingFactory('wellhello', 'error_channel', new JsonFormatter(true));
//addProcessor(callable $processor)
$logger->addProcessor(new GitProcessor(Logger:DEBUG));
```
