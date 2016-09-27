# Pluggit Logging

Logging is a logger factory system, which gives you ability to changer logger easily.

## Monolog
This library uses Monolog logging system.  To create factory, set default channel name as first parameter and Formatter as second one.
```php
<?php
use Cmp\Logging\Monolog\LoggingFactory;
use Monolog\Formatter\JsonFormatter(true);
$logger = new LoggingFactory('wellhello', new JsonFormatter(true));
```
## Handlers
### Built in handlers

- RotatingFileHandler


```php
<?php
use Cmp\Logging\Monolog\LoggingFactory;
use Monolog\Formatter\JsonFormatter(true);

$logger = new LoggingFactory('wellhello', new JsonFormatter(true));
//setRotatingFileHandlerConfiguration(directory path, date format, max files number, file name, file name format, default level)
$logger->setRotatingFileHandlerConfiguration('directory/path','Y-m-d', 14, '{channel}.log', '{date}_{filename}', 'error');
```
- SyslogUdpHandler
```php
<?php
use Cmp\Logging\Monolog\LoggingFactory;
use Monolog\Formatter\JsonFormatter(true);

$logger = new LoggingFactory('wellhello', new JsonFormatter(true));
//setSyslogUdpHandlerConfiguration(syslog UDP Host, syslog UDP Port, default channel)
$logger->setSyslogUdpHandlerConfiguration('12.34.56.78', '90', 'error');
```
### Adding custom handler
To add custom handler you should write handler builder implementing HandlerBuilderInterface:
```php
<?php
namespace Cmp\Logging\Monolog\Handler;

use Monolog\Formatter\FormatterInterface;

interface HandlerBuilderInterface
{
    /**
     * @param string             $channelName
     * @param string             $level
     * @param array              $processors
     * @param FormatterInterface $formatter
     *
     * @return HandlerInterface
     */
    public function build($channelName, $level, $processors = [], FormatterInterface $formatter);
}
```
The handler builder should be added using addHandlerBuilder method:
```php
<?php
use Cmp\Logging\Monolog\LoggingFactory;
use Monolog\Formatter\JsonFormatter(true);
use Customer\Namespace\CustomHandlerBuilder;

$logger = new LoggingFactory('wellhello', new JsonFormatter(true));
$handlerBuilder = new CustomHandlerBuilder($param1, $param2);
$logger->addHandlerBuilder($handlerBuilder);
```

## Processors
```php
<?php
use Cmp\Logging\Monolog\LoggingFactory;
use Monolog\Formatter\JsonFormatter(true);
use Customer\Namespace\CustomHandlerBuilder;
use Monolog\Logger\GitProcessor;

$logger = new LoggingFactory('wellhello', new JsonFormatter(true));
//addProcessor(callable $processor)
$logger->addProcessor(new GitProcessor(Logger:DEBUG));
```
