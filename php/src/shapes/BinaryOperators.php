<?php

namespace jmw\fabricad\shapes;

class BO_Settings
{
    public $outside = array();
    public $crossings = array();
    public $processed = array();
    
    
    public function __tostring(): string
    {
        $cnt = count($this->outside);
        
        $str = "\nNode | outside | proc. | Cross |\n";
        $str.= "-----|---------|-------|-------|\n";
            
        for($i = 0 ; $i < $cnt ; $i++) {
            $str .= str_pad($i, 5, ' ', STR_PAD_BOTH);
            $str .= "|";
            $str .= str_pad( ($this->outside[$i] ? 'yes' : 'no') , 9, ' ', STR_PAD_BOTH);
            $str .= "|";
            $str .= str_pad( ($this->processed[$i] >= 0 ? 'yes' : 'no') , 7, ' ', STR_PAD_BOTH);
            $str .= "|";
            $str .= str_pad($this->crossings[$i], 7, ' ', STR_PAD_BOTH);
            $str .= "|\n";
        }
        $str .= "\n";
        return $str;
    }
    
}

class BinaryOperators 
{   
    public static function debug($str)
    {
         // echo $str."\n";
    }
    
    public static function difference(Shape $one, Shape $other): array
    {
        if (!($one->intersects($other))) {
            return [$one];
        }
        
        if ($one instanceof Line) {
            return BinaryOperators::differenceL($one, $other->asPolygon());
        } elseif($other instanceof Line) {
            return [$one];
        } else {
            return BinaryOperators::differenceP($one->asPolygon(), $other->asPolygon());
        }
     }
     
     public static function differenceL(Line $one, Polygon $other): array
     {
         BinaryOperators::debug('');
         
         $results = [];
         BinaryOperators::debug('Line: '.$one);
         $lines = BinaryOperators::calculateLineSegments($one, $other);
         BinaryOperators::debug('Split into:');
                  
         foreach($lines as $line) {
             // add the line if the middle point is outside the polygon
             $str = $line.' - ';
             if (!$other->contains($line->getMiddlePoint())) {
                 $results[] = $line;
                 $str .= 'added';
             }
             BinaryOperators::debug($str);
         }
         
         return $results;
     }
     
