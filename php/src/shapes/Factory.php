<?php
namespace jmw\fabricad\shapes;

class Factory
{
    
    
    public static function create(string $type): Shape
    {
        $cname = strtoupper(substr($type, 0 ,1)).substr($type, 1);
        
        $class = '\\jmw\\fabricad\\shapes\\'.$cname;
        
        if (class_exists($class)) {
            return new $class();
        } else {
            throw new \Exception("Cannot find class: '".$class."'", 1514580268);
        }
        
        return null;
    }
    
    
}