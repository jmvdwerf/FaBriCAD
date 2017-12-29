<?php

namespace jmw\fabricad\blocks;

class Factory
{
    
    
    public static function create(string $type, string $name, $config = array()): AbstractBuildingBlock
    {
        $class = '\\jmw\\fabricad\\blocks\\'.$type;
        
        if (class_exists($class)) {
            return new $class($name, $config);
        } else {
            throw new \Exception('Cannot find class', 1514580268);
        }
        
        return null;
    }
    
    
}