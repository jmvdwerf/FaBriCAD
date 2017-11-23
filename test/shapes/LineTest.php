<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use jmw\frabricad\shapes\Ellipse;
use jmw\frabricad\shapes\Point;
use jmw\frabricad\shapes\Rectangle;
use jmw\frabricad\shapes\Line;

final class LineTest extends TestCase
{   
    /*
     * Notice that testing mirrorOnX and mirrorOnY indirectly test the 
     * boundingbox as well!
     * 
     * for <Function> in { size, angle, mirrorOnX }
     * Testing on the quadrants: test<Function>X with X in {0, ... , 7} as follows:
     * 
     *                  2 = PI/2
     *                  |
     *      3PI/4 = 3   |   1 = PI/4
     *                  | 
     *   PI = 4  --------------- 0 = 0
     *                  |
     *     -3PI/4 = 7   |   5 = -PI/4
     *                  |
     *                  6 = -PI/2
     *   
     */
    
    public function testMirrorOnY1()
    {
        $l = new Line(new Point(2,1), new Point(5,5));
        $s = $l->getLength();
        $a = $l->getAngle();
        
        $l->mirrorOnY();
        
        $bb = $l->getBoundingBox();
        
        $this->assertEquals(  2, $bb->getOrigin()->getX(), 'Origin: X');
        $this->assertEquals( -5, $bb->getOrigin()->getY(), 'Origin: Y');
        $this->assertEquals(  3, $bb->getWidth(), 'width');
        $this->assertEquals(  4, $bb->getHeight(), 'height');
        
        $this->assertEquals($s, $l->getLength(), 'Size the same');
        $this->assertEquals(-1 * $a, $l->getAngle(), 'Angle negated');
    }
    
    public function testMirrorOnY3()
    {
        $l = new Line(new Point(2,1), new Point(-1,5));
        $s = $l->getLength();
        $a = $l->getAngle();
        
        $l->mirrorOnY();
        
        $bb = $l->getBoundingBox();
        
        $this->assertEquals(-1, $bb->getOrigin()->getX(), 'Origin: X');
        $this->assertEquals( -5, $bb->getOrigin()->getY(), 'Origin: Y');
        $this->assertEquals( 3, $bb->getWidth(), 'width');
        $this->assertEquals( 4, $bb->getHeight(), 'height');
        
        $this->assertEquals($s, $l->getLength(), 'Size the same');
        $this->assertEquals(-1 * $a, $l->getAngle(), 'Angle negated');
    }
    
    public function testMirrorOnY5()
    {
        $l = new Line(new Point(2,1), new Point(5,-3));
        $s = $l->getLength();
        $a = $l->getAngle();
        
        $l->mirrorOnY();
        
        $bb = $l->getBoundingBox();
        
        $this->assertEquals( 2, $bb->getOrigin()->getX(), 'Origin: X');
        $this->assertEquals(-1, $bb->getOrigin()->getY(), 'Origin: Y');
        $this->assertEquals( 3, $bb->getWidth(), 'width');
        $this->assertEquals( 4, $bb->getHeight(), 'height');
        
        $this->assertEquals($s, $l->getLength(), 'Size the same');
        $this->assertEquals(-1 * $a, $l->getAngle(), 'Angle negated');
    }
    
    public function testMirrorOnY7()
    {
        $l = new Line(new Point(2,1), new Point(-1,-3));
        $s = $l->getLength();
        $a = $l->getAngle();
        
        $l->mirrorOnY();
        
        $bb = $l->getBoundingBox();
        
        $this->assertEquals( -1, $bb->getOrigin()->getX(), 'Origin: X');
        $this->assertEquals( -1, $bb->getOrigin()->getY(), 'Origin: Y');
        $this->assertEquals( 3, $bb->getWidth(), 'width');
        $this->assertEquals( 4, $bb->getHeight(), 'height');
        
        $this->assertEquals($s, $l->getLength(), 'Size the same');
        $this->assertEquals(-1 * $a, $l->getAngle(), 'Angle negated');
    }
    
