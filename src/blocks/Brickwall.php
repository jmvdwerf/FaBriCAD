<?php

namespace jmw\fabricad\blocks;

use jmw\fabricad\shapes\Rectangle;
use jmw\fabricad\shapes\Shape;
use jmw\fabricad\shapes\Point;

class Brickwall extends AbstractBuildingBlock
{
    public function render(): Shape
    {
        return new Rectangle(5,5, new Point(10, 10));
    }

    
}