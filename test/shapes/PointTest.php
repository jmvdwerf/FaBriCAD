<?php
declare(strict_types=1);

namespace jmw\fabricad\shapes\test;


use PHPUnit\Framework\TestCase;
use jmw\fabricad\shapes\Point;

final class PointTest extends TestCase
{
    /**
     * Test whether the Construct method works
     */
    public function testConstruct()
    {
        $valX = rand();
        $valY = rand();
        $p = new Point($valX, $valY);
        $this->assertEquals($valX, $p->getX());
        $this->assertEquals($valY, $p->getY());
    }
    
    public function testSetX()
    {
        $val = rand();
        $p = new Point();
        $p->setX($val);
        $this->assertEquals($val, $p->getX());
    }
    
    public function testSetY()
    {
        $val = rand();
        $p = new Point();
        $p->setY($val);
        $this->assertEquals($val, $p->getY());
    }
    
    public function testSet()
    {
        $valX = rand();
        $valY = rand();
        $p = new Point();
        $p2 = new Point($valX, $valY);
        $p->set($p2);
        $this->assertEquals($valX, $p->getX());
        $this->assertEquals($valY, $p->getY());
    }
    
    public function testMirrorX()
    {
        $valX = rand();
        $valY = rand();
        $p = new Point($valX, $valY);
        $p->mirrorOnX();
        
        $this->assertEquals(-1 * $valX, $p->getX());
        $this->assertEquals($valY, $p->getY());
    }
    
    public function testMirrorY()
    {
        $valX = rand();
        $valY = rand();
        $p = new Point($valX, $valY);
        $p->mirrorOnY();
        
        $this->assertEquals($valX, $p->getX());
        $this->assertEquals(-1*$valY, $p->getY());
    }
    
    public function testMax()
    {
        $valX = rand();
        $valY = rand();
        $valMX = rand();
        $valMY = rand();
        
        $p = new Point($valX, $valY);
        $p2 = new Point($valMX, $valMY);
        
        $p->max($p2);
        $this->assertEquals(max($valX,$valMX), $p->getX());
        $this->assertEquals(max($valY,$valMY), $p->getY());
    }
    
    public function testMin()
    {
        $valX = rand();
        $valY = rand();
        $valMX = rand();
        $valMY = rand();
        
        $p = new Point($valX, $valY);
        $p2 = new Point($valMX, $valMY);
        
        $p->min($p2);
        $this->assertEquals(min($valX,$valMX), $p->getX());
        $this->assertEquals(min($valY,$valMY), $p->getY());
    }
    
    public function testPrint()
    {
        $p = new Point(1.23456789, 9.87654321);
        $this->assertSame('[ 1.2346, 9.8765 ]', $p->printAsScad(4));
        $this->assertSame('1.235, 9.877', $p->__tostring());
    }
    
    public function testAdd()
    {
        $valX = rand();
        $valY = rand();
        $valMX = rand();
        $valMY = rand();
        
        $p = new Point($valX, $valY);
        $p2 = new Point($valMX, $valMY);
        
        $p->add($p2);
        
        $this->assertEquals($valX+$valMX, $p->getX());
        $this->assertEquals($valY+$valMY, $p->getY());
    }
    
    public function testMultiply()
    {
        $valX = rand();
        $valY = rand();
        $valMX = rand();
        $valMY = rand();
        
        $p = new Point($valX, $valY);
        $p2 = new Point($valMX, $valMY);
        
        $p->multiply($p2);
        
        $this->assertEquals($valX*$valMX, $p->getX());
        $this->assertEquals($valY*$valMY, $p->getY());
    }
    
    
    public function testGreaterSmallerThan()
    {
        $p1 = new Point(1,2);
        $p2 = new Point(2,3);
        $p3 = new Point(1,3);
        $p4 = new Point (2,2);
        $p5 = new Point(0,1);
        
        $this->assertFalse($p1->greaterThan($p2));
        $this->assertTrue($p1->smallerThan($p2));
        
        $this->assertFalse($p1->smallerThan($p3));
        $this->assertFalse($p1->greaterThan($p3));
        
        $this->assertFalse($p1->greaterThan($p4));
        $this->assertFalse($p1->smallerThan($p4));
        
        $this->assertTrue($p1->greaterThan($p5));
        $this->assertFalse($p1->smallerThan($p5));
    }
    
    public function testCopyFrom()
    {
        $x = rand();
        $y = rand();
        
        $p = new Point($x, $y);
        $q = Point::copyFrom($p);
        
        $p->setX($x+1);
        
        $this->assertFalse($p == $q);
    }
}










