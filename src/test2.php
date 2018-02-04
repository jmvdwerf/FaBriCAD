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


$shape1 = new Rectangle(1000, 1000);
$shape2 = new Rectangle(1000, 1000, new Point(500,500));
$shape3 = new Rectangle(400, 400, new Point(800, 300));

$c = new Container();
$c->addNonOverlappingParts($shape1);
$c->addNonOverlappingParts($shape2);
$c->addNonOverlappingParts($shape3);

?>
</pre>
<?php 
$v1 = new SVGVisualizer();
$v1->addShape($shape1, 'gray');
$v1->addShape($shape2, 'blue');
$v1->addShape($shape3, 'red');

echo $v1->render();

$colors = array('red','green', 'blue', 'pink', 'gray' ,'yellow');

$v2 = new SVGVisualizer();
$counter = 0;
foreach($c->getShapes() as $s) {
    $v2->addShape($s, $colors[$counter]);
    $counter++;
    echo $counter;
}
echo $v2->render();
?>
