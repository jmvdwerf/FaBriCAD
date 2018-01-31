<?php
declare(strict_types=1);

namespace jmw\fabricad\shapes\test;

require_once('AbstractShapeTest.php');

use jmw\fabricad\shapes\Rectangle;
use jmw\fabricad\shapes\Point;
use jmw\fabricad\shapes\BinaryOperators;

final class BinaryOperatorsTest extends AbstractShapeTest
{
    
    public function testDifference()
    {
        $r1 = new Rectangle(100,100);
        $r2 = new Rectangle(100,100, new Point(50,50));
        
        $diff = BinaryOperators::difference($r1, $r2);
        
        $this->assertCount(1, $diff);
        
        $shape = $diff[0];
        
        $this->assertPoint($shape->getPoints()[0],   0,   0);
        $this->assertPoint($shape->getPoints()[1],   0, 100);
        $this->assertPoint($shape->getPoints()[2],  50, 100);
        $this->assertPoint($shape->getPoints()[3],  50,  50);
        $this->assertPoint($shape->getPoints()[4], 100,  50);
        $this->assertPoint($shape->getPoints()[5], 100,   0);
    }
    
    public function notestUnion()
    {
        $r1 = new Rectangle(100,100);
        $r2 = new Rectangle(100,100, new Point(50,50));
        
        $diff = BinaryOperators::union($r1, $r2);
        
        $this->assertCount(1, $diff);
        
        $shape = $diff[0];
        
        $this->assertPoint($shape->getPoints()[0],   0,   0);
        $this->assertPoint($shape->getPoints()[1],   0, 100);
        $this->assertPoint($shape->getPoints()[2],  50, 100);
        $this->assertPoint($shape->getPoints()[3],  50, 150);
        $this->assertPoint($shape->getPoints()[4], 150, 150);
        $this->assertPoint($shape->getPoints()[5], 150,  50);
        $this->assertPoint($shape->getPoints()[6], 100,  50);
        $this->assertPoint($shape->getPoints()[7], 100,   0);
    }
    
    public function notestIntersection()
    {
        $r1 = new Rectangle(100,100);
        $r2 = new Rectangle(100,100, new Point(50,50));
        
        $diff = BinaryOperators::intersection($r1, $r2);
        
        $this->assertCount(1, $diff);
        /*
        $shape = $diff[0];
        
        $this->assertPoint($shape->getPoints()[0],  50, 100);
        $this->assertPoint($shape->getPoints()[1], 100, 100);
        $this->assertPoint($shape->getPoints()[2], 100,  50);
        $this->assertPoint($shape->getPoints()[3],  50,  50);
        */
    } 
        
    
}