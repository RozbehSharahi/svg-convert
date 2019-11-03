<?php

namespace RozbehSharahi\SvgConvert;

use InvalidArgumentException;
use Webmozart\Assert\Assert;

class ImageMagickConverter implements ConverterInterface
{

    static protected $tempDirectory = __DIR__ . '/tmp';

    static protected $supportedFormats = ['png', 'jpg', 'gif'];

    static public function setTempDirectory(string $directory): void
    {
        static::$tempDirectory = $directory;
    }

    public function __construct()
    {
        $this->assertTempFolderExists();
    }

    public function getBlob(Svg $svg, Configuration $configuration): string
    {
        $format = $configuration->getFormat();
        $width = $configuration->getWidth();
        $height = $configuration->getHeight();

        Assert::oneOf($format, self::$supportedFormats, "The given format `{$format}`` is not supported");

        $fileHash = $this->createUniqueFileHash($svg);
        $inputFilePath = self::$tempDirectory . "/input_{$fileHash}.svg";
        $outputFilePath = self::$tempDirectory . "/output_{$fileHash}." . $format;

        $resize = $configuration->hasDimension() ? "-resize {$width}x{$height}!" : "";

        file_put_contents($inputFilePath, $svg->getContent());
        exec("convert {$resize} {$inputFilePath} {$outputFilePath}");
        $blob = file_get_contents($outputFilePath);
        unlink($outputFilePath);
        unlink($inputFilePath);

        return $blob;
    }

    protected function createUniqueFileHash(Svg $svg): string
    {
        return md5($svg->getContent());
    }

    private function assertTempFolderExists(): self
    {
        if (!file_exists(static::$tempDirectory) && !mkdir(static::$tempDirectory, 0777, true)) {
            throw new InvalidArgumentException('Could not create temp directory path in ' . __METHOD__);
        }

        Assert::directory(static::$tempDirectory, 'Temp directory path exists but is not a directory');
        return $this;
    }
}