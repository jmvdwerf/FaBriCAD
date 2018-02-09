<?php
namespace jmw\fabricad\shapes;

abstract class Shape
{
    protected $origin = null;
    
    public function __construct(Point $origin = null)
    {
        if (empty($origin)) $origin = new Point(0,0);
        $this->origin = $origin;
    }
    
    /**
     * Sets the origin of the shape
     * 
     * @param Point $orig
     * @return Shape
     */
    public function setOrigin(Point $orig): Shape
    {
        $this->setOriginXY($orig);
        
        return $this;
    }
    
    public function setOriginXY(float $x, float $y)
    {
        $this->origin->setX($x);
        $this->origin->setY($y);
    }
    
    /**
     * Returns the origin of this shape
     * @return Point
     */
    public function getOrigin(): Point
    {
        return Point::copyFrom($this->origin);
    }
    
    /**
     * Returns true if the shape $s intersects with this shape. 
     * Just touching is sufficient!
     * 
     * @param Shape $s
     * @return bool
     */
    public function intersects(Shape $s): bool
    {
       $b1 = $this->getBoundingBox();
       $b2 = $s->getBoundingBox();

       $first = $b1->getOrigin()->smallerThan($b2->getTop());
       $second = $b1->getTop()->greaterThanOrEqual($b2->getOrigin());
       $res = $first && $second;

       return $res; 
    }
    
    public function isContainedIn(Shape $s): bool
    {
        // then my origin should be inside Shape $s
        // and my top should be inside Shape $s
        $bb = $this->getBoundingBox();
        return $s->contains($bb->getOrigin()) && $s->contains($bb->getTop());
    }
    
    public function asPolygon(): Polygon
    {
        return $this->getBoundingBox();
    }
    
    
    /**
     * Returns the boundingbox of this shape
     * @return Rectangle
     */
    public abstract function getBoundingBox(): Rectangle;
    
    public abstract function mirrorOnX(): Shape;
    public abstract function mirrorOnY(): Shape;
    
    public function contains(Point $pt): bool
    {
        return $this->getBoundingBox()->contains($pt);
    }
    
    public function __tostring()
    {
        $str = __CLASS__;
        return $str;
    }
    
    public abstract function flip(): Shape;
    
}