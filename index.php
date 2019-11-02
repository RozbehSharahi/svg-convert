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

use RozbehSharahi\SvgConvert\Svg;

/**
 * Example how to use
 */
(function() {

    Svg::setTempDirectory(__DIR__.'/tmp');

    // Return base64 encoded png ready to render in <img /> tag.
    echo Svg::createFromFile('example.svg')->getPngBase64();

    // Write into png file
    Svg::createFromFile('example.svg')->writeToFile('example.png');

    // Write into png file
    Svg::createFromFile('example.svg')->writeToFile('example.jpg');

    // Write into png file
    Svg::createFromFile('example.svg')->writeToFile('example.gif');

})();
