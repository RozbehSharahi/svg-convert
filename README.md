# SVG Converter
Library to convert SVG to other formats using ImageMagick.

Currently the library needs a tmp directory that you should configure, otherwise it will create it inside of the package in vendor folder which will not harm but is not nice at the same time.

## Install package

```bash
composer require rozbehsharahi/svg-convert
```

```php
<?php
use RozbehSharahi\SvgConvert\ImageMagickConverter;
use RozbehSharahi\SvgConvert\Svg;
use RozbehSharahi\SvgConvert\Configuration;

ImageMagickConverter::setTempDirectory(__DIR__.'/tmp');

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
        ->setDimension(1000,1000)
);

// Returns base64 string ready for <img> tag
Svg::createFromFile('example.svg')->getBase64(Configuration::create());

// Returns base64 string ready for <img> tag
Svg::createFromFile('example.svg')->getBase64(Configuration::create()->setFormat('jpg'));

// Returns base64 string ready for <img> tag
Svg::createFromFile('example.svg')->getBase64(Configuration::create()->setFormat('gif'));

// Renders the svg as png
Svg::createFromFile('example.svg')->render(Configuration::create());

// Create svg from different sources
Svg::createFromFile('example.svg');
Svg::createFromContent('SVG_STRING_HERE');
Svg::createFromBase64('BASE_64_STRING_HERE');
```

## Information

- The package does not depend on \Imagick and can be used on server that do not support the php-extension.
- Is based on imagemagick
