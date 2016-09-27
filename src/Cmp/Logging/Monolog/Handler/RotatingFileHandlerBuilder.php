<?php
namespace Cmp\Logging\Monolog\Handler;

use Monolog\Formatter\FormatterInterface;
use Monolog\Handler\RotatingFileHandler;

class RotatingFileHandlerBuilder implements HandlerBuilderInterface
{
    private $config = [];

    private $dateFormat;

    private $maxFiles;

    private $fileName;

    private $fileNameFormat;

    private $directoryPath;
    
    private $level;

    /**
     * RotatingFileHandlerBuilder constructor.
     *
     * @param $directoryPath
     * @param $dateFormat
     * @param $maxFiles
     * @param $fileName
     * @param $fileNameFormat
     * @param $level
     */
    public function __construct($directoryPath, $dateFormat, $maxFiles, $fileName, $fileNameFormat, $level)
    {
        $this->dateFormat = $dateFormat;
        $this->maxFiles = $maxFiles;
        $this->fileName = $fileName;
        $this->fileNameFormat = $fileNameFormat;
        $this->directoryPath = $directoryPath;
        $this->level = $level;
    }


    /**
     * @inheritDoc
     */
    public function build($channelName, $processors = [], FormatterInterface $formatter)
    {
        $fileName = $this->directoryPath.'/'.str_replace('{channel}', $channelName, $this->fileName);
        $handler = new RotatingFileHandler($fileName, $this->maxFiles);
        $handler->setFilenameFormat($this->fileNameFormat, $this->dateFormat);
        $handler->setLevel($this->level);
        $handler->setFormatter($formatter);
        array_map([$handler, 'pushProcessor'], $processors);

        return $handler;
    }

    public function setConfiguration($config)
    {
        $this->config = $config;
    }
}