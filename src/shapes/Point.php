<?php
namespace jmw\fabricad\shapes;

class Point {
    
    private $x = 0.0;
    private $y = 0.0;
    
    public function __construct(float $x = 0.0, float $y = 0.0)
    {
        $this->setX($x);
        $this->setY($y);
    }
    
    /**
     * @return $x
     */
    public function getX(): float
    {
        return $this->x;
    }
    
    /**
     * @return $y
     */
    public function getY(): float
    {
        return $this->y;
    }
    
    /**
     * @param number $x
     * @return /Point
     */
    public function setX(float $x): Point
    {
        $this->x = $x;
        return $this;
    }
    
    /**
     * @param number $y
     * @return /Point
     */
    public function setY(float $y): Point
    {
        $this->y = $y;
        return $this;
    }
    
    /**
     * Sets the point to $p
     * @param Point $p
     * @return Point
     */
    public function set(Point $p): Point
    {
        $this->setX($p->getX());
        $this->setY($p->getY());
        
        return $this;
    }
    
    /**
     * Creates a new /Point mirrored on the X axis.
     * @return /Point
     */
    public function mirrorOnX(): Point
    {
        $this->setX(-1* $this->getX());
        return $this;
    }
    
    /**
     * Creates a new /Point mirrored on the Y-axis.
     * @return /Point
     */
    public function mirrorOnY(): Point
    {
        $this->setY(-1 * $this->getY());
        return $this;
    }
    
    /**
     * Returns the /Point printed as a SCAD-point
     * @return string
     */
    public function printAsScad($precision = 3): string
    {
        return '[ '. $this->printAsString($precision) .' ]';
    }
    
    /**
     * Prints the point as a decimal with some precision
     * 
     * @param number $precision
     * @return string
     */
    public function printAsString($precision = 3): string
    {
        return round($this->getX(),$precision) . ', '. round($this->getY(),$precision);
    }
    
    /** 
     * Returns the point as a comma-separated pair.
     * @return string
     */
    public function __tostring(): string
    {
        return $this->printAsString(3);
    }
    
    /**
     * Sets the point to the maximal value with $pt,
     * i.e., x = max (x, pt.x) and y = max (y, pt.y)
     * 
     * @param /Point $pt
     * @return /Point
     */
    public function max(Point $pt): Point
    {
        if ($pt->getX() > $this->getX()) {
            $this->setX($pt->getX());
        }
        if ($pt->getY() > $this->getY()) {
            $this->setY($pt->getY());
        }
        
        return $this;
    }
    
    /**
     * Sets the point to the minimal value with $pt,
     * i.e., x = min (x, pt.x) and y = min (y, pt.y)
     * @param Point $pt
     * @return Point
     */
    public function min(Point $pt): Point
    {
        if ($pt->getX() < $this->getX()) {
            $this->setX($pt->getX());
        }
        if ($pt->getY() < $this->getY()) {
            $this->setY($pt->getY());
        }
        
        return $this;
    }
    
    /**
     * Adds a Point
     * 
     * @param Point $pt
     * @return Point
     */
    public function add(Point $pt): Point
    {
        $this->setX($this->getX() + $pt->getX());
        $this->setY($this->getY() + $pt->getY());
        return $this;
    }
    
    /**
     * Multiplies the point
     * 
     * @param Point $pt
     * @return Point
     */
    public function multiply(Point $pt): Point
    {
        $this->setX($this->getX() * $pt->getX());
        $this->setY($this->getY() * $pt->getY());
        return $this;
    }
    
    public function scalarMultiply(float $scalar): Point
    {
        $this->setX($scalar * $this->getX());
        $this->setY($scalar * $this->getY());
        return $this;
    }
    
    /**
     * Returns true if x > pt.x and y > pt.y
     * @param Point $pt
     * @return bool
     */
    public function greaterThan(Point $pt): bool
    {
        return (($this->getX() > $pt->getX()) && ( $this->getY() > $pt->getY()) );
    }
    
    /**
     * Returns true if x < pt.x and y < pt.y
     * @param Point $pt
     * @return bool
     */
    public function smallerThan(Point $pt): bool
    {
        return (($this->getX() < $pt->getX()) && ( $this->getY() < $pt->getY()) );
    }
    
    public function equals(Point $pt): bool
    {
        return $this->equalsXY($pt->getX(), $pt->getY());
    }
    
    /**
     * Returns true if x > pt.x and y > pt.y
     * @param Point $pt
     * @return bool
     */
    public function greaterThanOrEqual(Point $pt): bool
    {
        return (($this->getX() >= $pt->getX()) && ( $this->getY() >= $pt->getY()) );
    }
    
    /**
     * Returns true if x < pt.x and y < pt.y
     * @param Point $pt
     * @return bool
     */
    public function smallerThanOrEqual(Point $pt): bool
    {
        return (($this->getX() <= $pt->getX()) && ( $this->getY() <= $pt->getY()) );
    }
    
    public function equalsXY(float $x, float $y): bool
    {
        return
           (abs($x - $this->getX()) < 0.0001)
        && (abs($y - $this->getY()) < 0.0001);
    }
    
    public function flip(): Point
    {
        return new Point($this->getY(), $this->getX());
    }
    
    public static function copyFrom(Point $pt): Point
    {
        return new Point($pt->getX(), $pt->getY());
    }
    
    public static function find(Point $needle, $haystack = array()): int {
        foreach($haystack as $index => $pt) {
            if ($needle->equals($pt)) {
                return $index;
            }
        }
        return -1;
    }
    
    public function getLength(): float
    {
        return sqrt($this->getY() * $this->getY() + $this->getX() * $this->getX());
    }
    
    public function getAngle(): float
    {
        return atan2($this->getY(), $this->getX());
    }
    
    public static function fromPolar(float $length, float $angle, Point $origin = null): Point
    {
        $x = $length * cos($angle);
        $y = $length * sin($angle);
        
        if ($origin != null) {
            $x += $origin->getX();
            $y += $origin->getY();
        }
        
        return new Point($x, $y);
    }
    
    public function clone(): Point
    {
        return new Point($this->getX(), $this->getY());
    }
    
}