     public static function differenceP(Polygon $one, Polygon $other): array
     {
         $nt = $one->calculateIntersectionPointsWith($other)->expand();
         $no = $other->calculateIntersectionPointsWith($one)->expand();
         
         BinaryOperators::debug($one);
         BinaryOperators::debug($other);
         
         $settings_one = BinaryOperators::calculatePreconditions($nt, $no);
         $settings_other = BinaryOperators::calculatePreconditions($no, $nt);
         
         
         // dirA is true if $one turns clockwise, false otherwise
         $dirA = ($nt->direction() == Polygon::DIRECTION_CLOCKWISE);
         // dirB is true if $other turns counter clockwise, false otherwise
         $dirB = ($no->direction() == Polygon::DIRECTION_COUNTERCLOCKWISE);
         // If one turns into direction A, than the direction of the other should 
         // turn the other way around to get a proper difference!
         
         
         BinaryOperators::debug("ONE: \n".$settings_one);
         BinaryOperators::debug("OTHER: \n".$settings_other);
         BinaryOperators::debug("DIR one  : ".$dirA);
         BinaryOperators::debug("DIR other: ".$dirB);
         // */
         
         $results = array();
         
         // return $results;
         
         $indexB =  -1;
         
         $indexA = BinaryOperators::findNextUnused($settings_one->outside, $settings_one->processed, $nt->getPoints());
         
         $shape = new Polygon();
         
         while($indexA >= 0 && $indexA <= $nt->size())
         {
            // current point of polygon one:
            $pt = $nt->getPoint($indexA);
            
            BinaryOperators::debug("indexA: ".$indexA. '('.$pt.')');
            
            if ($shape->size() > 0 && ($pt->equals($shape->getOrigin()) || $settings_one->processed[$indexA] >= 0)) {
                
                // the shape is closed!
                BinaryOperators::debug("CLOSE figure");
                BinaryOperators::debug("ONE: \n".$settings_one);
                BinaryOperators::debug("OTHER\n".$settings_other);
                // */
                
                $results[] = $shape->simplify();
                $shape = new Polygon();
                $indexA = BinaryOperators::findNextUnused($settings_one->outside, $settings_one->processed, $nt->getPoints());
            } else {
                // the shape is not yet closed
                $shape->addPoint($pt);
                $settings_one->processed[$indexA] = count($results); 
                // determine the next point, either on one or other
                if ($settings_one->crossings[$indexA] < 0) {
                    // it is no crossing point, just give me the next index
                    $indexA = BinaryOperators::nextIndex($indexA, $dirA, $nt->size());
                } else {
                    // it is a crossing point! 
                    BinaryOperators::debug("Index A is a crossing point! (in B: ".$settings_one->crossings[$indexA].")");
                    
                    $indexB = $settings_one->crossings[$indexA];
                    
                    // determine the direction we should walk in Other
                    $dir = BinaryOperators::givedir($indexB, $no, $settings_other);
                    
                    BinaryOperators::debug("Direction B (".($dirB? '1': '0')."): ".($dir ? '1' : '0'));
                    
                    $nextB = BinaryOperators::nextIndex($indexB, $dirB, $no->size());
                    
                    // we know that, because of Polygon::expand() that the next point of B
                    // will NOT be a crossing point with A
                    if (!$settings_other->outside[$nextB]) {
                        $indexB = $nextB;
                    
                        BinaryOperators::debug("next B: ".$indexB);
                    
                        while ($indexB >= 0 && $indexB < $no->size() && $settings_other->crossings[$indexB] < 0 && !$settings_other->outside[$indexB]  && $settings_other->processed[$indexB] < 0 ) 
                        {
                            // the node is not a crossing point, and inside one, so just add the node to Settings
                            $settings_other->processed[$indexB] = count($results);
                            $shape->addPoint($no->getPoint($indexB));
                            $indexB = BinaryOperators::nextIndex($indexB, $dirB, $no->size());
                            BinaryOperators::debug("next B: ".$indexB);
                        }
                        
                        if ($settings_other->crossings[$indexB] >= 0) {
                            $indexA = $settings_other->crossings[$indexB];
                        }
                    } else {
                    
                        BinaryOperators::debug("current B: ".$indexB);
                        BinaryOperators::debug("current A: ".$indexA);
                        BinaryOperators::debug("next A: ".BinaryOperators::nextIndex($indexA, $dirA, $nt->size()));
                        
                        $indexA = BinaryOperators::nextIndex($indexA, $dirA, $nt->size());
                        
                    }
                }
                
            }
         }
         
         // echo $settings_one;
         
         return $results;
     }
     
     /** 
      * 
      * @param int $index
      * @param array $nodes
      * @param bool $inside
      * @return bool
      */
     private static function givedir(int $index, Polygon $polygon, BO_Settings $settings, bool $inside = true): bool
     {
         // get the next
         $dir = $polygon->direction();
         $next = BinaryOperators::nextIndex($index, $dir, $polygon->size());
         if ($settings->outside[$next] && $inside) {
             return !$dir;
         } else {
             return $dir;
         }
     }
    