    public function testMirrorOnX1()
    {
        $l = new Line(new Point(2,1), new Point(5,5));
        $s = $l->getLength();
        $a = $l->getAngle();
        
        $l->mirrorOnX();
        
        $bb = $l->getBoundingBox();
        
        $this->assertEquals(-5, $bb->getOrigin()->getX(), 'Origin: X');
        $this->assertEquals( 1, $bb->getOrigin()->getY(), 'Origin: Y');
        $this->assertEquals( 3, $bb->getWidth(), 'width');
        $this->assertEquals( 4, $bb->getHeight(), 'height');
        
        $this->assertEquals($s, $l->getLength(), 'Size the same');
        $this->assertEquals(M_PI - $a, $l->getAngle(), 'Angle negated');
    }
    
    public function testMirrorOnX3()
    {
        $l = new Line(new Point(2,1), new Point(-1,5));
        $s = $l->getLength();
        $a = $l->getAngle();
        
        $l->mirrorOnX();
        
        $bb = $l->getBoundingBox();
        
        $this->assertEquals(-2, $bb->getOrigin()->getX(), 'Origin: X');
        $this->assertEquals( 1, $bb->getOrigin()->getY(), 'Origin: Y');
        $this->assertEquals( 3, $bb->getWidth(), 'width');
        $this->assertEquals( 4, $bb->getHeight(), 'height');
        
        $this->assertEquals($s, $l->getLength(), 'Size the same');
        $this->assertEquals(-1 * ($a - M_PI), $l->getAngle(), 'Angle negated');
    }
    
    public function testMirrorOnX5()
    {
        $l = new Line(new Point(2,1), new Point(5,-3));
        $s = $l->getLength();
        $a = $l->getAngle();
        
        $l->mirrorOnX();
        
        $bb = $l->getBoundingBox();
        
        $this->assertEquals(-5, $bb->getOrigin()->getX(), 'Origin: X');
        $this->assertEquals( -3, $bb->getOrigin()->getY(), 'Origin: Y');
        $this->assertEquals( 3, $bb->getWidth(), 'width');
        $this->assertEquals( 4, $bb->getHeight(), 'height');
        
        $this->assertEquals($s, $l->getLength(), 'Size the same');
        $this->assertEquals(-1 * M_PI - $a, $l->getAngle(), 'Angle negated');
    }
    
    public function testMirrorOnX7()
    {
        $l = new Line(new Point(2,1), new Point(-1,-3));
        $s = $l->getLength();
        $a = $l->getAngle();
        
        $l->mirrorOnX();
        
        $bb = $l->getBoundingBox();
        
        $this->assertEquals(-2, $bb->getOrigin()->getX(), 'Origin: X');
        $this->assertEquals( -3, $bb->getOrigin()->getY(), 'Origin: Y');
        $this->assertEquals( 3, $bb->getWidth(), 'width');
        $this->assertEquals( 4, $bb->getHeight(), 'height');
        
        $this->assertEquals($s, $l->getLength(), 'Size the same');
        $this->assertEquals(-1 * M_PI - $a, $l->getAngle(), 'Angle negated');
    }
    
    public function testSize0()
    {
        $l = new Line(new Point(2,1), new Point(5,1));
        $this->assertEquals(3, $l->getLength());        
    }
    
    public function testSize1()
    {
        $l = new Line(new Point(2,1), new Point(5,5));
        $this->assertEquals(5, $l->getLength());
    }
    
    public function testSize2()
    {
        $l = new Line(new Point(2,1), new Point(2,4));
        $this->assertEquals(3, $l->getLength());
    }
    
    public function testSize3()
    {
        $l = new Line(new Point(2,1), new Point(-1,5));
        $this->assertEquals(5, $l->getLength());
    }
    
    public function testSize4()
    {
        $l = new Line(new Point(2,1), new Point(-1,1));
        $this->assertEquals(3, $l->getLength());
    }
    
    public function testSize5()
    {
        $l = new Line(new Point(2,1), new Point(5,-3));
        $this->assertEquals(5, $l->getLength());
    }
    
