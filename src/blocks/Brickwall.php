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
    
    protected function renderLines(): array
    {
        $bb = $this->getShape()->getBoundingBox();
        
        $totalheight = $bb->getTop()->getY();
        $prevY = $bb->getOrigin()->getY();
        $startX = $bb->getOrigin()->getX();
        $endX = $bb->getTop()->getX();
        
        $lines = array();
        
        $counter = 0;
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
        
        $lines = $this->renderLines();
        
        $c = new Container();
        //foreach($this->getShape()->asPolygon()->getLines() as $line) {
        //    $c->addShape($line);
        //}
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
        
        $c->addShape($this->getShape());
        
        return $c;
    }
}