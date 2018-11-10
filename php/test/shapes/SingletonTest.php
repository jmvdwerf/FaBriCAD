<?php
declare(strict_types=1);

namespace jmw\fabricad\shapes\test;

use jmw\fabricad\shapes\Point;
use jmw\fabricad\shapes\Singleton;

require_once('AbstractShapeTest.php');

final class SingletonTest extends AbstractShapeTest
{
    public function testSingletonFromEmpty()
    {
        $s = new Singleton();
        $this->assertPoint($s->getOrigin(), 0, 0);
        
        $x = rand();
        $y = rand();
        $pt = new Point($x, $y);
        $s->setOrigin($pt);
        
        $this->assertEquals($s->getOrigin(), $pt);
        
        $this->assertPoint($s->getOrigin(), $x, $y);
    }
    
    public function testSingletonFromPoint()
    {
        $x = rand();
        $y = rand();
        $pt = new Point($x, $y);
        
        $s = new Singleton($pt);
        
        $this->assertPoint($s->getOrigin(), $x, $y);
        
        $p = $s->asPolygon();
        $this->assertCount(1, $p->getPoints());
        $this->assertPoint($p->getPoint(0), $x, $y);
        
        $this->assertTrue($s->contains($pt));
        $this->assertFalse($s->contains(new Point($x * 2, $y + 3)));
     
        
        $s->move(2, 4);
        $this->assertPoint($s->getOrigin(), $x+2, $y + 4);
        
    }
    
    public function testMirrorOnX()
    {
        $x = rand();
        $y = rand();
        $pt = new Point($x, $y);
        
        $s = new Singleton($pt);
        $this->assertInstanceOf(Singleton::class, $s->mirrorOnX());
        
        $this->assertPoint($s->getOrigin(), -1 * $x, $y);
    }
    
    public function testMirrorOnY()
    {
        $x = rand();
        $y = rand();
        $pt = new Point($x, $y);
        
        $s = new Singleton($pt);
        $this->assertInstanceOf(Singleton::class, $s->mirrorOnY());
        
        $this->assertPoint($s->getOrigin(), $x, -1 * $y);
    }
    
    public function testClone()
    {
        $x = rand();
        $y = rand();
        $pt = new Point($x, $y);
        
        $s = new Singleton($pt);
        $clone = $s->clone();
        
        $s->move(10, 10);
        
        $this->assertNotEquals($s, $clone);
        $this->assertPoint($clone->getOrigin(), $x, $y);
    }
    
    public function testScale()
    {
        $x = rand();
        $y = rand();
        $pt = new Point($x, $y);
        
        $s = new Singleton($pt);
        $this->assertInstanceOf(Singleton::class, $s->scale(2, 0.5));
        
        $this->assertPoint($s->getOrigin(), 2 * $x, 0.5 * $y);
    }
    
    public function testBoundingBox()
    {
        $x = rand();
        $y = rand();
        $pt = new Point($x, $y);
        
        $s = new Singleton($pt);
        
        $this->assertBoundingBox($s, $x, $y, 0, 0);
    }
    
    public function testFlip()
    {
        $x = rand();
        $y = rand();
        $pt = new Point($x, $y);
        
        $s = new Singleton($pt);
        
        $this->assertInstanceOf(Singleton::class, $s->flip());
        $this->assertPoint($s->getOrigin(), $y, $x);
    }
}