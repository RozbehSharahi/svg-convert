<?php

namespace RozbehSharahi\SvgConvert;

use InvalidArgumentException;
use Webmozart\Assert\Assert;

class Svg
{

    static protected $tempDirectory = __DIR__ . '/tmp';
    static protected $supportedFormats = ['png', 'jpg', 'gif'];
    static protected $formatHeaderMapping = [
        'png' => 'Content-Type: image/png',
        'jpg' => 'Content-Type: image/jpeg',
        'gif' => 'Content-Type: image/gif',
    ];

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

    public function getBase64(Configuration $configuration): string
    {
        return "data:image/{$configuration->getFormat()};base64," . base64_encode($this->getBlob($configuration));
    }

    public function writeToFile(Configuration $configuration): self
    {
        Assert::true($configuration->hasFile(), 'You have to define a file on your configuration for writeInFile');

        file_put_contents($configuration->getFile(), $this->getBlob($configuration));

        return $this;
    }

    public function render(Configuration $configuration): self
    {
        Assert::oneOf($configuration->getFormat(), self::$supportedFormats, "The given format is not supported");


        $blob = $this->getBlob($configuration);
        header(self::$formatHeaderMapping[$configuration->getFormat()]);
        echo $blob;

        return $this;
    }

    public function getBlob(Configuration $configuration): string
    {
        Assert::oneOf($configuration->getFormat(), self::$supportedFormats, "The given format is not supported");

        $fileHash = $this->createUniqueFileHash();
        $inputFilePath = $this->getTempDirectory() . "/input_{$fileHash}.svg";
        $outputFilePath = $this->getTempDirectory() . "/output_{$fileHash}." . $configuration->getFormat();
        $width = $configuration->getWidth();
        $height = $configuration->getHeight();

        $resize = $configuration->hasDimension() ? "-resize {$width}x{$height}" : "";

        file_put_contents($inputFilePath, $this->content);
        exec("convert {$resize} {$inputFilePath} {$outputFilePath}");
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

