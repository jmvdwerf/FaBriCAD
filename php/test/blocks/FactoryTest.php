<?php
namespace jmw\fabricad\blocks\test;

use PHPUnit\Framework\TestCase;
use jmw\fabricad\blocks\Factory;
use jmw\fabricad\blocks\Brickwall;

final class FactoryTest extends TestCase
{
    
    public function testCreateBrickwall()
    {
        $name = 'my first brick';
        $config = ['width' => 10, 'height' => 5];
        try {
            $brickwall = Factory::create('Brickwall', $name, $config);
            
            $this->assertTrue($brickwall instanceof Brickwall);
            $this->assertEquals($name, $brickwall->getName());
            $this->assertEquals($config, $brickwall->getConfig());
            
        } catch(\Exception $e) {
            $this->assertEquals(true, false, 'No exception expected!');
        }
    }
    
    public function testCreateException()
    {
        $name = 'Some unknown type';
        
        $errorno = 0;
        $type = null;
        try {
            $type = Factory::create('erroneousType', $name);
            
            $this->assertEquals($name, $type->getName());
        } catch(\Exception $e) {
            $errno = $e->getCode();
            $errmsg = $e->getMessage();
        }
        $this->assertEquals(null, $type);
        $this->assertEquals(1514580268, $errno);
    }
    
}