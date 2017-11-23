<?php
declare(strict_types=1);

namespace jmw\fabricad\shapes\test;

use jmw\fabricad\shapes\Ellipse;
use jmw\fabricad\shapes\Point;

final class EllipseTest extends AbstractShapeTest
{
    public function testEllipseConstruct()
    {
        $a = rand();
        $b = rand();
        
        $e = new Ellipse($a, $b);
        
        $this->assertEquals($a, $e->getXFactor());
        $this->assertEquals($b, $e->getYFactor());
    }
    
    public function testEllipseXFactor()
    {
        $a = rand();
        $b = rand();
        
        $e = new Ellipse();
        $e->setXFactor($a);
        
        $this->assertEquals($a, $e->getXFactor());
    }
    
    public function testEllipseYFactor()
    {
        $a = rand();
        $b = rand();
        
        $e = new Ellipse();
        $e->setYFactor($b);
        
        $this->assertEquals($b, $e->getYFactor());
    }
    
    public function testBoundingBox()
    {
        $a = rand();
        $b = rand();
        $o = new Point(rand(), rand());
        
        $e = new Ellipse($a, $b, $o);
        $bb = $e->getBoundingBox();
        
        $this->assertEquals($o->getX()-$a, $bb->getOrigin()->getX(), 'Origin: X');
        $this->assertEquals($o->getY()-$b, $bb->getOrigin()->getY(), 'Origin: Y');
        $this->assertEquals($a * 2, $bb->getWidth(), 'Width');
        $this->assertEquals($b * 2, $bb->getHeight(), 'Height');
        
    }
    
    public function testMirrorOnX()
    {
        $a = rand();
        $b = rand();
        $x = rand();
        $y = rand();
        
        $o = new Point($x, $y);
        
        $e = new Ellipse($a, $b, $o);
        $e->mirrorOnX();
        
        $bb = $e->getBoundingBox();
        
        $this->assertEquals((-1 * $x) - $a, $bb->getOrigin()->getX(), 'Origin: X');
        $this->assertEquals($y - $b, $bb->getOrigin()->getY(), 'Origin: Y');
        $this->assertEquals($a * 2, $bb->getWidth(), 'Width');
        $this->assertEquals($b * 2, $bb->getHeight(), 'Height');
    }
    
    public function testMirrorOnY()
    {
        $a = rand();
        $b = rand();
        $x = rand();
        $y = rand();
        
        $o = new Point($x, $y);
        
        $e = new Ellipse($a, $b, $o);
        $e->mirrorOnY();
        
        $bb = $e->getBoundingBox();
        
        $this->assertEquals((1 * $x) - $a, $bb->getOrigin()->getX(), 'Origin: X');
        $this->assertEquals((-1 * $y) - $b, $bb->getOrigin()->getY(), 'Origin: Y');
        $this->assertEquals($a * 2, $bb->getWidth(), 'Width');
        $this->assertEquals($b * 2, $bb->getHeight(), 'Height');
    }
    
    
    public function testContains()
    {
        $e = new Ellipse(4,4);
        
        $this->assertFalse($e->contains(new Point(4,4)));
        $this->assertTrue($e->contains(new Point(0,0)));
        $this->assertTrue($e->contains(new Point(2,2)));
    }
    
    
}









