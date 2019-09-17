<?php
use PHPUnit\Framework\TestCase;
use Reductionist\Reductionist;

class ResizerTest extends TestCase 
{
    protected $modx;
    protected $resizer;
    protected $basePath;
    protected $srcDir = '/assets/';
    protected $outDir = '/output/';
    protected $removeOutput = false;
    protected $width = 450;
    protected $height = 250;
    protected $quality = 60;
    protected $formats = ['jpg' => IMAGETYPE_JPEG, 'png' => IMAGETYPE_PNG, 'gif' => IMAGETYPE_GIF];
    protected $newFormats = ['webp' => IMAGETYPE_WEBP];
    protected $todoFormats = ['jp2' => IMAGETYPE_JP2];
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
        $this->resizer->debug = true;
        if (!is_dir($this->basePath . $this->outDir)) mkdir($this->basePath . $this->outDir);
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
            $thumb = $this->basePath . $this->outDir . 'width-' . $file;
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
            $thumb = $this->basePath . $this->outDir . 'height-' . $file;
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

    public function testResizeFar() 
    {
        $dir = $this->basePath . $this->srcDir;
        $files = scandir($dir);
        foreach ($files as $file) {
            if (strpos($file, '.') === 0) continue;
            if (is_dir($dir . DIRECTORY_SEPARATOR . $file)) continue;
            $full = $this->basePath . $this->srcDir . $file;
            $thumb = $this->basePath . $this->outDir . 'far-' . $file;
            $this->resizer->processImage(
                $full,
                $thumb,
                ['w' => $this->width, 'h' => $this->height, 'q' => $this->quality, 'far' => 'c', 'bg' => '000']
            );
            $this->assertFileExists($thumb);
            $this->assertFileNotEquals($full, $thumb);
            $this->assertGreaterThan(filesize($thumb), filesize($full));
            $this->assertEquals(getimagesize($thumb)[0], $this->width);
            $this->assertEquals(getimagesize($thumb)[1], $this->height);
            if ($this->removeOutput) unlink($thumb);
        }
    }

    public function testResizeZc() 
    {
        $dir = $this->basePath . $this->srcDir;
        $files = scandir($dir);
        foreach ($files as $file) {
            if (strpos($file, '.') === 0) continue;
            if (is_dir($dir . DIRECTORY_SEPARATOR . $file)) continue;
            $full = $this->basePath . $this->srcDir . $file;
            $thumb = $this->basePath . $this->outDir . 'zc-' . $file;
            $this->resizer->processImage(
                $full,
                $thumb,
                ['w' => $this->width, 'h' => $this->height, 'q' => $this->quality, 'zc' => 'c']
            );
            $this->assertFileExists($thumb);
            $this->assertFileNotEquals($full, $thumb);
            $this->assertGreaterThan(filesize($thumb), filesize($full));
            $this->assertEquals(getimagesize($thumb)[0], $this->width);
            $this->assertEquals(getimagesize($thumb)[1], $this->height);
            if ($this->removeOutput) unlink($thumb);
        }
    }

    public function testResizeScale() 
    {
        $dir = $this->basePath . $this->srcDir;
        $files = scandir($dir);
        foreach ($files as $file) {
            if (strpos($file, '.') === 0) continue;
            if (is_dir($dir . DIRECTORY_SEPARATOR . $file)) continue;
            $full = $this->basePath . $this->srcDir . $file;
            $thumb = $this->basePath . $this->outDir . 'scalew-' . $file;
            $this->resizer->processImage(
                $full,
                $thumb,
                ['w' => $this->width, 'q' => $this->quality, 'scale' => 2]
            );
            $this->assertFileExists($thumb);
            $this->assertFileNotEquals($full, $thumb);
            $this->assertGreaterThan(filesize($thumb), filesize($full));
            $this->assertEquals(getimagesize($thumb)[0], (int) $this->width * 2);
            if ($this->removeOutput) unlink($thumb);

            $thumb = $this->basePath . $this->outDir . 'scaleh-' . $file;
            $this->resizer->processImage(
                $full,
                $thumb,
                ['h' => $this->height, 'q' => $this->quality, 'scale' => 2]
            );
            $this->assertFileExists($thumb);
            $this->assertFileNotEquals($full, $thumb);
            $this->assertGreaterThan(filesize($thumb), filesize($full));
            $this->assertEquals(getimagesize($thumb)[1], (int) $this->height * 2);
            if ($this->removeOutput) unlink($thumb);
        }
    }

    public function testWmt() 
    {
        $dir = $this->basePath . $this->srcDir;
        $files = scandir($dir);
        foreach ($files as $file) {
            if (strpos($file, '.') === 0) continue;
            if (is_dir($dir . DIRECTORY_SEPARATOR . $file)) continue;
            $full = $this->basePath . $this->srcDir . $file;
            $thumb = $this->basePath . $this->outDir . 'wmt-' . $file;
            $this->resizer->processImage(
                $full,
                $thumb,
                ['w' => $this->width, 'fltr' => ['wmt|TEST|50']]
            );
            $this->assertFileExists($thumb);
            $this->assertFileNotEquals($full, $thumb);
            if ($this->removeOutput) unlink($thumb);
        }
        $this->assertTrue(false, implode(PHP_EOL, $this->resizer->debugmessages));
    }

    public function testWmi() 
    {
        $dir = $this->basePath . $this->srcDir;
        $wm = $this->basePath . '/cases/sepia.png';
        $files = scandir($dir);
        foreach ($files as $file) {
            if (strpos($file, '.') === 0) continue;
            if (is_dir($dir . DIRECTORY_SEPARATOR . $file)) continue;
            $full = $this->basePath . $this->srcDir . $file;
            $thumb = $this->basePath . $this->outDir . 'wmi-' . $file;
            $this->resizer->processImage(
                $full,
                $thumb,
                ['w' => $this->width, 'fltr' => ['wmi|' . $wm . '|C|50']]
            );
            $this->assertFileExists($thumb);
            $this->assertFileNotEquals($full, $thumb);
            if ($this->removeOutput) unlink($thumb);
        }
    }

    public function testConvertFormat()
    {
        $this->removeOutput = false;
        $dir = $this->basePath . $this->srcDir;
        $files = scandir($dir);
        $failures = [];
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
                    ['q' => $this->quality]
                );
                try {
                    $this->assertFileExists($thumb);
                    $this->assertFileNotEquals($full, $thumb);
                    $this->assertNotEquals($type, getimagesize($full)[2]);
                    $this->assertEquals($type, getimagesize($thumb)[2]);
                } catch(PHPUnit_Framework_ExpectationFailedException $e) {
                    $failures[] = $e->getMessage();
                }
                if ($this->removeOutput) unlink($thumb);
            }
        }
        if(!empty($failures)) {
            throw new PHPUnit_Framework_ExpectationFailedException (count($failures) . " assertions failed:\n\t" . implode("\n\t", $failures));
        }
    }
}