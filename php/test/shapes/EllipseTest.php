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
        $e = new Ellipse(4,4, new Point(2,2));
        
        $this->assertTrue($e->contains(new Point(6,2), true));
        $this->assertTrue($e->contains(new Point(2,2)));
        $this->assertTrue($e->contains(new Point(4,4)));
    }
    
    public function testClone()
    {
        $a = rand();
        $b = rand();
        
        $e = new Ellipse($a, $b);
        $c = $e->clone();
        
        $this->assertFalse($e === $c);
        $this->assertInstanceOf(Ellipse::class, $c);
        
        $this->assertEquals($a, $c->getXFactor());
        $this->assertEquals($b, $c->getYFactor());
    }
    
    public function testFlip()
    {
        $e = new Ellipse(3,2, new Point(1,2));
        
        $flipped = $e->flip();
        
        $this->assertPoint($flipped->getOrigin(), 2, 1);
        $this->assertEquals(2, $flipped->getXFactor());
        $this->assertEquals(3, $flipped->getYFactor());
    }
    
    public function testSetOrigin()
    {
        $e = new Ellipse(4,4);
        $e->setOrigin(new Point(2,3));
        
        $this->assertPoint($e->getOrigin(), 2, 3);
        
        $this->assertFalse($e->contains(new Point(6,7)));
        $this->assertTrue($e->contains(new Point(2,3)));
        $this->assertTrue($e->contains(new Point(4,5)));
    }
    
    public function testMove()
    {
        $e = new Ellipse(4,4);
        $e->move(2,3);
        
        $this->assertPoint($e->getOrigin(), 2, 3);
                
        $this->assertFalse($e->contains(new Point(6,7)));
        $this->assertTrue($e->contains(new Point(2,3)));
        $this->assertTrue($e->contains(new Point(4,5)));
    }
    
    public function testScale()
    {
        $e = new Ellipse(8,2, new Point(2,3));
        $e->scale(0.5, 2);
        
        $this->assertPoint($e->getOrigin(), 2, 3);
        
        $this->assertFalse($e->contains(new Point(6,7)));
        $this->assertTrue($e->contains(new Point(2,3)));
        $this->assertTrue($e->contains(new Point(4,5)));
    }
    
    public function testAsPolygon()
    {
        $e = new Ellipse(3, 3);
        $poly = $e->asPolygon();
        
        foreach($poly->getPoints() as $pt) {
            $this->assertTrue($e->contains($pt, true), 'Point not on Ellipse: '.$pt);
        }
    }
    
}









