<?php


namespace jmw\fabricad\converter\svg;

use jmw\fabricad\config\Blueprint;
use jmw\fabricad\converter\AbstractConverter;
use jmw\fabricad\shapes\Line;
use jmw\fabricad\shapes\Polygon;
use jmw\fabricad\shapes\Shape;
use jmw\fabricad\shapes\Ellipse;
use jmw\fabricad\shapes\Point;

/**
 * 
 * @author jmw
 *
 */
class SVGConverter extends AbstractConverter
{
    private $current = '';
    private $files = array();
    
    /**
     * 
     * {@inheritDoc}
     * @see \jmw\fabricad\converter\AbstractConverter::processBlueprint()
     */
    public function processBlueprint(Blueprint $print)
    {
        // export shape to SVG.
        $shape = $print->render();
        $bb = $shape->getBoundingBox();
        
        $unit = !empty($print->getSetting('unit')) ? $print->getSetting('unit') : $this->unit;
                
        $this->current = '<?xml version="1.0" encoding="UTF-8" standalone="no"?>'."\n";
        
        $this->current .= '<svg height="'.$bb->getTop()->getY().$unit.'" width="'.$bb->getTop()->getX().$unit.'" ';
        $this->current .= ' viewBox="'.$bb->getOrigin()->getX().' '.$bb->getOrigin()->getY().' '.$bb->getTop()->getX().' '.$bb->getTop()->getY().'"';
        $this->current .= '>';
        
        //$this->current .= '<svg height="'.$shape->getBoundingBox()->getTop()->getY().'" width="'.$shape->getBoundingBox()->getTop()->getX().'">';
        $this->top = $shape->getBoundingBox()->getTop()->getY();
        
        $this->processShape($shape);
        $this->current .= "\n".'</svg>';
        
        $this->files[$print->getId()] = $this->current;
    }

    public function preprocessor()
    {
        $this->files = array();
        
        $this->unit = $this->getProject()->getSetting('unit');
        if (empty($this->unit)) {
            $this->unit = 'mm';
        }
        
        
    }

    public function export($filename)
    {
        foreach($this->files as $fname => $content) {
            $file = basename($filename)."_".$fname.".svg";
            
            file_put_contents($file, $content);
        }
    }

    public function postprocessor() {}
    
    protected function processPolygon(Polygon $p)
    {
        $style = 'fill:royalblue;stroke:black;stroke-width:1" fill-opacity="0.5';
        
        $points = array();
        foreach($p->getPoints() as $pt) {
            $newp = new Point($pt->getX(), $this->top - $pt->getY());
            $points[] = $newp;
        }
        
        $this->current .= "\n\t".'<polygon points="'.implode($points," ").'" style="'.$style.'" />';
    }
    
    protected function processEllipse(Ellipse $e)
    {
        $style = 'fill:yellow;stroke:purple;stroke-width:1';
        
        $str = '<ellipse cx="'.$e->getOrigin()->getX().'" cy="'.($this->top - $e->getOrigin()->getY()).'" rx="'.$e->getXFactor().'" ry="'.$e->getYFactor().'" style="'.$style.'" />';
        
        $this->current .= "\n\t".$str;
    }
    
    protected function processLine(Line $l)
    {
        $style = 'stroke:red;stroke-width:1';
        
        $str = '<line x1="'.$l->getOrigin()->getX().'" y1="'.($this->top - $l->getOrigin()->getY()).'" x2="'.$l->getEndPoint()->getX().'" y2="'.($this->top - $l->getEndPoint()->getY()).'" style="'.$style.'" />';
        
        $this->current .= "\n\t".$str;
    }
}