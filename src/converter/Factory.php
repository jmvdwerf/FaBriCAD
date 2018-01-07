<?php

namespace jmw\fabricad\converter;

use jmw\fabricad\converter\svg\SVGConverter;

class Factory
{
    public static function convert($type, $file, $project): AbstractConverter
    {
        switch(strtolower($type)) {
            case 'svg':
                $s = new SVGConverter();
                $s->convert($project);
                $s->export($file);
                
                return $s;
            default:
                throw new \Exception("No converter for: '".$type."'", 1515358384);
        }
        
        return null;
    }
}