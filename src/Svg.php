<?php

namespace RozbehSharahi\SvgConvert;

use InvalidArgumentException;
use Webmozart\Assert\Assert;

class Svg
{

    protected static $tempDirectory = __DIR__ . '/tmp';

    private $content;

    static public function setTempDirectory(string $directory): void
    {
        static::$tempDirectory = $directory;
    }

    static public function createFromFile(string $file): self
    {
        Assert::file($file, "Appears not to be file: `{$file}`");
        return new static(file_get_contents($file));
    }

    static public function createFromContent(string $content): self
    {
        return new static($content);
    }

    static public function createFromBase64(string $content): self
    {
        return new static(base64_decode($content));
    }

    public function __construct(string $content)
    {
        $this->assertTempFolderExists();
        $this->content = $content;
    }

    public function getPngBase64(): string
    {
        $fileHash = $this->createUniqueFileHash();
        $inputFilePath = "input_{$fileHash}.svg";
        $outputFilePath = "output_{$fileHash}.png";

        file_put_contents($inputFilePath, $this->content);
        exec("convert {$inputFilePath} {$outputFilePath}");
        $imageContent = file_get_contents($outputFilePath);
        unlink($outputFilePath);
        unlink($inputFilePath);

        return "data:image/png;base64," . base64_encode($imageContent);
    }

    public function writeToFile(string $file): self
    {
        $fileHash = $this->createUniqueFileHash();

        $inputFilePath = "input_{$fileHash}.svg";

        file_put_contents($inputFilePath, $this->content);
        exec("convert {$inputFilePath} {$file}");
        unlink(($inputFilePath));
        
        return $this;
    }

    private function getTempDirectory(): string
    {
        return self::$tempDirectory;
    }

    protected function createUniqueFileHash(): string
    {
        return md5($this->content);
    }

    private function assertTempFolderExists(): self
    {
        if (!file_exists($this->getTempDirectory()) && !mkdir($this->getTempDirectory())) {
            throw new InvalidArgumentException('Could not create temp directory path in ' . __METHOD__);
        }

        Assert::directory($this->getTempDirectory(), 'Temp directory path exists but is not a directory');

        return $this;
    }

}

