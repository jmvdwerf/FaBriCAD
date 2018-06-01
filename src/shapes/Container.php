<?php

namespace jmw\fabricad\shapes;


class Container extends Shape implements \Iterator
{
    
    /**
     * Collection of shapes
     * @var array
     */
    private $shapes = null;
    
    public function __construct($items = [])
    {
        $this->shapes = new ShapeList();

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
        return $this;
    }
    
    public function mirrorOnX(): Shape
    {
        $this->bb = new Rectangle();
        foreach($this->getShapes() as $s) {
            $s->mirrorOnX();
        }
        
        return $this;
    }

    public function mirrorOnY(): Shape
    {
        $this->bb = new Rectangle();
        foreach($this->getShapes() as $s) {
            $s->mirrorOnY();
        }
        
        return $this;
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
       $first = true;
       $min = new Point();
       $max = new Point();
       
       foreach($this->shapes as $s) {
           $b = $s->getBoundingBox();
           if ($first) {
               $min = $b->getOrigin();
               $max = $b->getTop();
               $first = false;
           } else {
               $min->min($b->getOrigin());
               $max->max($b->getTop());
           }
       }
       
       return Rectangle::fromPoints($min, $max);
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
    
    public function setOrigin(Point $orig): Shape
    {
        return $this;
    }
    
    public function scale(float $x = 1, float $y = 1): Shape
    {
        foreach($this->getShapes() as $s) {
            // scale the element
            $s->scale($x, $y);
            // also scale the origin!
            $orig = $s->getOrigin();
            $s->setOrigin( $orig->multiplyXY($x, $y));
        }
        
        return $this;
    }
    
    public function contains(Point $pt): bool
    {
        foreach($this->shapes as $shape) {
            if ($shape->contains($pt)) {
                return true;
            }
        }
        
        return false;
    }
    
    public function asPolygon(): Polygon
    {
        return $this->getBoundingBox();
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