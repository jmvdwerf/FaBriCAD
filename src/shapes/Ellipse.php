<?php
namespace jmw\fabricad\shapes;


class Ellipse extends Shape
{
    
    protected $origin = null;
    
    private $a = 0.0;
    private $b = 0.0;
    
    const NORTHEAST = 0;
    const NORTHWEST = 1;
    const SOUTHWEST = 2;
    const SOUTHEAST = 3;
    
    public function __construct(float $a = 0,float $b = 0, $origin = null)
    {
        $this->origin = new Point();
        
        $this->setXFactor($a);
        $this->setYFactor($b);
        
        if (!empty($origin)) {
            $this->origin = $origin;
        }
    }
    
    public function setOrigin(Point $pt): Ellipse
    {
        return $this->setOriginXY($pt->getX(), $pt->getY());
    }
    
    public function setOriginXY(float $x = 0, float $y = 0): Ellipse
    {
        $this->origin->setXY($x, $y);
        return $this;
    }
    
    public function getOrigin(): Point
    {
        return Point::copyFrom($this->origin);
    }
    
    public function getXFactor(): float
    {
        return $this->a;
    }
    
    public function getYFactor(): float
    {
        return $this->b;
    }
    
    public function setXFactor(float $a): Ellipse
    {
        $this->a = $a;
        return $this;
    }
    
    public function setYFactor(float $b): Ellipse
    {
        $this->b = $b;
        return $this;
    }
    
    public function getBoundingBox(): Rectangle
    {
        return new Rectangle(
            2*$this->getXFactor(), 
            2*$this->getYFactor(), 
            new Point($this->getOrigin()->getX()-$this->getXFactor(),$this->getOrigin()->getY()-$this->getYFactor())
        );
    }
    public function mirrorOnX(): Shape
    {
        $this->origin->mirrorOnX();
        return $this;
    }

    public function mirrorOnY(): Shape
    {
        $this->origin->mirrorOnY();
        return $this;
    }

    public function contains(Point $pt): bool
    {
        $xcomp = ($pt->getX() - $this->getOrigin()->getX());
        $xcomp *= $xcomp;
        $xcomp = $xcomp / ($this->getXFactor() * $this->getXFactor());
        
        $ycomp = ($pt->getY() - $this->getOrigin()->getY());
        $ycomp *= $ycomp;
        $ycomp = $ycomp / ($this->getYFactor() * $this->getYFactor());
        
        return (($xcomp + $ycomp) < 1);
    }
    
    /**
     * 
     * @param float $angle
     * @return Point
     */
    public function getCrossingPoint(float $angle = 0.0): Point
    {
        return new Point( 
            $this->getOrigin()->getX() + $this->getXFactor() * cos($angle), 
            $this->getOrigin()->getY() + $this->getYFactor() * sin($angle));
    }
    
    
    /**
     * 
     * {@inheritDoc}
     * @see \jmw\fabricad\shapes\Shape::asPolygon()
     */
    public function asPolygon(): Polygon
    {
        $p = new Polygon();
        
        $steps = 100;
        
        for($i = 0 ; $i < $steps ; $i++) {
            $angle = $i * (2 * M_PI / $steps);
            $p->addPoint($this->getCrossingPoint($angle));
        }
        
        return $p;
    }
    
    public function flip(): Shape
    {
        return new Ellipse($this->getYFactor(), $this->getXFactor(), $this->getOrigin()->flip());
    }
    
    public function clone(): Shape
    {
        return new Ellipse($this->getXFactor(), $this->getYFactor(), $this->getOrigin());
    }
    
    public function move(float $x = 0, float $y = 0): Shape
    {
        $this->origin->addXY($x, $y);
        return $this;
    }

    public function scale(float $x = 1, float $y = 1): Shape
    {
        $this->setXFactor($this->getXFactor() * $x);
        $this->setYFactor($this->getYFactor() * $y);
        return $this;
    }

    
    
    
    
}