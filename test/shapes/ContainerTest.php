<?php
declare(strict_types=1);

namespace jmw\fabricad\shapes\test;

require_once('AbstractShapeTest.php');

use jmw\fabricad\shapes\Container;
use jmw\fabricad\shapes\Rectangle;
use jmw\fabricad\shapes\Point;


final class ContainerTest extends AbstractShapeTest
{
    /** 
     * Tests adding a shape to the container
     */
    public function testConstructShape()
    {
        
        $r = array();
        $r[] = new Rectangle(5,5, new Point(10,10));
        $r[] = new Rectangle(10,10, new Point(8,8));
        
        $c = new Container($r);
               
        $this->assertCount(count($r), $c->getShapes());
        $this->assertEquals(count($r), $c->size());
        
        $item = 0;
        foreach($c as $key => $val) {
            $this->assertEquals($r[$key], $val);
            $this->assertTrue($r[$key] === $val);
            $item++;
        }
        
        $this->assertEquals($c->size(), $item);
    }
    
    /**
     * Tests adding a shape from the con
     */
    public function testAddShape()
    {
        $r = array();
        $r[] = new Rectangle(5,5, new Point(10,10));
        $r[] = new Rectangle(10,10, new Point(8,8));
        
        $c = new Container($r);
        
        $rNew = new Rectangle(3,3, new Point(-3, -3));
        $r[] = $rNew;
        
        $c->addShape($rNew);
        
        $this->assertCount(3, $c->getShapes());
        $this->assertEquals(3, $c->size());
        
        $item = 0;
        foreach($c as $key => $val) {
            $this->assertEquals($r[$key], $val);
            $item++;
        }
        
        $this->assertEquals($c->size(), $item);
        
    }
    
    /**
     * Tests removing a shape from the con
     */
    public function testRemoveShape()
    {
        $r = array();
        $r[] = new Rectangle(5,5, new Point(10,10));
        $r[] = new Rectangle(10,10, new Point(8,8));
        
        $c = new Container();
        
        $rNew = new Rectangle(3,3, new Point(-3, -3));
        $r[] = $rNew;
        
        $c->addShape($r[0]);
        $c->addShape($rNew);
        $c->addShape($r[1]);
        
        $this->assertCount(3, $c->getShapes());
        $this->assertEquals(3, $c->size());
        
        $c->removeShape($rNew);
        
        $this->assertCount(2, $c->getShapes());
        $this->assertEquals(2, $c->size());
        
        
        $item = 0;
        foreach($c as $key => $val) {
            $this->assertEquals($r[$key], $val);
            $item++;
        }
        
        $this->assertEquals($c->size(), $item);
    }

    public function testBoundingBox()
    {
        $r = array();
        $r[] = new Rectangle(5,5, new Point(10,10));
        $r[] = new Rectangle(10,10, new Point(8,8));
        $r[] = new Rectangle(3,3, new Point(-3, -3));
        $c = new Container($r);

        $this->assertBoundingBox($c, -3, -3, 21, 21);
    }

    public function testMirrorOnX()
    {
        $r = array();
        $r[] = new Rectangle(5,5, new Point(10,10));
        $r[] = new Rectangle(10,10, new Point(8,8));
        $r[] = new Rectangle(3,3, new Point(-3, -3));
        $c = new Container($r);
        
        $c->mirrorOnX();
        
        $p = [new Point(-15, 10), new Point(-18,8), new Point(0,-3)];
        
        foreach($c as $key => $val) {
            $this->assertPoint($r[$key]->getOrigin(), $p[$key]->getX(), $p[$key]->getY());
        }
        
    }
    
    /**
     * 
     */
    public function testMirrorOnY()
    {
        $r = array();
        $r[] = new Rectangle(5,5, new Point(10,10));
        $r[] = new Rectangle(10,10, new Point(8,8));
        $r[] = new Rectangle(3,3, new Point(-3, -3));
        $c = new Container($r);
        
        $c->mirrorOnY();
        
        $p = [new Point(10, -15), new Point(8,-18), new Point(-3,0)];
        
        foreach($c as $key => $val) {
            $this->assertPoint($r[$key]->getOrigin(), $p[$key]->getX(), $p[$key]->getY());
        }
        
    }
    
