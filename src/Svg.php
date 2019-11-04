<?php

namespace RozbehSharahi\SvgConvert;

class Svg
{

    static protected $defaultConverter;

    private $content;

    private $converter;

    static public function createFromContent(string $content): self
    {
        Assert::svg($content, 'Given svg was not valid');
        return new static(self::ensureDoctype($content));
    }

    static public function createFromFile(string $file): self
    {
        Assert::file($file);
        return static::createFromContent(file_get_contents($file));
    }

    static public function createFromBase64(string $base64): self
    {
        Assert::base64($base64, 'Could not create from base64 since value is not valid base64 string');
        return static::createFromContent(base64_decode($base64));
    }

    static protected function getDefaultConverter(): ConverterInterface
    {
        return self::$defaultConverter = self::$defaultConverter ?: new ImageMagickConverter();
    }

    static public function setDefaultConverter(ConverterInterface $converter): void
    {
        self::$defaultConverter = $converter;
    }

    static private function ensureDoctype(string $content)
    {
        if (strpos($content, '<?xml') !== 0) {
            return '<?xml version="1.0" encoding="UTF-8" standalone="no"?>' . $content;
        }
        return $content;
    }

    public function __construct(string $content)
    {
        $this->content = $content;
        $this->converter = self::getDefaultConverter();
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function use(ConverterInterface $converter): self
    {
        return $this->setConverter($converter);
    }

    public function setConverter(ConverterInterface $converter): self
    {
        $this->converter = $converter;
        return $this;
    }

    public function getBase64Url(Configuration $configuration): string
    {
        return "data:image/{$configuration->getFormat()};base64," . $this->getBase64($configuration);
    }

    public function getBase64(Configuration $configuration): string
    {
        return base64_encode($this->converter->getBlob($this, $configuration));
    }

    public function writeToFile(Configuration $configuration): self
    {
        Assert::true($configuration->hasFile(), 'You have to define a file on your configuration for writeInFile');
        file_put_contents($configuration->getFile(), $this->converter->getBlob($this, $configuration));
        return $this;
    }

    public function render(Configuration $configuration): self
    {
        $blob = $this->converter->getBlob($this, $configuration);

        header($configuration->getHeader());
        echo $blob;
        return $this;
    }

}

