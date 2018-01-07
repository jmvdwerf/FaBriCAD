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
        $this->origin->set($orig);
        
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
     * Just touching returns false.
     * 
     * @param Shape $s
     * @return bool
     */
    public function intersects(Shape $s): bool
    {
       $b1 = $this->getBoundingBox();
       $b2 = $s->getBoundingBox();
       
       return 
           (
                $b1->getOrigin()->smallerThanOrEqual($b2->getTop())
               && $b1->getTop()->greaterThanOrEqual($b2->getOrigin())  
           ); 
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
    
}