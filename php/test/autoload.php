<?php


spl_autoload_register(function ($name) {
    $items = explode("\\", $name);
    
    if (is_array($items) && count($items) > 2 && $items[0] == 'jmw' && $items[1] == 'fabricad') {
        array_shift($items);
        array_shift($items);
        
        $file = __DIR__.DIRECTORY_SEPARATOR.'../src/'.implode(DIRECTORY_SEPARATOR, $items).".php";
        
        // echo "DIR: ".__DIR__."\n";
        // echo "FILE: ".$file."\n";
        if (file_exists($file)) require_once($file);
    }
});