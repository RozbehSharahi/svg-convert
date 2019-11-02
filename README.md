# SVG Converter
Library to convert SVG to other formats using ImageMagick.

Currently the library needs a tmp directory that you should configure, otherwise it will create it inside of the package in vendor folder which will not harm but is not nice at the same time.

## Install package

```bash
composer require rozbehsharahi/svg-convert
```

```php
<?php
use RozbehSharahi\SvgConvert\Svg;

Svg::setTempDirectory(__DIR__.'/tmp');

// Return base64 encoded png ready to render in <img /> tag.
echo Svg::createFromFile('example.svg')->getPngBase64();

// Write into png file
Svg::createFromFile('example.svg')->writeToFile('example.png');

// Write into png file
Svg::createFromFile('example.svg')->writeToFile('example.jpg');

// Write into png file
Svg::createFromFile('example.svg')->writeToFile('example.gif');
```

## Information

- The package does not depend on \Imagick and can be used on server that do not support the php-extension.
- Is based on imagemagick