    public function notestFlatten()
    {
        $r = array();
        $r[] = new Rectangle(5,5, new Point(10,10));
        $r[] = new Rectangle(10,10, new Point(20,20));
        $r[] = new Rectangle(3,3, new Point(-30, -30));
        $c = new Container($r);
        
        $r2 = new Rectangle(20,20, new Point(100,100));
        
        $c2 = new Container([$r2]);
        
        $c->addShape($c2);
        
        $items = $c->flatten();
        
        $this->assertCount(4, $items);
        
        foreach($items as $shape) {
            if ($shape->contains(new Point(10,10))) {
                $this->assertFalse($shape === $r[0]);
            } elseif ($shape->contains(new Point(20, 20))) {
                $this->assertFalse($shape === $r[1]);
            } elseif ($shape->contains(new Point(-30,-30))) {
                $this->assertFalse($shape === $r[2]);
            } elseif ($shape->contains(new Point(100,100))) {
                $this->assertFalse($shape === $r2);
            } else {
                $this->assertFalse(true, 'Dit mag niet!');
            }
        }
        
        
    }
    
    public function testClone()
    {
        $r = array();
        $r[] = new Rectangle(5,5, new Point(10,10));
        $r[] = new Rectangle(10,10, new Point(8,8));
        $r[] = new Rectangle(3,3, new Point(-3, -3));
        $c = new Container($r);
        
        $clone = $c->clone();
        
        $this->assertFalse($clone === $c);
        $this->assertInstanceOf(Container::class, $clone);
        
        foreach($clone as $key => $shape) {
            $this->assertFalse($r[$key] === $shape);
        }
    }
    
    public function notestaddNonOverlappingPartsWithNoOverlappingItems()
    {
        $shape1 = new Rectangle(10, 10);
        $shape2 = new Rectangle(10, 10, new Point(200,200));
        
        $c = new Container([$shape1, $shape2]);
        
        $items = $c->flatten(true);
        
        $this->assertCount(2, $items);
        
        foreach($items as $it) {
            if ($it->contains(new Point(0, 0))) {
                $this->assertRectangle($it, 0, 0, 10, 10);
            } else {
                $this->assertRectangle($it, 200, 200, 10, 10);
            }
        }
    }
    
    public function notestaddNonOverlappingPartsWithOverlappingSimple()
    {
        $shape1 = new Rectangle(100, 100);
        $shape2 = new Rectangle(100, 100, new Point(50,50));
        $shape3 = new Rectangle(40, 40, new Point(80, 30));
        $shape4 = new Rectangle(20, 20, new Point(10, 10));
        
        $c = new Container([$shape1, $shape2, $shape3, $shape4]);
        
        $items = $c->flatten(true);
        
        $this->assertCount(3, $items);
        
        foreach($items as $s) {
            if ($s->hasPoint(new Point(150,150))) {
                $this->assertCount(6, $s->getPoints());
                $this->assertTrue($s->hasPoint(new Point(100, 50)));
                $this->assertTrue($s->hasPoint(new Point(150, 50)));
                $this->assertTrue($s->hasPoint(new Point(150,150)));
                $this->assertTrue($s->hasPoint(new Point( 50,150)));
                $this->assertTrue($s->hasPoint(new Point( 50,100)));
                $this->assertTrue($s->hasPoint(new Point(100,100)));
            } elseif ($s->hasPoint(new Point(100, 100))) {
                $this->assertRectangle($s, 0, 0, 100, 100);
            } else {
                $this->assertCount(4, $s->getPoints());
                $this->assertTrue($s->hasPoint(new Point(120, 30)));
                $this->assertTrue($s->hasPoint(new Point(100, 30)));
                $this->assertTrue($s->hasPoint(new Point(100,50)));
                $this->assertTrue($s->hasPoint(new Point(120,50)));
            }
        }
    }
    
    public function testOrigin()
    {
        $c = new Container();
        $this->assertPoint($c->getOrigin(), 0, 0);
    }
    
    public function testFlip()
    {
        $x = rand();
        $y = rand();
        $h = rand();
        $w = rand();
        
        $r = new Rectangle($w, $h, new Point($x, $y));
        $c = new Container([$r]);
        
        $flipped = $c->flip();
        
        $this->assertCount(1, $flipped->getShapes());
        $this->assertRectangle($flipped->getShapes()[0], $y, $x, $h, $w);
    }
}









