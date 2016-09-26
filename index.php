<?php
require_once __DIR__.'/vendor/autoload.php';

$logger = new \Cmp\Logging\Monolog\LoggingFactory('wellhello', new \Monolog\Formatter\JsonFormatter(true));
$logger->setRotatingFileHandlerConfiguration('log', '{channel}.log', '{date}_{filename}', 'error', 'Y-m-d');
$logger = $logger->get();
$h = $logger->getHandlers();
var_dump($h);
die;
$logger->warning('abc', ['ss' => 'abc']);

die('aa');