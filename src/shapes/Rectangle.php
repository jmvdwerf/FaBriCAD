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
    
    protected function updateRectangle(float $width, float $height, float $x, float $y): Rectangle
    {
        $this->updatePointXY(Quadrangle::SOUTHWEST, $x, $y);
        $this->updatePointXY(Quadrangle::NORTHWEST, $x, $y + $height);
        $this->updatePointXY(Quadrangle::NORTHEAST, $x + $width, $y + $height);
        $this->updatePointXY(Quadrangle::SOUTHEAST, $x + $width, $y);
        
        return $this;
    }
    
    /**
     * Sets the new origin. It moves the whole thing, ensuring that the height
     * and width remain the same.
     *
     * @param Point $pt
     * @return Rectangle
     */
    public function setOrigin(Point $pt): Rectangle
    {
        return $this->updateRectangle($this->getWidth(), $this->getHeight(), $pt->getX(), $pt->getY());
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
        
        return $this->updateRectangle($width, $height, $pt->getX() - $width, $pt->getY() - $height);
    }
    
    /**
     * Returns the top point
     * @return Point
     */
    public function getTop(): Point
    {
        return $this->getNorthEast();
    }
    
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
    
    public function scale(float $x = 1, float $y = 1): Shape
    {
        $w = $x * $this->getWidth();
        $h = $y * $this->getHeight();
        
        return $this->updateRectangle($w, $h, $this->getOrigin()->getX(), $this->getOrigin()->getY());
    }
    
}







