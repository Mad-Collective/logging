<?php
namespace Cmp\Logging\Monolog\Handler;

use Monolog\Formatter\FormatterInterface;
use Monolog\Handler\RotatingFileHandler;

class RotatingFileHandlerBuilder implements HandlerBuilderInterface
{
    /**
     * @var string
     */
    private $dateFormat;

    /**
     * @var integer
     */
    private $maxFiles;

    /**
     * @var string
     */
    private $fileName;

    /**
     * @var string
     */
    private $fileNameFormat;

    /**
     * @var string
     */
    private $directoryPath;

    /**
     * @var integer
     */
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
    public function build($channelName, FormatterInterface $formatter, $processors = [])
    {
        $fileName = $this->directoryPath.'/'.str_replace('{channel}', $channelName, $this->fileName);
        $handler = new RotatingFileHandler($fileName, $this->maxFiles);
        $handler->setFilenameFormat($this->fileNameFormat, $this->dateFormat);
        $handler->setFormatter($formatter);
        $handler->setLevel($this->level);
        array_map([$handler, 'pushProcessor'], $processors);

        return $handler;
    }
}