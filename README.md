# svg-convert
Library to convert SVG to other formats using ImageMagick.

Just keep in mind to set the right default root directory path:

## Install package

```bash
composer require rozbehsharahi/svg-convert
```

```php
<?php
use RozbehSharahi\SvgConvert\Svg;

Svg::setDefaultRootDirectory(__DIR__);

echo Svg::createFromFile('/my-file.svg')->getPngBase64();
```
