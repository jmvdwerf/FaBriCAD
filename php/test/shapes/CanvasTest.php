<?php
declare(strict_types=1);

namespace jmw\fabricad\shapes\test;

use jmw\fabricad\shapes\Canvas;
use jmw\fabricad\shapes\Point;
use jmw\fabricad\shapes\Rectangle;
use jmw\fabricad\shapes\Container;

require_once('AbstractShapeTest.php');

class CanvasTest extends AbstractShapeTest
{
    public function testCreation()
    {
        $r = array();
        $r[] = new Rectangle(3,4, new Point(2,2));
        $r[] = new Rectangle(5,5, new Point(7,7));
        $c = new Canvas($r, new Point(4,4));
        
        $this->assertPoint($c->getOrigin(), 4, 4);
        
        $this->assertCount(2, $c->getShapes());
    }
    
    public function testCreationEmpytOrigin()
    {
        $r = array();
        $r[] = new Rectangle(3,4, new Point(2,2));
        $r[] = new Rectangle(5,5, new Point(7,7));
        $c = new Canvas($r);
        
        $this->assertPoint($c->getOrigin(), 0, 0);
        
        $c->setOrigin(new Point(4,3));
        $this->assertPoint($c->getOrigin(), 4, 3);
        
        $this->assertCount(2, $c->getShapes());
    }
    
    public function testFlatten()
    {
        $r = array();
        $r[] = new Rectangle(3,4, new Point(2,2));
        $r[] = new Rectangle(5,5, new Point(7,9));
        $c = new Canvas($r, new Point(4,4));
        
        $items = $c->flatten();
        
        $this->assertCount(2, $items);
        
        foreach($items as $it) {
            if ($it->contains(new Point(6,6))) {
                $this->assertRectangle($it, 6, 6, 3, 4);
            } else {
                $this->assertRectangle($it, 11, 13, 5, 5);
            }
        }
    }
    
    public function testFlattenAfterMove()
    {
        $r = array();
        $r[] = new Rectangle(3,4, new Point(2,2));
        $r[] = new Rectangle(5,5, new Point(7,9));
        $c = new Canvas($r, new Point(4,4));
        $c->move(-1, 2);
        
        $items = $c->flatten();
        
        $this->assertCount(2, $items);
        
        foreach($items as $it) {
            if ($it->contains(new Point(5,8))) {
                $this->assertRectangle($it, 5, 8, 3, 4);
            } else {
                $this->assertRectangle($it, 10, 15, 5, 5);
            }
        }
    }
    
    public function testContains()
    {
    
        $r = array();
        $r[] = new Rectangle(3,4, new Point(2,2));
        $r[] = new Rectangle(5,5, new Point(7,9));
        $c = new Canvas($r, new Point(100,100));
        
        $this->assertFalse($c->contains(new Point(4, 5)));
        $this->assertTrue($c->contains(new Point(104, 105)));
        
        $r2 = new Rectangle(30, 30, new Point(20, 20));
        $c2 = new Canvas([$r2], new Point(200, 200));
        $c->addShape($c2);
        
        $this->assertFalse($c2->contains(new Point(30, 30)));
        $this->assertTrue($c2->contains(new Point(230, 230)));
        
        $this->assertFalse($c->contains(new Point(230, 230)));
        $this->assertTrue($c->contains(new Point(330, 330)));
    }
    
    public function testBoundingBox()
    {
        
        $r = array();
        $r[] = new Rectangle(3,4, new Point(2,2));
        $r[] = new Rectangle(5,5, new Point(7,9));
        $c = new Canvas($r, new Point(100,100));
        $cont = new Container($r);
        $this->assertBoundingBox($cont, 2, 2, 10, 12);
        $this->assertBoundingBox($c, 102, 102, 10, 12);
        
        $r2 = new Rectangle(30, 30, new Point(20, 20));
        $c2 = new Canvas([$r2], new Point(200, 200));
        $c->addShape($c2);
        
        
    }
}