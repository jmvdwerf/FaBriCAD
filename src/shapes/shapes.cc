
#include "shapes.h"

std::vector<polygon> split(polygon p1, polygon p2)
{
  std::vector<polygon> out;
  bg::intersection(p1, p2, out);
  bg::sym_difference(p1, p2, out);

  return out;
}
