<?php
namespace jmw\fabricad\shapes;


class Line extends Shape
{
    
    private $end = null;
    private $origin = null;
    
    public function __construct(Point $start, Point $end)
    {
        $this->end = new Point();
        $this->origin = new Point();
        
        $this->setOrigin($start);
        $this->setEndPoint($end);
    }
    
    public function setOrigin(Point $start): Shape
    {
        $this->setOriginXY($start->getX(), $start->getY());
        return $this;
    }
    
    public function setOriginXY(float $x, float $y): Shape
    {
        $this->origin->setXY($x, $y);
        return $this;
    }
    
    public function getOrigin(): Point
    {
        return Point::copyFrom($this->origin);
    }
    
    public function getEndPoint(): Point
    {
        return Point::copyFrom($this->end);
    }
    
    public function setEndPoint(Point $end): Line
    {
        return $this->setEndPointXY($end->getX(), $end->getY());
    }
    
    public function setEndPointXY(float $x = 0.0, float $y=0.0): Line
    {
        $this->end->setXY($x, $y);
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
    
    public function getMiddlePoint(): Point
    {
        $x = ($this->getEndPoint()->getX() - $this->getOrigin()->getX()) / 2;
        $y = ($this->getEndPoint()->getY() - $this->getOrigin()->getY()) / 2;
        
        return new Point($x + $this->getOrigin()->getX(), $y + $this->getOrigin()->getY());
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
        $this->end->mirrorOnX();
        $this->origin->mirrorOnX();
        
        return $this;
    }

    public function mirrorOnY(): Shape
    {
        $this->end->mirrorOnY();
        $this->origin->mirrorOnY();
        
        return $this;
    }

    public function getBoundingBox(): Rectangle
    {        
        return Rectangle::fromPoints($this->origin, $this->end);
    }
    
    
    public function contains(Point $pt): bool
    {
        if ($this->isPerpendicular()) {
            // check if $ptY is in between the bounds
            return
                   (abs($pt->getX() - $this->getOrigin()->getX()) < 0.001 )
                   && ($pt->getY() <= $this->getBoundingBox()->getTop()->getY())
                   && ($pt->getY() >= $this->getBoundingBox()->getOrigin()->getY());
        }
        
        $y = $this->getY($pt->getX());
        return 
            (abs($y-$pt->getY()) < 0.001)
            && ($pt->getX() <= $this->getBoundingBox()->getTop()->getX())
            && ($pt->getX() >= $this->getBoundingBox()->getOrigin()->getX());
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
        } elseif ($this->getEndPoint()->getX() > $this->getOrigin()->getX()) {
            usort($points, function(Point $p1, Point $p2) { return $p1->getX() - $p2->getX();} );
        } else {
            // sort on the Y -axis
            if ($this->getEndPoint()->getY() < $this->getOrigin()->getY()) {
                usort($points, function(Point $p1, Point $p2) { return $p2->getY() - $p1->getY();} );
            } else {
                usort($points, function(Point $p1, Point $p2) { return $p1->getY() - $p2->getY();} );
            }
        }
    }
    
    public function asPolygon(): Polygon
    {
        return new Polygon([$this->getOrigin(), $this->getEndPoint()]);
    }
    
    public function flip(): Shape
    {
        return new Line($this->getOrigin()->flip(), $this->getEndPoint()->flip());
    }
    
    public static function fromVector(float $length, float $angle, Point $start = null): Line
    {
        if ($start == null) {
            $start = new Point();
        }
        
        $x = $length * cos($angle) + $start->getX();
        $y = $length * sin($angle) + $start->getY();
        
        return new Line($start, new Point($x, $y));
    }
    
    public function clone(): Shape
    {
        return new Line($this->getOrigin(), $this->getEndPoint());
    }
    
    public function move(float $x = 0, float $y = 0): Shape
    {
        $this->origin->addXY($x, $y);
        $this->end->addXY($x, $y);
        return $this;
    }

    public function scale(float $x = 1, float $y = 1): Shape
    {
        $this->end->multiplyXY($x, $y);
        return $this;
    }

}