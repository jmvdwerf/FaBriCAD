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
        
        $str = "Node | outside | proc. | Cross |\n";
        $str.= "-----|---------|-------|-------|\n";
            
        for($i = 0 ; $i < $cnt ; $i++) {
            $str .= str_pad($i, 5, ' ', STR_PAD_BOTH);
            $str .= "|";
            $str .= str_pad( ($this->outside[$i] ? 'yes' : 'no') , 9, ' ', STR_PAD_BOTH);
            $str .= "|";
            $str .= str_pad( ($this->processed[$i] > 0 ? 'yes' : 'no') , 7, ' ', STR_PAD_BOTH);
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
    
    public static function expand(Polygon $one, Polygon $other): array
    {
        // $nt is the extended polygon
        $nt = $one->calculateIntersectionPointsWith($other)->expand();
        $no = $other->calculateIntersectionPointsWith($one)->expand();
        
        return [$nt, $no];
    }
    
     /**
     * Calculates the difference from Polygon $one with Polygon $other.
     * Returns an array of polygons.
     *
     * @param Polygon $one
     * @param Polygon $other
     * @return array
     */
    public static function difference(Polygon $one, Polygon $other): array
    {
        // $nt is the extended polygon
        $nt = $one->calculateIntersectionPointsWith($other)->expand();
        $no = $other->calculateIntersectionPointsWith($one)->expand();
        
        $settings_one = BinaryOperators::calculatePreconditions($nt, $no);
        
        $settings_other = BinaryOperators::calculatePreconditions($no, $nt);
        
        $dirA = ($nt->direction() == Polygon::DIRECTION_CLOCKWISE);
        $dirB = ($no->direction() == Polygon::DIRECTION_CLOCKWISE);
        
        $results = [];
        
        $indexB = -1;
        $indexA = BinaryOperators::findNextUnused(
            $settings_one->outside,
            $settings_one->processed,
            $nt->getPoints()
            );
        
        $cnt = count($nt->getPoints());
        $cno = count($no->getPoints());
        
        $output = new Polygon();
        while($indexA >= 0) {
            $pt = $nt->getPoints()[$indexA];
            
            if ($output->getOrigin()->equals($pt)) {
                $results[] = $output->simplify();
                $indexA = BinaryOperators::findNextUnused(
                    $settings_one->outside,
                    $settings_one->processed,
                    $nt->getPoints()
                    );
                $output = new Polygon();
            } else {
                $output->addPoint($pt);
                $settings_one->processed[$indexA] = count($results) + 1;
                
                // determine next point
                if ($settings_one->crossings[$indexA] >= 0) {
                    // it is a crossing point!
                    // so, we move to the other polygon!
                    $sameB = $settings_one->crossings[$indexA];
                    
                    $dirB = BinaryOperators::determineDirection($sameB, $settings_other->outside);
                    $indexB = BinaryOperators::nextIndex($sameB, $dirB, $cno);
                    // as long as B is inside, we keep adding points, until
                    // we are again at a crossing point with A
                    
                    while($settings_other->crossings[$indexB] < 0 && !$settings_other->outside[$indexB]) {
                        $pt = $no->getPoints()[$indexB];
                        $output->addPoint($pt);
                        // increase index B
                        $indexB = BinaryOperators::nextIndex($indexB, $dirB, $cno);
                        
                    }
                    $output->addPoint($no->getPoints()[$indexB]);
                    $indexA = $settings_other->crossings[$indexB];
                }
                
                $indexA = BinaryOperators::nextIndex($indexA, $dirA, $cnt);
            }
        }
        
        
        return $results;
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
        // $nt is the extended polygon
        $nt = $one->calculateIntersectionPointsWith($other)->expand();
        $no = $other->calculateIntersectionPointsWith($one)->expand();
                
        $settings_one = BinaryOperators::calculatePreconditions($nt, $no);
        
        $settings_other = BinaryOperators::calculatePreconditions($no, $nt);
        
        $dirA = ($nt->direction() == Polygon::DIRECTION_CLOCKWISE);
        $dirB = ($no->direction() == Polygon::DIRECTION_CLOCKWISE);
        
        $results = [];
        
        $indexB = -1;
        $indexA = BinaryOperators::findNextUnused(
            $settings_one->outside, 
            $settings_one->processed, 
            $nt->getPoints()
        );
        
        $cnt = count($nt->getPoints());
        $cno = count($no->getPoints());
        
        $output = new Polygon();
        while($indexA >= 0) {
            $pt = $nt->getPoints()[$indexA];
            if ($output->getOrigin()->equals($pt)) {
                $results[] = $output->simplify();
                $indexA = BinaryOperators::findNextUnused(
                    $settings_one->outside,
                    $settings_one->processed,
                    $nt->getPoints()
                    );
                $output = new Polygon();
               //if (count($results) == 2) 
               break;
            } else {
                $output->addPoint($pt);
                $settings_one->processed[$indexA] = count($results) + 1;
                
                // determine next point
                if ($settings_one->crossings[$indexA] >= 0) {
                    // it is a crossing point!
                    // so, we move to the other polygon!
                    $sameB = $settings_one->crossings[$indexA];
                    
                    $dirB = BinaryOperators::determineDirection($sameB, $settings_other->outside, false);
                    $indexB = BinaryOperators::nextIndex($sameB, $dirB, $cno);
                    // as long as B is inside, we keep adding points, until 
                    // we are again at a crossing point with A
                   
                    while($settings_other->crossings[$indexB] < 0 && $settings_other->outside[$indexB]) {
                        $pt = $no->getPoints()[$indexB];
                        $output->addPoint($pt);
                        // increase index B
                        $indexB = BinaryOperators::nextIndex($indexB, $dirB, $cno);
                        
                    }
                    $output->addPoint($no->getPoints()[$indexB]);
                    $indexA = $settings_other->crossings[$indexB]; 
                }
                
                $indexA = BinaryOperators::nextIndex($indexA, $dirA, $cnt);
            }
        }
        
        
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
    public static function intersection(Polygon $one, Polygon $other): array
    {
        // $nt is the extended polygon
        $nt = $one->calculateIntersectionPointsWith($other)->expand();
        $no = $other->calculateIntersectionPointsWith($one)->expand();
        
        $settings_one = BinaryOperators::calculatePreconditions($nt, $no);
        $settings_other = BinaryOperators::calculatePreconditions($no, $nt);
        
        $dirA = ($nt->direction() == Polygon::DIRECTION_CLOCKWISE);
        $dirB = ($no->direction() == Polygon::DIRECTION_CLOCKWISE);
        
        $results = [];
        
        $indexB = -1;
        $indexA = BinaryOperators::findNextUnusedCrossing(
            $settings_one->crossings,
            $settings_one->processed,
            $nt->getPoints()
            );
        
        $cnt = count($nt->getPoints());
        $cno = count($no->getPoints());
        
        $output = new Polygon();
        while($indexA >= 0) {
            $pt = $nt->getPoints()[$indexA];

            if ($output->getOrigin()->equals($pt)) {
                $results[] = $output->simplify();
                $indexA = BinaryOperators::findNextUnusedCrossing(
                    $settings_one->crossings,
                    $settings_one->processed,
                    $nt->getPoints()
                    );
                $output = new Polygon();
            } else {
                $output->addPoint($pt);
                $settings_one->processed[$indexA] = count($results) + 1;
                
                // determine next point
                $sameB = $settings_one->crossings[$indexA];
                // if nextA is inside, add those points, until the next crossing
                $indexA = BinaryOperators::nextIndex($indexA, $dirA, $cnt);
                if (!$settings_one->outside[$indexA]) {
                    // it is inside, add points until next crossing!
                    while(!$settings_one->outside[$indexA] && $settings_one->crossings[$indexA] < 0) {
                        $output->addPoint($nt->getPoints()[$indexA]);
                        $indexA = BinaryOperators::nextIndex($indexA, $dirA, $cnt);
                    }
                } else {
                    // else, if nextB is inside, add those points, until the next crossing
                    $nextB = BinaryOperators::nextIndex($sameB, $dirB, $cno);
                    while(!$settings_other->outside[$nextB] && $settings_other->crossings[$nextB] < 0) {
                        $output->addPoint($no->getPoints()[$nextB]);
                        $nextB = BinaryOperators::nextIndex($nextB, $dirB, $cno);
                    }
                    $indexA = $settings_other->crossings[$nextB];
                }
            }
        }
        
        
        return $results;
    }
    
    /**
     * Given an index, this function tries to determine which direction to follow
     * in the binary operations. 
     * Return value:
     *   - True:   next index is upwards
     *   - False:  next index is downwards
     *   
     * If inside is set, it looks for the direction inwards, if it is false, the
     * other way around.
     * $outside is an array such that $outside[index] = true iff the node is outside
     * 
     * @param int $index
     * @param array $points
     * @param array $outside
     * @param string $inside
     * @return bool
     */
    private static function determineDirection(int $index, $outside = array(), $inside = true): bool
    {
        $next = ($index+1) % count($outside);
        if ($inside)
            return !$outside[$next];
        else
            return $outside[$next];
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