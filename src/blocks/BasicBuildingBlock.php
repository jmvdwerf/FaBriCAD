<?php

namespace jmw\fabricad\blocks;

use jmw\fabricad\shapes\Shape;

/**
 * The abs
 * @author jmw
 *
 */
class BasicBuildingBlock
{
    
    /**
     * 
     * @var array
     */
    protected $config = array();
    
    /**
     * @var string
     */
    protected $name = "";
    
    /**
     * 
     * @var string
     */
    protected $description = "";
    
    /**
     * 
     * @var \jmw\fabricad\shapes\Shape
     */
    protected $shape = null;
    
    /**
     * 
     * @return \jmw\fabricad\shapes\Shape
     */
    public function render(): Shape
    {
        return $this->shape;
    }
    
    
    /**
     * 
     * @param string $name
     * @param array $config
     */
    public function __construct(string $name = '', $config = array())
    {
        $this->setConfig($config);
        $this->setName($name);
    }
    
    /**
     * Returns the name of the object
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }
    
    /**
     * Sets the name of the building block
     * 
     * @param string $name
     * @return BasicBuildingBlock
     */
    public function setName(string $name): BasicBuildingBlock
    {
        $this->name = $name;
        
        return $this;
    }
    
    /**
     * @return string $description
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $description
     * @return BasicBuildingBlock
     */
    public function setDescription(string $description): BasicBuildingBlock
    {
        $this->description = $description;
        
        return $this;
    }
    
    /**
     * @return array $config
     */
    public function getConfig(): array
    {
        return $this->config;
    }

    /**
     * @param array $config
     */
    public function setConfig($config = array()): BasicBuildingBlock
    {
        $this->config = $config;
        return $this;
    }
    
    /**
     * 
     * @return Shape
     */
    public function  getShape(): Shape
    {
        return $this->shape;
    }
    
    /**
     * 
     * @param Shape $shape
     * @return BasicBuildingBlock
     */
    public function setShape(Shape $shape): BasicBuildingBlock
    {
        $this->shape = $shape;
        return $this;
    }

 
    
}