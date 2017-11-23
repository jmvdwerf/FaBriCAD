<?php
namespace jmw\frabricad\shapes;

class Rectangle extends Shape
{
    
    private $height = 0.0;
    private $width = 0.0;
    
    /**
     * Constructor, optional height and width to provide
     * @param float $h
     * @param float $w
     */
    public function __construct(float $w = 0.0, float $h = 0.0, Point $origin = null)
    {
        parent::__construct($origin);
        $this->setHeight($h);
        $this->setWidth($w);
    }
    
    /**
     * Returns the height of the object
     * @return float
     */
    public function getHeight(): float
    {
        return $this->height;
    }
    
    /**
     * Returns the width of the object
     * @return float
     */
    public function getWidth(): float
    {
        return $this->width;
    }
    
    /**
     * Sets the height
     * 
     * @param float $h
     * @return Rectangle
     */
    public function setHeight(float $h): Rectangle
    {
        $this->height = $h;
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
        $this->width = $w;
        
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
        return new Point(
            $this->getOrigin()->getX()+$this->getWidth(), 
            $this->getOrigin()->getY()+$this->getHeight()
        );
    }
    
    /**
     * Mirrors the rectangle over the Y-axis
     * 
     * @return Rectangle
     */
    public function mirrorOnX(): Shape
    {
        $this->origin->setX(-1* $this->origin->getX() - $this->width);
        return $this;
    }
    
    /**
     * Mirrors the rectangle over the X-axis
     * 
     * @return Rectangle
     */
    public function mirrorOnY(): Shape
    {
        $this->origin->setY(-1* $this->origin->getY() - $this->height);
        return $this;
    }
    
    /**
     * Returns the bounding box of the object. As this is the rectangle itself,
     * we just return the object itself.
     * {@inheritDoc}
     * @see \jmw\HuisBouwer\shapes\Shape::getBoundingBox()
     */
    public function getBoundingBox(): Rectangle
    {
        return new Rectangle($this->getWidth(), $this->getHeight(), $this->getOrigin());
    }
    
    public function toPolygon(): Polygon
    {
        $o = $this->getOrigin();
        return new Polygon(array(
            new Point($o->getX(), $o->getY()),
            new Point($o->getX(), $o->getY() + $this->getHeight()),
            new Point($o->getX() + $this->getWidth(), $o->getY() + $this->getHeight()),
            new Point($o->getX() + $this->getWidth(), $o->getY()),
        ));
    }

    public function contains(Point $pt): bool
    {
        return 
            (  ($this->getTop()->greaterThan($pt))
            && ($this->getOrigin()->smallerThan($pt))
            );
    }
    
}







