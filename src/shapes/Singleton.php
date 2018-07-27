<?php

namespace jmw\fabricad\shapes;

class Singleton extends Shape
{
    
    private $pt = null;
    
    public function __construct(Point $point = null)
    {
        if ($point != null) {
            $this->pt = Point::copyFrom($point);
        } else {
            $this->pt = new Point();
        }
    }
    
    public function contains(Point $pt): bool
    {
        return $this->pt->equals($pt);
    }

    public function move(float $x = 0, float $y = 0): Shape
    {
        $this->pt->addXY($x, $y);
        return $this;
    }

    public function getOrigin(): Point
    {
        return Point::copyFrom($this->pt);
    }

    public function asPolygon(): Polygon
    {
        return new Polygon([$this->pt]);
    }

    public function mirrorOnX(): Shape
    {
        $this->pt->mirrorOnX();
        return $this;
    }

    public function mirrorOnY(): Shape
    {
        $this->pt->mirrorOnY();
        return $this;
    }

    public function setOrigin(Point $pt): Shape
    {
        $this->pt = Point::copyFrom($pt);
        return $this;
    }

    public function clone(): Shape
    {
        return new Singleton($this->pt);
    }

    public function scale(float $x = 1, float $y = 1): Shape
    {
        $this->pt->multiplyXY($x, $y);
        return $this;
    }

    public function getBoundingBox(): Rectangle
    {
        return new Rectangle(0,0, $this->pt);
    }

    public function flip(): Shape
    {
        $this->pt->flip();
        return $this;
    }
    
    public function __tostring(): string
    {
        $s = get_class($this)."\n\t".$this->getOrigin()->__tostring()."\n";
        return $s;
    }

    
}