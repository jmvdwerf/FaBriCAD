<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;

use jmw\frabricad\shapes\Point;
use jmw\frabricad\shapes\Polygon;
use jmw\frabricad\shapes\Shape;
use jmw\frabricad\shapes\Line;

final class PolygonTest extends TestCase
{
    
    public function testEmptiness()
    {
        $p = new Polygon();
        
        $this->assertPoint($p->getOrigin());
        
        $this->assertTrue($p->isEmpty());
        
        $p->mirrorOnX();
        $this->assertBoundingBox($p);
        
        $p->mirrorOnY();
        $this->assertBoundingBox($p);
    }
    
    public function testSinglePoint()
    {
        $p = new Polygon(array(new Point(3,5)));
        $this->assertBoundingBox($p, 3, 5);
    }
    
    public function testSinglePointOnMirrorX()
    {
        $p = new Polygon(array(new Point(3,5)));
        $p->mirrorOnX();
        $this->assertBoundingBox($p, -3, 5);
    }
    
    public function testSinglePointOnMirrorY()
    {
        $p = new Polygon(array(new Point(3,5)));
        $p->mirrorOnY();
        $this->assertBoundingBox($p, 3, -5);
    }
    
    
    public function testBoundingBox()
    {
        $r = $this->createRhombus();
        $this->assertBoundingBox($r, 2, 0, 4, 4);
    }
    
    public function testMirrorOnX()
    {
        $r = $this->createRhombus();
        $r->mirrorOnX();
        $this->assertBoundingBox($r,-6,0,4,4);
    }
    
    public function testMirrorOnY()
    {
        $r = $this->createRhombus();
        $r->mirrorOnY();
        $this->assertBoundingBox($r, 2,-4,4,4);
    }
    
    public function testOrigin()
    {
        $r = $this->createRhombus();
        $this->assertEquals(2, $r->getOrigin()->getX());
        $this->assertEquals(2, $r->getOrigin()->getY());
    }
    
    public function testMoveOrigin()
    {
        $r = $this->createRhombus();
        $r->setOrigin(new Point(-4,20));
        $this->assertBoundingBox($r, -4, 18, 4,4);
    }
    
    public function testPointOrder()
    {
        $r = $this->createRhombus();
        $points = $r->getPoints();
        $this->assertCount(4, $points);
        $this->assertPoint($points[0], 2,2);
        $this->assertPoint($points[1], 4,4);
        $this->assertPoint($points[2], 6,2);
        $this->assertPoint($points[3], 4,0);
    }
    
    public function testGetLines()
    {
        $r = $this->createRhombus();
        $lines = $r->getLines();
        $this->assertCount(4, $lines);
        $this->assertLine($lines[0], 2,2, 4,4);
        $this->assertLine($lines[1], 4,4, 6,2);
        $this->assertLine($lines[2], 6,2, 4,0);
        $this->assertLine($lines[3], 4,0, 2,2);
    }
    
    public function testContains()
    {
        $r = $this->createRhombus();
        
        $this->assertFalse($r->contains(new Point(2,2)));
        $this->assertFalse($r->contains(new Point(1,1)));
        $this->assertTrue($r->contains(new Point(4,2)));
        $this->assertFalse($r->contains(new Point(5,5)));
        
        $s = $this->createStar();
        
        $this->assertFalse($s->contains(new Point(2,3)));
        $this->assertFalse($s->contains(new Point(5,3)));
        $this->assertTrue($s->contains(new Point(3,0)));
        $this->assertTrue($s->contains(new Point(3,2)));
        $this->assertTrue($s->contains(new Point(3,-2)));
        
    }
    
    public function testConvex()
    {
        $r = $this->createRhombus();
        
        $this->assertTrue($r->isConvex());
        
        $s = $this->createStar();
        
        $this->assertFalse($s->isConvex());
        
        $m = $this->createMonster();
        
        $this->assertFalse($m->isConvex());
    }
    
    
    private function createRhombus(): Polygon
    {
        return new Polygon(
            array(
                new Point(2,2),
                new Point(4,4),
                new Point(6,2),
                new Point(4,0),
                new Point(4,0)
            )
        );
    }
    
    private function createStar(): Polygon
    {
        return new Polygon(
            array(
                new Point(0,0),
                new Point(2,1),
                new Point(3,3),
                new Point(4,1),
                new Point(6,0),
                new Point(4,-1),
                new Point(3,-3),
                new Point(2,-1)
            )    
        );
    }
    
    private function createMonster(): Polygon
    {
        return new Polygon(
            array(
                new Point(0,0),
                new Point(2,2),
                new Point(0,4),
                new Point(4,4),
                new Point(0,4)
            )
        );
    }
    
    private function assertBoundingBox(Shape $s, $x = 0, $y = 0, $w = 0, $h = 0)
    {
        $bb = $s->getBoundingBox();
        $this->assertPoint($bb->getOrigin(), $x, $y);
        $this->assertEquals($h, $bb->getHeight());
        $this->assertEquals($w, $bb->getWidth());
    }
    
    private function assertPoint(Point $pt, float $x = 0, float $y = 0)
    {
        $this->assertEquals($x, $pt->getX());
        $this->assertEquals($y, $pt->getY());
    }
    
    private function assertLine(Line $l, float $sX, float $sY, float $eX, float $eY)
    {
        $this->assertPoint($l->getOrigin(), $sX, $sY);
        $this->assertPoint($l->getEndPoint(), $eX, $eY);
    }
    
    
}