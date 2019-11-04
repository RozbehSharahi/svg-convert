<?php

namespace RozbehSharahi\SvgConvert;

class Configuration
{
    private $file;

    private $format = 'png';

    private $width = 0;

    private $height = 0;

    private $formatHeaderMapping = [
        'png' => 'Content-Type: image/png',
        'jpg' => 'Content-Type: image/jpeg',
        'gif' => 'Content-Type: image/gif',
    ];

    static public function create(): self
    {
        return new static();
    }

    public function setFile(?string $file, string $format = null): self
    {
        $pathInfo = pathinfo($file);

        Assert::notEmpty($pathInfo['extension'], 'File path is not valid, could not determine format.');
        Assert::notEmpty($pathInfo['basename'], 'File path is not valid, could not determine filename.');

        $this->file = $file;

        // we are implicitly setting extension if not defined
        $this->format = $format ?: $pathInfo['extension'];

        return $this;
    }

    public function getFile(): string
    {
        return $this->file;
    }

    public function getFileExtension()
    {
        Assert::notEmpty($this->file, 'Can not get file extension when file is not set');
        return $this->format;
    }

    public function hasFile(): bool
    {
        return (bool)$this->file;
    }

    public function setDimension(int $width, int $height): self
    {
        $this->width = $width;
        $this->height = $height;
        return $this;
    }

    public function hasDimension()
    {
        return $this->width && $this->height;
    }

    public function getWidth(): int
    {
        return $this->width;
    }

    public function getHeight(): int
    {
        return $this->height;
    }

    public function getFormat(): string
    {
        return $this->format;
    }

    public function setFormat(string $format): self
    {
        $this->format = $format;
        return $this;
    }

    public function getHeader(): string
    {
        Assert::keyExists($this->formatHeaderMapping, $this->format, "No header for format {$this->format} defined");
        return $this->formatHeaderMapping[$this->format];
    }

}
