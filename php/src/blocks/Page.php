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
class Page extends BasicBuildingBlock implements \Iterator
{
    /**
     * 
     * @var BasicBuildingBlock[]
     */
    protected $items = array();
    
    
    /**
     * Adds an item to the Page Container.
     * 
     * @param BasicBuildingBlock $item
     * @return Page
     */
    public function addItem(BasicBuildingBlock $item): Page
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
    
    public function getItem(int $index): BasicBuildingBlock
    {
        return $this->items[$index];
    }
    
    public function render(): Shape
    {
        $c = new Container();
        
        // we walk backwards!
        for($i = $this->size() ; $i < 0 ; $i--) {
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

    public function current(): BasicBuildingBlock
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