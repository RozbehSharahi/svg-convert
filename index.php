<?php

$possibleAutoLoadPaths = [
    __DIR__ . '/../../autoload.php',
    __DIR__ . '/../vendor/autoload.php',
    __DIR__ . '/vendor/autoload.php'
];

foreach ($possibleAutoLoadPaths as $file) {
    if (file_exists($file)) {
        /** @noinspection PhpIncludeInspection */
        require_once $file;
    }
}

use RozbehSharahi\SvgConvert\Configuration;
use RozbehSharahi\SvgConvert\Svg;
use RozbehSharahi\SvgConvert\ImageMagickConverter;
use RozbehSharahi\SvgConvert\RsvgConverter;

/**
 * Example how to use
 */
(function () {

    // Write into png file
    Svg::createFromFile('example.svg')->writeToFile(Configuration::create()->setFile('example.png'));

    // Write into jpg file
    Svg::createFromFile('example.svg')->writeToFile(Configuration::create()->setFile('example.jpg'));

    // Write into gif file
    Svg::createFromFile('example.svg')->writeToFile(Configuration::create()->setFile('example.gif'));

    // Write into png with given dimension
    Svg::createFromFile('example.svg')->writeToFile(
        Configuration::create()
            ->setFile('example_1000x1000.png')
            ->setDimension(1000, 1000)
    );

    // Returns base64 string ready for <img> tag
    Svg::createFromFile('example.svg')->getBase64Url(Configuration::create());

    // Returns base64 string ready for <img> tag
    Svg::createFromFile('example.svg')->getBase64Url(Configuration::create()->setFormat('jpg'));

    // Returns base64 string ready for <img> tag
    Svg::createFromFile('example.svg')->getBase64Url(Configuration::create()->setFormat('gif'));

    // Returns base64 encoded image
    Svg::createFromFile('example.svg')->getBase64(Configuration::create()->setFormat('gif'));

    // Renders the svg as png
    Svg::createFromFile('example.svg')->render(Configuration::create());

    // Use different converter (RSVG)
    Svg::createFromFile('example.svg')->use(new RsvgConverter)->getBase64Url(Configuration::create());

    // Create svg from different sources
    Svg::createFromFile('example.svg');
    Svg::createFromContent('<svg>...</svg>');
    Svg::createFromBase64('aSBsb3ZlIHByb2dhbW1pbmcK');

    // Set default converter
    Svg::setDefaultConverter(new RsvgConverter());

    // Set command for converters
    ImageMagickConverter::setCommand('/usr/bin/convert');
    RsvgConverter::setCommand('/usr/bin/rsvg-convert');

})();
