<html>
<body>
<?php

require_once('visualization/SVGVisualizer.php');
require_once('shapes/Point.php');
require_once('shapes/Shape.php');
require_once('shapes/Ellipse.php');
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
use jmw\fabricad\shapes\Ellipse;


$p = new Polygon([
    new Point(1000, 0),
    new Point(1500, 250),
    new Point(750, 1000),
    new Point(1000, 250),
    new Point(250, 750),
    new Point(0, 250)
    ]);

//$l = new Line(new Point(0, 500), new Point(1500,500));
//$w = $l->asPolygon();
$w = new Polygon([
    new Point(500, 250),
    new Point(1200, 250),
    new Point(1200, 500),
    new Point(500, 500)
]);
// */
 /*
$p = new Rectangle(1000,1000);
// $w = new Rectangle(1000,1000, new Point(500,500));
$w = new Polygon([
    new Point(500, 0),
    new Point(1000, 500),
    new Point(500, 1000),
    new Point(0, 500)
]);
// */
/*
$tuin = new Polygon([
    new Point(0,0),
    new Point(0, 630),
    new Point(620, 630),
    new Point(620, 940),
    new Point(950, 940),
    new Point(950, 1410),
    new Point(1316, 1410),
    new Point(1316, 0)
]);

$terras = new Ellipse(520,520, new Point(1316,0));
$boom = new Ellipse(30,30, new Point(676, 90));
// */


echo '<pre>';
$a = $p->calculateIntersectionPointsWith($w)->expand();
$b = $w->calculateIntersectionPointsWith($p)->expand();

$diff = array();
//$diff = BinaryOperators::intersection($p, $w);
$diff = BinaryOperators::union($p, $w);
//$diff = BinaryOperators::difference($p, $w);

echo "Found ".count($diff)." shapes";
echo '</pre>';

$v1 = new SVGVisualizer();
$v1->addShape($p, 'blue');
$v1->addShape($w, 'red');
//$v1->addShape($tuin);
//$v1->addShape($terras);
//$v1->addShape($boom);


$v3 = new SVGVisualizer();
$v3->addShape($a, 'blue');
$v3->addShape($b, 'red');
echo $v3->render();

$v4 = new SVGVisualizer();
//$v4->addShape($a, 'blue');
$v4->addShape($b, 'red');
//$v4->addShape($l);
//echo $v4->render();

$v2 = new SVGVisualizer();
//$v2->addShape($p, 'yellow');
//$v2->addShape($w, 'gray');
$colors = array('red','green', 'blue', 'pink', 'gray' ,'yellow');

foreach($diff as $i=>$s) {
    $v2->addShape($s, $colors[$i % count($colors)]);
}
//$v2->addShape($p2);
//$v2->addShape($w2);
echo $v2->render();
?>
</body>
</html>