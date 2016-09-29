# Pluggit Logging

Logging is a logger factory system, which gives you ability to changer logger easily.

## Instalation

Add this repo to your composer.json

````json
"repositories": {
  "pluggit/logging": {
    "type": "vcs",
    "url": "git@github.com:CMProductions/logging.git"
  }
}
````

Then require it as usual:

``` bash
composer require "pluggit/logging"
```

## Monolog
This library uses Monolog logging system. You need to install the library:

``` bash
composer require monolog/monolog ^1.2
```

To create factory, set default channel name as first parameter and Formatter as second one.
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
use Monolog\Logger;

$logger = new LoggingFactory('wellhello', new JsonFormatter(true));
//addRotatingFileHandlerBuilder(directory path, date format, max files number, file name, file name format, level)
$logger->addRotatingFileHandlerBuilder('directory/path','Y-m-d', 14, '{channel}.log', '{date}_{filename}', Logger::ERROR);
```
- SyslogUdpHandler
```php
<?php
use Cmp\Logging\Monolog\LoggingFactory;
use Monolog\Formatter\JsonFormatter(true);

$logger = new LoggingFactory('wellhello', new JsonFormatter(true));
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

$logger = new LoggingFactory('wellhello', new JsonFormatter(true));
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

$logger = new LoggingFactory('wellhello', new JsonFormatter(true));
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

$logger = new LoggingFactory('wellhello', new JsonFormatter(true));
//addProcessor(callable $processor)
$logger->addProcessor(new GitProcessor(Logger:DEBUG));
```
