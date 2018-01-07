<?php

namespace jmw\fabricad\config;

use jmw\fabricad\blocks\AbstractBuildingBlock;

class JSONReader
{
    public static function fromFile(string $filename): Project
    {
        if (file_exists($filename)) {
            return JSONReader::fromString(file_get_contents($filename));
        } else {
            throw new \Exception("File '".$filename."' does not exist!", 1515331846);
        }
    }
    
    public static function fromString(string $input): Project
    {
        $arr = json_decode($input, true);
        
        if (empty($arr)) {
            throw new \Exception("Could not parse project configuration!", 1515333473);
        }
        
        $p = new Project();
        var_dump($arr);
        foreach($arr as $key => $val) {
            switch($key) {
                case 'name':
                    $p->setName($val); 
                    break;
                case 'author':
                    $p->setAuthor($val); 
                    break;
                case 'description':
                    $p->setDescription($val); 
                    break;
                case 'license':
                    $p->setLicense($val);
                    break;
                case 'version':
                    $p->setVersion($val);
                    break;
                case 'blueprints':
                    foreach($val as $blueprint) {
                        $p->addBlueprint(JSONReader::parseBlueprint($blueprint));
                    }
                    break;
            }
        }
        
        return $p;
    }
    
    private static function parseBlueprint($arr = array()): Blueprint
    {
        // var_dump($arr);
        $b = new Blueprint();
        
        foreach($arr as $key => $val) {
            switch($key) {
                case 'name':
                    $b->setName($val);
                    break;
                case 'config':
                    $b->setSettings($val);
                    break;
                case 'blocks':
                    foreach($val as $key => $block) {
                        $b->addBlock(JSONReader::parseBuildingBlock($block));
                    }
                    break;
            }
        }
        
        return $b;
    }
    
    private static function parseBuildingBlock($arr = []): AbstractBuildingBlock 
    {
        $b = \jmw\fabricad\blocks\Factory::create($arr['type'], $arr['name']);
        $b->setConfig($arr['config']);
        
        // parse its shape
        
        return $b;
    }
}