<?php

namespace jmw\fabricad\blocks;

use jmw\fabricad\shapes\Shape;
use jmw\fabricad\shapes\Point;
use jmw\fabricad\shapes\Canvas;
use jmw\fabricad\shapes\Singleton;
use jmw\fabricad\shapes\Line;
use jmw\fabricad\shapes\Polygon;


class Latij extends BasicBuildingBlock
{
    
    public function getWidth(): float
    {
        if (isset($this->config['width'])) { 
            return $this->config['width'];
        } else {
            return 5.0;
        }
    }
    
    public function getHeight(): float
    {
        if (isset($this->config['height'])) {
            return $this->config['height'];
        } else {
            return 10.0;
        }
    }
    
    public function getBrickHeight(): float {
        if (isset($this->config['brickheight'])) {
            return $this->config['brickheight'];
        } else {
            return 5.0;
        }
    }
    
    public function getBrickWidth(): float {
        if (isset($this->config['brickwidth'])) {
            return $this->config['brickwidth'];
        } else {
            return 5.0;
        }
    }
    
    public function getNrOfStones(): float {
        if (isset($this->config['stones'])) {
            return $this->config['stones'];
        } else {
            return 5;
        }
    }
       
    private $porring = null;
    
    public function getPorringPunt(): Point
    {
        if ($this->porring == null)
        {
            if (isset($this->config['porringpunt']) && is_array($this->config['porringpunt'])) {
                $this->porring = new Point(
                    $this->config['porringpunt']['x'], 
                    $this->config['porringpunt']['y']
                    );
            } else {
                $this->porring = new Point(
                    0,
                    - 1.5 * ($this->getWidth() / 2)
                    );
            }
        }
        return $this->porring;
    }
    
    public function render(): Shape
    {
        $c = new Canvas([], new Point(
            $this->getShape()->getOrigin()->getX() + ($this->getWidth() / 2 ),
            $this->getShape()->getOrigin()->getY()
            ));
        
        // $c->addShape(new Singleton());
        // $c->addShape(new Singleton($this->getPorringPunt()));
        
        $starthoek = atan2(-1 * $this->getPorringPunt()->getY(), $this->getWidth() / 2);
        $maxhoek = atan2(-1 * $this->getPorringPunt()->getY() + $this->getHeight(), $this->getBrickWidth() / 2);
        
        $step = ($maxhoek - $starthoek) / ($this->getNrOfStones()+1);
        
        $maxX = 0;
        
        for($hoek = $starthoek ; $hoek < M_PI_2 ; $hoek += $step) { 
        
            $line = Line::fromVector(100, $hoek, $this->getPorringPunt());
        
            $x1 = $line->calculateX(0);
            $x2 = $line->calculateX($this->getHeight());
            
            $vlijn[] = new Line(new Point($x1, 0), new Point($x2, $this->getHeight()));
        
        }
        
        $brickline = [];
        
        $odd = ($this->getNrOfStones() % 2);
        
        for($i = 0 ; $i < count($vlijn) - 1 ; $i++ ) {
            $nr = count($vlijn) - 1 - $i;
            
            // eerste rand
            $x1 = $vlijn[$nr]->calculateX(0);
            $x2 = $vlijn[$nr - 1]->calculateX(0);
            
            $brickline[] = new Line(new Point($x1, 0), new Point($x2, 0));
            
            if ($this->getHeight() > $this->getBrickHeight()) {
                $height = ($nr % 2 == $odd) ? $this->getBrickHeight() : $this->getBrickHeight() / 2;
                        
                for($y = $height ; $y < $this->getHeight(); $y += $this->getBrickHeight() ) {
                    $x1 = $vlijn[$nr]->calculateX($y);
                    $x2 = $vlijn[$nr - 1]->calculateX($y);
                    
                    $brickline[] = new Line(new Point($x1, $y), new Point($x2, $y));
                }
            }
            
            // laatste rand
            $x1 = $vlijn[$nr]->calculateX($this->getHeight());
            $x2 = $vlijn[$nr - 1]->calculateX($this->getHeight());
            
            $brickline[] = new Line(new Point($x1, $this->getHeight()), new Point($x2, $this->getHeight()));
            
        }
        
        // add Polygon around it
        $surrounding = new Polygon();
        
        for($j = 0 ; $j < count($vlijn) ; $j++) {
            $surrounding->addPoint($vlijn[$j]->getEndPoint());
        }
        for($j = count($vlijn) - 1 ; $j >= 0 ; $j--) {
            $surrounding->addPoint($vlijn[$j]->getEndPoint()->mirrorOnX());
        }
        
        for($j = 0 ; $j < count($vlijn) ; $j++) {
            $surrounding->addPoint($vlijn[$j]->getOrigin()->mirrorOnX());
        }
        for($j = count($vlijn) - 1 ; $j >= 0 ; $j--) {
            $surrounding->addPoint($vlijn[$j]->getOrigin());
        }
        
        foreach($vlijn as $l) {
            $c->addShape($l);
            $c->addShape($l->clone()->mirrorOnX());
        }
        
        foreach($brickline as $l) {
            $c->addShape($l);
            $c->addShape($l->clone()->mirrorOnX());
        }
        
        // en de middenrij
        $max = count($vlijn) -1;
        for($h = 0; $h <= $this->getHeight() ; $h += $this->getBrickHeight()) {
            $y = min($h, $this->getHeight());
            $x = $vlijn[$max]->calculateX($y);
            
            $c->addShape(
                new Line(
                    new Point($x, $y),
                    new Point(-1*$x, $y)
                ));
        }
        $c->addShape($surrounding);
        
        return $c;
    }
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
}