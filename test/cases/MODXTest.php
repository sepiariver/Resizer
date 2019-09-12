<?php
use PHPUnit\Framework\TestCase;

class MODXTest extends TestCase 
{
    protected $modx;
    protected $resizer;
    
    protected function setUp(): void
    {
        require(dirname(dirname(__FILE__)) . '/config.core.php');
        require(MODX_CORE_PATH . "model/modx/modx.class.php");
        $this->modx = new modX();

        require(dirname(dirname(dirname(__FILE__))) . '/core/components/resizer/model/resizer.class.php');
        $this->resizer = new Resizer($this->modx, 2);
    }
    public function testConfig()
    {
        $this->assertSame(MODX_CONFIG_KEY, 'config');
        $this->assertTrue($this->modx instanceof modX);
        $this->assertTrue($this->resizer instanceof Resizer);
    }
}