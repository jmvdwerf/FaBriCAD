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
    
    public function getY($x): float 
    {
        $a = tan($this->getAngle());
        $b = $this->getConstant();
        
        return $a * $x + $b;
    }
    
    public function getPoint($x): Point
    {
        return new Point($x, $this->getY($x));
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
        $result = $this->getBoundingBox()->contains($pt);
        
        if ($this->isPerpendicular()) {
            return $result;  
        }
        
        $y = tan($this->getAngle()) * $pt->getX() + $this->getConstant();
        return 
               (abs($y - $pt->getY()) < 0.001)
            && $result
        ;
    }
    
    public function isPerpendicular(): bool
    {
        if (abs($this->getEndPoint()->getX() - $this->getOrigin()->getX()) < 0.00001) {
            return true;
        } else {
            return false;
        }
    }
    
    
    public function meets(Line $s): bool
    {
        if (!$this->intersects($s)) return false;
        
        $x = 0;
        $y = 0;
        
        if ($this->isPerpendicular()) {
            $x = $this->getOrigin()->getX();
            $y = $s->getY($this->getOrigin()->getX()); 
        } elseif ($s->isPerpendicular()) {
            $x = $s->getOrigin()->getX();
            $y = $this->getY($x);
        } else {
            $a = tan($this->getAngle());
            $b = $this->getConstant();
            $c = tan($s->getAngle());
            $d = $s->getConstant();
            
            if ($a == $c) return false;
            
            $x = ($b - $d)/($c-$a);
            $y = $a * $x + $b;
        }

        $p = new Point($x, $y);
        
        $result1 = $this->contains($p);
        $result2 = $s->contains($p);
        
       // var_dump($result1, $result2);
        
        return $result1 && $result2;;
    }
    
    public function meetsAt(Line $s): Point
    {
        if (!$this->intersects($s)) return null;
        
        if ($this->isPerpendicular()) {
            $x = $this->getOrigin()->getX();
            return $s->getPoint($x);
        }
        
        if ($s->isPerpendicular()) {
            $x = $s->getOrigin()->getX();
            return $this->getPoint($x);
        }
        
        $a = tan($this->getAngle());
        $b = $this->getConstant();
        $c = tan($s->getAngle());
        $d = $s->getConstant();
        
        if ($a == $c) return null;
        
        $x = ($b - $d)/($c-$a);
        $y = $a * $x + $b;
        
        return new Point($x, $y);
    }
    
    public function __toString(): string
    {
        return 'LINE: ('.$this->getOrigin().') - ('.$this->getEndPoint().')'."\n";
    }
    
    public function giveFunction()
    {
        $a = tan($this->getAngle());
        $b = $this->getConstant();
        return 'LINE: Y = '.round($a,3).' * X + '.round($b,3)."\n";
    }


    /**
     * Orders the point based on the X-axis of this formula
     * @param array $points
     * @return array
     */
    public function orderPoints(&$points = array())
    {
        if ($this->getEndPoint()->getX() < $this->getOrigin()->getX()) {
            usort($points, function(Point $p1, Point $p2) { return $p2->getX() - $p1->getX();} );
        } else {
            usort($points, function(Point $p1, Point $p2) { return $p1->getX() - $p2->getX();} );
        }
    }
}