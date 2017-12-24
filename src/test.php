<html>
<body>
<?php

use jmw\fabricad\shapes\Point;
use jmw\fabricad\shapes\Line;
use jmw\fabricad\shapes\Polygon;
use jmw\fabricad\shapes\Rectangle;
use jmw\fabricad\visualizer\SVGVisualizer;
use jmw\fabricad\shapes\BinaryOperators;

require_once('autoload.php');
require_once('visualization/SVGVisualizer.php');

///*
$p = new Polygon(
    array(
        new Point(100, 300), // 0
        new Point(300, 500), // 1
        new Point(800, 600), // 2
        new Point(900, 100), // 3
        new Point(500,  50), // 4
        new Point(400, 200)  // 5
    )
);

$w = new Polygon(
    array(
        new Point(800, 50),  // 0
        new Point(450, 600), // 1
        new Point(300, 100), // 2
        new Point(200, 600), // 3
        new Point(850, 800)  // 4
    )
);
// */
//  /*
$p = new Polygon(
    [
        new Point(  0, 50),     // 0
        new Point(300, 10),     // 1
        new Point(450, 70),     // 2
        new Point(400, 200),    // 3
//        new Point(200, 250),
        new Point( 50, 300),    // 4
        new Point( 20, 250)     // 5
    ]
);

$w = new Polygon([
    new Point(50, 5),        // 0
    new Point(100, 400),     // 1
    new Point(100, 200),     // 2
    new Point(350, 300),     // 3
    new Point(320, 100)      // 4
]);
// */
// /*
$p = new Rectangle(100,100);
$w = new Rectangle(100,100, new Point(50,50));
// */
echo '<pre>';
$diff = array();
$diff = BinaryOperators::intersection($p, $w);
//$diff = BinaryOperators::union($p, $w);
//$diff = BinaryOperators::difference($p, $w);
echo '</pre>';

$v1 = new SVGVisualizer();
$v1->addShape($p, 'blue');
$v1->addShape($w, 'red');
echo $v1->render();


$a = $p->calculateIntersectionPointsWith($w)->expand();
$b = $w->calculateIntersectionPointsWith($p)->expand();

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
$v2->addShape($p, 'yellow');
$v2->addShape($w, 'gray');
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