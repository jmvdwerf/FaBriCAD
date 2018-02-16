<?php
declare(strict_types=1);

namespace jmw\fabricad\shapes\test;

require_once('AbstractShapeTest.php');

use jmw\fabricad\shapes\Line;
use jmw\fabricad\shapes\Polygon;
use jmw\fabricad\shapes\Rectangle;
use jmw\fabricad\shapes\Point;
use jmw\fabricad\shapes\BinaryOperators;

final class BinaryOperatorsTest extends AbstractShapeTest
{
    
    public function testDifferenceWithNonOverlapping()
    {
        $r1 = new Rectangle(100,100, new Point(0,100));
        $r2 = new Rectangle(100,100, new Point(500,500));
        
        $items = BinaryOperators::difference($r1, $r2);
        
        $this->assertCount(1, $items);
        $this->assertContains($r1, $items);
        
    }
    
    public function testDifferenceWithLines()
    {
        $r = new Rectangle(100,100, new Point(0,100));
        $l = new Line(new Point(50,0), new Point(50,250));
        
        $items = BinaryOperators::difference($r, $l);
        
        $this->assertCount(1, $items);
        $this->assertContains($r, $items);
        
        $items = BinaryOperators::difference($l, $r);
        
        $this->assertCount(2, $items);
        
        foreach($items as $s) {
            if ($s->contains(new Point(50,0))) {
                $this->assertLine($s, 50, 0, 50, 100);
            } else {
                $this->assertLine($s, 50, 200, 50, 250);
            }
        }
    }
    
    
    public function testDifference()
    {
        $r1 = new Rectangle(100,100);
        $r2 = new Rectangle(100,100, new Point(50,50));
        
        $diff = BinaryOperators::difference($r1, $r2);
        
        $this->assertCount(1, $diff);
        
        $shape = $diff[0];
        
        $this->assertPoint($shape->getPoints()[0],   0,   0);
        $this->assertPoint($shape->getPoints()[1],   0, 100);
        $this->assertPoint($shape->getPoints()[2],  50, 100);
        $this->assertPoint($shape->getPoints()[3],  50,  50);
        $this->assertPoint($shape->getPoints()[4], 100,  50);
        $this->assertPoint($shape->getPoints()[5], 100,   0);
    }
    
    public function testUnionWithNoOverlap()
    {
        $r1 = new Rectangle(100,100);
        $r2 = new Rectangle(100,100, new Point(500,500));
        
        $items = BinaryOperators::union($r1, $r2);
        
        $this->assertCount(2, $items);
        $this->assertContains($r1, $items);
        $this->assertContains($r2, $items);
    }
    
    public function testUnion()
    {
        $r1 = new Rectangle(100,100);
        $r2 = new Rectangle(100,100, new Point(50,50));
        
        $diff = BinaryOperators::union($r1, $r2);
        
        $this->assertCount(1, $diff);
        
        $shape = $diff[0];
        
        $this->assertPoint($shape->getPoints()[0],   0,   0);
        $this->assertPoint($shape->getPoints()[1],   0, 100);
        $this->assertPoint($shape->getPoints()[2],  50, 100);
        $this->assertPoint($shape->getPoints()[3],  50, 150);
        $this->assertPoint($shape->getPoints()[4], 150, 150);
        $this->assertPoint($shape->getPoints()[5], 150,  50);
        $this->assertPoint($shape->getPoints()[6], 100,  50);
        $this->assertPoint($shape->getPoints()[7], 100,   0);
    }
    
    public function testIntersectionWithNoOverlapping()
    {
        $r1 = new Rectangle(100,100);
        $r2 = new Rectangle(100,100, new Point(500,500));
        
        $items = BinaryOperators::intersection($r1, $r2);
        
        $this->assertCount(0, $items);
    }
    
    public function testIntersectionWithTwoLines()
    {
        $l1 = new Line(new Point(10,0), new Point(10,200));
        $l2 = new Line(new Point(0,20), new Point(150,20));
        
        $items = BinaryOperators::intersection($l1, $l2);
        
        $this->assertCount(0, $items);
    }
    
    public function testIntersectionWithLineAndSimpleShape()
    {
        $r = new Rectangle(100,100, new Point(0,100));
        $l = new Line(new Point(50,0), new Point(50,250));
        
        $items = BinaryOperators::intersection($r, $l);
        
        $this->assertCount(1, $items);
        
        $this->assertLine($items[0], 50, 100, 50, 200);
        
        $items = BinaryOperators::intersection($l, $r);
        
        $this->assertCount(1, $items);
        
        $this->assertLine($items[0], 50, 100, 50, 200);
    }
    
    public function testDifferenceAndIntersectionWithLineAndComplexShape()
    {
        $p = new Polygon([
            new Point(10,0),
            new Point(20,0),
            new Point(20,30),
            new Point(30,30),
            new Point(30,0),
            new Point(40,0),
            new Point(40,50),
            new Point(10,50)
        ]);
        
        $l = new Line(new Point(0,20), new Point(50,20));
        
        $items =  BinaryOperators::difference($l, $p);
        
        $this->assertCount(3, $items);
        
        foreach($items as $s) {
            if ($s->contains(new Point(0,20))) {
                $this->assertLine($s, 0, 20, 10, 20);
            } elseif($s->contains(new Point(25,20))) {
                $this->assertLine($s, 20, 20, 30,20);
            } else {
                $this->assertLine($s, 40, 20, 50,20);
            }
        }
        
        $items =  BinaryOperators::intersection($l, $p);
        
        $this->assertCount(2, $items);
        
        foreach($items as $s) {
            if ($s->contains(new Point(15,20))) {
                $this->assertLine($s, 10, 20, 20, 20);
            } else {
                $this->assertLine($s, 30, 20, 40,20);
            }
        }
        
        $items =  BinaryOperators::intersection($p, $l);
        
        $this->assertCount(2, $items);
        
        foreach($items as $s) {
            if ($s->contains(new Point(15,20))) {
                $this->assertLine($s, 10, 20, 20, 20);
            } else {
                $this->assertLine($s, 30, 20, 40,20);
            }
        }
    }
    
    public function testIntersection()
    {
        $r1 = new Rectangle(100,100);
        $r2 = new Rectangle(100,100, new Point(50,50));
        
        $diff = BinaryOperators::intersection($r1, $r2);
        
        $this->assertCount(1, $diff);
        /*
        $shape = $diff[0];
        
        $this->assertPoint($shape->getPoints()[0],  50, 100);
        $this->assertPoint($shape->getPoints()[1], 100, 100);
        $this->assertPoint($shape->getPoints()[2], 100,  50);
        $this->assertPoint($shape->getPoints()[3],  50,  50);
        */
    } 
        
    
}