    /**
     * Calculates the union of Polygon $one and Polygon $other.
     * Returns an array of polygons.
     *
     * @param Polygon $one
     * @param Polygon $other
     * @return array
     */
    public static function union(Polygon $one, Polygon $other): array
    {
        if (!($one->intersects($other))) {
            return [$one, $other];
        }
        
        $nt = $one->calculateIntersectionPointsWith($other)->expand();
        $no = $other->calculateIntersectionPointsWith($one)->expand();
        
        $settings_one = BinaryOperators::calculatePreconditions($nt, $no);
        $settings_other = BinaryOperators::calculatePreconditions($no, $nt);
        
        
        // dirA is true if $one turns clockwise, false otherwise
        $dirA = ($nt->direction() == Polygon::DIRECTION_CLOCKWISE);
        // dirB is true if $other turns counter clockwise, false otherwise
        $dirB = ($no->direction() == Polygon::DIRECTION_CLOCKWISE);
        // If one turns into direction A, than the direction of the other should
        // turn the other way around to get a proper difference!
        
        
        BinaryOperators::debug("ONE: \n".$settings_one);
        BinaryOperators::debug("OTHER: \n".$settings_other);
        BinaryOperators::debug("DIR one  : ".$dirA);
        BinaryOperators::debug("DIR other: ".$dirB);
        
        // return [];
        
        $results = array();
        
        $indexB =  -1;
        
        $indexA = BinaryOperators::findNextUnused($settings_one->outside, $settings_one->processed, $nt->getPoints());
        
        $shape = new Polygon();
        
        while($indexA >= 0 && $indexA <= $nt->size())
        {
            // echo "indexA: ".$indexA." ";
            // current point of polygon one:
            $pt = $nt->getPoint($indexA);
            
            // echo $pt."\n";
            
            // whatever happens, we use this point!
            $settings_one->processed[$indexA] = count($results);
            
            if ($shape->size() > 0 && $pt->equals($shape->getOrigin())) {
                
                // the shape is closed!
                BinaryOperators::debug("CLOSE figure");
                BinaryOperators::debug("ONE: \n".$settings_one);
                BinaryOperators::debug("\nOTHER\n".$settings_other);
                 // */
                
                $results[] = $shape->simplify();
                $shape = new Polygon();
                $indexA = BinaryOperators::findNextUnused($settings_one->outside, $settings_one->processed, $nt->getPoints());
            } else {
                // the shape is not yet closed
                $shape->addPoint($pt);
                // determine the next point, either on one or other
                if ($settings_one->crossings[$indexA] < 0) {
                    // it is no crossing point, just give me the next index
                    $indexA = BinaryOperators::nextIndex($indexA, $dirA, $nt->size());
                } else {
                    // it is a crossing point! We need to switch to B if the next point
                    // of B is outside
                    BinaryOperators::debug('Crossing point! (B: '.$settings_one->crossings[$indexA].")");
                    
                    $indexB = BinaryOperators::nextIndex($settings_one->crossings[$indexA], $dirB, $no->size());
                    BinaryOperators::debug('index B: '.$indexB);
                    while($indexB >= 0 && $indexB < $no->size() && $settings_other->outside[$indexB] && $settings_other->crossings[$indexB] < 0 ) // && $settings_other->processed[$indexB] < 0) 
                    {
                        $pt = $no->getPoint($indexB);
                        BinaryOperators::debug('is point: '.$pt);
                        $shape->addPoint($pt);
                        $settings_other->processed[$indexB] = count($results);
                        $indexB = BinaryOperators::nextIndex($indexB, $dirB, $no->size());
                        BinaryOperators::debug('index B: '.$indexB);
                    }
                    BinaryOperators::debug("\nCrossing point! (A: ".$settings_other->crossings[$indexB].")");
                    if ($settings_other->crossings[$indexB] < 0) {
                        // the node is not inside!
                        $indexA = BinaryOperators::nextIndex($indexA, $dirA, $nt->size());
                    } else {
                        $indexA = $settings_other->crossings[$indexB];
                        $shape->addPoint($nt->getPoint($indexA));
                        $settings_one->processed[$indexA] = count($results);
                        // $indexB is again a crossing point!
                        $indexA = BinaryOperators::nextIndex($indexA, $dirA, $no->size());
                    }
                }
                
            }
        }
        
        // echo $settings_one;
        
        return $results;
    }
        
