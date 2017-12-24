<?php
declare(strict_types=1);

namespace jmw\fabricad\shapes\test;

require_once('AbstractShapeTest.php');

use jmw\fabricad\shapes\Rectangle;
use jmw\fabricad\shapes\Point;

final class ShapeTest extends AbstractShapeTest
{
    
    public function testIntersects()
    {
        $r1 = new Rectangle(10, 5, new Point(2,3));
        $r2 = new Rectangle(10, 5, new Point(10,0));
        $r3 = new Rectangle(10, 5, new Point(12,5));
        $r4 = new Rectangle(10, 3, new Point(-2,1));
        $r5 = new Rectangle(10, 2, new Point(-2,1));
        
        
        $this->assertTrue($r1->intersects($r1));
        $this->assertTrue($r2->intersects($r2));
        $this->assertTrue($r1->intersects($r2));
        $this->assertTrue($r2->intersects($r1));
        
        // all true, because the borders are the same
        $this->assertTrue($r1->intersects($r3));
        $this->assertTrue($r3->intersects($r1));
        
        $this->assertTrue($r2->intersects($r3));
        $this->assertTrue($r3->intersects($r2));
        
        $this->assertTrue($r1->intersects($r4));
        $this->assertTrue($r4->intersects($r1));
        
        $this->assertTrue($r1->intersects($r5));
        $this->assertTrue($r5->intersects($r1));
        
    }
    
}
    