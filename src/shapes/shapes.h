#ifndef _FABRICAD_SHAPES_SHAPES_H_
#define _FABRICAD_SHAPES_SHAPES_H_


#include <boost/geometry.hpp>
#include <boost/geometry/geometries/point.hpp>
#include <boost/geometry/geometries/box.hpp>
#include <boost/geometry/geometries/polygon.hpp>
#include <boost/geometry/geometries/multi_polygon.hpp>
#include <boost/geometry/geometries/linestring.hpp>
#include <boost/geometry/geometries/multi_linestring.hpp>

#include <iostream>
#include <vector>
#include <string>

namespace bg = boost::geometry;
namespace trans = boost::geometry::strategy::transform;

typedef bg::model::point<float, 2, bg::cs::cartesian> point;
typedef bg::model::box<point> box;
typedef bg::model::polygon<point, false, false> polygon;
// typedef bg::model::multi_polygon<polygon> multi_polygon;
typedef bg::model::linestring<point> linestring;
// typedef bg::model::multi_linestring<linestring> multi_linestring;

struct shapelayer
{
  std::vector<polygon> polygons;
  std::vector<linestring> lines;
  std::string name;
  std::string id;
};

//namespace fabricad::shapes
//{
  std::vector<polygon> split(polygon p1, polygon p2);
//}

void createSVGFile(const std::string filename, std::vector<shapelayer> const &layers);
void createSVGFile(const std::string filename, shapelayer const &layer);

void polygonmerge(std::vector<polygon> *orig, std::vector<polygon> const &toadd);
void linestringmerge(std::vector<linestring> *orig, std::vector<linestring> const &toadd);

void calculateDifference(std::vector<linestring> items, std::vector<polygon> const &overlap, std::vector<linestring> *list);
std::vector<linestring> calculateLineDifference(linestring line, std::vector<polygon> const& overlap);

float getPointFor(float angle, float length);

std::string p2s(point p);

float getDxFor(float angle, float length);
float getDyFor(float angle, float length);

#endif
