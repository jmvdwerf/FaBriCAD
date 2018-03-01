<?php
namespace jmw\fabricad\shapes;

class Polygon extends Shape
{
    public const DIRECTION_CLOCKWISE = 0;
    public const DIRECTION_COUNTERCLOCKWISE = 1;
    
    /**
     * All the points of the Polygon
     * @var array
     */
    protected $points = array();
    
    /**
     * Returns the origin, being the first point of the polygon. If no such
     * point exists, the global origin is returned.
     * {@inheritDoc}
     * @see \jmw\fabricad\shapes\Shape::getOrigin()
     */
    public function getOrigin(): Point
    {
        if (count($this->points) > 0) {
            return Point::copyFrom($this->points[0]);
        } else {
            return new Point(0,0);
        }
    }
    
    /**
     * If set, the first point in the array is considered to be the origin.
     * @param array $points
     */
    public function __construct($points = array())
    {
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
    
    public function getPoint(int $index): Point
    {
        return $this->points[$index];
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
            if ($this->points[count($this->points)-1]->equals($pt)|| $this->points[0]->equals($pt)) {
                return; 
            }
        }
        $this->points[] = Point::copyFrom($pt);
        
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
        if (isset($this->points[$index])) {
            
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
        if (count($this->getPoints()) == 2) {
            $l = new Line($this->points[0], $this->points[1]);
            return [$l];
        }
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
     * @see \jmw\fabricad\shapes\Shape::mirrorOnX()
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
     * @see \jmw\fabricad\shapes\Shape::mirrorOnY()
     */
    public function mirrorOnY(): Shape
    {
        for($i = 0 ; $i < count($this->points); $i++) {
            $this->points[$i]->mirrorOnY();
        }
        
        return $this;
    }
    
    public function flipPoints(int $index1, int $index2)
    {
        if (($index1 >= 0) && ($index1 < $this->size()) && ($index2 >= 0) && ($index2 < $this->size())) {
            $pt = Point::copyFrom($this->points[$index1]);
            $this->points[$index1]->set($this->points[$index2]);
            $this->points[$index2]->set($pt);
        }
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
        
        return Rectangle::fromPoints($min, $max);
    }
    
    /**
     * Calculates whether a point is inside the shape
     * 
     * {@inheritDoc}
     * @see \jmw\fabricad\shapes\Shape::contains()
     */
    public function contains(Point $pt): bool
    {
        $c = false;
        $pts = $this->getPoints();
        for ($i = 0, $j = $this->size()-1; $i < $this->size(); $j = $i++) {
            if ( (($pts[$i]->getY() > $pt->getY()) != ($pts[$j]->getY() > $pt->getY())) &&
                ($pt->getX() < ($pts[$j]->getX()-$pts[$i]->getX()) * ($pt->getY()-$pts[$i]->getY()) / ($pts[$j]->getY()-$pts[$i]->getY()) + $pts[$i]->getX()) )
                $c = !$c;
        }
        return $c;
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
                
        foreach($this->getLines() as $line) {
            
            if ($line->meets($l)) {
                $points[] = $line->meetsAt($l);
            }
        }
        $l->orderPoints($points);
        
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
    
    
    /**
     * Returns the direction of the Polygon (i.e. (counter) clockwise)
     * 
     * @return int
     */
    public function direction(): int
    {
        $area = 0;
        foreach($this->getLines() as $line) {
            $p1 = $line->getOrigin();
            $p2 = $line->getEndPoint();
            
            $area += ($p1->getX() * $p2->getY() - $p2->getX() * $p1->getY());
        }
        
        if ($area < 0) {
            return Polygon::DIRECTION_CLOCKWISE;
        } else {
            return Polygon::DIRECTION_COUNTERCLOCKWISE;
        }
    }
    
    
    /**
     * Calculates the intersection points with another Polygon
     *  
     * @param Polygon $other
     * @param bool $extends
     * @return Polygon
     */
    public function calculateIntersectionPointsWith(Polygon $other): Polygon
    {
        $nt = new Polygon();
        
        foreach($this->getLines() as $line) {

            $first = Point::copyFrom($line->getOrigin());
            $nt->addPoint($first);
            
            $ips = $other->intersectionPoints($line);
                            
            foreach($ips as $pt) {
                $nt->addPoint($pt);
            }
            
            $nt->addPoint(Point::copyFrom($line->getEndPoint()));
        }
        return $nt;
    }
    
    /**
     * Adds a point in the middle of each line
     * 
     * @return Polygon
     */
    public function expand(): Polygon
    {
        $p = new Polygon();
        
        if ($this->size() == 0) {
            return $p;
        }
        
        $p->addPoint($this->getPoint(0));
        foreach($this->getLines() as $l) {
            $p->addPoint($l->getMiddlePoint());
            $p->addPoint($l->getEndPoint());
        }
                
        return $p;
    }
    
    /**
     * Simplifies the Polygon by removing in between points that are on the same
     * line
     * 
     * @return Polygon
     */
    public function simplify(): Polygon
    {
        $p = new Polygon();
        foreach($this->getPoints() as $pt) {
            $p->addPoint($pt);
        }
        
        do {
        
            // remove redundant nodes;
            $lines = $p->getLines();
            $N = count($lines);
            $rmNode = array();
            
            for($i = 0 ; $i < $N ; $i++) {
                if (abs($lines[$i]->getAngle() - $lines[($i+1) % $N]->getAngle() ) < 0.001) {
                    // the two consecutive lines have the same angle, so the point
                    // in between can be removed!
                    $rmNode[] = ($i+1) % $N;
                }
            }
            $pts = $p->getPoints();
            
            $p = new Polygon();
            foreach($pts as $index => $pt) {
                if (!in_array($index, $rmNode)) {
                    $p->addPoint($pt);
                }
            }
        } while (count($rmNode) > 0);
        
        return $p;
    }
    
    /**
     * 
     * {@inheritDoc}
     * @see \jmw\fabricad\shapes\Shape::asPolygon()
     */
    public function asPolygon(): Polygon
    {
        return $this;
    }
    
    public function __tostring(): string
    {
        $s = "Polygon [";
        foreach($this->getPoints() as $pt) {
            $s .= "\n\t".$pt;
        }
        $s.= "\n]\n";
        
        return $s;
    }
    
    public function hasPoint(Point $pt) {
        foreach($this->getPoints() as $point) {
            if ($pt->equals($point)) return true;
        }
    }
    
    public function flip(): Shape
    {
        $pp = array();
        
        foreach($this->getPoints() as $pt) {
            $pp[] = $pt->flip();
        }
        
        return new Polygon($pp);
    }
    
    /**
     * Calculates the Centroid, or center of gravity of a polygon
     * @see https://math.stackexchange.com/questions/3177/why-doesnt-a-simple-mean-give-the-position-of-a-centroid-in-a-polygon
     * @return \jmw\fabricad\shapes\Point
     */
    public function getCentroid(): Point
    {
        if ($this->size() == 0) {
            return new Point();
        }
        
        $cx = 0;
        $cy = 0;
        $a = 0;
        
        for($i = 0 ; $i < $this->size() ; $i++) {
            $prev = $this->points[$i];
            $cur = $this->points[(($i+1) % ($this->size()))];
            $factor = ($prev->getX() * $cur->getY() - $cur->getX()* $prev->getY());
            $cx += ( $prev->getX() + $cur->getX() ) * $factor;
            $cy += ( $prev->getY() + $cur->getY() ) * $factor;
            $a += $factor;
        }
        
        $a = $a / 2;
        
        $cx = (1 / ( 6 * $a)) * $cx;
        $cy = (1 / ( 6 * $a)) * $cy;
        
        return new Point($cx, $cy);
    }
    
    public function clone(): Shape
    {
        return new Polygon($this->getPoints());
    }
    
    public function move(float $x = 0, float $y = 0): Shape
    {
        for($i = 0 ; $i < count($this->points) ; $i++) {
            $this->points[$i]->addXY($x, $y);
        }
        
        return $this;
    }

    /**
     * Scaling is performed by taking the Centroid, and scale each point from 
     * this centroid.
     * {@inheritDoc}
     * @see \jmw\fabricad\shapes\Shape::scale()
     */
    public function scale(float $x = 1, float $y = 1): Shape
    {
        $centroid = $this->getCentroid();
        
        for($i = 0 ; $i < $this->size(); $i++) {
            $l = new Line($centroid, $this->getPoint($i));
            $l->scale($x, $y);
            $this->updatePoint($i, $l->getEndPoint());
        }
        
        return $this;
    }
}







?>