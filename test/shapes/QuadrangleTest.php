<?php
declare(strict_types=1);

namespace jmw\fabricad\shapes\test;

use jmw\fabricad\shapes\Quadrangle;
use jmw\fabricad\shapes\Point;

final class QuadrangleTest extends AbstractShapeTest
{
    public function testSizeEmptiness()
    {
        $s = new Quadrangle();
        $this->assertEquals(4, $s->size());
    }
    
    public function testBoundingBox()
    {
        $s = $this->createRhombus();
        $this->assertBoundingBox($s, 2,0,4,4);
        
    }
    
    public function testSetPoints()
    {
        $s = new Quadrangle();
        
        $s->setSouthEast(new Point(4,0));
        $this->assertPoint($s->getPoints()[3], 4, 0);
        
        $s->setNorthWest(new Point(4,4));
        $this->assertPoint($s->getPoints()[1], 4, 4);
        
        $s->setSouthWest(new Point(2,2));
        $this->assertPoint($s->getPoints()[0], 2, 2);
        
        $s->setNorthEast(new Point(6,2));
        $this->assertPoint($s->getPoints()[2], 6, 2);
        
        $this->assertPoint($s->getSouthWest(), 2, 2);
        $this->assertPoint($s->getNorthWest(), 4, 4);
        $this->assertPoint($s->getNorthEast(), 6, 2);
        $this->assertPoint($s->getSouthEast(), 4, 0);
        
        $this->assertBoundingBox($s, 2,0,4,4);
    }
    
    private function createRhombus()
    {
        return new Quadrangle(
                new Point(2,2),
                new Point(4,4),
                new Point(6,2),
                new Point(4,0)
        );
    }
}