    public static function intersection(Shape $one, Shape $two): array
    {
        if (!$one->intersects($two)) {
            return [];
        }
        
        $fstIsLine = false;
        $sndIsLine = false;
        
        if ($one instanceof Line) {
            $fstIsLine = true;
        }
        if ($two instanceof Line) {
            $sndIsLine = true;
        }
        
        if ($fstIsLine && $sndIsLine) {
            return [];
        } elseif($fstIsLine) {
            return BinaryOperators::intersectionL($one, $two->asPolygon());   
        } elseif($sndIsLine) {
            return BinaryOperators::intersectionL($two, $one->asPolygon());
        } else {
            return BinaryOperators::intersectionP($one->asPolygon(), $two->asPolygon());
        }
    }
    
    private static function intersectionL(Line $l, Polygon $p): array
    {
        $results = [];
        
        $lines = BinaryOperators::calculateLineSegments($l, $p);
        
        /** @var Line */
        foreach($lines as $line) {
            // add the line if the middle point is inside the polygon
            if ($p->contains($line->getMiddlePoint())) {
                $results[] = $line;
            }
        }
        
        return $results;
    }
    
    private static function calculateLineSegments(Line $l, Polygon $p): array
    {

        
        $results = [];
        
        $points = $p->intersectionPoints($l);
        $first = $l->getOrigin();
        
        foreach($points as $pt) {
            if ($pt->equals($first)) {
                continue;
            }
            $results[] = new Line($first, $pt);
            $first = $pt;
        }
        $results[] = new Line($first, $l->getEndPoint());
        
        return $results;
    }
    
    
    /**
     * Calculates the intersection of Polygon $one with Polygon $other.
     * Returns an array of polygons.
     *
     * @param Polygon $one
     * @param Polygon $other
     * @return array
     */
    private static function intersectionP(Polygon $one, Polygon $other): array
    {
        // $nt is the extended polygon
        $nt = $one->calculateIntersectionPointsWith($other)->expand();
        $no = $other->calculateIntersectionPointsWith($one)->expand();
        
        $settings_one = BinaryOperators::calculatePreconditions($nt, $no);
        $settings_other = BinaryOperators::calculatePreconditions($no, $nt);
        
        
        $dirA = ($nt->direction() == Polygon::DIRECTION_CLOCKWISE);
        $dirB = ($no->direction() == Polygon::DIRECTION_CLOCKWISE);
        
        
        BinaryOperators::debug("ONE: \n".$settings_one);
        BinaryOperators::debug("OTHER: \n".$settings_other);
        BinaryOperators::debug("DIR one  : ".$dirA);
        BinaryOperators::debug("DIR other: ".$dirB."\n");

        $results = [];
        
        $indexA = BinaryOperators::findNextUnusedCrossing(
            $settings_one->crossings,
            $settings_one->processed,
            $nt->getPoints()
            );
        $indexB = ($indexA >= 0) ? $settings_one->crossings[$indexA] : -1;
        
        $output = new Polygon();
        $prevA = -1;
        
        while($indexA >= 0 && $indexB >= 0) {
            // INV 1: $settings_one->crossings[$indexA] = $indexB
            $pt = $nt->getPoint($indexA);
            
            BinaryOperators::debug('index A: '.$indexA.' ('.$pt.')');
            BinaryOperators::debug('index B: '.$indexB);
                        
            
            
            BinaryOperators::debug($output);

            if (count($output->getPoints()) > 0 && ($output->getOrigin()->equals($pt)|| $prevA == $indexA ) ) {
                $results[] = $output->simplify();
                $indexA = BinaryOperators::findNextUnusedCrossing(
                    $settings_one->crossings,
                    $settings_one->processed,
                    $nt->getPoints()
                    );
                $indexB = ($indexA >= 0) ? $settings_one->crossings[$indexA] : -1;
                
                $output = new Polygon();
                
                BinaryOperators::debug("CLOSE figure\n");
                BinaryOperators::debug("ONE: \n".$settings_one);
                BinaryOperators::debug("\nOTHER\n".$settings_other);
                
            } else {
                $output->addPoint($pt);
                $settings_one->processed[$indexA] = count($results);
                
                $prevA = $indexA;
                
                $nextA = BinaryOperators::nextIndex($indexA, $dirA, $nt->size());
                $nextB = BinaryOperators::nextIndex($indexB, $dirB, $no->size());
                // we know that nextA and nextB are no crossings, because of expand();
                
                BinaryOperators::debug('next A: '.$nextA);
                BinaryOperators::debug('next B: '.$nextB);
                
                if (!$settings_one->outside[$nextA] && $settings_one->processed[$nextA] < 0 ) {
                    // we know that $nextA is inside B, so let's add points of A
                    // until we are at the next crossing!
                    
                    BinaryOperators::debug("Continue with A");
                    
                    while($nextA >= 0 && $nextA < $nt->size() && $settings_one->crossings[$nextA] < 0 )
                    {
                        $pt = $nt->getPoint($nextA);
                        $output->addPoint($pt);
                        $settings_one->processed[$nextA] = count($results);
                        $nextA = BinaryOperators::nextIndex($nextA, $dirA, $nt->size());
                        BinaryOperators::debug("next A: ".$nextA);
                    }
                    // we are at the next crossing!
                    $indexA = $nextA;
                    $indexB = $settings_one->crossings[$indexA];
                } else {
                    // we know that $nextA is outside, or already processed! 
                    // so let's test B
                    if (!$settings_other->outside[$nextB] && $settings_other->processed[$nextB] < 0) {
                        // we know that $nextB is inside A, so let's add points of B
                        // until we are at the next crossing!
                        BinaryOperators::debug("Continue with B");
                        
                        while($nextB >= 0 && $nextB < $no->size() && $settings_other->crossings[$nextB] < 0 )
                        {
                            $pt = $no->getPoint($nextB);
                            $output->addPoint($pt);
                            $settings_other->processed[$nextB] = count($results);
                            $nextB = BinaryOperators::nextIndex($nextB, $dirB, $no->size());
                            BinaryOperators::debug("next B: ".$nextB);
                        }
                        // we are at the next crossing!
                        $indexB = $nextB;
                        $indexA = $settings_other->crossings[$indexB];
                        
                    } else {
                        // hmm, B is also outside, let's just continue to the while,
                        // since the invariant holds...
                        BinaryOperators::debug("Go back");
                        continue;
                    } 
                }
            }
        }
        
        
        return $results;
    }
        
