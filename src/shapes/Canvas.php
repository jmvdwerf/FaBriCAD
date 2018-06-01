<?php

namespace jmw\fabricad\shapes;


/**
 * The main difference between a Canvas and a Container is that all the elements
 * in the Canvas are relative to the Canvas. 
 * 
 * @author jmw
 *
 */
class Canvas extends Container
{
    protected $origin = null;
    
    public function __construct($shapes = array(), Point $origin = null)
    {
        if ($origin == null) {
            $this->origin = new Point();
        } else {
            $this->origin = $origin;
        }
        
        parent::__construct($shapes);
    }
    
    public function setOrigin(Point $pt): Shape
    {
        return $this->setOriginXY($pt->getX(), $pt->getY());
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
    
    /** 
     * This function flattens all shapes, and positions them relative to the
     * origin of the Canvas
     * {@inheritDoc}
     * @see \jmw\fabricad\shapes\Container::flatten()
     */
    public function flatten(bool $removeOverlap = false): array
    {
        $results = parent::flatten($removeOverlap);
        
        foreach($results as $s) {
            $s->move($this->getOrigin()->getX(), $this->getOrigin()->getY());
        }
        
        return $results;
    }
    
    public function move(float $x = 0, float $y = 0): Shape
    {
        $this->origin->addXY($x, $y);
        return $this;
    }
    
}

