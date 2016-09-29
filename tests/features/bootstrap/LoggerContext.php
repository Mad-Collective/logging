<?php
namespace features\Cmp\Logging;

use Behat\Behat\Context\Context;
use Cmp\Logging\Monolog\Formatter\ElasticSearchFormatter;
use Cmp\Logging\Monolog\Handler\RotatingFileHandlerBuilder;
use Cmp\Logging\Monolog\LoggingFactory;
use Monolog\Handler\SyslogUdpHandler;
use Monolog\Logger;
use Monolog\Processor\MemoryUsageProcessor;
use RuntimeException;

class LoggerContext implements Context
{
    use FileTrait;

    /**
     * @var LoggingFactory
     */
    protected $logger;

    /**
     * @var UdpSocketStub
     */
    protected $udpSocket;

    /**
     * LoggerContext constructor.
     */
    public function __construct()
    {
        $this->logger = new LoggingFactory('wellhello', new ElasticSearchFormatter(true));
        $this->udpSocket = new UdpSocketStub('127.0.0.1', 10);
        if( ! ini_get('date.timezone') )
        {
            date_default_timezone_set('UTC');
        }
    }

    /**
     * @Given I built a logger with rotating file handler
     */
    public function iBuiltALoggerWithRotatingFileHandler()
    {
        $this->removeLogFile('/tmp/log', 'Y-m-d', '{channel}.log', '{date}_{filename}', 'test_channel');
        $this->logger->addRotatingFileHandlerBuilder(
            '/tmp/log',
            'Y-m-d',
            14,
            '{channel}.log',
            '{date}_{filename}',
            Logger::INFO
        );
    }

    /**
     * @When I log warning :log
     */
    public function iLogWarning($log)
    {
        $this->logger->get('test_channel')->info($log);
    }

    /**
     * @Then I should log into file :log
     */
    public function iShouldLogIntoFile($log)
    {
        $content = $this->getLogFileContent('/tmp/log', 'Y-m-d', '{channel}.log', '{date}_{filename}', 'test_channel');
        $content = json_decode($content[0]);
        if ($content->message != $log) {
            throw new RuntimeException("The does not contain log");
        }
        $this->removeLogFile('/tmp/log', 'Y-m-d', '{channel}.log', '{date}_{filename}', 'test_channel');
    }

    /**
     * @Given I built a logger with syslog handler
     */
    public function iBuiltALoggerWithSyslogHandler()
    {
        $this->logger->addSyslogUdpHandlerBuilder('127.0.0.1', 80, Logger::INFO);

        /**
         * @var SyslogUdpHandler[]
         */
        $handlers = $this->logger->get('test_channel')->getHandlers();
        foreach ($handlers as $handler) {
            if ($handler instanceof SyslogUdpHandler) {
                $handler->setSocket($this->udpSocket);
            }
        }
    }

    /**
     * @Then I should log into syslog udp :log
     */
    public function iShouldLogIntoSyslogUdp($log)
    {
        $lines = $this->udpSocket->getLines();
        $content = json_decode($lines[0]);

        if ($content->message != $log) {
            throw new RuntimeException("The does not contain log");
        }
    }

    /**
     * @Given I built a logger with syslog handler and rotating file handler
     */
    public function iBuiltALoggerWithSyslogHandlerAndRotatingFileHandler()
    {
        $this->iBuiltALoggerWithRotatingFileHandler();
        $this->iBuiltALoggerWithSyslogHandler();
    }

    /**
     * @Given I built a logger error handler
     */
    public function iBuiltALoggerErrorHandler()
    {
        $this->logger->addErrorHandlerBuilder(
            new RotatingFileHandlerBuilder('/tmp/log', 'Y-m-d', 14, '{channel}.log', '{date}_{filename}', Logger::INFO)
        );
    }

    /**
     * @When I log an error :log
     */
    public function iLogAnError($log)
    {
        $this->logger->get('test_channel')->error($log);
    }

    /**
     * @Then I should have only :number log in the file
     */
    public function iShouldHaveOnlyLogInTheFile($number)
    {
        $content = $this->getLogFileContent('/tmp/log', 'Y-m-d', '{channel}.log', '{date}_{filename}', 'test_channel');
        if ($number != count($content)) {
            throw new RuntimeException("Log does not contain exactly  ".$number." logs");
        }
    }

    /**
     * @Given I add a memory processor
     */
    public function iAddAMemoryProcessor()
    {
        $this->logger->addProcessor(new MemoryUsageProcessor());
    }

    /**
     * @Then I should have log with memory informaton
     */
    public function iShouldHaveLogWithMemoryInformaton()
    {
        $content = $this->getLogFileContent('/tmp/log', 'Y-m-d', '{channel}.log', '{date}_{filename}', 'test_channel');

        $log = json_decode($content[0]);

        if (is_null($log->extra->memory_usage)) {
            throw new RuntimeException("Log does not contain memory usage info");
        }
    }

    /**
     * @When I log warning :log with an exception
     */
    public function iLogWarningWithAnException($log)
    {
        $this->logger->get('test_channel')->info($log, ['context' => new \Exception()]);
    }

    /**
     * @Then I should log an exception into file log
     */
    public function iShouldLogAnExceptionIntoFileLog()
    {
        $lines = $this->udpSocket->getLines();
        $line = json_decode($lines[0]);
        if (!is_string($line->context->context)) {
            throw new RuntimeException("The exception has not been normalized");
        }
    }

}

