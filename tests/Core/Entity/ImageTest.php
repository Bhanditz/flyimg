<?php
namespace Tests\Core\Entity;

use Core\Entity\Image;
use Core\Exception\ReadFileException;
use Tests\Core\BaseTest;

/**
 * @backupGlobals disabled
 */
class ImageTest extends BaseTest
{
    /**
     * Test parseOptions Method
     */
    public function testParseOptions()
    {
        $expectedParseArray = [
            'mozjpeg' => 1,
            'quality' => 90,
            'unsharp' => '0.25x0.25+8+0.065',
            'width' => 200,
            'height' => 100,
            'face-crop' => 0,
            'face-crop-position' => 0,
            'face-blur' => 1,
            'crop' => 1,
            'background' => '#999999',
            'strip' => 1,
            'resize' => 1,
            'gravity' => 'Center',
            'filter' => 'Lanczos',
            'rotate' => '-45',
            'scale' => '50',
            'sampling-factor' => '1x1',
            'refresh' => true,
            'extent' => '100x80',
            'preserve-aspect-ratio' => '1',
            'preserve-natural-size' => '1',
            'webp-lossless' => '0',
            'gif-frame' => '0',
            'thread' => '1',
        ];

        $this->assertEquals($this->image->getOptions(), $expectedParseArray);
    }

    /**
     * Test SaveToTemporaryFile
     */
    public function testSaveToTemporaryFile()
    {
        $this->assertFileExists($this->image->getTemporaryFile());
    }

    /**
     * Test SaveToTemporaryFileException
     */
    public function testSaveToTemporaryFileException()
    {
        $this->expectException(ReadFileException::class);
        $this->image = new Image([], parent::JPG_TEST_IMAGE.'--fail');
    }

    /**
     * Test GenerateFilesName
     */
    public function testGenerateFilesName()
    {
        $image = new Image($this->coreManager->parse(parent::OPTION_URL), parent::JPG_TEST_IMAGE);
        $this->assertEquals($this->image->getNewFileName(), $image->getNewFileName());
        $this->assertNotEquals($this->image->getNewFilePath(), $image->getNewFilePath());
    }

    /**
     * Test ExtractByKey
     */
    public function testExtractByKey()
    {
        $this->image->extract('width');
        $this->assertFalse(array_key_exists('width', $this->image->getOptions()));
    }
}
