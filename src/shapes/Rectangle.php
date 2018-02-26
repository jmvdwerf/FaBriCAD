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
        $y = $this->getOrigin()->getY() + $h;
        
        $this->points[Quadrangle::NORTHEAST]->setY($y);
        $this->points[Quadrangle::NORTHWEST]->setY($y);
        
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
        $x = $this->getOrigin()->getX() + $w;
        
        $this->points[Quadrangle::SOUTHEAST]->setX($x);
        $this->points[Quadrangle::NORTHEAST]->setX($x);
        
        return $this;
    }
    
    /**
     * Sets the new top point. It moves the whole thing down, i.e., it ensures
     * that the height and width remain the same.
     * 
     * @param Point $pt
     * @return Rectangle
     */
    public function setTop(Point $pt): Rectangle
    {
        $width = $this->getWidth();
        $height = $this->getHeight();
        
        $orig = new Point($pt->getX() - $width, $pt->getY() - $height);
        $this->setOrigin($orig);
        
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
    
    /**
     * Creates a rectangle from two points.
     * @param Point $orig
     * @param Point $top
     * @return Rectangle
     */
    public static function fromPoints(Point $orig, Point $top): Rectangle
    {       
        $x = min($orig->getX(), $top->getX() );
        $y = min($orig->getY(), $top->getY() );
        
        $w = ($orig->getX() == $x) ? $top->getX() - $x : $orig->getX() - $x;
        $h = ($orig->getY() == $y) ? $top->getY() - $y : $orig->getY() - $y;
                
        return new Rectangle($w, $h, new Point($x,$y) );
    }
    
    public function flip(): Shape
    {
        return new Rectangle($this->getHeight(), $this->getWidth(), $this->getOrigin()->flip());
    }
    
    public function clone(): Shape
    {
        return new Rectangle($this->getWidth(), $this->getHeight(), $this->getOrigin());
    }
    
}







