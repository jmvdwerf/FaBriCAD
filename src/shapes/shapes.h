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

namespace bg = boost::geometry;

typedef bg::model::point<float, 2, bg::cs::cartesian> point;
typedef bg::model::box<point> box;
typedef bg::model::polygon<point, false, false> polygon;
typedef bg::model::multi_polygon<polygon> multi_polygon;
typedef bg::model::linestring<point> linestring;
typedef bg::model::multi_linestring<linestring> multi_linestring;
typedef boost::variant<point, box, polygon, linestring> geometry;


//namespace fabricad::shapes
//{
  std::vector<polygon> split(polygon p1, polygon p2);
//}

struct print_visitor : public boost::static_visitor<>
{
    void operator()(polygon const& g) const { std::cout << bg::wkt<polygon>(g) << std::endl; }
    void operator()(box const& g) const { std::cout << bg::wkt<box>(g) << std::endl; }
    void operator()(linestring const& g) const { std::cout << bg::wkt<linestring>(g) << std::endl; }
    void operator()(point const& g) const { std::cout << bg::wkt<point>(g) << std::endl; }
};

#endif
