#include "shapes.h"

#include <fstream>
#include <iostream>

std::vector<polygon> split(polygon p1, polygon p2)
{
  std::vector<polygon> out;
  bg::intersection(p1, p2, out);
  bg::sym_difference(p1, p2, out);

  return out;
}

void polygonmerge(std::vector<polygon> *orig, std::vector<polygon> const& toadd)
{
  orig->insert( orig->end(), toadd.begin(), toadd.end());
}

void linestringmerge(std::vector<linestring> *orig, std::vector<linestring> const& toadd)
{
  orig->insert( orig->end(), toadd.begin(), toadd.end());
}

void calculateDifference(std::vector<linestring> items, std::vector<polygon> const& overlap, std::vector<linestring> *list)
{
  for(size_t i = 0 ; i < items.size(); i++)
  {
    std::vector<linestring> diff = calculateLineDifference(items[i], overlap);
    list->insert(list->end(), diff.begin(), diff.end());
  }
}

std::vector<linestring> calculateLineDifference(linestring line, std::vector<polygon> const& overlap)
{
  // Ik heb een shape S en een overlap A. Voor deze shape bereken ik het
  // verschil van S met A. Dat geeft mij een verzameling nieuwe elementen,
  // die niet overlappen met A. Vervolgens controleer ik deze nieuwe set S'
  // op overlap met het volgende element, B. Dit herhaal ik totdat ik alle
  // elementen in de overlap heb gehad.
  std::vector<linestring> q;
  q.push_back(line);
  for(size_t i = 0 ; i < overlap.size() ; i++)
  {
    //bg::correct(overlap.at(i));
    std::vector<linestring> newQ;
    while(!q.empty())
    {
      linestring l = q.front();
      q.erase(q.begin());
      bg::difference(l, overlap.at(i), newQ);
    }
    q.insert(q.begin(), newQ.begin(), newQ.end());
  }

  return q;
}


/*
 * Gives the x component given a length and an angle
 *     tan(angle) = o / length;
 */
float getPointFor(float angle, float length)
{
  return tan(angle) * length;
}

std::string p2s(point p)
{
  return "( " + std::to_string(p.get<0>()) + ", " + std::to_string(p.get<1>()) + ")";
}


float getDxFor(float angle, float length)
{
  return sin(angle) * length;
}

float getDyFor(float angle, float length)
{
  return cos(angle) * length;
}

point increasePoint(point p, float inc)
{
  float x = p.get<0>() + inc;
  float y = p.get<1>() + inc;

  return point(x, y);
}

polygon ellipseToPolygon(point const& center, float a, float b)
{
  return ellipseToPolygon(center, a, b, 24);
}

polygon ellipseToPolygon(point const& center, float a, float b, size_t sides)
{
  float step;
  if (sides == 0) {
    step = M_PI / 12;
  } else {
    step = 2 * M_PI / sides;
  }

  float cx = center.get<0>();
  float cy = center.get<1>();

  polygon p;

  for(float angle = 0; angle < 2 * M_PI ; angle += step)
  {
    float x = a * cos(angle) + cx;
    float y = b * sin(angle) + cy;
    bg::append(p.outer(), point(x,y));
  }
  bg::correct(p);

  return p;
}

float ellipseCircumference(float a, float b) {
  // We use Ramanujan's second approximation
  // pi (a + b) [ 1 + 3 h / (10 + (4 - 3 h)^1/2 ) ]
  // with h = (a-b)^2 / (a+b)^2
  float h = ((a-b)*(a-b)) / (a+b)*(a+b);
  return M_PI * (a+b) * ( 1 + 3*h / (10 + sqrt(4 - 3 * h)) );
}


point getUpperIntersectionEllipseLine(float a, float b, float m, float c)
{
  float a2 = a * a;
  float b2 = b * b;
  float m2 = m * m;
  float c2 = c * c;

  // Check if there are solutions:
  if (c2 >= ( a2 * m2 + b2) || b == 0 || a == 0) {
    // There is no solution!
    return point(0,0);
  }

  float det = sqrt(a2 * m2 + b2 - c2) / (a * b);
  float denominator = (1 / a2) + (m2 / b2);

  float x1 = (-1 * det - ((c * m) / b2)) / denominator;
  float x2 = (det - ((c * m) / b2)) / denominator;

  float y1 = m * x1 + c;

  if (y1 >= 0) {
    return point(x1, y1);
  } else {
    return point(x2, m * x2 + c);
  }
}




