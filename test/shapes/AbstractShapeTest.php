<?php
declare(strict_types=1);

namespace jmw\fabricad\shapes\test;

use PHPUnit\Framework\TestCase;
use jmw\fabricad\shapes\Line;
use jmw\fabricad\shapes\Point;
use jmw\fabricad\shapes\Shape;
use jmw\fabricad\shapes\Rectangle;

abstract class AbstractShapeTest extends TestCase
{
    public function assertBoundingBox(Shape $s, $x = 0, $y = 0, $w = 0, $h = 0)
    {
        $this->assertRectangle($s->getBoundingBox(), $x, $y, $w, $h);
    }
    
    public function assertRectangle(Rectangle $s, $x = 0, $y = 0, $w = 0, $h = 0)
    {
        $this->assertPoint($s->getOrigin(), $x, $y);
        $this->assertEquals($h, $s->getHeight());
        $this->assertEquals($w, $s->getWidth());
    }
    
    public function assertPoint(Point $pt, float $x = 0, float $y = 0)
    {
        $this->assertEquals($x, $pt->getX(), 'Point X failed');
        $this->assertEquals($y, $pt->getY(), 'Point Y failed');
    }
    
    public function assertLine(Line $l, float $sX, float $sY, float $eX, float $eY)
    {
        $this->assertPoint($l->getOrigin(), $sX, $sY);
        $this->assertPoint($l->getEndPoint(), $eX, $eY);
    }
}