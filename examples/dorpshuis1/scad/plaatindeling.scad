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

module poging1()
{
  cube([500,800,1]);

  // translate([0,0,0]) rotate([0,0,0])
  translate([5,5,0]) rotate([0,0,0]) front();
  translate([295,280,0]) rotate([0,0,-90]) achter();
  translate([5,795,8]) rotate([0,180,180]) binnen();
  translate([325,625,0]) rotate([0,0,-90]) dakvoor();
  translate([420,210,0]) rotate([0,0,90]) berging_zijkant();
  translate([380,650,0]) rotate([0,0,0]) bergingachter();
  translate([5,510,0]) rotate([0,0,-90]) buiten();

  translate([270,220,0]) rotate([0,0,90]) dakkapeldak(); // cube([0.1, 67.2385, 10]);
  translate([5,530,8]) rotate([0,180,270]) dakkapeldak(); //
  translate([5,192+17,0]) dakkapelzijkant();
  translate([45,192+17,0]) dakkapelzijkant();

  // translate([235,460,0]) bergingdak();
  // translate([15,250,0]) rotate([0,0,0]) dakachter();

}

module bestepogingtotnutoe()
{
  cube([500,800,1]);

  // translate([0,0,0]) rotate([0,0,0])
  translate([495,370,0]) rotate([0,0,90]) front();
  translate([300,280,0]) rotate([0,0,-90]) achter();
  translate([25,795,8]) rotate([0,180,180]) binnen();
  translate([15,260,0]) rotate([0,0,0]) dakvoor();
  translate([310,210,0]) rotate([0,0,00]) berging_zijkant();
  translate([390,655,0]) rotate([0,0,0]) bergingachter();
  translate([20,5,0]) rotate([0,0,0]) buiten();
  translate([5,433,0]) bergingdak();

  translate([135,440,0]) rotate([0,0,0]) dakkapeldak(); // cube([0.1, 67.2385, 10]);
  translate([225,440,8]) rotate([0,180,0]) dakkapeldak(); //
  translate([435,295,0]) dakkapelzijkant();
  translate([435,335,0]) dakkapelzijkant();

  translate([100,810,0]) rotate([0,0,0]) dakachter();

}

module poging2()
{
  cube([500,800,1]);

  // translate([0,0,0]) rotate([0,0,0])
  translate([5,515,0]) rotate([0,0,-90]) front();
  translate([300,280,0]) rotate([0,0,-90]) achter();
  translate([5,795,8]) rotate([0,180,180]) binnen();
  translate([15,5,0]) rotate([0,0,0]) dakvoor();
  translate([320,190,0]) rotate([0,0,0]) berging_zijkant();
  translate([205,190,0]) rotate([0,0,0]) bergingachter();
  translate([495,340,0]) rotate([0,0,90]) buiten();
  translate([380,680,0]) bergingdak();

  translate([180,190,0]) rotate([0,0,90]) dakkapeldak(); // cube([0.1, 67.2385, 10]);
  translate([490,660,8]) rotate([0,180,90]) dakkapeldak(); //
  translate([15,190,0]) dakkapelzijkant();
  translate([60,190,0]) dakkapelzijkant();

  translate([100,810,0]) rotate([0,0,0]) dakachter();

}

poging2();
translate([510,0,0]) bestepogingtotnutoe();

