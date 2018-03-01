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
    
    public function setOrigin(Point $pt): Canvas
    {
        return $this->setOriginXY($pt->getX(), $pt->getY());
    }
    
    public function setOriginXY(float $x, float $y): Canvas
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
    public function flatten(): array
    {
        $results = array();
        foreach($this->getShapes() as $shape) {
            if ($shape instanceof Container) {
                $results = array_merge($results, $shape->flatten());
            } else {
                $results[] = $shape->clone();
            }
        }
        
        foreach($results as $s) {
            $s->move($this->getOrigin()->getX(), $this->getOrigin()->getY());
        }
        
        return $results;
    }
}

