<?php

namespace jmw\fabricad\config;

use jmw\fabricad\blocks\BasicBuildingBlock;
use jmw\fabricad\shapes\Ellipse;
use jmw\fabricad\shapes\Point;
use jmw\fabricad\shapes\Polygon;
use jmw\fabricad\shapes\Rectangle;

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
                case 'settings':
                    $p->setSettings($val);
                    break;
                case 'blueprints':
                    foreach($val as $key => $blueprint) {
                        $p->addBlueprint($key, JSONReader::parseBlueprint($blueprint));
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
    
    private static function parseBuildingBlock($arr = []): BasicBuildingBlock
    {
        if (!isset($arr['type'])) {
            throw new \Exception('No type defined in Block');
        }
        
        $name = isset($arr['name']) ? $arr['name'] : '';
        
        $b = \jmw\fabricad\blocks\Factory::create($arr['type'], $name);
        
        if (isset($arr['config'])) $b->setConfig($arr['config']);
        
        // parse its shape
        if (!isset($arr['shape'])) {
            throw new \Exception('No shape defined in Block');
        }
        
        if (!isset($arr['shape']['type'])) {
            throw new \Exception('No type defined in Shape');
        }
        
        $shape = \jmw\fabricad\shapes\Factory::create($arr['shape']['type']);
        if ($shape instanceof Rectangle) {
            $shape->setOrigin(new Point($arr['shape']['origin']['x'], $arr['shape']['origin']['y']));
            $shape->setWidth($arr['shape']['width']);
            $shape->setHeight($arr['shape']['height']);
        } elseif ($shape instanceof Ellipse) {
            $shape->setXFactor($arr['shape']['A']);
            $shape->setYFactor($arr['shape']['B']);
            $shape->setOriginXY($arr['shape']['origin']['x'], $arr['shape']['origin']['y']);
        } elseif ($shape instanceof Polygon) {
            foreach($arr['shape']['points'] as $pt) {
                $shape->addPoint(new Point($pt['x'], $pt['y']));
            }
        }
        
        $b->setShape($shape);
        
        return $b;
    }
}