<?php

namespace jmw\fabricad\config;

use jmw\fabricad\blocks\AbstractBuildingBlock;

class Blueprint implements \Iterator
{
    /**
     *
     * @var string
     */
    private $name = '';
    
    /**
     *
     * @var string
     */
    private $description = '';
    
    /**
     * 
     * @var array
     */
    private $blocks = array();
    
    public function __construct(string $name = '', string $description = '', $blocks = array())
    {
        $this->setName($name);
        $this->setDescription($description);
        $this->setBlocks($blocks);
    }
    
    /**
     * 
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * 
     * @param string $name
     * @return Blueprint
     */
    public function setName(string $name): Blueprint
    {
        $this->name = $name;
        return $this;
    }

    /**
     * 
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * 
     * @param string $description
     * @return Blueprint
     */
    public function setDescription(string $description): Blueprint
    {
        $this->description = $description;
        return $this;
    }

    /**
     * 
     * @return array
     */
    public function getBlocks(): array
    {
        return $this->blocks;
    }
    
    public function addBlock(AbstractBuildingBlock $block): Blueprint
    {
        $this->blocks[] = $block;
        
        return $this;
    }

    /**
     * 
     * @param AbstractBuildingBlock[] $blocks
     * @return Blueprint
     */
    public function setBlocks(array $blocks): Blueprint
    {
        $this->blocks = $blocks;
        return $this;
    }
    
    public function size(): int
    {
        return count($this->blocks);
    }
    
    // -------------------------------------------------------------------------
    // Iterator implementation
    private $it_counter = 0;
    
    public function next()
    {
        ++$this->it_counter;
    }
    
    public function valid()
    {
        return isset($this->blocks[$it_counter]);
    }
    
    public function current()
    {
        return $this->blocks[$this->it_counter];
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
