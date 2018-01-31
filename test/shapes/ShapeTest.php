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
        $r1 = new Rectangle(100, 100);
        $r2 = new Rectangle(50,50, new Point(25, 25));
        $r3 = new Rectangle(100, 100, new Point(50,50));
        // $r2 fully in $r1
        $this->assertTrue($r1->intersects($r2));
        
        // $r3 overlaps $R1
        $this->assertTrue($r1->intersects($r3));
        
    }
    
}
    