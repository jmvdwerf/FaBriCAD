<?php

namespace jmw\fabricad\config\test;

use PHPUnit\Framework\TestCase;
use jmw\fabricad\config\Blueprint;
use jmw\fabricad\blocks\BasicBuildingBlock;

final class BlueprintTest extends TestCase
{
    public function testNameProperty()
    {
        $src = 'This is a string';
        $bp = new Blueprint();
        $r = $bp->setName($src);
        
        $this->assertEquals($bp, $r);
        $this->assertEquals($src, $bp->getName());
    }
    
    public function testDescriptionProperty()
    {
        $src = 'This is a string';
        $bp = new Blueprint();
        $r = $bp->setDescription($src);
        
        $this->assertEquals($bp, $r);
        $this->assertEquals($src, $bp->getDescription());
    }
    
    public function testAddBlock()
    {
        $s = new BasicBuildingBlock();
        $bp = new Blueprint();
        
        $r = $bp->addBlock($s);
        
        $this->assertEquals($bp, $r);
        $this->assertEquals(1, $bp->size());
        $this->assertContains($s, $bp->getBlocks());
    }
    
    public function testSetBlocks()
    {
        $s = [ new BasicBuildingBlock(), new BasicBuildingBlock(), new BasicBuildingBlock()];
        $bp = new Blueprint();
        
        $r = $bp->setBlocks($s);
        
        $this->assertEquals($bp, $r);
        $this->assertEquals(count($s), $bp->size());
        $this->assertEquals($s, $bp->getBlocks());
    }
    
    public function testConstructor()
    {
        $n = 'Name';
        $d = 'Description';
        $c = [ 'setting1' => 'value1', 'setting2' => 'value2'];
        
        $s = [ new BasicBuildingBlock(), new BasicBuildingBlock()];
        
        $bp = new Blueprint($n, $d, $c, $s);
        
        $this->assertEquals($n, $bp->getName());
        $this->assertEquals($d, $bp->getDescription());
        $this->assertEquals($c, $bp->getSettings());
        $this->assertEquals($s, $bp->getBlocks());
    }
    
    public function testIterator()
    {
        $s = [ new BasicBuildingBlock(), new BasicBuildingBlock(), new BasicBuildingBlock()];
        $bp = new Blueprint();
        
        for($i = 0 ; $i < count($s) ; $i++) {
            $s[$i]->setName('block '.$i);
            $bp->addBlock($s[$i]);
        }
        
        foreach($bp as $key => $val) {
            $this->assertEquals($s[$key], $val);
        }
    }
}