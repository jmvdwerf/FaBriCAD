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

$r = new Polygon([
new Point(100,0),
new Point(200,0),
new Point(200,300),
new Point(300,300),
new Point(300,0),
new Point(400,0),
new Point(400,500),
new Point(100,500)
]);

$l = new Line(new Point(0,200), new Point(500,200));

$c =new Container();

$items = BinaryOperators::difference($l, $r);
var_dump($items);
$c->addShapes($items);

?>
</pre>
<?php 
$v1 = new SVGVisualizer();
$v1->addShape($r, 'gray');
$v1->addShape($l, 'blue');
//$v1->addShape($shape3, 'red');

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
