
include <voorkant.scad>
include <zijkant_binnen.scad>
include <zijkant_buiten.scad>
include <grondoppervlak.scad>
include <bergingachterkant.scad>
include <bergingzijkant.scad>
include <achterkant.scad>
include <dakvoor.scad>
include <dakachter.scad>
include <dakkapeldak.scad>
include <dakkapel_zijkant.scad>
include <bergingdak.scad>

// translate([0,0,0]) oppervlak();

translate([0,8,0]) rotate([90,0,0]) front();
rotate([90,0,90]) buiten();
translate([275-8,0,0]) rotate([90,0,90]) binnen();
translate([275,360-8,0]) rotate([90,0,180]) bergingachter();
translate([170+8,360,0]) rotate([90,0,-90]) berging_zijkant();

translate([275,260-8,0]) rotate([90,0,180]) achter();

translate([165,260,136]) bergingdak();

translate([265,270,192-(10*tan(30))]) rotate([30,0,180]) dakachter();

translate([105,0,192]) rotate([90,0,90]) dakkapelzijkant();
translate([165-8,0,192]) rotate([90,0,90]) dakkapelzijkant();

translate([0,-10,192-(10*tan(30))]) rotate([30,0,0]) dakvoor();
translate([135, 0, 240]) rotate([0,39.8,0]) dakkapeldak();
translate([135-(8*sin(atan(25/30))), 0, 240+8*cos(atan(25/30))]) rotate([0,180-39.8,0]) dakkapeldak(); //
