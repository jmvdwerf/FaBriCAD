<?php
declare(strict_types=1);

namespace jmw\fabricad\shapes\test;

use jmw\fabricad\shapes\Quadrangle;
use jmw\fabricad\shapes\Point;

final class QuadrangleTest extends AbstractShapeTest
{
    public function testBoundingBox()
    {
        $s = $this->createRhombus();
        $this->assertBoundingBox($s, 2,0,4,4);
        
    }
    
    public function testSetPoints()
    {
        $s = new Quadrangle(new Point(), new Point(), new Point(), new Point());
        
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
    
    public function testClone()
    {
        $q = new Quadrangle(
            new Point(1,1),
            new Point(1,4),
            new Point(4,4),
            new Point(4,1)
        );
        
        $c = $q->clone();
        
        $this->assertInstanceOf(Quadrangle::class, $c);
        
        
        $q->setNorthEast(new Point(1,3));
        $q->setNorthWest(new Point(4,3));
        
        $this->assertBoundingBox($c, 1, 1, 3, 3);
        $this->assertBoundingBox($q, 1, 1, 3, 2);
        
    }
    
    public function testFlip()
    {
        $flipped = $this->createRhombus()->flip();
        
        $this->assertPoint($flipped->getSouthWest(), 2, 2);
        $this->assertPoint($flipped->getNorthWest(), 0, 4);
        $this->assertPoint($flipped->getNorthEast(), 2, 6);
        $this->assertPoint($flipped->getSouthEast(), 4, 4);
        
    }
    
    private function createRhombus(): Quadrangle
    {
        return new Quadrangle(
                new Point(2,2),
                new Point(4,4),
                new Point(6,2),
                new Point(4,0)
        );
    }
    
    
}