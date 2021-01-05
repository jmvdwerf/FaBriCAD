#ifndef _FABRICAD_BLOCKS_RAYEDBRICKARCH_H_
#define _FABRICAD_BLOCKS_RAYEDBRICKARCH_H_

#include <string>

#include "../shapes/shapes.h"


namespace fabricad::blocks
{
  class RayedBrickArch
  {
  public:
  	RayedBrickArch(int rays, int stones);

  	point get(int ray, int stone);
  	RayedBrickArch* set(int ray, int stone, point p);

  	int rays();
  	int stones();

  	polygon outerPolygon();
  	polygon outerPolygon(point offset);
  	polygon outerPolygon(float dx, float dy);

  	void addBrickWork(std::vector<linestring> &lines);
  	void addBrickWork(std::vector<linestring> &lines, point offset);
  	void addBrickWork(std::vector<linestring> &lines, float dx, float dy);

  protected:
    int rays_;
    int stones_;
  	std::vector<std::vector<point>> points_;

  };
}

#endif