    private static function calculatePreconditions(Polygon $nt, Polygon $no): BO_Settings
    {
        $res = new BO_Settings();
        
        foreach($nt->getPoints() as $index => $pt) {
            
            $inside = $no->contains($pt);
            
            $res->processed[$index] = -1;
            $res->crossings[$index] = Point::find($pt, $no->getPoints());
            $res->outside[$index] = (!$inside) && ($res->crossings[$index] < 0);
        }
        
        return $res;
    }
    
    /**
     * Gives the next index, given the previous, a max and a min.
     * Assumptions: 
     *   - $index in [$min..$max)
     *   - $min <= $max
     * If dir is true, we go up, if dir is false, we go down...
     * 
     * @param int $index
     * @param bool $dir
     * @param int $max
     * @param int $min
     * @return number
     */
    private static function nextIndex(int $index, bool $dir, int $max, int $min = 0) 
    {
        $next = $dir ? $index + 1 : $index - 1;
        
        if ($next < $min) {
            return $next + $max - $min;
        }
        if ($next >= $max) {
            return $min;
        }
        return $next;
    }
    
    private static function findNextUnused($outside = array(), $processed = array(), $nodes = array()): int 
    {
        for($i = 0 ; $i < count($nodes);$i++) {
            if ($outside[$i] && $processed[$i] < 0) {
                return $i;
            }
        }
        
        return -1;
    }
    
    private static function findNextUnusedCrossing($crossing = array(), $processed = array(), $nodes = array()): int
    {
        for($i = 0 ; $i < count($nodes);$i++) {
            if ($crossing[$i] >= 0 && $processed[$i] < 0) {
                return $i;
            }
        }
        
        return -1;
    }
    
}
