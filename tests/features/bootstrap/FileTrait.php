<?php
/**
 * Created by PhpStorm.
 * User: jaroslawgabara
 * Date: 28/09/16
 * Time: 13:06
 */

namespace features\Cmp\Logging;


trait FileTrait
{
    protected function getLogFileContent($path, $dateFormat, $filename, $fileFormat, $channelName)
    {
        $path = $this->getLogFilePath($path, $dateFormat, $filename, $fileFormat, $channelName);
        return $this->getFileContent($path);
    }

    protected function getFileContent($path)
    {
        $lines = [];
        $file = fopen($path, "r");
        while(!feof($file)){
            $line = fgets($file);
            if ($line) {
                $lines[] = $line;
            }
        }
        fclose($file);

        return $lines;
    }

    protected function removeLogFile($path, $dateFormat, $filename, $fileFormat, $channelName)
    {
        $path = $this->getLogFilePath($path, $dateFormat, $filename, $fileFormat, $channelName);
        return $this->removeFile($path);
    }

    protected function removeFile($path)
    {
        if (file_exists($path)) {
            return unlink($path);
        }
    }

    protected function getLogFilePath($path, $dateFormat, $filename, $fileFormat, $channelName)
    {
        $filename = str_replace('{channel}', $channelName, $filename);
        $fileFormat = str_replace('{date}', date($dateFormat), $fileFormat);
        $filename = str_replace('{filename}', $filename, $fileFormat);
        return  $path .'/'.$filename;
    }

}