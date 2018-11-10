<?php

namespace jmw\fabricad\converter;

use jmw\fabricad\config\Blueprint;
use jmw\fabricad\config\Project;

use jmw\fabricad\shapes\Ellipse;
use jmw\fabricad\shapes\Line;
use jmw\fabricad\shapes\Polygon;
use jmw\fabricad\shapes\Shape;
use jmw\fabricad\shapes\Container;

abstract class AbstractConverter
{
    
    public abstract function export($filename);
    
    public abstract function preprocessor();
    
    public abstract function postprocessor();
    
    public abstract function processBlueprint(Blueprint $print);
    
    /**
     * 
     * @var \jmw\fabricad\config\Project
     */
    protected $project = null;
    
    /**
     * 
     * @return Project
     */
    public function getProject(): Project
    {
        return $this->project;
    }
    
    /**
     * 
     * @param Project $project
     * @return AbstractConverter
     */
    public function setProject(Project $project): AbstractConverter
    {
        $this->project = $project;
        return $this;
    }
    
    public final function convert(Project $project)
    {
        $this->setProject($project);
        $this->preprocessor();
        
        foreach($project as $blueprint) {
            $this->processBlueprint($blueprint);
        }
        
        $this->postprocessor();
    }
    
    protected abstract function processEllipse(Ellipse $e);
    
    protected abstract function processLine(Line $l);
    
    protected abstract function processPolygon(Polygon $p);
    
    protected final function processShape(Shape $s)
    {
        if ($s instanceof Ellipse) {
            $this->processEllipse($s);
        } elseif ($s instanceof Line) {
            $this->processLine($s);
        } elseif($s instanceof Polygon) {
            $this->processPolygon($s);
        } elseif($s instanceof Container) {
            foreach($s as $elem) {
                $this->processShape($elem);
            }
        }
    }
   
}