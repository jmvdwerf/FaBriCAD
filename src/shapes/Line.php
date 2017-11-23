<?php
namespace jmw\fabricad\shapes;


class Line extends Shape
{
    
    private $end = null;
    
    public function __construct(Point $origin = null, Point $end = null)
    {
        parent::__construct($origin);
        if (empty($end)) $end = new Point(0,0);
        
        $this->setEndPoint($end);
    }
    
    public function getEndPoint(): Point
    {
        return $this->end;
    }
    
    public function setEndPoint(Point $end): Line
    {
        $this->end = $end;
        return $this;
    }
    
    public function getLength(): float
    {
        $dX = $this->getEndPoint()->getX() - $this->getOrigin()->getX();
        $dY = $this->getEndPoint()->getY() - $this->getOrigin()->getY();
        
        return sqrt($dX * $dX + $dY * $dY);
    }
    
    public function getAngle(): float
    {
        $dX = $this->getEndPoint()->getX() - $this->getOrigin()->getX();
        $dY = $this->getEndPoint()->getY() - $this->getOrigin()->getY();
        
        return atan2($dY, $dX);
    }
    
    public function getConstant(): float
    {
        $a = tan($this->getAngle());
        return $this->getOrigin()->getY() - $a * $this->getOrigin()->getX();
    }
    
    
    public function mirrorOnX(): Shape
    {
        $this->getEndPoint()->mirrorOnX();
        $this->getOrigin()->mirrorOnX();
        
        return $this;
    }

    public function mirrorOnY(): Shape
    {
        $this->getEndPoint()->mirrorOnY();
        $this->getOrigin()->mirrorOnY();
        
        return $this;
    }

    public function getBoundingBox(): Rectangle
    {
        $oX = min($this->getEndPoint()->getX(), $this->getOrigin()->getX());
        $oY = min($this->getEndPoint()->getY(), $this->getOrigin()->getY());
        
        $w = abs($this->getEndPoint()->getX() - $this->getOrigin()->getX());
        $h = abs($this->getEndPoint()->getY() - $this->getOrigin()->getY());
        
        return new Rectangle($w, $h, new Point($oX, $oY));
    }
    
    
    public function contains(Point $pt): bool
    {
        $y = tan($this->getAngle()) * $pt->getX() + $this->getConstant();
     
        return 
               (abs($y - $pt->getY()) < 0.0000001)
            && ($this->getOrigin()->getX() <= $pt->getX())
            && ($this->getEndPoint()->getX() >= $pt->getX())
        ;
    }
    
    
    public function meets(Line $s): bool
    {
        if (!$this->intersects($s)) return false;
        
        $a = tan($this->getAngle());
        $b = $this->getConstant();
        $c = tan($s->getAngle());
        $d = $s->getConstant();
        
        if ($a == $c) return false;
        
        $x = ($b - $d)/($c-$a);
        
        $y = $a * $x + $b;
        
        return $this->contains(new Point($x, $y));
    }
    
    public function meetsAt(Line $s): Point
    {
        if (!$this->intersects($s)) return null;
        
        $a = tan($this->getAngle());
        $b = $this->getConstant();
        $c = tan($s->getAngle());
        $d = $s->getConstant();
        
        if ($a == $c) return null;
        
        $x = ($b - $d)/($c-$a);
        $y = $a * $x + $b;
        
        return new Point($x, $y);
    }

    
}