<?php

namespace RozbehSharahi\SvgConvert;

use Webmozart\Assert\Assert;

class RsvgConverter implements ConverterInterface
{

    static protected $supportedFormats = ['png'];

    public function getBlob(Svg $svg, Configuration $configuration): string
    {
        $format = $configuration->getFormat();

        Assert::oneOf($format, self::$supportedFormats, "Given format `{$format}` is not supported by RSVG converter");

        $svg = base64_encode($svg->getContent());
        $width = $configuration->getWidth();
        $height = $configuration->getHeight();
        $resize = $configuration->hasDimension() ? "--width={$width} --height={$height}" : "";

        return shell_exec("(echo '{$svg}' | base64 --decode | rsvg-convert {$resize})");
    }

}