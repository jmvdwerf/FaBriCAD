<?php

namespace jmw\fabricad\config\test;

use PHPUnit\Framework\TestCase;
use jmw\fabricad\config\Blueprint;
use jmw\fabricad\config\Project;

final class ProjectTest extends TestCase
{
    public function testNameProperty()
    {
        $src = 'This is a string';
        $p = new Project();
        $r = $p->setName($src);
        
        $this->assertEquals($p, $r);
        $this->assertEquals($src, $p->getName());
    }
    
    public function testDescriptionProperty()
    {
        $src = 'This is a string';
        $p = new Project();
        $r = $p->setDescription($src);
        
        $this->assertEquals($p, $r);
        $this->assertEquals($src, $p->getDescription());
    }
    
    public function testVersionProperty()
    {
        $src = 'This is a string';
        $p = new Project();
        $r = $p->setVersion($src);
        
        $this->assertEquals($p, $r);
        $this->assertEquals($src, $p->getVersion());
    }
    
    public function testLicenseProperty()
    {
        $src = 'This is a string';
        $p = new Project();
        $r = $p->setLicense($src);
        
        $this->assertEquals($p, $r);
        $this->assertEquals($src, $p->getLicense());
    }
    
    public function testAuthorProperty()
    {
        $src = 'This is a string';
        $p = new Project();
        $r = $p->setAuthor($src);
        
        $this->assertEquals($p, $r);
        $this->assertEquals($src, $p->getAuthor());
    }
    
    public function testSettings()
    {
        $s = ['setting 1' => 'some value', 'setting 2' => 'some other value'];
        $p = new Project();
        
        $r = $p->setSettings($s);
        $this->assertEquals($p, $r);
        $this->assertEquals($s, $p->getSettings());
        
    }
    
    public function testConstructor()
    {
        $n = 'name';
        $a = 'author';
        $d = 'description';
        $v = 'version';
        $l = 'license';
        $b = [ new Blueprint(), new Blueprint()];
        $s = ['setting 1' => 'some value', 'setting 2' => 'some other value'];
        
        $p = new Project($n, $a, $d, $v, $l, $b, $s);
        
        $this->assertEquals($n, $p->getName());
        $this->assertEquals($a, $p->getAuthor());
        $this->assertEquals($d, $p->getDescription());
        $this->assertEquals($v, $p->getVersion());
        $this->assertEquals($l, $p->getLicense());
        $this->assertEquals($b, $p->getBlueprints());
        $this->assertEquals($s, $p->getSettings());
        
        
        
    }
    
    public function testBlueprints()
    {
        $bp = [new Blueprint(), new Blueprint() ];
        $p = new Project();
        
        $r = $p->addBlueprint('1', $bp[0]);
        
        $this->assertEquals($p, $r);
        $this->assertEquals(1, $p->size());
        
        $r = $p->addBlueprint('2', $bp[1]);
        
        $this->assertEquals(2, $p->size());
        
        $c = 0;
        foreach($p->getBlueprints() as $key => $val) {
            $this->assertEquals($bp[$key], $val);
            $c++;
        }
        
        $this->assertEquals(count($bp), $c);
    }
    
    public function testIterator()
    {
        $bp = [new Blueprint(), new Blueprint() ];
        $p = new Project();
        
        $r = $p->setBlueprints($bp);
        
        $this->assertEquals($p, $r);
        $this->assertEquals(2, $p->size());
        
        $c = 0;
        foreach($p as $key => $val) {
            $this->assertEquals($bp[$key], $val);
            $c++;
        }
        
        $this->assertEquals(count($bp), $c);
    }
    
}