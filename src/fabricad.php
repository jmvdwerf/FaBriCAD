<?php

namespace jmw\fabricad;

use jmw\fabricad\config\JSONReader;
use jmw\fabricad\converter\Factory;

spl_autoload_register(function ($name) {
    $items = explode("\\", $name);
    
    if (is_array($items) && count($items) > 2 && $items[0] == 'jmw' && $items[1] == 'fabricad') {
        array_shift($items);
        array_shift($items);
        
        $file = __DIR__.DIRECTORY_SEPARATOR.implode(DIRECTORY_SEPARATOR, $items).".php";
        
        // echo "DIR: ".__DIR__."\n";
        // echo "FILE: ".$file."\n";
        
        require_once($file);
    } else {
        throw new \Exception("Cannot find class: ".$name, 1515327695);
    }
});


FaBriCAD::main($_SERVER['argv']);


class FaBriCAD
{
    
    /**
     * 
     * @var \jmw\fabricad\config\Project
     */
    private $config = array('in' => '', 'out' => []);
    
    public static function main($args = array())
    {
        $f = new FaBriCAD();
        $status = $f->run($args);
        
        exit($status);
    }
       
    
    private function run($args = array()): int
    {
        $this->config = array('in' => '', 'out' => []);
        
        if (!is_array($args) || count($args) < 2 || in_array('--help', $args)) {
            echo $this->returnHelp($args[0]);
            return 1;
        }
        
        $i = 0;
        $cmd = '';
        while($i < count($args)) {
            if(substr($args[$i],0,1) == '-') {
                // it is a command!
                $cmd = trim(substr($args[$i], 1));
                $this->register($cmd);
            } else {
                $this->register($cmd, $args[$i]);
            }
            $i++;
        }
        
        if (empty($this->config['in'])) {
            echo $this->returnHelp($args[0]);
            return 2;
        }
        
        try {
            $project = JSONReader::fromFile($this->config['in']);
        } catch(\Exception $e) {
            echo $e->getCode().": ".$e->getMessage();
            return $e->getCode();
        }

        
        foreach($this->config['out'] as $type => $file) {
            $filename = $file;
            if (empty($filename)) {
                $filename = pathinfo($this->config['in'], PATHINFO_FILENAME).".".$type;
            }
            Factory::convert($type, $filename, $project);
        }
        
        return 0;
    }
    
    private function register(string $cmd, $filename = '') {
        switch(substr($cmd, 0, 1)) {
            case 'I':
                $this->config['in'] = $filename;
                break;
            case 'T':
                $this->config['out'][substr($cmd,1)] = $filename;
                break;
        }
    }
    
    private function returnHelp($progname = ''): string
    {
        if (empty($progname)) {
            $progname = basename(__FILE__);
        }
        
        $str = 'FaBriCAD is a program that creates blueprints in several output formats.';
        $str .= "\n\n";
        $str .= 'command line options: '."\n";
        $str .= "\t -I <project-file>\twith <project-file> the config file\n";
        $str .= "\t -T<type> <outputfile>\twith <type> the output type and\n";
        $str .= "\t\t\t\t<outputfile> the file to write to.\n";
        // $str .= "\t\t\t\tIf no output file is given, STD_OUT is used\n";
        $str .= "\t --help\t\t\toutputs this help message\n\n\n";
        
        $str .= "Currently supported output types:\n";
        $str .= "\tsvg\tExports to an HTML page with for each blueprint an SVG image\n";
        $str .= "\n\n";
        $str .= "For example: \n\n\t".$progname." -I ../examples/shed.fabricad -Tsvg shed.svg -Tdxf shed.dxf\n\n";
        $str .= "will output an SVG drawing to shed.svg, and a DXF drawing to shed.dxf.\n\n\n";
        $str .= "FaBriCAD (c) 2018, Jan Martijn van der Werf\n\n";
        
        return $str;
    }
    
}