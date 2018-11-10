<?php
declare(strict_types=1);

namespace jmw\fabricad\shapes\test;

use jmw\fabricad\shapes\Ellipse;
use jmw\fabricad\shapes\Factory;
use jmw\fabricad\shapes\Rectangle;
use jmw\fabricad\shapes\Container;
use jmw\fabricad\shapes\Polygon;
use jmw\fabricad\shapes\Canvas;

require_once('AbstractShapeTest.php');


class FactoryTest extends AbstractShapeTest
{
    public function testCreateEllipse() {
        $shape = Factory::create('Ellipse');
        $this->assertInstanceOf(Ellipse::class, $shape);
        
        $this->assertEquals('jmw\fabricad\shapes\Ellipse', $shape->__tostring());
    }
    
    public function testCreateRectangle() {
        $shape = Factory::create('Rectangle');
        $this->assertInstanceOf(Rectangle::class, $shape);
    }
    
    public function testCreatePolygon() {
        $shape = Factory::create('Polygon');
        $this->assertInstanceOf(Polygon::class, $shape);
    }
    
    public function testCreateContainer() {
        $shape = Factory::create('Container');
        $this->assertInstanceOf(Container::class, $shape);
    }
    
    public function testCreateCanvas() {
        $shape = Factory::create('Canvas');
        $this->assertInstanceOf(Canvas::class, $shape);
    }
    
    public function testCreateThrowsException() {
        try {
            $shape = Factory::create('SomethingNotExisting');
        } catch(\Exception $e) {
            $this->assertEquals(1514580268, $e->getCode());
        }
    }
    
}
