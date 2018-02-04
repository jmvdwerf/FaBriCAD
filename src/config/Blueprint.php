<?php

namespace jmw\fabricad\config;

use jmw\fabricad\blocks\BasicBuildingBlock;
use jmw\fabricad\shapes\Container;

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
    private $id = '';
    
    /**
     *
     * @var string
     */
    private $description = '';
    
    
    private $settings = array();
    
    /**
     * 
     * @var array
     */
    private $blocks = array();
    
    public function __construct(string $name = '', string $description = '', $config = array(), $blocks = array())
    {
        $this->setName($name);
        $this->setDescription($description);
        $this->setSettings($config);
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
     * Gets the id
     * 
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }
    
    /**
     * Sets the id
     * 
     * @param string $id
     * @return Blueprint
     */
    public function setId(string $id): Blueprint
    {
        $this->id = $id;
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
     * @return BasicBuildingBlock[]
     */
    public function getBlocks(): array
    {
        return $this->blocks;
    }
    
    public function addBlock(BasicBuildingBlock $block): Blueprint
    {
        $this->blocks[] = $block;
        
        return $this;
    }
    
    /**
     * Sets the settings to this array
     * 
     * @return array
     */
    public function getSettings(): array
    {
        return $this->settings;
    }
    
    /**
     * 
     * @param array $settings
     * @return Blueprint
     */
    public function setSettings(array $settings): Blueprint
    {
        $this->settings = $settings;
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
    
    
    public function render(): Container {
        $c = new Container();
        
        echo "\nstart".date('U')."\n";

        // we walk backward!
        for($i = $this->size() -1; $i >= 0 ; $i--) {
            $shape = $this->blocks[$i]->render();
            if ($shape instanceof Container) {
                $items = $shape->flatten();
            } else {
                $items = [$shape];
            }
            
            foreach($items as $item) {
                echo 'a';
                $c->addNonOverlappingParts($item);
                echo 'b';
                //$c->addShape($item);
            }
        }
        echo "\nend".date('U')."\n";
        
        return $c;
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
        return isset($this->blocks[$this->it_counter]);
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
