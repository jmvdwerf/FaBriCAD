<?php

namespace jmw\fabricad\blocks;

use jmw\fabricad\shapes\Shape;

/**
 * The abs
 * @author jmw
 *
 */
abstract class AbstractBuildingBlock
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
     * @return \jmw\fabricad\shapes\Shape
     */
    public abstract function render(): Shape;
    
    
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
     * @return AbstractBuildingBlock
     */
    public function setName(string $name): AbstractBuildingBlock
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
     */
    public function setDescription(string $description): AbstractBuildingBlock
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
    public function setConfig($config = array()): AbstractBuildingBlock
    {
        $this->config = $config;
        return $this;
    }

 
    
}