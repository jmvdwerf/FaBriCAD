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
    private $page = '';
    
    private $current = '';
    private $top = 0;
    
    /**
     * 
     * {@inheritDoc}
     * @see \jmw\fabricad\converter\AbstractConverter::processBlueprint()
     */
    public function processBlueprint(Blueprint $print)
    {
        // export shape to SVG.
        $shape = $print->render();
        
        $this->current = '<svg height="'.$shape->getBoundingBox()->getTop()->getY().'" width="'.$shape->getBoundingBox()->getTop()->getX().'">';
        $this->top = $shape->getBoundingBox()->getTop()->getY();
        
        $this->processShape($shape);
        $this->current .= "\n".'</svg>';
        
        $text  = '<h2>'.$print->getName().'</h2>'."\n\n";
        $text .= $this->current. "\n\n";
        $text .= '<table><tr><th>Name</th><td>'.$print->getName().'</td></tr>';
        $text .= '<tr><th>Description</th><td>'.$print->getDescription().'</td></tr>';
        $text .= '</table>';
        
        $this->page .= $text;
        
        $this->current = '';
    }

    public function preprocessor()
    {
        $this->page = '<html><body><h1>'.$this->getProject()->getName().'</h1>'
            . '<table>'
            . '<tr><th>Description</th><td>'.$this->getProject()->getDescription().'</td></tr>'
            . '<tr><th>Author</th><td>'.$this->getProject()->getAuthor().'</td></tr>'
            . '<tr><th>Version</th><td>'.$this->getProject()->getVersion().'</td></tr>'
            . '<tr><th>License</th><td>'.$this->getProject()->getLicense().'</td></tr>'
            . '</table>';
        
        $this->counter = 0;
        $this->files = array();
    }

    public function export($filename)
    {
        file_put_contents($filename, $this->page);
    }

    public function postprocessor()
    {
        $this->page .= '</body></html>';
    }
    
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
        $style = 'stroke:black;stroke-width:1';
        
        $str = '<line x1="'.$l->getOrigin()->getX().'" y1="'.($this->top - $l->getOrigin()->getY()).'" x2="'.$l->getEndPoint()->getX().'" y2="'.($this->top - $l->getEndPoint()->getY()).'" style="'.$style.'" />';
        
        $this->current .= "\n\t".$str;
    }
}