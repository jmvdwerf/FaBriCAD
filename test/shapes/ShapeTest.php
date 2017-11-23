<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;

use jmw\frabricad\shapes\Shape;
use jmw\frabricad\shapes\Rectangle;
use jmw\frabricad\shapes\Point;

final class ShapeTest extends TestCase
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
        
        $this->assertFalse($r1->intersects($r3));
        $this->assertFalse($r3->intersects($r1));
        
        $this->assertFalse($r2->intersects($r3));
        $this->assertFalse($r3->intersects($r2));
        
        $this->assertTrue($r1->intersects($r4));
        $this->assertTrue($r4->intersects($r1));
        
        $this->assertFalse($r1->intersects($r5));
        $this->assertFalse($r5->intersects($r1));
        
    }
    
}
    