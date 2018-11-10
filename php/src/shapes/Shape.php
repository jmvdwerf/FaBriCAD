<?php
namespace jmw\fabricad\shapes;

abstract class Shape
{
    /**
     * Returns the origin of this shape
     * @return Point
     */
    public abstract function getOrigin(): Point;
    
    public abstract function setOrigin(Point $pt): Shape;
    
    /**
     * Returns true if the shape $s intersects with this shape. 
     * Just touching is sufficient!
     * 
     * @param Shape $s
     * @return bool
     */
    public function intersects(Shape $s): bool
    {
       $b1 = $this->getBoundingBox();
       $b2 = $s->getBoundingBox();

       $first = $b1->getOrigin()->smallerThan($b2->getTop());
       $second = $b1->getTop()->greaterThanOrEqual($b2->getOrigin());
       $res = $first && $second;

       return $res; 
    }
    
    public function isContainedIn(Shape $s): bool
    {
        // then my origin should be inside Shape $s
        // and my top should be inside Shape $s
        $bb = $this->getBoundingBox();
        return $s->contains($bb->getOrigin()) && $s->contains($bb->getTop());
    }
    
    public abstract function asPolygon(): Polygon;
    
    
    /**
     * Returns the boundingbox of this shape
     * @return Rectangle
     */
    public abstract function getBoundingBox(): Rectangle;
    
    public abstract function mirrorOnX(): Shape;
    public abstract function mirrorOnY(): Shape;
    
    public abstract function contains(Point $pt): bool;
    
    public function __tostring()
    {
        $str = get_class($this);
        return $str;
    }
    
    public abstract function flip(): Shape;
    
    public abstract function clone(): Shape;
    
    // -------------------------------------------------------------------------
    // Operations on a shape
        
    public abstract function scale(float $x = 1, float $y = 1): Shape;
    
    public abstract function move(float $x = 0, float $y = 0): Shape;
    
    
    public static function removeOverlap($shapes = array()):  array
    {
        $q = [];
        foreach($shapes as $s) {
            $q[] = $s;
        }
        
        $result = array();
        
        while(count($q) > 0) {
            $first = array_shift($q);
            $result[] = $first;
            
            $newQ = array();
            while(count($q) > 0) {
                $second = array_shift($q);
                if ($second->intersects($first)) {

                    $items = BinaryOperators::difference($second, $first);

                    $newQ = array_merge($newQ, $items);
                } else {
                    $newQ[] = $second;
                }
            }
            $q = $newQ;
        }
                
        return $result;
    }
    
    public static function removeOverlapFrom(Shape $s, $items = []): array
    {
        $toCheck = [$s];
        foreach($items as $i) {
            $toAdd = [];
            foreach($toCheck as $shape) {
                if ($i->intersects($shape)) {
                    $toAdd = array_merge($toAdd, BinaryOperators::difference($shape, $i));
                } else {
                    $toAdd[] = $shape;
                }
            }
            $toCheck = $toAdd;
        }
        
        return $toCheck;
    }
}