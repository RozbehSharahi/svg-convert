<?php

namespace RozbehSharahi\SvgConvert;

use Webmozart\Assert\Assert;

class ImageMagickConverter implements ConverterInterface
{

    static protected $supportedFormats = ['png', 'jpg', 'gif'];

    public function __construct()
    {
        Assert::notEmpty(shell_exec('which convert'), 'rsvg-convert not installed! Cannot use ' . __METHOD__);
    }

    public function getBlob(Svg $svg, Configuration $configuration): string
    {
        $format = $configuration->getFormat();

        Assert::oneOf($format, self::$supportedFormats, "Given format `{$format}` is not supported by rsgv converter");

        $svg = base64_encode($svg->getContent());
        $width = $configuration->getWidth();
        $height = $configuration->getHeight();
        $resize = $configuration->hasDimension() ? "-resize {$width}x{$height}" : '';

        return shell_exec("(echo '{$svg}' | base64 --decode | convert {$resize} svg:- png:-)");
    }

}