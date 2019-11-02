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

    Svg::setDefaultRootDirectory(__DIR__);
    $svg = Svg::createFromFile('/example.svg');
    echo "<img alt='test' src='{$svg->getPngBase64(600, 700)}' />";

})();
