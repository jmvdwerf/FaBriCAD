<html>
<body>
<pre>
<?php
$colors = [
    'red', 'blue',  'pink', 'green', 'gray', 'purple',
    'brown', 'yellow', 'lawngreen', 'lemonchiffon', 'lightcyan',
    'lime', 'navy', 'olive', 'lightsteelblue', 'mediumaquamarine', 'seagreen'
];



require_once('visualization/SVGVisualizer.php');
require_once('shapes/Point.php');
require_once('shapes/Shape.php');
require_once('shapes/Container.php');
require_once('shapes/Line.php');
require_once('shapes/Polygon.php');
require_once('shapes/Quadrangle.php');
require_once('shapes/Rectangle.php');
require_once('shapes/ShapeList.php');
require_once('shapes/BinaryOperators.php');

use jmw\fabricad\shapes\Point;
use jmw\fabricad\shapes\Line;
use jmw\fabricad\shapes\Polygon;
use jmw\fabricad\shapes\Rectangle;
use jmw\fabricad\visualizer\SVGVisualizer;
use jmw\fabricad\shapes\BinaryOperators;
use jmw\fabricad\shapes\Container;
use jmw\fabricad\shapes\ShapeList;
use jmw\fabricad\shapes\Shape;


// $shape1 = new Rectangle(100, 100);
// $shape2 = new Rectangle(100, 100, new Point(50,50));
// $shape3 = new Rectangle(40, 40, new Point(80, 30));

// $c = new Container();
// $c->addNonOverlappingParts($shape1);
// $c->addNonOverlappingParts($shape2);
// $c->addNonOverlappingParts($shape3);

//$r = new Rectangle(100,100, new Point(0,100));
//$l = new Line(new Point(50,0), new Point(50,250));

$wall = new Polygon([
    new Point(  0,   0 ),
    new Point(270,   0 ),
    new Point(270, 200 ),
    new Point(160, 200 ),
    new Point(160, 175 ),
    new Point(110, 175 ),
    new Point(110, 200 ),
    new Point(  0, 200 )
]);

$kapel = new Polygon([
    new Point(110, 175 ),
    new Point(110, 225 ),
    new Point(135, 250 ),
    new Point(160, 225 ),
    new Point(160, 175 )
]);


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
$v1->addShape($wall->calculateIntersectionPointsWith($kapel)->expand(), "blue");
$v1->addShape($kapel->calculateIntersectionPointsWith($wall)->expand(), "red");
echo $v1->render();

echo "<hr />";

//$list = new Container();
//$list->addShape($kapel, true);
//$list->addShape($wall, true);

$items = BinaryOperators::difference($wall, $kapel);

$v1 = new SVGVisualizer();
foreach($items as $shape) {
    $v1->addShape($shape, "red");
}
echo $v1->render();


?>
