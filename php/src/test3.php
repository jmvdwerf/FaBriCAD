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
use jmw\fabricad\shapes\Polygon;
use jmw\fabricad\visualizer\SVGVisualizer;
use jmw\fabricad\shapes\BinaryOperators;

$second = new Polygon([
    new Point(200, 100),
    new Point(200, 200),
    new Point(300, 200),
    new Point(300, 100),
]);
$first = new Polygon([
    new Point(100, 200),
    new Point(100, 700),
    new Point(1100, 700),
    new Point(1100, 200),
]);

$v0 = new SVGVisualizer();
$v0->addShape($second, $colors[0]);
$v0->addShape($first, $colors[1]);
echo $v0->render();


$items = BinaryOperators::difference($second, $first);
$i = 0;
$v1 = new SVGVisualizer();
foreach($items as $s) {
    $v1->addShape($s, $colors[$i++]);
}
echo $v1->render();
echo "items: ".count($items);
echo "<hr />";

