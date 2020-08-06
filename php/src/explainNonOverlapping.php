<html>
<body>
<pre>
<?php

require_once('visualization/SVGVisualizer.php');
require_once('shapes/Point.php');
require_once('shapes/Shape.php');
require_once('shapes/Container.php');
require_once('shapes/Line.php');
require_once('shapes/Polygon.php');
require_once('shapes/Quadrangle.php');
require_once('shapes/Rectangle.php');
require_once('shapes/BinaryOperators.php');

use jmw\fabricad\shapes\Point;
use jmw\fabricad\shapes\Line;
use jmw\fabricad\shapes\Polygon;
use jmw\fabricad\shapes\Rectangle;
use jmw\fabricad\visualizer\SVGVisualizer;
use jmw\fabricad\shapes\BinaryOperators;
use jmw\fabricad\shapes\Container;


// $shape1 = new Rectangle(100, 100);
// $shape2 = new Rectangle(100, 100, new Point(50,50));
// $shape3 = new Rectangle(40, 40, new Point(80, 30));

// $c = new Container();
// $c->addNonOverlappingParts($shape1);
// $c->addNonOverlappingParts($shape2);
// $c->addNonOverlappingParts($shape3);

//$r = new Rectangle(100,100, new Point(0,100));
//$l = new Line(new Point(50,0), new Point(50,250));


$r = array();
/*
$r['blue'] = new Rectangle(100,200, new Point(100, 100));
$r['red'] = new Rectangle(100,200, new Point(700, 100));
$r['gray'] = new Rectangle(100,200, new Point(300, 200));
$r['green'] = new Rectangle(100,200, new Point(350, 300));
*/

$r['blue'] = new Rectangle(1000, 500, new Point(100, 200));
$r['yellow'] = new Rectangle(50, 1000, new Point(0, 0));
$r['red']  = new Rectangle(100, 1000, new Point(200, 100));
$r['green'] = new Rectangle(250, 250, new Point(150, 150));
$r['brown'] = new Rectangle(50, 1000, new Point(1200, 0));

$v1 = new SVGVisualizer();
foreach($r as $c => $s) {
    $v1->addShape($s, $c);
}

echo $v1->render();

$shapes = $r;

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
    
    $v = new SVGVisualizer();
    $v->addShape($first, 'red');
    foreach($q as $s) {
        $v->addShape($s, 'gray');
    }
    
    echo $v->render();
    
}

$v2 = new SVGVisualizer();
$i = 0;
$colors = ['red', 'blue',  'pink', 'green', 'gray', 'purple', 'brown', 'yellow'];
foreach($result as $s) {
    $v2->addShape($s, $colors[$i++]);
}

echo $v2->render();
?>
