<?php
namespace jmw\fabricad\shapes;


class Ellipse extends Shape
{
    
    private $a = 0.0;
    private $b = 0.0;
    
    public function __construct(float $a = 0,float $b = 0, $origin = null)
    {
        parent::__construct($origin);
        
        $this->setXFactor($a);
        $this->setYFactor($b);
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
    
}