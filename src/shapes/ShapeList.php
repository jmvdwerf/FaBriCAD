<?php

namespace jmw\fabricad\shapes;

class ShapeList implements \Iterator 
{
    private $count = 0;
    private $root = null;
    
    private $updated = true;
    
    public function size(): int
    {
        return $this->count;
    }
    
    public function empty(): bool
    {
        return ($this->count == 0);
    }
    
    public function addAll($shapes = array()): ShapeList
    {
        foreach($shapes as $s) {
            $this->add($s);
        }
        return $this;
    }
    
    public function add(Shape $s): ShapeList
    {
        $this->count++;
        if ($this->root == null) {
            $this->root = new ShapeNode($s);
            return $this;
        } 
        
        $this->root->insert($s);
        
        $this->updated = true;
        
        return $this;
    }
    
    public function flatten(): array
    {
        if ($this->updated) {
            if ($this->root == null) {
                $this->cur_list = [];
            } else {
                $this->cur_list = $this->root->flatten();
            }
            $this->updated = false;
        }
        
        return $this->cur_list;
    }
    
    public function getRoot()
    {
        return $this->root;
    }
    
    
    
    private $cur_list = array();
    private $cur_cnt = 0;
    
    public function next()
    {
        $this->cur_cnt++;
    }

    public function valid()
    {
        return (isset($this->cur_list[$this->cur_cnt]));       
    }

    public function current()
    {
        return $this->cur_list[$this->cur_cnt];
    }

    public function rewind()
    {
        $this->cur_cnt = 0;
        $this->flatten();
    }

    public function key()
    {
        return $this->cur_cnt;
    }

    
    
}

class ShapeNode 
{
    
    private $shapes = array();
    
    private $left = null;
    private $right = null;
    
    private $min = 0;
    private $max = 0;
    
    
    public function size(): int
    {
        return count($this->shapes);
    }
    
    public function __construct(Shape $s) {
        $bb = $s->getBoundingBox();
        $this->min = $bb->getOrigin()->getX();
        $this->max = $bb->getTop()->getX();
        
        $this->shapes[] = $s;
    }
    
    public function getShapes(): array
    {
        return $this->shapes;
    }
    
    public function getLeft(): ?ShapeNode
    {
        return $this->left;
    }
    
    public function getRight(): ?ShapeNode
    {
        return $this->right;
    }
    
    
    public function insert(Shape $s)
    {
        $bb = $s->getBoundingBox();
        $smin = $bb->getOrigin()->getX();
        $smax = $bb->getTop()->getX();
        
        if ($smax < $this->min) {
            // insert into the left
            if ($this->left == null) {
                $this->left = new ShapeNode($s);
            } else {
                $this->left->insert($s);
            }
        } elseif ($smin > $this->max) {
            // insert into the right
            if ($this->right == null) {
                $this->right = new ShapeNode($s);
            } else {
                $this->right->insert($s);
            }
        } else {
            // it overlaps, so add it to our shapes
            $this->shapes[] = $s;
        }
    }
    
    public function flatten(): array
    {
        $result = array();
        
        if ($this->left != null) {
            $result = $this->left->flatten();
        }

        $result = array_merge($result, $this->shapes);
        
        if ($this->right != null) {        
            $result = array_merge($result, $this->right->flatten());
        }
        return $result;
    }
}








