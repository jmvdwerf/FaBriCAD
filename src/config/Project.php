<?php

namespace jmw\fabricad\config;

class Project implements \Iterator
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
     * @var string
     */
    private $version = '';
    
    /**
     * 
     * @var string
     */
    private $license = '';
    
    /**
     * 
     * @var string
     */
    private $author = '';
    
    /**
     * 
     * @var array
     */
    private $settings = array();
    
    /**
     * 
     * @var array
     */
    private $blueprints = array();
    
    /**
     * 
     * @param string $name
     * @param string $author
     * @param string $description
     * @param string $version
     * @param string $license
     * @param Blueprint[] $blueprints
     * @param array $settings
     */
    public function __construct(
        string $name = '', 
        string $author = '', 
        string $description = '', 
        string $version = '', 
        string $license = '', 
        $blueprints = array(),
        $settings = array())
    {
        $this->setName($name);
        $this->setAuthor($author);
        $this->setDescription($description);
        $this->setVersion($version);
        $this->setLicense($license);
        $this->setBlueprints($blueprints);
        $this->setSettings($settings);
    }
    
    /**
     * @return string $name
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return Project
     */
    public function setName(string $name): Project
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string $description
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @param string $description
     * @return Project
     */
    public function setDescription(string $description): Project
    {
        $this->description = $description;
        return $this;
    }

    /**
     * @return string $version
     */
    public function getVersion(): string
    {
        return $this->version;
    }

    /**
     * 
     * @param string $version
     * @return Project
     */
    public function setVersion($version): Project
    {
        $this->version = $version;
        return $this;
    }

    /**
     * 
     * @return string
     */
    public function getLicense(): string
    {
        return $this->license;
    }
    
    /**
     * 
     * @param string $license
     * @return string
     */
    public function setLicense(string $license): string
    {
        $this->license = $license;
        return $this;
    }

    /**
     * 
     * @return string
     */
    public function getAuthor(): string
    {
        return $this->author;
    }

    /**
     * 
     * @param string $author
     * @return Project
     */
    public function setAuthor(string $author): Project
    {
        $this->author = $author;
        return $this;
    }

    /**
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
     * @return Project
     */
    public function setSettings(array $settings): Project
    {
        $this->settings = $settings;
        return $this;
    }

    /**
     * 
     * @return Blueprint[]
     */
    public function getBlueprints(): array
    {
        return $this->blueprints;
    }

    /**
     * 
     * @param Blueprint[] $blueprints
     * @return Project
     */
    public function setBlueprints(array $blueprints): Project
    {
        $this->blueprints = $blueprints;
        return $this;
    }
    
    public function addBlueprint(Blueprint $print): Project
    {
        $this->blueprints[] = $print;
        
        return $this;
    }
    
    public function size(): int
    {
        return count($this->blueprints);
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
        return isset($this->blueprints[$this->it_counter]);
    }

    public function current()
    {
        return $this->blueprints[$this->it_counter];
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