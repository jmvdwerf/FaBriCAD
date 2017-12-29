<?php

namespace jmw\fabricad\blocks;

use jmw\fabricad\shapes\Container;
use jmw\fabricad\shapes\Shape;

/**
 * A Page is the main element in drawing: it contains all the other Shapes
 *  
 * @author jmw
 *
 */
class Page extends AbstractBuildingBlock implements \Iterator
{
    /**
     * 
     * @var AbstractBuildingBlock[]
     */
    protected $items = array();
    
    
    /**
     * Adds an item to the Page Container.
     * 
     * @param AbstractBuildingBlock $item
     * @return Page
     */
    public function addItem(AbstractBuildingBlock $item): Page
    {
        $this->items[] = $item;
        
        return $this;
    }
    
    public function size(): int
    {
        return count($this->items);
    }
    
    public function getItems(): array
    {
        return $this->items;
    }
    
    public function render(): Shape
    {
        $c = new Container();
        foreach($this->items as $block) {
            $c->addShape($block->render());
        }
        
        return $c;
    }
    
    
    // -------------------------------------------------------------------------
    // Iterator implementation
    private $it_counter = 0;
    
    public function next()
    {
        ++$this->it_counter;        
    }

    public function valid(): bool
    {
        return isset($this->items[$this->it_counter]);
    }

    public function current(): AbstractBuildingBlock
    {
        return $this->items[$this->it_counter];
    }

    public function rewind()
    {
        $this->it_counter = 0;
    }

    public function key()
    {
        return $this->it_counter;
    }

    
}