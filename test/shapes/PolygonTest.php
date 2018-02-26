<?php
declare(strict_types=1);

namespace jmw\fabricad\shapes\test;

require_once 'AbstractShapeTest.php';

use jmw\fabricad\shapes\Line;
use jmw\fabricad\shapes\Point;
use jmw\fabricad\shapes\Polygon;
use jmw\fabricad\shapes\Rectangle;

final class PolygonTest extends AbstractShapeTest
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
        
        $this->assertTrue($r->contains(new Point(2,2)));
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
    
    public function testDeletePoint()
    {
        $r = $this->createRhombus();
        $orig = $this->createRhombus();
        
        $r->deletePoint(10);
        
        $this->assertEquals($r, $orig);
        
        $r->deletePoint(2);
        
        $this->assertEquals(3, $r->size());
        
        $expKey = 0;
        foreach($r->getPoints() as $key => $pt) {
            $this->assertEquals($expKey, $key);
            $expKey++;
        }
        
        $this->assertPoint($r->getPoints()[0], 2, 2);
        $this->assertPoint($r->getPoints()[1], 4, 4);
        $this->assertPoint($r->getPoints()[2], 4, 0);
    }
    
    public function testUpdatePoint()
    {
        $s = new Polygon(array(new Point(2,2)));
        $this->assertEquals(1, $s->size());
        
        $this->assertTrue($s->updatePointXY(0, 4, 4));
        
        $this->assertPoint($s->getOrigin(), 4, 4);
        
        $r = $this->createRhombus();
        $this->assertTrue($r->updatePoint(2, new Point(7,2)));
        
        $this->assertFalse($r->updatePointXY(12, 3, 4));
        
        $this->assertPoint($r->getPoints()[2], 7 ,2);
    }
    // *
    
    public function testIntersectionPoints()
    {
        $w = new Polygon([
            new Point(50, 5),        // 0
            new Point(100, 400),     // 1
            new Point(100, 200),     // 2
            new Point(350, 300),     // 3
            new Point(320, 100)      // 4
        ]);
        
        $l = new Line(new Point(400, 200), new Point(50, 300));
        
        $intersects = $w->intersectionPoints($l);
        
        $this->assertCount(4, $intersects);
    }
    
    public function testDirection()
    {
        $p = $this->createRhombus();
        $this->assertEquals(Polygon::DIRECTION_CLOCKWISE, $p->direction());
        
        $p2 = $p->mirrorOnX();
        $this->assertEquals(Polygon::DIRECTION_COUNTERCLOCKWISE, $p2->direction());
    }
    
    public function testCalculateIntersectionPointsWith()
    {
        $r1 = new Rectangle(100,100);
        $r2 = new Rectangle(100,100, new Point(50,50));
        
        $pts = $r1->calculateIntersectionPointsWith($r2);
        $this->assertCount(6, $pts->getPoints());
        $this->assertPoint($pts->getPoints()[0],   0,   0);
        $this->assertPoint($pts->getPoints()[1],   0, 100);
        $this->assertPoint($pts->getPoints()[2],  50, 100);
        $this->assertPoint($pts->getPoints()[3], 100, 100);
        $this->assertPoint($pts->getPoints()[4], 100,  50);
        $this->assertPoint($pts->getPoints()[5], 100,   0);
        
    }
    
    
    
    public function testSimplify()
    {
        $r1 = new Rectangle(100,100);
        $r2 = new Rectangle(100,100, new Point(50,50));
        
        $pts = $r1->calculateIntersectionPointsWith($r2)->expand()->simplify();
        $this->assertCount(4, $pts->getPoints());
        $this->assertPoint($pts->getPoints()[0],   0,   0);
        $this->assertPoint($pts->getPoints()[1],   0, 100);
        $this->assertPoint($pts->getPoints()[2], 100, 100);
        $this->assertPoint($pts->getPoints()[3], 100,   0);
    }
    
    
    public function testClone()
    {
        $q = new Polygon([
            new Point(1,1),
            new Point(1,4),
            new Point(4,4),
            new Point(4,1)
        ]);
        
        $c = $q->clone();
        
        $this->assertInstanceOf(Polygon::class, $c);
        
        $q->updatePointXY(1, 1, 3);
        $q->updatePointXY(2, 4, 3);
        
        $this->assertBoundingBox($c, 1, 1, 3, 3);
        $this->assertBoundingBox($q, 1, 1, 3, 2);
    }
    
    
    protected function createRhombus(): Polygon
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
}