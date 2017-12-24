<?php
namespace jmw\fabricad\shapes;

class Rectangle extends Quadrangle
{
    
    /**
     * Constructor, optional height and width to provide
     * @param float $h
     * @param float $w
     */
    public function __construct(float $w = 0.0, float $h = 0.0, Point $origin = null)
    {
        if ($origin == null) { $origin = new Point(); }
        
        $nw = new Point($origin->getX(), $origin->getY() + $h);
        $ne = new Point($origin->getX() + $w, $origin->getY() + $h);
        $se = new Point($origin->getX() + $w, $origin->getY());
        
        parent::__construct($origin, $nw, $ne, $se);
    }
    
    /**
     * Returns the height of the object
     * @return float
     */
    public function getHeight(): float
    {
        return abs($this->getNorthWest()->getY() - $this->getSouthWest()->getY());
    }
    
    /**
     * Returns the width of the object
     * @return float
     */
    public function getWidth(): float
    {
        return abs($this->getSouthWest()->getX() - $this->getSouthEast()->getX());
    }
    
    /**
     * Sets the height
     * 
     * @param float $h
     * @return Rectangle
     */
    public function setHeight(float $h): Rectangle
    {
        $this->getNorthEast()->setY($this->getOrigin()->getY() + $h);
        $this->getNorthWest()->setY($this->getNorthEast()->getY());
        return $this;
    }
    
    /**
     * Sets the width of the object
     * 
     * @param float $w
     * @return Rectangle
     */
    public function setWidth(float $w): Rectangle
    {
        $this->getSouthEast()->setX($this->getOrigin()->getX() + $w);
        $this->getNorthEast()->setX($this->getSouthEast()->getX());
        return $this;
    }
    
    /**
     * Sets the new top point. If the X or Y is smaller than the current origin,
     * the origin is moved accordingly.
     * 
     * @param Point $pt
     * @return Rectangle
     */
    public function setTop(Point $pt): Rectangle
    {
        $newW = $pt->getX() - $this->getOrigin()->getX();
        if ($newW < 0) {
            $this->getOrigin()->setX($this->getOrigin()->getX()-$newW);
            $this->setWidth(-1*$newW);
        } else {
            $this->setWidth($newW);
        }
        $newH = $pt->getY() - $this->getOrigin()->getY();
        if ($newH < 0) {
            $this->getOrigin()->setY($this->getOrigin()->getY()-$newH);
            $this->setHeight(-1*$newH);
        } else {
            $this->setHeight($newH);
        }
        
        return $this;
    }
    
    /**
     * Returns the top point
     * @return Point
     */
    public function getTop(): Point
    {
        return $this->getNorthEast();
    }
    
    /*
     * Mirrors the rectangle over the Y-axis
     * 
     * @return Rectangle
     *
    public function mirrorOnX(): Shape
    {
        $this->origin->setX(-1* $this->origin->getX() - $this->width);
        return $this;
    }
    
    /**
     * Mirrors the rectangle over the X-axis
     * 
     * @return Rectangle
     *
    public function mirrorOnY(): Shape
    {
        $this->origin->setY(-1* $this->origin->getY() - $this->height);
        return $this;
    }*/
    
    /**
     * Returns the bounding box of the object. As this is the rectangle itself,
     * we just return the object itself.
     * {@inheritDoc}
     * @see \jmw\fabricad\shapes\Shape::getBoundingBox()
     */
    public function getBoundingBox(): Rectangle
    {
        return new Rectangle($this->getWidth(), $this->getHeight(), $this->getOrigin());
    }

    public function contains(Point $pt): bool
    {
        
        return 
            (  ($this->getTop()->greaterThanOrEqual($pt))
            && ($this->getOrigin()->smallerThanOrEqual($pt))
            );
    }
    
}







