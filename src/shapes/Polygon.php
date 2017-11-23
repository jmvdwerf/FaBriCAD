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
     * The origin of the bounding box 
     * @var Point
     */
    private $minPoint = null;
    
    /**
     * The far end point of the bounding box
     * @var Point
     */
    private $maxPoint = null;
    
    /**
     * Returns the origin, being the first point of the polygon. If no such
     * point exists, the global origin is returned.
     * {@inheritDoc}
     * @see \jmw\HuisBouwer\shapes\Shape::getOrigin()
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
     * @see \jmw\HuisBouwer\shapes\Shape::setOrigin()
     */
    public function setOrigin(Point $orig): Shape
    {
        $update = new Point(
            -1 * $this->getOrigin()->getX() + $orig->getX(), 
            -1 * $this->getOrigin()->getY() + $orig->getY()
        );
        
        $this->points[0] = $orig;
        $this->minPoint->set($orig);
        $this->maxPoint->set($orig);
        
        for($i = 1 ; $i < count($this->points); $i++)
        {
            $this->points[$i]->add($update);
            $this->minPoint->min($this->points[$i]);
            $this->maxPoint->max($this->points[$i]);
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
        
        // Set the bounding box
        $this->minPoint = new Point($origin->getX(), $origin->getY());
        $this->maxPoint = new Point($origin->getX(), $origin->getY());
        
        foreach($points as $pt) {
            $this->addPoint($pt);
            $this->minPoint->min($pt);
            $this->maxPoint->max($pt);
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
        
        // update the bounding box
        $this->minPoint->min($pt);
        $this->maxPoint->max($pt);
        
        return $this;
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
        if ($this->isEmpty()) return $this;
        
        $this->points[0]->setX(-1 * $this->points[0]->getX());
        
        $this->minPoint->set($this->points[0]);
        $this->maxPoint->set($this->points[0]);
        
        for($i = 1 ; $i < count($this->points); $i++) {
            $this->points[$i]->setX(-1 * $this->points[$i]->getX());
            
            $this->minPoint->min($this->points[$i]);
            $this->maxPoint->max($this->points[$i]);
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
        if ($this->isEmpty()) return $this;
        
        $this->points[0]->setY(-1 * $this->points[0]->getY());
        
        $this->minPoint->set($this->points[0]);
        $this->maxPoint->set($this->points[0]);
        
        for($i = 1 ; $i < count($this->points); $i++) {
            $this->points[$i]->setY(-1 * $this->points[$i]->getY());
            
            $this->minPoint->min($this->points[$i]);
            $this->maxPoint->max($this->points[$i]);
        }
        
        return $this;
    }

    /**
     * Returns the bounding box
     * 
     * {@inheritDoc}
     * @see \jmw\HuisBouwer\shapes\Shape::getBoundingBox()
     */
    public function getBoundingBox(): Rectangle
    {
        $r = new Rectangle();
        $r->setOrigin($this->minPoint);
        $r->setTop($this->maxPoint);
        
        return $r;
    }
    
    /**
     * Calculates whether a point is inside the shape
     * 
     * {@inheritDoc}
     * @see \jmw\HuisBouwer\shapes\Shape::contains()
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