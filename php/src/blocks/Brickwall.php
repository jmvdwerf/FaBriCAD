<?php

namespace jmw\fabricad\blocks;

use jmw\fabricad\shapes\Shape;
use jmw\fabricad\shapes\Container;
use jmw\fabricad\shapes\BinaryOperators;
use jmw\fabricad\shapes\Polygon;
use jmw\fabricad\shapes\Line;
use jmw\fabricad\shapes\Point;
use jmw\fabricad\shapes\Rectangle;

class Brickwall extends BasicBuildingBlock
{
    
    public function getStart(): int
    {
        if (isset($this->config['start'])) {
            return $this->config['start'];
        } else {
            return 0;
        }
    }
    
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
    
    public function isHorizontal(): bool
    {
        if (isset($this->config['vertical'])) {
            return ($this->config['vertical'] == 0);
        } else {
            return true;
        }
    }
    
    /**
     * 
     * @param Rectangle $bb
     * @return array
     */
    protected function renderBricks(Shape $s): array
    {
        $bb = $s->getBoundingBox();
        
        $totalheight = $bb->getTop()->getY() + $this->getBrickHeight();
        $prevY = $bb->getOrigin()->getY();
        $startX = $bb->getOrigin()->getX();
        $endX = $bb->getTop()->getX() + $this->getBrickWidth();
        
        $lines = array();
        
        $counter = $this->getStart();
        for($y = $bb->getOrigin()->getY() ; $y <= $totalheight ; $y += $this->getBrickHeight() ) {
            $lines[] = new Line(new Point($startX, $y), new Point($endX, $y ));
            $start = $startX + (1-($counter % 2)/2) * $this->getBrickWidth();
            for($x = $start ; $x < $endX ; $x += $this->getBrickWidth() ) {
                $lines[] = new Line(new Point($x, $prevY), new Point($x, $y));
            }
            $prevY = $y;
            $counter++;
        }
        
        return $lines;
    }
    
    public function render(): Shape
    {
        
        $bb = $this->getShape()->getBoundingBox();
        
        if ($this->isHorizontal()) {
            
            $lines = $this->renderBricks($bb);

        } else {
            $bricks = $this->renderBricks($bb->flip());
            // now, we need to flip the end point's X and Y's!
            
            $lines = array();
            
            // For the horizontal lines, we simply switch the X and Y of the end point
            foreach($bricks as $l) {
                $lines[] = new Line($l->getOrigin()->flip(), $l->getEndPoint()->flip());
            }
            
            
        }
            
        $c = new Container();
        
        if ($this->getShape() instanceof Polygon) {
            foreach($lines as $l) {
                // echo "I have: ".$l."\n";
                
                $items = BinaryOperators::intersection($this->getShape()->asPolygon(), $l);
                
                // echo "I get: \n";

                foreach($items as $it) {
                    // echo " - ".$it;
                    $c->addShape($it);
                }
            }
        }
        // add lines of the polygon
        //$c->addShapes($this->getShape()->asPolygon()->getLines());
        $c->addShape($this->getShape());
        
        return $c;
    }
}