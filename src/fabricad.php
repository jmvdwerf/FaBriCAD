<?php

namespace jmw\fabricad;

spl_autoload_register(function ($name) {
    $items = explode("\\", $name);
    
    if (is_array($items) && count($items) > 2 && $items[0] == 'jmw' && $items[1] == 'fabricad') {
        array_shift($items);
        array_shift($items);
        
        $file = __DIR__.DIRECTORY_SEPARATOR.implode(DIRECTORY_SEPARATOR, $items).".php";
        
        echo "DIR: ".__DIR__."\n";
        echo "FILE: ".$file."\n";
        
        require_once($file);
    } else {
        throw new \Exception("Cannot find class: ".$name, 1515327695);
    }
});


FaBriCAD::main($_SERVER);


class FaBriCAD
{
    
    public static function main($args = array())
    {
        $c = new \jmw\fabricad\shapes\Rectangle();
    }
    
}