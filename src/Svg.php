<?php

namespace RozbehSharahi\SvgConvert;

use Webmozart\Assert\Assert;

class Svg
{

    private $content;

    private $converter;

    static public function createFromFile(string $file, ConverterInterface $converter = null): self
    {
        Assert::file($file, "Appears not to be file: `{$file}`");
        return new static(file_get_contents($file));
    }

    static public function createFromContent(string $content, ConverterInterface $converter = null): self
    {
        return new static($content);
    }

    static public function createFromBase64(string $content, ConverterInterface $converter = null): self
    {
        return new static(base64_decode($content));
    }

    public function __construct(string $content, ConverterInterface $converter = null)
    {
        $this->content = $content;
        $this->converter = $converter ?: $this->getDefaultConverter();
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
        header($configuration->getHeader());
        echo $this->converter->getBlob($this, $configuration);
        return $this;
    }

    private function getDefaultConverter(): ImageMagickConverter
    {
        return new ImageMagickConverter();
    }

}

