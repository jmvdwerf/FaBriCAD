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
$r[] = new Rectangle(100,200, new Point(100, 100));
$r[] = new Rectangle(100,200, new Point(700, 100));
$r[] = new Rectangle(100,200, new Point(300, 200));
$r[] = new Rectangle(100,200, new Point(350, 300));


?>
</pre>
<?php 
$v1 = new SVGVisualizer();
foreach($r as $s) {
    $v1->addShape($s, 'gray');
}

echo $v1->render();
?>
