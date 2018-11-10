<?php

namespace jmw\fabricad\blocks;

class Factory
{
    
    
    public static function create(string $type, string $name, $config = array()): BasicBuildingBlock
    {
        $cname = strtoupper(substr($type, 0 ,1)).substr($type, 1);
                
        $class = '\\jmw\\fabricad\\blocks\\'.$cname;
        
        if (class_exists($class)) {
            return new $class($name, $config);
        } else {
            throw new \Exception("Cannot find class: '".$class."'", 1514580268);
        }
        
        return null;
    }
    
    
}