<?php

declare(strict_types=1);

namespace jmw\fabricad\shapes\test;

require_once('AbstractShapeTest.php');

use jmw\fabricad\shapes\Point;
use jmw\fabricad\shapes\Line;

final class LineTest extends AbstractShapeTest
{ 
    
    public function testBoundingBox()
    {
        $l = new Line(new Point(0,0), new Point(5,5));
        $this->assertBoundingBox($l, 0,0, 5,5);
        
        $l = new Line(new Point(2,2), new Point(5,5));
        $this->assertBoundingBox($l, 2,2, 3, 3);
        
        $l = new Line(new Point(0,0), new Point(-5,-5));
        $this->assertBoundingBox($l, -5,-5, 5,5);
        
        
    }
    
    
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
        $l = new Line(new Point(0,0), $ep);
        
        $angle = $l->getAngle();
        
        $this->assertEquals(0, $angle);
    }
    
    public function testAngle1()
    {
        $x = 1;
        $y = 1;
        $ep = new Point($x,$y);
        $l = new Line(new Point(0,0), $ep);
        
        $angle = $l->getAngle();
        
        $this->assertEquals(M_PI_4, $angle);
    }
    
    public function testAngle2()
    {
        $x = 0;
        $y = 1;
        $ep = new Point($x,$y);
        $l = new Line(new Point(0,0), $ep);
        
        $angle = $l->getAngle();
        
        $this->assertEquals(M_PI_2, $angle);
    }
    
    public function testAngle3()
    {
        $x = -1;
        $y = 1;
        $ep = new Point($x,$y);
        $l = new Line(new Point(0,0), $ep);
        
        $angle = $l->getAngle();
        
        $this->assertEquals(3 * M_PI_4, $angle);
    }
    
    public function testAngle4()
    {
        $x = -1;
        $y = 0;
        $ep = new Point($x,$y);
        $l = new Line(new Point(0,0), $ep);
        
        $angle = $l->getAngle();
        
        $this->assertEquals(M_PI, $angle);
    }
    
    public function testAngle5()
    {
        $x = 1;
        $y = -1;
        $ep = new Point($x,$y);
        $l = new Line(new Point(0,0), $ep);
        
        $angle = $l->getAngle();
        
        $this->assertEquals(-1 * M_PI_4, $angle);
    }
    
    public function testAngle6()
    {
        $x = 0;
        $y = -1;
        $ep = new Point($x,$y);
        $l = new Line(new Point(0,0), $ep);
        
        $angle = $l->getAngle();
        
        $this->assertEquals(-1 * M_PI_2, $angle);
    }
    
    public function testAngle7()
    {
        $x = -1;
        $y = -1;
        $ep = new Point($x,$y);
        $l = new Line(new Point(0,0), $ep);
        
        $angle = $l->getAngle();
        
        $this->assertEquals(-3 * M_PI_4, $angle);
    }
    
    
    public function testContains()
    {
        $l = new Line(new Point(0,0), new Point(10,10));
        
        for($i = 1; $i < 10 ; $i++) {
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
    
    public function testMeets3()
    {
        $l1 = new Line(new Point(100,300), new Point(300, 500));
        $l2 = new Line(new Point(300,100), new Point(200, 600));
        
        $this->assertBoundingBox($l1, 100,300,200,200);
        $this->assertBoundingBox($l2, 200,100,100,500);
        
        $this->assertTrue($l1->intersects($l2));
        
        $this->assertTrue($l2->meets($l1));
    }
    
    public function testLineOrder()
    {
        $l1 = new Line(new Point(10,10), new Point(100,10));
        $l2 = new Line(new Point(100,10), new Point(10,10));
        
        $points = array(new Point(30,10), new Point(10,10), new Point(20,10), new Point(40,10));
        
        $l1->orderPoints($points);
        
        $this->assertEquals(10, $points[0]->getX());
        $this->assertEquals(20, $points[1]->getX());
        $this->assertEquals(30, $points[2]->getX());
        $this->assertEquals(40, $points[3]->getX());
    }
    
    public function testLineReverseOrder()
    {
        $l1 = new Line(new Point(100,10), new Point(10,10));
        
        $points = array(new Point(30,10), new Point(10,10), new Point(20,10), new Point(40,10));
        
        $l1->orderPoints($points);
        
        $this->assertEquals(10, $points[3]->getX());
        $this->assertEquals(20, $points[2]->getX());
        $this->assertEquals(30, $points[1]->getX());
        $this->assertEquals(40, $points[0]->getX());
    }

    
    public function testMeetPoints() 
    {
        $l1 = new Line(new Point(500, 50), new Point(400, 200));
        $l2 = new Line(new Point(450, 600), new Point(300,100));
        
        $this->assertFalse($l1->isPerpendicular());
        $this->assertFalse($l2->isPerpendicular());
        
        $this->assertFalse($l1->meets($l2));
        $this->assertFalse($l2->meets($l1));
    }
    
    
    public function testMeetLines() {
        $l1 = new Line(new Point(400, 200), new Point(50,300));
        $l2 = new Line(new Point(100,400), new Point(100,200));
        
        $this->assertTrue($l1->meets($l2));
        $this->assertTrue($l2->meets($l1));
    }
    
    
    public function testPerpendicular()
    {
        $l1 = new Line(new Point(0,0), new Point(100,50));
        $l2 = new Line(new Point(50,0), new Point(50,100));
        
        $this->assertFalse($l1->isPerpendicular());
        $this->assertTrue($l2->isPerpendicular());
        
        $this->assertTrue($l1->meets($l2));
        $this->assertTrue($l2->meets($l1));
        
        $this->assertPoint($l1->meetsAt($l2), 50, 25);
        $this->assertPoint($l2->meetsAt($l1), 50, 25);
        
    }
    
    
    public function testMeetLines2() 
    {
        $l1 = new Line(new Point(150,50), new Point(50,50));
        $l2 = new Line(new Point(100,100), new Point(100,0));
        
        $this->assertTrue($l1->meets($l2));
        $this->assertTrue($l1->intersects($l2));
        
        $this->assertTrue($l1->intersects($l2));
        $this->assertTrue($l2->intersects($l1));
    }
    
    
    public function testVector()
    {
        $angle = atan(4/3);
        $length = 5;
        
        $l = Line::fromVector($length, $angle);
        
        $this->assertEquals($angle, $l->getAngle()  , $delta=0.0001);
        $this->assertEquals($length, $l->getLength(), $delta=0.0001);
        
        $l2 = Line::fromVector($length, $angle, new Point(3,3));
        
        $this->assertPoint($l2->getEndPoint(), 3+3, 3+4);
        $this->assertPoint($l2->getOrigin(), 3, 3);
    }
    
    public function testClone()
    {
        $l = new Line(new Point(1,1), new Point(4,5));
        
        $c = $l->clone();
        
        $this->assertInstanceOf(Line::class, $c);
        
        $this->assertFalse($l === $c);
    }
    
    public function testFlip()
    {
        $x1 = rand();
        $x2 = rand();
        $y1 = rand();
        $y2 = rand();
        
        $l = new Line(new Point($x1, $y1), new Point($x2, $y2));
        
        $flipped = $l->flip();
        
        $this->assertPoint($flipped->getOrigin(), $y1, $x1);
        $this->assertPoint($flipped->getEndPoint(), $y2, $x2);
    }
    
    public function testMove()
    {
        $x1 = rand();
        $x2 = rand();
        $y1 = rand();
        $y2 = rand();
        
        $mx = rand();
        $my = rand();
        
        $l = new Line(new Point($x1, $y1), new Point($x2, $y2));
        $l->move($mx, $my);
        
        $this->assertPoint($l->getOrigin() , $x1 + $mx,  $y1 + $my);
        $this->assertPoint($l->getEndPoint(), $x2 + $mx, $y2 + $my);
    }
    
    public function testScale()
    {
        $x1 = rand();
        $x2 = rand();
        $y1 = rand();
        $y2 = rand();
        
        $mx = rand();
        $my = rand();
        
        $l = new Line(new Point($x1, $y1), new Point($x2, $y2));
        $l->scale($mx, $my);
        
        $this->assertPoint($l->getOrigin() , $x1,  $y1);
        $this->assertPoint($l->getEndPoint(), $x2 * $mx, $y2 * $my);
    }
}















