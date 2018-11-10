<?php
declare(strict_types=1);

namespace jmw\fabricad\blocks\test;

use PHPUnit\Framework\TestCase;
use jmw\fabricad\blocks\Brickwall;
use jmw\fabricad\blocks\Page;

final class PageTest extends TestCase
{
    
    public function testName()
    {
        $name = "This is my name";
        $p = new Page();
        $h = $p->setName($name);
        
        $this->assertEquals($p, $h);
        $this->assertEquals($name, $p->getName());
    }
    
    public function testDescription()
    {
        $descr = "This is my description";
        $p = new Page();
        $h = $p->setDescription($descr);
        
        $this->assertEquals($p, $h);
        $this->assertEquals($descr, $p->getDescription());
    }
    
    public function testIterator()
    {
        $s = array();
        $s[] = new Brickwall();
        $s[] = new Brickwall();
        
        $s[0]->setName("Wall 1");
        $s[1]->setName("Wall 2");
        
        $page = new Page();
        
        $page->addItem($s[0]);
        $page->addItem($s[1]);
        
        $this->assertEquals(2, $page->size());
        
        $cnt = 0;
        
        foreach($page as $key => $val) {
            $this->assertEquals($s[$key], $val);
            $cnt++;
        }
        
        $this->assertEquals($page->size(), $cnt);
    }
    
    public function testGetItems()
    {
        $s = array();
        $s[] = new Brickwall();
        $s[] = new Brickwall();
        
        $s[0]->setName("Wall 1");
        $s[1]->setName("Wall 2");
        
        $page = new Page();
        
        $page->addItem($s[0]);
        $page->addItem($s[1]);
        
        $this->assertEquals(2, $page->size());
        
        $cnt = 0;
        
        foreach($page->getItems() as $key => $val) {
            $this->assertEquals($s[$key], $val);
            $cnt++;
        }
        
        $this->assertEquals($page->size(), $cnt);
    }
}

