<?php
namespace jmw\fabricad\shapes;

/**
 * This class is a polygon with exactly four points
 * 
 * @author jmw
 *
 */
class Quadrangle extends Polygon
{
    const SOUTHWEST = 0;
    const NORTHWEST = 1;
    const NORTHEAST = 2;
    const SOUTHEAST = 3;
    
    /**
     * Creates a new Quadrangle with four points.
     * 
     * @param Point $sw
     * @param Point $nw
     * @param Point $ne
     * @param Point $se
     */
    public function __construct(Point $sw, Point $nw, Point $ne, Point $se) {
        // check if these elements are not null. If so, create a new point for them.
        $this->points = array($sw, $nw, $ne, $se);
    }
    
    public function getSouthWest(): Point
    {
        return Point::copyFrom($this->points[Quadrangle::SOUTHWEST]);
    }
    
    public function getNorthWest(): Point
    {
        return Point::copyFrom($this->points[Quadrangle::NORTHWEST]);
    }
    
    public function getNorthEast(): Point
    {
        return Point::copyFrom($this->points[Quadrangle::NORTHEAST]);
    }
    
    public function getSouthEast(): Point
    {
        return Point::copyFrom($this->points[Quadrangle::SOUTHEAST]);
    }
    
    public function setSouthWest(Point $pt): Quadrangle
    {
        $this->updatePoint(Quadrangle::SOUTHWEST, $pt);
        return $this;
    }
    
    public function setSouthEast(Point $pt): Quadrangle
    {
        $this->updatePoint(Quadrangle::SOUTHEAST, $pt);
        return $this;
    }
    
    public function setNorthWest(Point $pt): Quadrangle
    {
        $this->updatePoint(Quadrangle::NORTHWEST, $pt);
        return $this;
    }
    
    public function setNorthEast(Point $pt): Quadrangle
    {
        $this->updatePoint(Quadrangle::NORTHEAST, $pt);
        return $this;
    }
    
    public function mirrorOnX(): Shape
    {
        parent::mirrorOnX();
        
        // west becomes east and vice versa
        $this->flipPoints(Quadrangle::SOUTHWEST, Quadrangle::SOUTHEAST);
        $this->flipPoints(Quadrangle::NORTHWEST, Quadrangle::NORTHEAST);
        
        return $this;
    }
    
    public function mirrorOnY(): Shape
    {
        parent::mirrorOnY();
        
        // south becomes north and vice versa
        $this->flipPoints(Quadrangle::NORTHWEST, Quadrangle::SOUTHWEST);
        $this->flipPoints(Quadrangle::NORTHEAST, Quadrangle::SOUTHEAST);
                
        return $this;
    }
    
    public function flip(): Shape
    {
        // $sw = null, Point $nw = null, Point $ne = null, Point $se
        return new Quadrangle(
            $this->getSouthWest()->flip(),
            $this->getSouthEast()->flip(),
            $this->getNorthEast()->flip(),
            $this->getNorthWest()->flip()
        );
    }
    
    public function clone(): Shape
    {
        // $sw = null, Point $nw = null, Point $ne = null, Point $se = null
        return new Quadrangle(
            $this->getSouthWest(),
            $this->getNorthWest(),
            $this->getNorthEast(),
            $this->getSouthEast()
        );
    }
}
    