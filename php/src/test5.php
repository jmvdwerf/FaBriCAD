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

$a = new Polygon([
    new Point(1135, 1790 ),
    new Point(1135, 2220 ),
    new Point(1565, 2220 ),
    new Point(1565, 1790 )
]);

$r = array();

for($i = 0 ; $i < 3 ; $i++) {
    for ($j = 0 ; $j < 3 ; $j++) {
        $r[] = new Rectangle(130, 130, new Point(1135+($i * 150) , 1790 + ($j * 150) ) );
    }
}

/*
$r['blue'] = new Rectangle(100,200, new Point(100, 100));
$r['red'] = new Rectangle(100,200, new Point(700, 100));
$r['gray'] = new Rectangle(100,200, new Point(300, 200));
$r['green'] = new Rectangle(100,200, new Point(350, 300));
*/

$v1 = new SVGVisualizer();
$v1->addShape($a, "blue");
foreach($r as $shape) {
    $v1->addShape($shape, "red");
}
echo $v1->render();

echo "<hr />";

//$list = new Container();
//$list->addShape($kapel, true);
//$list->addShape($wall, true);
$items = [];

$items = BinaryOperators::difference($a, $r[2]);

$v1 = new SVGVisualizer();
foreach($items as $shape) {
    $v1->addShape($shape, "red");
}
echo $v1->render();


?>
