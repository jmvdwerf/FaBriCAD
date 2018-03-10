<?php

namespace jmw\fabricad\shapes;


class Container extends Shape implements \Iterator
{
    
    /**
     * Collection of shapes
     * @var array
     */
    private $shapes = null;
    
    /**
     * Internal representation of the bounding box of this Shape. The bounding
     * box $bb is *relative* to the origin of this container!
     *  
     * @var Rectangle
     */
    private $bb_min = null;
    private $bb_max = null;
    
    public function __construct($items = [])
    {
        $this->shapes = new ShapeList();
        
        $this->bb_min = new Point();
        $this->bb_max = new Point();
        
        $this->addShapes($items);
    }
        
    /**
     * Returns the shape of this Container
     * @param bool $removeOverlap if false, all shapes are adjusted, such that no 2 shapes overlap.
     * @return Shape[]
     */
    public function getShapes(bool $removeOverlap = false): array
    {
        return $this->shapes->flatten($removeOverlap);
    }
    
    public function addShapes($shapes = [], bool $nonOverlappingPartsOnly = false): Container 
    {
        foreach($shapes as $s) {
            $this->addShape($s, $nonOverlappingPartsOnly);
        }
        
        return $this;
    }
    
    /**
     * Returns the number of Shapes in this container.
     * @return int
     */
    public function size(): int
    {
        return $this->shapes->size();
    }
    
    public function addShape(Shape $s, bool $nonOverlappingPartsOnly = false): Container
    {
        $this->shapes->add($s, $nonOverlappingPartsOnly);
        
        $this->updateInternalBox($s);
        
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
        $this->bb_min->min($r->getOrigin());
        $this->bb_max->max($r->getTop());
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
       return Rectangle::fromPoints($this->bb_min, $this->bb_max);
    }
    
    /**
     * This function returns an array of Shapes
     * 
     * @return Shape[]
     */
    public function flatten(bool $removeOverlap = false): array
    {
        $list = array();
        
        foreach($this->getShapes($removeOverlap) as $shape) {
            if ($shape instanceof Container) {
                $list = array_merge($list, $shape->flatten($removeOverlap));
            } else {
                $list[] = $shape->clone();
            }
        }
        
        return $list;
    }
    
    public function flip(): Shape
    {
        $cc = array();
        
        foreach($this->getShapes() as $s) {
            $cc[] = $s->flip();
        }
        
        return new Container($cc);
    }
    
    public function clone(): Shape
    {
        $cc = array();
        foreach($this->getShapes() as $s) {
            $cc[] = $s->clone();
        }
        
        return new Container($cc);
    }
    
    public function move(float $x = 0, float $y = 0): Shape
    {
        foreach($this->getShapes() as $s) {
            $s->move($x, $y);
        }
        return $this;
    }
    
    public function getOrigin(): Point
    {
        return new Point();
    }
    
    public function scale(float $x = 1, float $y = 1): Shape
    {
        foreach($this->getShapes() as $s) {
            $s->scale($x, $y);
        }
        
        return $this;
    }
    
    
    /**
     * Increases the counter for the Iterator
     * 
     * {@inheritDoc}
     * @see \Iterator::next()
     */
    public function next()
    {
        $this->shapes->next();
    }

    /**
     * Returns whether the current position is actually a Shape
     * 
     * {@inheritDoc}
     * @see \Iterator::valid()
     */
    public function valid()
    {
        return $this->shapes->valid();
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
        return $this->shapes->current();
    }

    /**
     * Rewinds the Iterator interface
     * 
     * {@inheritDoc}
     * @see \Iterator::rewind()
     */
    public function rewind()
    {
        $this->shapes->rewind();
    }


    /**
     * Returns the current key in the iterator
     * {@inheritDoc}
     * @see \Iterator::key()
     */
    public function key()
    {
        return $this->shapes->key();
    }
            
}