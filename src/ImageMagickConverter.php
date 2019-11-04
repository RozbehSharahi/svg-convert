<?php

namespace RozbehSharahi\SvgConvert;

class ImageMagickConverter implements ConverterInterface
{

    static protected $supportedFormats = ['png', 'jpg', 'gif'];

    static protected $command = 'convert';

    static public function setCommand(string $command): void
    {
        Assert::commandExists($command);
        static::$command = $command;
    }

    public function __construct()
    {
        Assert::commandExists(static::$command, static::$command . ' not installed for ' . __METHOD__);
    }

    public function getBlob(Svg $svg, Configuration $configuration): string
    {
        $format = $configuration->getFormat();

        Assert::oneOf($format, static::$supportedFormats, "Given format `{$format}` is not supported by rsgv converter");

        $svg = base64_encode($svg->getContent());
        $width = $configuration->getWidth();
        $height = $configuration->getHeight();
        $resize = $configuration->hasDimension() ? "-resize {$width}x{$height}" : '';
        $command = $this->getCommand();

        $command = "(echo '{$svg}' | base64 --decode | {$command} {$resize} -background none svg:- png:-)";
        return shell_exec($command);
    }

    protected function getCommand(): string
    {
        return static::$command;
    }

}