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
    public function __construct(Point $sw = null, Point $nw = null, Point $ne = null, Point $se = null) {
        // check if these elements are not null. If so, create a new point for them.
        
        if ($sw == null) { $sw = new Point(); }
        if ($nw == null) { $nw = new Point(); }
        if ($ne == null) { $ne = new Point(); }
        if ($se == null) { $se = new Point(); }
        
        parent::__construct();
        
        $this->points = array($sw, $nw, $ne, $se);
    }
    
    public function getSouthWest(): Point
    {
        return $this->points[Quadrangle::SOUTHWEST];
    }
    
    public function getNorthWest(): Point
    {
        return $this->points[Quadrangle::NORTHWEST];
    }
    
    public function getNorthEast(): Point
    {
        return $this->points[Quadrangle::NORTHEAST];
    }
    
    public function getSouthEast(): Point
    {
        return $this->points[Quadrangle::SOUTHEAST];
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
}
    