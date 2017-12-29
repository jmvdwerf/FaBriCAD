<?php

namespace jmw\fabricad\blocks;

use jmw\fabricad\shapes\Polygon;
use jmw\fabricad\shapes\Shape;
use jmw\fabricad\shapes\Point;

class BasicBlock extends AbstractBuildingBlock
{
    /**
     * 
     * @var array
     */
    protected $points = array();
    
    public function render(): Shape
    {
        return new Polygon($this->getPoints());
    }
    
    /**
     * @return array $points
     */
    public function getPoints(): array
    {
        return $this->points;
    }

    /**
     * @param array: $points
     */
    public function setPoints($points = array()): BasicBlock
    {
        $this->points = $points;
        return $this;
    }

    
    
}