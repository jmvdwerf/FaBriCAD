<?php
namespace jmw\frabricad\shapes;

class Polygon extends Shape
{
    /**
     * All the points of the Polygon
     * @var array
     */
    private $points = array();
    
    /**
     * Returns the origin, being the first point of the polygon. If no such
     * point exists, the global origin is returned.
     * {@inheritDoc}
     * @see \jmw\fabricad\shapes\Shape::getOrigin()
     */
    public function getOrigin(): Point
    {
        if (count($this->points) > 0) {
            return $this->points[0];
        } else {
            return new Point(0,0);
        }
    }
    
    /**
     * Sets the origin, being the first point of the polygon
     * 
     * {@inheritDoc}
     * @see \jmw\fabricad\shapes\Shape::setOrigin()
     */
    public function setOrigin(Point $orig): Shape
    {
        $update = new Point(
            -1 * $this->getOrigin()->getX() + $orig->getX(), 
            -1 * $this->getOrigin()->getY() + $orig->getY()
        );
        
        $this->points[0] = $orig;
        
        for($i = 1 ; $i < count($this->points); $i++)
        {
            $this->points[$i]->add($update);
        }
        
        return $this;
    }
    
    /**
     * If set, the first point in the array is considered to be the origin.
     * @param array $points
     */
    public function __construct($points = array())
    {
        $origin = null;
        if (count($points)>0) {
            $origin = $points[0];
        } else {
            $origin = new Point(0,0);
        }
        parent::__construct($origin);
        
        foreach($points as $pt) {
            $this->addPoint($pt);
        }
    }
    
    /**
     * Returns all the points of the polygon
     * 
     * @return array
     */
    public function getPoints(): array
    {
        return $this->points;
    }
    
    /**
     * returns true if the polygon does not contain any points
     * 
     * @return bool
     */
    public function isEmpty(): bool
    {
        return (count($this->points) == 0);
    }
    
    public function size(): int
    {
        return count($this->points);
    }
    
    /**
     * Adds a new point to the polygon,  ensures that no two consecutive points 
     * of the polygon are equal.
     *  
     * @param Point the next point of the Polygon. 
     * 
     * @return Polygon
     */
    public function addPoint(Point $pt) {
        if (count($this->points) > 0) {
            if ($this->points[count($this->points)-1]->equals($pt)) {
                return; 
            }
        }
        $this->points[] = $pt;
        
        return $this;
    }
    
    public function deletePoint(int $index) {
        if ((0 <= $index) && ($index < count($this->points))) {
            array_splice($this->points, $index, 1);
        }
    }
    
    public function updatePoint(int $index, Point $pt): bool
    {
        return $this->updatePointXY($index, $pt->getX(), $pt->getY());
    }

    public function updatePointXY(int $index, float $x, float $y): bool
    {
        if ($index == 0 && $this->size() == 1) {
            $this->getOrigin()->setX($x);
            $this->getOrigin()->setY($y);
            
            return true;
        }
        
        if (isset($this->points[$index])) {
            // check if the update is allowed: 
            //(1) the previous should not equal this one
            $prev = (($index - 1) + $this->size()) % $this->size();
            if ($this->points[$prev]->equalsXY($x, $y)) return false;
            
            //(2) the next should not equal this one
            $next = (($index + 1) + $this->size()) % $this->size();
            if ($this->points[$next]->equalsXY($x, $y)) return false;
            
            $this->points[$index]->setX($x);
            $this->points[$index]->setY($y);
            
            return true;
        }
        
        return false;
    }
    
    /**
     * Returns the lines of the polygon
     * 
     * @return array
     */
    public function getLines(): array
    {
        $lines = array();
        if (count($this->points) > 1) {
            $prev = $this->points[0];
            for($i = 1 ; $i < count($this->points); $i++) {
                $lines[] =  new Line($prev, $this->points[$i]);
                $prev = $this->points[$i];
            }
            $lines[] = new Line($prev, $this->points[0]);
        }
        
        return $lines;
    }
    
    /**
     * Mirrors the polygon on the X-axis
     * {@inheritDoc}
     * @see \jmw\HuisBouwer\shapes\Shape::mirrorOnX()
     */
    public function mirrorOnX(): Shape
    {
        for($i = 0 ; $i < count($this->points); $i++) {
            $this->points[$i]->mirrorOnX();
        }
        
        return $this;
    }

    /**
     * Mirrors the polygon over the Y-axis
     * 
     * {@inheritDoc}
     * @see \jmw\HuisBouwer\shapes\Shape::mirrorOnY()
     */
    public function mirrorOnY(): Shape
    {
        for($i = 0 ; $i < count($this->points); $i++) {
            $this->points[$i]->mirrorOnY();
        }
        
        return $this;
    }

    /**
     * Returns the bounding box
     * 
     * {@inheritDoc}
     * @see \jmw\fabricad\shapes\Shape::getBoundingBox()
     */
    public function getBoundingBox(): Rectangle
    {
        $min = Point::copyFrom($this->getOrigin());
        $max = Point::copyFrom($this->getOrigin());
        
        foreach($this->getPoints() as $pt) {
            $min->min($pt);
            $max->max($pt);
        }
        
        $r = new Rectangle();
        $r->setOrigin($min);
        $r->setTop($max);
        
        return $r;
    }
    
    /**
     * Calculates whether a point is inside the shape
     * 
     * {@inheritDoc}
     * @see \jmw\fabricad\shapes\Shape::contains()
     */
    public function contains(Point $pt): bool
    {
        if (!parent::contains($pt)) return false;
        
        $max = $this->getBoundingBox()->getTop();
        $max->scalarMultiply(2);
        
        $points = $this->intersectionPoints(new Line($pt, $max));
        
        return !((count($points) % 2) == 0);
    }
    
    /**
     * Calculates the points that are both on the polygon and the line
     * 
     * @param Line $l
     * @return array
     */
    public function intersectionPoints(Line $l): array
    {
        $points = array();
        $lines = $this->getLines();
        foreach($lines as $p) {
            if ($p->meets($l)) {
                $points[] = $p->meetsAt($l);
            }
        }
        
        return $points;
    }
    
    /**
     * Calculates wheher the polygon is convex.
     * 
     * @return bool
     */
    public function isConvex(): bool
    {
        $n = count($this->points);
        if ($n < 4 ) return true;
        
        $sign = false;
        for($i = 0 ; $i < $n ; $i++) {
            $p0 = $this->points[$i];
            $p1 = $this->points[($i+1)%$n];
            $p2 = $this->points[($i+2)%$n];
            $dx1 = $p2->getX() - $p1->getX();
            $dy1 = $p2->getY() - $p1->getY();
            $dx2 = $p0->getX() - $p1->getX();
            $dy2 = $p0->getX() - $p1->getY();
            $zcrossproduct = $dx1 * $dy2 - $dy1 * $dx2;
            
            if ($i==0) {
                $sign = ($zcrossproduct > 0);
            } else {
                if ($sign != ($zcrossproduct > 0)) return false;
            }
        }
        
        return true;
    }
    

}







?>