    public function testSize6()
    {
        $l = new Line(new Point(2,1), new Point(2,-2));
        $this->assertEquals(3, $l->getLength());
    }
    
    public function testSize7()
    {
        $l = new Line(new Point(2,1), new Point(-1,-3));
        $this->assertEquals(5, $l->getLength());
    }
    
    public function testAngle0()
    {
        $x = 1;
        $y = 0;
        $ep = new Point($x,$y);
        $l = new Line();
        $l->setEndPoint($ep);
        
        $angle = $l->getAngle();
        
        $this->assertEquals(0, $angle);
    }
    
    public function testAngle1()
    {
        $x = 1;
        $y = 1;
        $ep = new Point($x,$y);
        $l = new Line();
        $l->setEndPoint($ep);
        
        $angle = $l->getAngle();
        
        $this->assertEquals(M_PI_4, $angle);
    }
    
    public function testAngle2()
    {
        $x = 0;
        $y = 1;
        $ep = new Point($x,$y);
        $l = new Line();
        $l->setEndPoint($ep);
        
        $angle = $l->getAngle();
        
        $this->assertEquals(M_PI_2, $angle);
    }
    
    public function testAngle3()
    {
        $x = -1;
        $y = 1;
        $ep = new Point($x,$y);
        $l = new Line();
        $l->setEndPoint($ep);
        
        $angle = $l->getAngle();
        
        $this->assertEquals(3 * M_PI_4, $angle);
    }
    
    public function testAngle4()
    {
        $x = -1;
        $y = 0;
        $ep = new Point($x,$y);
        $l = new Line();
        $l->setEndPoint($ep);
        
        $angle = $l->getAngle();
        
        $this->assertEquals(M_PI, $angle);
    }
    
    public function testAngle5()
    {
        $x = 1;
        $y = -1;
        $ep = new Point($x,$y);
        $l = new Line();
        $l->setEndPoint($ep);
        
        $angle = $l->getAngle();
        
        $this->assertEquals(-1 * M_PI_4, $angle);
    }
    
    public function testAngle6()
    {
        $x = 0;
        $y = -1;
        $ep = new Point($x,$y);
        $l = new Line();
        $l->setEndPoint($ep);
        
        $angle = $l->getAngle();
        
        $this->assertEquals(-1 * M_PI_2, $angle);
    }
    
    public function testAngle7()
    {
        $x = -1;
        $y = -1;
        $ep = new Point($x,$y);
        $l = new Line();
        $l->setEndPoint($ep);
        
        $angle = $l->getAngle();
        
        $this->assertEquals(-3 * M_PI_4, $angle);
    }
    
    public function testContains()
    {
        $l = new Line(new Point(0,0), new Point(10,10));
        
        for($i = 0; $i < 11 ; $i++) {
            $this->assertTrue($l->contains(new Point($i,$i)), "value: ".$i);
        }
            
        $this->assertFalse($l->contains(new Point(11,11)));    
        $this->assertFalse($l->contains(new Point(-1,-1)));
    }
    
    
    public function testMeets()
    {
        $l1 = new Line(new Point(0,0), new Point(10,10));
        $l2 = new Line(new Point(7,1), new Point(9,3));
        $l3 = new Line(new Point(0,10), new Point(10,0));
        
        $this->assertFalse($l1->meets($l2));
        $this->assertFalse($l2->meets($l1));
        
        $this->assertTrue($l1->meets($l3));
        $this->assertTrue($l3->meets($l1));
        
        $p1 = $l1->meetsAt($l3);
        $p2 = $l3->meetsAt($l1);
        
        $this->assertPoint($p1, 5, 5);
        $this->assertPoint($p2, 5, 5);
    }
    
    
    public function testMeets2()
    {
        $l1 = new Line(new Point(4,4), new Point(6,2));
        $l2 = new Line(new Point(4,2), new Point(6,4));
        
        $this->assertTrue($l2->meets($l1));
    }
    
    private function assertPoint(Point $pt, float $x = 0, float $y = 0)
    {
        $this->assertEquals($x, $pt->getX());
        $this->assertEquals($y, $pt->getY());
    }
}















