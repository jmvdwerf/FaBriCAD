<?php
declare(strict_types=1);

namespace jmw\fabricad\shapes\test;

require_once('AbstractShapeTest.php');

use jmw\fabricad\shapes\Rectangle;
use jmw\fabricad\shapes\Point;
use jmw\fabricad\shapes\Shape;

final class ShapeTest extends AbstractShapeTest
{
    
    public function testIntersects()
    {
        $r1 = new Rectangle(100, 100);
        $r2 = new Rectangle(50,50, new Point(25, 25));
        $r3 = new Rectangle(100, 100, new Point(50,50));
        // $r2 fully in $r1
        $this->assertTrue($r1->intersects($r2));
        
        // $r3 overlaps $R1
        $this->assertTrue($r1->intersects($r3));
        
    }
    
    public function testContainedIn()
    {
        $first = new Rectangle(100, 100, new Point(20, 20));
        $second = new Rectangle(30, 30, new Point(50, 50));
        
        $this->assertTrue($second->isContainedIn($first));
    }
    
    public function testOverlaps()
    {
        $r = [];
        $r['blue'] = new Rectangle(1000, 500, new Point(100, 200));
        $r['red']  = new Rectangle(100, 1000, new Point(200, 100));
        $r['green'] = new Rectangle(250, 250, new Point(150, 150));
        
        $items = Shape::removeOverlap($r);
        
        $this->assertCount(5, $items);
    }
    
}
    