<?php
declare(strict_types=1);

namespace jmw\fabricad\shapes\test;

use jmw\fabricad\shapes\Point;
use jmw\fabricad\shapes\Rectangle;
use jmw\fabricad\shapes\ShapeList;

require_once('AbstractShapeTest.php');

class ShapeListTest extends AbstractShapeTest
{
    public function testCreateList()
    {
        $list = new ShapeList();
        
        $this->assertTrue($list->empty());
        $this->assertCount(0, $list->flatten());
        
        $r = array();
        $r['green'] = new Rectangle(100,200, new Point(350, 300));
        $r['blue'] = new Rectangle(100,200, new Point(100, 100));
        $r['gray'] = new Rectangle(100,200, new Point(300, 200));
        $r['red'] = new Rectangle(100,200, new Point(700, 100));
        
        foreach($r as $s) {
            $list->add($s);
        }
        
        $this->assertCount(1, $list->getRoot()->getLeft()->getShapes());
        $this->assertCount(1, $list->getRoot()->getRight()->getShapes());
        $this->assertCount(2, $list->getRoot()->getShapes());
        
        $this->assertFalse($list->empty());
        $this->assertEquals(4, $list->size());
        
    }
    
    public function testTree()
    {
        $list = new ShapeList();
        
        $this->assertTrue($list->empty());
        
        $r = array();
        $r[] = new Rectangle(10,20, new Point(70, 80));
        $r[] = new Rectangle(10,20, new Point(10, 10));
        $r[] = new Rectangle(10,20, new Point(30, 20));
        $r[] = new Rectangle(10,20, new Point(35, 40));
        
        $list->addAll($r);
        
        $this->assertEquals(4, $list->size());
        
        $this->assertCount(1, $list->getRoot()->getLeft()->getShapes());
        $this->assertNull($list->getRoot()->getLeft()->getLeft());
        $this->assertCount(2, $list->getRoot()->getLeft()->getRight()->getShapes());
        $this->assertEquals(2, $list->getRoot()->getLeft()->getRight()->size());
        $this->assertCount(1, $list->getRoot()->getShapes());
               
        foreach($list->getRoot()->getLeft()->getShapes() as $s1) {
            foreach($list->getRoot()->getShapes() as $s2) {
                $this->assertTrue($s1->getBoundingBox()->getOrigin()->getX() < $s2->getBoundingBox()->getOrigin()->getX());
            }
        }
        
        
    }
    
    public function testFlattenWithoutRemovingOverlap()
    {
        $list = new ShapeList();
            
        $this->assertTrue($list->empty());
        
        $r = [];
        $r['blue'] = new Rectangle(1000, 500, new Point(100, 200));
        $r['red']  = new Rectangle(100, 1000, new Point(200, 100));
        $r['green'] = new Rectangle(250, 250, new Point(150, 150));
        
        foreach($r as $s) {
            $list->add($s);
        }
        
        foreach($list as $key => $it) {
            $this->assertContains($it, $r);
        }
        
        $items = $list->flatten();
        $this->assertCount(3, $items);
        foreach($r as $s) {
            $this->assertContains($s, $items);
        }
        
    }
    
    public function testFlattenWithRemovingOverlap()
    {
        $list = new ShapeList();
        
        $r = array();
        $r['blue'] = new Rectangle(1000, 500, new Point(100, 200));
        $r['yellow'] = new Rectangle(50, 1000, new Point(0, 0));
        $r['red']  = new Rectangle(100, 1000, new Point(200, 100));
        $r['green'] = new Rectangle(250, 250, new Point(150, 150));
        $r['brown'] = new Rectangle(50, 1000, new Point(1200, 0));
        
        foreach($r as $s) {
            $list->add($s);
        }
        
        $this->assertCount(1, $list->getRoot()->getLeft()->getShapes());
        $this->assertCount(1, $list->getRoot()->getRight()->getShapes());
        $this->assertCount(3, $list->getRoot()->getShapes());
        
        $items = $list->flatten(true);
        $this->assertCount(7, $items);
    }
}
