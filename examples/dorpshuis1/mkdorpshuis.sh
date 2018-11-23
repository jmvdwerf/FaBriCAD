#!/bin/sh

fabricad voorkant.fabricad -Tscad scad/voorkant.scad -Tsvg svg/dorpshuis
fabricad zijkant_binnen.fabricad -Tscad scad/zijkant_binnen.scad -Tsvg svg/dorpshuis
fabricad zijkant_buiten.fabricad -Tscad scad/zijkant_buiten.scad -Tsvg svg/dorpshuis
fabricad grondoppervlak.fabricad -Tscad scad/grondoppervlak.scad -Tsvg svg/dorpshuis
fabricad bergingachter.fabricad -Tscad scad/bergingachterkant.scad -Tsvg svg/dorpshuis
fabricad berging_zijkant.fabricad -Tscad scad/bergingzijkant.scad -Tsvg svg/dorpshuis
fabricad achterkant.fabricad -Tscad scad/achterkant.scad -Tsvg svg/dorpshuis
fabricad dakvoor.fabricad -Tscad scad/dakvoor.scad -Tsvg svg/dorpshuis
fabricad dakkapel_zijkant1.fabricad -Tscad scad/dakkapel_zijkant.scad -Tsvg svg/dorpshuis
