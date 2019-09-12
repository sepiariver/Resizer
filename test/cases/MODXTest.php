<?php
use PHPUnit\Framework\TestCase;

class MODXTest extends TestCase 
{
    protected $modx;
    protected $resizer;
    protected $basePath;
    
    protected function setUp(): void
    {
        $this->basePath = dirname(dirname(__FILE__));
        require_once($this->basePath . '/config.core.php');
        require_once(MODX_CORE_PATH . "model/modx/modx.class.php");
        $this->modx = new modX();

        require_once(dirname($this->basePath) . '/core/components/resizer/model/resizer.class.php');
        $this->resizer = new Resizer($this->modx, 2);
    }
    public function testConfig()
    {
        $this->assertSame(MODX_CONFIG_KEY, 'config');
        $this->assertTrue($this->modx instanceof modX);
        $this->assertTrue($this->resizer instanceof Resizer);
    }
    public function testResize() 
    {
        $files = ['test-01.jpg', 'test-06.jpg', 'test-16.jpg', 'test-27.jpg'];
        foreach ($files as $file) {
            $full = $this->basePath . '/assets/' . $file;
            $thumb = $this->basePath . '/output/' . $file;
            $this->resizer->processImage(
                $full,
                $thumb,
                ['w' => 400, 'q' => 60]
            );
            $this->assertFileExists($thumb);
            $this->assertFileNotEquals($full, $thumb);
            $this->assertGreaterThan(filesize($thumb), filesize($full));
            $this->assertEquals(getimagesize($thumb)[0], 400);
        }
    }
}