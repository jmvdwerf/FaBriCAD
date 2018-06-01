<?php
namespace jmw\fabricad\blocks;

use jmw\fabricad\shapes\Line;
use jmw\fabricad\shapes\Point;
use jmw\fabricad\shapes\Rectangle;
use jmw\fabricad\shapes\Shape;

class StaandVerband extends Brickwall
{
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
        
        
        $counter = ($this->getStart() > 0);
        
        for($y = $bb->getOrigin()->getY() ; $y <= $totalheight ; $y += $this->getBrickHeight() ) {
            $lines[] = new Line(new Point($startX, $y), new Point($endX, $y ));
            
            if ($counter) {
                $brickwidth = $this->getBrickWidth() / 2;
                $start = $startX + $brickwidth;
            } else {
                $brickwidth = $this->getBrickWidth();
                $start = $startX + (0.75 * $this->getBrickWidth() );
            }
            
            for($x = $start ; $x < $endX ; $x += $brickwidth ) {
                $lines[] = new Line(new Point($x, $prevY), new Point($x, $y));
            }
                
            $prevY = $y;
            $counter = !$counter;
        }
        
        return $lines;
    }
}