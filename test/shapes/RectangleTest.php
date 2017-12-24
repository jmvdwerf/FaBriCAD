<?php
declare(strict_types=1);


namespace jmw\fabricad\shapes\test;

use jmw\fabricad\shapes\Rectangle;
use jmw\fabricad\shapes\Point;


final class RectangleTest extends AbstractShapeTest
{
    
    public function testConstruct()
    {
        $w = rand();
        $h = rand();
        
        $r = new Rectangle($w, $h);

        $this->assertRectangle($r,0,0,$w, $h);
    }
    
    public function testConstructWithOrigin()
    {
        $w = rand();
        $h = rand();
        
        $x = rand();
        $y = rand();
        
        $o = new Point($x, $y);
        
        $r = new Rectangle($w, $h, $o);
        
        $this->assertRectangle($r,$x,$y,$w, $h);
    }
    
    public function testSetOrigin()
    {
        $w = rand();
        $h = rand();
        
        $x = rand();
        $y = rand();
        
        $o = new Point($x, $y);
        
        $r = new Rectangle($w, $h);
        
        $r->setOrigin($o);
        
        $this->assertRectangle($r, $x, $y, $w, $h);
    }
    
    public function testSetHeight()
    {
        $h = rand();
                    
        $r = new Rectangle();
        
        $r->setHeight($h);
        
        $this->assertEquals($h, $r->getHeight());
    }
    
    public function testSetWidth()
    {
        $w = rand();
        
        $r = new Rectangle();
        
        $r->setWidth($w);
        
        $this->assertEquals($w, $r->getWidth());
    }
    
    public function testBoundingBox()
    {
        $w = rand();
        $h = rand();
        
        $x = rand();
        $y = rand();
        
        $o = new Point($x, $y);
        $r = new Rectangle($w, $h, $o);
        
        $r->setHeight($h);
        
        $this->assertRectangle($r->getBoundingBox(), $x, $y, $w, $h);
    }
    
    public function testTop()
    {
        $x = rand();
        $y = rand();
        $w = rand();
        $h = rand();
        
        $o = new Point($x, $y);
        $r = new Rectangle($w, $h, $o);

        $this->assertPoint($r->getTop(), $x + $w, $y+$h);
    }
    
    public function testMirrorX()
    {
        $w = rand();
        $h = rand();
        
        $x = rand();
        $y = rand();
        
        $o = new Point($x, $y);
        $r = new Rectangle($w, $h, $o);
        
        $r->mirrorOnX();
        
        $this->assertRectangle($r, -1 * $x - $w, $y, $w, $h);
        $this->assertPoint($r->getTop(), -1 * $x, $y+$h);
    }
    
    public function testMirrorY()
    {
        $w = 4;
        $h = 5;
        
        $x = 2;
        $y = 2;
        
        $o = new Point($x, $y);
        $r = new Rectangle($w, $h, $o);
        
        $r->mirrorOnY();
        
        $this->assertRectangle($r, $x, -1 * $y - $h, $w, $h);
        $this->assertPoint($r->getTop(), $x + $w, -1 * $y);
    }
    
    public function testSetTop1()
    {
        // set the top to something bigger than the origin
        $r = new Rectangle(5, 100, new Point(2,1));
        $r->setTop(new Point(5,5));
        
        $this->assertEquals(4, $r->getHeight());
        $this->assertEquals(3, $r->getWidth());
        
    }
    
    public function testSetTop2()
    {
        // set the top to something smaller than the origin
        $r = new Rectangle(5, 100, new Point(5,5));
        $r->setTop(new Point(2,3));
        
        $this->assertEquals(2, $r->getHeight());
        $this->assertEquals(3, $r->getWidth());
    }
    
    public function testToPolygon()
    {
        $p = new Rectangle(10, 5, new Point(3,4));
        
        $pts = $p->getPoints();
        $this->assertCount(4, $pts);
        
        $this->assertPoint($pts[0], 3,4);
        $this->assertPoint($pts[1], 3,9);
        $this->assertPoint($pts[2], 13,9);
        $this->assertPoint($pts[3], 13,4);
    }
    
    
    public function testContains()
    {
        $r = new Rectangle(10,10);
        
        $this->assertTrue($r->contains(new Point(3,5)));
        // on the border, so true
        $this->assertTrue($r->contains(new Point(0,5)));
        $this->assertTrue($r->contains(new Point(10,5)));
        $this->assertTrue($r->contains(new Point(5,10)));
        $this->assertTrue($r->contains(new Point(5,0)));
    }
    
    public function testContains2()
    {
        $r = new Rectangle(100,0, new Point(50,50));
        $p = new Point(100,50);
        
        $this->assertPoint($r->getTop(), 150,50);
        $this->assertPoint($r->getOrigin(), 50,50);
        
        $this->assertTrue($r->contains($p));
    }
}

















