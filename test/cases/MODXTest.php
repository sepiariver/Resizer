<?php
use PHPUnit\Framework\TestCase;

class MODXTest extends TestCase 
{
    protected $modx;
    
    protected function setUp(): void
    {
        require(dirname(dirname(__FILE__)) . '/config.core.php');
        require(MODX_CORE_PATH . "model/modx/modx.class.php");
        $this->modx = new modX();
    }
    public function testConfig()
    {
        $this->assertSame(MODX_CONFIG_KEY, 'config');
        $this->assertTrue($this->modx instanceof modX);
    }
}