<?php

namespace RozbehSharahi\SvgConvert;

class RsvgConverter implements ConverterInterface
{

    static protected $supportedFormats = ['png'];

    static protected $command = 'rsvg-convert';

    static public function setCommand(string $command): void
    {
        Assert::commandExists($command);
        self::$command = $command;
    }

    public function __construct()
    {
        Assert::commandExists(self::$command, self::$command . ' not installed for ' . __METHOD__);
    }

    public function getBlob(Svg $svg, Configuration $configuration): string
    {
        $format = $configuration->getFormat();

        Assert::oneOf($format, self::$supportedFormats, "Given format `{$format}` is not supported by RSVG converter");

        $svg = base64_encode($svg->getContent());
        $width = $configuration->getWidth();
        $height = $configuration->getHeight();
        $resize = $configuration->hasDimension() ? "--width={$width} --height={$height}" : "";
        $command = self::$command;

        return shell_exec("(echo '{$svg}' | base64 --decode | {$command} {$resize})");
    }

}