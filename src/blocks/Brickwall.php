<?php

namespace jmw\fabricad\blocks;

use jmw\fabricad\shapes\Shape;
use jmw\fabricad\shapes\Container;
use jmw\fabricad\shapes\BinaryOperators;
use jmw\fabricad\shapes\Polygon;
use jmw\fabricad\shapes\Line;
use jmw\fabricad\shapes\Point;

class Brickwall extends BasicBuildingBlock
{
    
    public function getBrickHeight(): float {
        if (isset($this->config['height'])) {
            return $this->config['height'];
        } else {
            return 5.0;
        }
    }
    
    public function getBrickWidth(): float {
        if (isset($this->config['width'])) {
            return $this->config['width'];
        } else {
            return 10.0;
        }
    }
    
    public function getAngle(): float {
        if (isset($this->config['angle'])) {
            return $this->config['angle'];
        } else {
            return 0.0;
        }
    }
    
    public function render(): Shape
    {
        
        $bb = $this->getShape()->getBoundingBox();
        
        $totalheight = $bb->getTop()->getY();
        $startX = $bb->getOrigin()->getX();
        $endX = $bb->getTop()->getX();
        
        $lines = array();
        
        for($y = $bb->getOrigin()->getY() ; $y < $totalheight ; $y += $this->getBrickHeight() ) {
            $endY = $y + ( sin($this->getAngle()) * $y );
            $lines[] = new Line(new Point($startX, $y), new Point($endX, $endY ));
        }
        
        $c = new Container();

        echo $this->getShape()->asPolygon();
        
        // get the difference with the shape
        if ($this->getShape() instanceof Polygon) {
            foreach($lines as $l) {
                echo $l;
                
                //$items = BinaryOperators::intersection($this->getShape()->asPolygon(), $l->asPolygon());
                
                //var_dump(count($items));
                
                foreach($items as $it) {
                    $c->addShape($it);
                }
            }
        }
        
        $c->addShape($this->getShape());
        
        return $c;
    }
}