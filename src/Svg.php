<?php

namespace RozbehSharahi\SvgConvert;

use InvalidArgumentException;
use Webmozart\Assert\Assert;

class Svg
{

    static protected $tempDirectory = __DIR__ . '/tmp';
    static protected $supportedFormats = ['png', 'jpg', 'gif'];

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

    public function getBase64(string $format = 'png'): string
    {
        return "data:image/{$format};base64," . base64_encode($this->getBlob($format));
    }

    public function writeToFile(string $filePath): self
    {
        $pathInfo = pathinfo($filePath);

        Assert::notEmpty($pathInfo['extension'], 'File path is not valid, could not determine format.');
        Assert::notEmpty($pathInfo['basename'], 'File path is not valid, could not determine filename.');

        file_put_contents($pathInfo['dirname'] . '/' . $pathInfo['basename'], $this->getBlob($pathInfo['extension']));

        return $this;
    }

    public function getBlob(string $format = 'png'): string
    {
        Assert::oneOf($format, self::$supportedFormats, "The given format is not supported");

        $fileHash = $this->createUniqueFileHash();
        $inputFilePath = $this->getTempDirectory() . "/input_{$fileHash}.svg";
        $outputFilePath = $this->getTempDirectory() . "/output_{$fileHash}." . $format;

        file_put_contents($inputFilePath, $this->content);
        exec("convert {$inputFilePath} {$outputFilePath}");
        $blob = file_get_contents($outputFilePath);
        unlink($outputFilePath);
        unlink($inputFilePath);

        return $blob;
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
        if (!file_exists($this->getTempDirectory()) && !mkdir($this->getTempDirectory(), 0777, true)) {
            throw new InvalidArgumentException('Could not create temp directory path in ' . __METHOD__);
        }

        Assert::directory($this->getTempDirectory(), 'Temp directory path exists but is not a directory');

        return $this;
    }

}

