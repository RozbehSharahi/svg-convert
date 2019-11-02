<?php

namespace RozbehSharahi\SvgConvert;

use Imagick;
use ImagickException;
use InvalidArgumentException;
use Webmozart\Assert\Assert;

class Svg
{
    const TYPE_FILE = 'file';

    /**
     * @var string
     */
    protected static $defaultRootDirectory = '/var/www/html';

    /**
     * @var string
     */
    protected $sourceType;

    /**
     * @var string
     */
    private $source;

    /**
     * @var string
     */
    private $basePath;

    public static function createFromFile(string $file, string $rootDirectory = null): self
    {
        return new self($file, self::TYPE_FILE, $rootDirectory ?: self::$defaultRootDirectory);
    }

    /**
     * @param string $defaultRootDirectory
     */
    public static function setDefaultRootDirectory(string $defaultRootDirectory): void
    {
        self::$defaultRootDirectory = $defaultRootDirectory;
    }

    /**
     * Svg constructor.
     * @param string $source
     * @param string $sourceType
     * @param string $rootDirectory
     */
    public function __construct(
        string $source,
        string $sourceType,
        string $rootDirectory
    ) {
        Assert::oneOf($sourceType, [static::TYPE_FILE], "Currently not supported file type: {$this->sourceType}");

        $this->sourceType = $sourceType;
        $this->source = $source;
        $this->basePath = $rootDirectory;

        if ($this->sourceType === static::TYPE_FILE) {
            Assert::fileExists($this->getServerPath(), "File `{$this->getServerPath()}` does not exist");
        }
    }

    /**
     * @return string
     */
    protected function getServerPath(): string
    {
        return $this->basePath . $this->source;
    }

    /**
     * @param int|null $width
     * @param int|null $height
     * @return string
     * @throws ImagickException
     */
    public function getPngBase64(int $width = null, int $height = null): string
    {
        if ($this->sourceType === self::TYPE_FILE) {
            return $this->getPngBase64FromFile($width, $height);
        }

        throw new InvalidArgumentException("Converting to png base64 is not implemented for {$this->sourceType}");
    }

    /**
     * @param int|null $width
     * @param int|null $height
     * @return string
     * @throws ImagickException
     */
    protected function getPngBase64FromFile(int $width = null, int $height = null): string
    {
        $imageMagick = new Imagick();
        $imageMagick->readImageBlob(file_get_contents($this->getServerPath()));
        $imageMagick->setImageFormat("png32");

        if ($width && $height) {
            $imageMagick->resizeImage($width, $height, Imagick::FILTER_LANCZOS, 1);
        }

        $imageContent = $imageMagick->getImageBlob();

        return "data:image/png;base64," . base64_encode($imageContent);
    }

}

