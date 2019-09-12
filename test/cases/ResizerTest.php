<?php
use PHPUnit\Framework\TestCase;

class ResizerTest extends TestCase 
{
    protected $modx;
    protected $resizer;
    protected $basePath;
    protected $srcDir = '/assets/';
    protected $outDir = '/output/';
    protected $removeOutput = true;
    protected $width = 400;
    protected $height = 300;
    protected $quality = 60;
    protected $formats = ['jpg' => IMAGETYPE_JPEG, 'png' => IMAGETYPE_PNG, 'gif' => IMAGETYPE_GIF];
    protected $newFormats = ['webp' => IMAGETYPE_WEBP]; // , 'jp2' => IMAGETYPE_JP2
    protected $testNewFormats = true;
    protected $graphicsLib = 2;
    
    protected function setUp(): void
    {
        $this->basePath = dirname(dirname(__FILE__));
        require_once($this->basePath . '/config.core.php');
        require_once(MODX_CORE_PATH . "model/modx/modx.class.php");
        $this->modx = new modX();

        require_once(dirname($this->basePath) . '/core/components/resizer/model/resizer.class.php');
        $this->resizer = new Resizer($this->modx, $this->graphicsLib);
    }
    public function testConfig()
    {
        $this->assertSame(MODX_CONFIG_KEY, 'config');
        $this->assertTrue($this->modx instanceof modX);
        $this->assertTrue($this->resizer instanceof Resizer);
    }
    public function testResizeWidth() 
    {
        $dir = $this->basePath . $this->srcDir;
        $files = scandir($dir);
        foreach ($files as $file) {
            if (strpos($file, '.') === 0) continue;
            if (is_dir($dir . DIRECTORY_SEPARATOR . $file)) continue;
            $full = $this->basePath . $this->srcDir . $file;
            $thumb = $this->basePath . $this->outDir . $file;
            $this->resizer->processImage(
                $full,
                $thumb,
                ['w' => $this->width, 'q' => $this->quality]
            );
            $this->assertFileExists($thumb);
            $this->assertFileNotEquals($full, $thumb);
            $this->assertGreaterThan(filesize($thumb), filesize($full));
            $this->assertEquals(getimagesize($thumb)[0], $this->width);
            if ($this->removeOutput) unlink($thumb);
        }
    }
    public function testResizeHeight() 
    {
        $dir = $this->basePath . $this->srcDir;
        $files = scandir($dir);
        foreach ($files as $file) {
            if (strpos($file, '.') === 0) continue;
            if (is_dir($dir . DIRECTORY_SEPARATOR . $file)) continue;
            $full = $this->basePath . $this->srcDir . $file;
            $thumb = $this->basePath . $this->outDir . $file;
            $this->resizer->processImage(
                $full,
                $thumb,
                ['h' => $this->height, 'q' => $this->quality]
            );
            $this->assertFileExists($thumb);
            $this->assertFileNotEquals($full, $thumb);
            $this->assertGreaterThan(filesize($thumb), filesize($full));
            $this->assertEquals(getimagesize($thumb)[1], $this->height);
            if ($this->removeOutput) unlink($thumb);
        }
    }
    public function testConvertFormat()
    {
        $this->removeOutput = false;
        $dir = $this->basePath . $this->srcDir;
        $files = scandir($dir);
        foreach ($files as $file) {
            if (strpos($file, '.') === 0) continue;
            if (is_dir($dir . DIRECTORY_SEPARATOR . $file)) continue;
            $full = $this->basePath . $this->srcDir . $file; 
            $formats = ($this->testNewFormats) ? array_merge($this->formats, $this->newFormats) : $this->formats;
            foreach ($formats as $ext => $type) {
                if (pathinfo($full, PATHINFO_EXTENSION) === $ext) continue;
                $thumb = $this->basePath . $this->outDir . $file . '.' . $ext;
                $this->resizer->processImage(
                    $full,
                    $thumb,
                    ['h' => $this->height, 'q' => $this->quality]
                );
                $this->assertFileExists($thumb, 'Failed: ' . $thumb);
                $this->assertFileNotEquals($full, $thumb,  'Failed: ' . $thumb);
                $this->assertGreaterThan(filesize($thumb), filesize($full), 'Failed: ' . $thumb);
                $this->assertEquals(getimagesize($thumb)[1], $this->height, 'Failed: ' . $thumb);
                $this->assertEquals($type, getimagesize($thumb)[2]);
                if ($this->removeOutput) unlink($thumb);
            }
        }
    }
}