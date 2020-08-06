<?php


/**
 * * Dormers is an array. Each dormer has 3 parameters:
 *   - x startpoint of dormer
 *   - h height of dormer
 *   - l length of dormer
 *
 * @param      float|integer  $height     The height
 * @param      float|integer  $width      The width
 * @param      float          $length     The length
 * @param      float          $stickingx  The stickingx
 * @param      float|integer  $thickness  The thickness
 * @param      array          $dormers    The dormers
 *
 * @return     array          The roof.
 */
function calculateRoof(float $height,float $width,float $length, float $stickingx, float $thickness, $dormers =[])
{
  $points = [];
  $points[] = ['x' => 0, 'y' =>0];

  $a = atan($height / $width);
  $l1 = $stickingx / cos($a);
  $k1 = $thickness / cos($a);
  $lp1 = $thickness * tan($a);

  foreach($dormers as $d)
  {
    $points[] = ['x' => $d['x'], 'y' => 0];
    // next point up: l1 + l'1 + l4
    $l4 = ($d['h'] - $k1) / sin($a);

    $y = $l1 + $lp1 + $l4;

    $points[] = ['x' => $d['x'], 'y'=>$y];
    $points[] = ['x' => $d['x'] + $d['l'], 'y'=>$y];
    $points[] = ['x' => $d['x'] + $d['l'], 'y'=> 0];
  }

  $totH = ($l1 + sqrt($height*$height + $width * $width) + $lp1);

  $points[] = ['x' => $length, 'y' => 0];
  $points[] = ['x' => $length, 'y' => $totH];
  $points[] = ['x' => 0, 'y' => $totH];

  return $points;
}

$arr = calculateRoof(75,130, 275, 10, 8, [['x'=>105, 'h'=>23, 'l'=>60]]);

var_dump($arr);



