<?php

namespace Enzim\Lib\TikaWrapper;

use Symfony\Component\Process\Process;
use SplFileInfo;

class TikaWrapper {

    protected $timeout = 60;

    public function __construct(array $config = [])
    {
        if(isset($config['timeout']))
            $this->timeout = $config['timeout'];
    }

    /**
     * @param string $fileName
     * @return int
     */
    public function getWordCount($fileName){
        return str_word_count($this->getText($fileName));
    }

    /**
     * @param $filename
     * @return string
     */
    public function getXHTML($filename){
        return $this->run("--xml", $filename);
    }

    /**
     * @param $filename
     * @return string
     */
    public function getHTML($filename){
        return $this->run("--html", $filename);
    }

    /**
     * @param string $filename
     * @return string
     */
    public function getText($filename) {
        return $this->run("--text", $filename);
    }

    /**
     * @param $filename
     * @return string
     */
    public function getTextMain($filename){
        return $this->run("--text-main", $filename);
    }

    /**
     * @param $filename
     * @return string
     */
    public function getMetadata($filename){
        return $this->run("--metadata", $filename);
    }

    /**
     * @param $filename
     * @return string
     */
    public function getJson($filename){
        return $this->run("--json", $filename);
    }

    /**
     * @param $filename
     * @return string
     */
    public function getXmp($filename){
        return $this->run("--xmp", $filename);
    }

    /**
     * @param $filename
     * @return string
     */
    public function getLanguage($filename){
        return $this->run("--language", $filename);
    }

    /**
     * @param $filename
     * @return string
     */
    public function getDocumentType($filename){
        return $this->run("--detect", $filename);
    }

    /**
     * @param string $option
     * @param string $fileName
     * @param int $timeout
     * @return string
     */
    private function run($option, $fileName, $timeout = 60){
        $file = new SplFileInfo($fileName);
        $tikaPath = __DIR__ . "/../vendor/";
        $shellCommand = 'java -jar tika-app-1.12.jar ' . $option . ' "' . $file->getRealPath() . '"';

        $process = new Process($shellCommand, null, null, null, $timeout);
        $process->setWorkingDirectory($tikaPath);
        $process->run();

        if (!$process->isSuccessful()) {
            throw new \RuntimeException($process->getErrorOutput());
        }

        return $process->getOutput();
    }
}
