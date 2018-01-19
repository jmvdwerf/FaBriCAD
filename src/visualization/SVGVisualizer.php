<?php

namespace jmw\fabricad\visualizer;

use jmw\fabricad\shapes\Ellipse;
use jmw\fabricad\shapes\Line;
use jmw\fabricad\shapes\Point;
use jmw\fabricad\shapes\Shape;
use jmw\fabricad\shapes\Polygon;


class SVGVisualizer
{
    private $shapes = array();
    private $colors = array();
    
    public function addShape(Shape $s, string $color = 'blue')
    {
        $this->shapes[] = $s;
        $this->colors[] = $color;
    }
    
    public function render(): string
    {
        $min = new Point();
        $max = new Point();
        //get the largest bounding box
        $margin = new Point(10,10);
        foreach($this->shapes as $s) {
            /** @var jmw\fabricad\shapes\Rectangle */
            $bb = $s->getBoundingBox();
            $min->min($bb->getOrigin());
            $max->max($bb->getTop()->add($margin));
        }
        
        $str = '<svg height="'.$max->getY().'" width="'.($max->getX()+40).'">';
        
        foreach($this->shapes as $index => $s) {
            if ($s instanceof Polygon) {
                $str .= "\n\t";
                $str .= '<polygon points="'.implode($s->getPoints()," ").'" style="fill:'.$this->colors[$index].';stroke:black;stroke-width:1" fill-opacity="0.5" />';
                foreach($s->getPoints() as $key => $point) {
                    $str .= "\n\t";
                    $str .= '<circle cx="'.$point->getX().'" cy="'.$point->getY().'" r="5" stroke="black"; stroke-width="2" fill="black" />';
                    $str .= "\n\t";
                    $pp = Point::copyFrom($point)->add($margin);
                    $str .= '<text x="'.$pp->getX().'" y="'.$pp->getY().'" font-family="verdana" font-size="20" fill="'.$this->colors[$index].'">'.$key.'</text>';
                }
            } elseif ($s instanceof Line) {
                $str .= "\n\t";
                $str .= '<line x1="'.$s->getOrigin()->getX().'" y1="'.$s->getOrigin()->getY().'" x2="'.$s->getEndPoint()->getX().'" y2="'.$s->getEndPoint()->getY().'" style="stroke:black;stroke-width:2" />';
            } elseif ($s instanceof Ellipse) {
                $style = 'fill:green;stroke:black;stroke-width:1';
                $str .= "\n\t";
                $str .= '<ellipse cx="'.$s->getOrigin()->getX().'" cy="'.($s->getOrigin()->getY()).'" rx="'.$s->getXFactor().'" ry="'.$s->getYFactor().'" style="'.$style.'" />';
            }
        }
        $str .= "\n";
        $str .= '</svg>';
        return $str;
    }
}