<?php

namespace jmw\fabricad\shapes;


class Container extends Shape implements \Iterator
{
    
    /**
     * Collection of shapes
     * @var array
     */
    private $shapes = array();
    
    /**
     * Internal representation of the bounding box of this Shape. The bounding
     * box $bb is *relative* to the origin of this container!
     *  
     * @var Rectangle
     */
    private $bb = null;
    
    public function __construct($items = [], Point $origin = null)
    {
        parent::__construct($origin);
        
        $this->bb = new Rectangle(0,0, $origin);
        
        foreach($items as $s) {
            $this->addShape($s);
        }
    }
    
    /**
     * Returns the shape of this Container
     * @return Shape[]
     */
    public function getShapes(): array
    {
        return $this->shapes;
    }
    
    /**
     * Returns the number of Shapes in this container.
     * @return int
     */
    public function size(): int
    {
        return count($this->shapes);
    }
    
    /**
     * Adds a shape to the collection. The coordinates of the share are relative
     * to the origin of the Container!
     * 
     * @param Shape $s
     * @return Container $this
     */
    public function addShape(Shape $s): Container
    {
        $this->shapes[] = $s;
        $this->updateInternalBox($s);
        
        return $this;
    }
    
    /**
     * Removes a shape from the collection.
     * 
     * @param Shape $s
     * @return Container $this
     */
    public function removeShape(Shape $s): Container
    {
        $index = -1;
        foreach($this->shapes as $key => $value) {
            if ($value == $s) {
                $index = $key;
                break;
            }
        }
        if ($index >= 0) {
            array_splice($this->shapes, $index, 1);
        }
        
        return $this;
    }
    
    public function mirrorOnX(): Shape
    {
        $this->bb = new Rectangle();
        foreach($this->getShapes() as $s) {
            $s->mirrorOnX();
            $this->updateInternalBox($s);
        }
        
        return $this;
    }

    public function mirrorOnY(): Shape
    {
        $this->bb = new Rectangle();
        foreach($this->getShapes() as $s) {
            $s->mirrorOnY();
            $this->updateInternalBox($s);
        }
        
        return $this;
    }

    /**
     * This function updates the bounding box with the bounding box of the Shape
     * added.
     *
     * @param Shape $s
     */
    private function updateInternalBox(Shape $s)
    {
        $r = $s->getBoundingBox();
        $this->bb->getOrigin()->min($r->getOrigin());
        $this->bb->getTop()->max($r->getTop());
    }
    
    /**
     * Returns the bounding box of this Shape
     * 
     * {@inheritDoc}
     * @see \jmw\fabricad\shapes\Shape::getBoundingBox()
     */
    public function getBoundingBox(): Rectangle
    {
       // As $bb keeps the internal bounding box, relative to our own origin,
       // we need to translate the bounding box with our origin.
       $r = new Rectangle();
       $orig = Point::copyFrom($this->bb->getOrigin())->add($this->getOrigin());
       $r->setOrigin($orig);
       $top = Point::copyFrom($this->bb->getTop())->add($this->getOrigin());
       $r->setTop($top);
           
       return $r;
    }
    
    
    // -------------------------------------------------------------------------
    // Functions for the iterator over the internal Shapes
    private $it_counter = 0;
    
    /**
     * Increases the counter for the Iterator
     * 
     * {@inheritDoc}
     * @see \Iterator::next()
     */
    public function next()
    {
        $this->it_counter += 1;
    }

    /**
     * Returns whether the current position is actually a Shape
     * 
     * {@inheritDoc}
     * @see \Iterator::valid()
     */
    public function valid()
    {
        return isset($this->shapes[$this->it_counter]);
    }

    /**
     * Returns the current Shape. Only call if valid() returns true!
     * 
     * {@inheritDoc}
     * @see \Iterator::current()
     * @return Shape
     */
    public function current()
    {
        return $this->shapes[$this->it_counter];
    }

    /**
     * Rewinds the Iterator interface
     * 
     * {@inheritDoc}
     * @see \Iterator::rewind()
     */
    public function rewind()
    {
        $this->it_counter = 0;
    }


    /**
     * Returns the current key in the iterator
     * {@inheritDoc}
     * @see \Iterator::key()
     */
    public function key()
    {
        return $this->it_counter;
    }


    
}