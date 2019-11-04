<?php

namespace RozbehSharahi\SvgConvert;

use Webmozart\Assert\Assert;

class Svg
{

    static protected $defaultConverter;

    private $content;

    private $converter;

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

    static protected function getDefaultConverter(): ConverterInterface
    {
        return self::$defaultConverter = self::$defaultConverter ?: new ImageMagickConverter();
    }

    static public function setDefaultConverter(ConverterInterface $converter): void
    {
        self::$defaultConverter = $converter;
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

    public function getBase64(Configuration $configuration): string
    {
        $blob = $this->converter->getBlob($this, $configuration);
        return "data:image/{$configuration->getFormat()};base64," . base64_encode($blob);
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

    public function use(ConverterInterface $converter): self
    {
        return $this->setConverter($converter);
    }

    public function setConverter(ConverterInterface $converter): self
    {
        $this->converter = $converter;
        return $this;
    }

}

