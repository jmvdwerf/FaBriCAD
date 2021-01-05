#include "RayedBrickArch.h"

namespace fabricad::blocks
{

  RayedBrickArch::RayedBrickArch(int rays, int stones)
  {
  	rays_ = rays;
  	stones_ = stones;

  	points_.resize(rays_);
  	for(int ray = 0 ; ray < rays_ ; ray++)
  	{
  		points_[ray].resize(stones_);
  		for(int stone = 0; stone < stones_ ; stone++)
  		{
  			points_[ray][stone] = point(0,0);
  		}
  	}
  }

  point RayedBrickArch::get(int ray, int stone)
  {
		if (ray < 0 || ray >= rays() || stone < 0 || stone >= stones() )
  	{
  		return point(0,0);
  	} else {
  		return points_[ray][stone];
  	}
  }

  RayedBrickArch* RayedBrickArch::set(int ray, int stone, point p)
  {
  	if (!(ray < 0 || ray >= rays() || stone < 0 || stone >= stones() ))
  	{
  		points_[ray][stone].set<0>(p.get<0>());
  		points_[ray][stone].set<1>(p.get<1>());
  	}
  	return this;
  }

  int RayedBrickArch::rays()
  {
  	return rays_;
  }

  int RayedBrickArch::stones()
  {
  	return stones_;
  }

	polygon RayedBrickArch::outerPolygon()
	{
		return outerPolygon(0.0, 0.0);
	}

	polygon RayedBrickArch::outerPolygon(point offset)
	{
		return outerPolygon(offset.get<0>(), offset.get<1>());
	}

  polygon RayedBrickArch::outerPolygon(float dx, float dy)
  {
  	polygon outer;

  	 // Walk the first half
    for(int ray = 0 ; ray < rays() ; ray++) {
      bg::append(outer.outer(),
        point(
          dx - get(ray, 0).get<0>(), // X point
          dy + get(ray, 0).get<1>() // Y point
        )
      );
    }
    // Walk the second half
    for(int ray = rays() - 1 ; ray >= 0 ; ray--)
    {
      bg::append(outer.outer(),
        point(
          dx + get(ray, 0).get<0>(), // X point
          dy + get(ray, 0).get<1>() // Y point
        )
      );
    }

    // Walk the upper part of the second half
    for(int ray = 0 ; ray < rays() ; ray++)
    {
      bg::append(outer.outer(),
        point(
          dx + get(ray, stones() - 1).get<0>(), // X point
          dy + get(ray, stones() - 1).get<1>() // Y point
        )
      );
    }

    // Walk the upper part of the first half
    for(int ray = rays() - 1 ; ray >= 0 ; ray--)
    {
      bg::append(outer.outer(),
        point(
          dx - get(ray, stones() - 1).get<0>(), // X point
          dy + get(ray, stones() - 1).get<1>() // Y point
        )
      );
    }
    return outer;
  }

	void RayedBrickArch::addBrickWork(std::vector<linestring> &lines)
	{
		addBrickWork(lines, 0.0, 0.0);
	}

	void RayedBrickArch::addBrickWork(std::vector<linestring> &lines, point offset)
	{
		addBrickWork(lines, offset.get<0>(), offset.get<1>());
	}

	void RayedBrickArch::addBrickWork(std::vector<linestring> &lines, float dx, float dy)
	{
		// Add the inner rays, from right to left
		for(int ray = 1 ; ray < rays() ; ray++)
		{
			point p1 = point(get(ray, 0).get<0>()+dx, get(ray, 0).get<1>() + dy);
			point p2 = point(get(ray, stones()-1).get<0>()+dx, get(ray, stones()-1).get<1>() + dy);

			lines.push_back(linestring({p1, p2}));
		}
		// and the second half
		for(int ray = rays() -1 ; ray > 0 ; ray--)
		{
			point p1 = point(dx - get(ray, 0).get<0>(), get(ray, 0).get<1>() + dy);
			point p2 = point(dx - get(ray, stones()-1).get<0>(), get(ray, stones()-1).get<1>() + dy);

			lines.push_back(linestring({p1, p2}));
		}

		// Add the "horizontal" lines, even stones
		for(int stone = 2 ; stone < stones() - 1 ; stone+=2)
		{
			for(int ray = 0 ; ray < rays() -1 ; ray += 2)
			{
				point p1 = point(dx + get(ray, stone).get<0>(), dy + get(ray, stone).get<1>());
				point p2 = point(dx + get(ray+1, stone).get<0>(), dy + get(ray+1, stone).get<1>());

				point p3 = point(dx - get(ray, stone).get<0>(), dy + get(ray, stone).get<1>());
				point p4 = point(dx - get(ray+1, stone).get<0>(), dy + get(ray+1, stone).get<1>());

				lines.push_back(linestring({p1, p2}));
				lines.push_back(linestring({p3, p4}));
			}

		}

		// Add the lines of the cap
		for(int stone = 2 - ((rays()+1) % 2) ; stone < stones() - 1 ; stone+=2)
		{
			point p1 = point(dx + get(rays()-1, stone).get<0>(), dy + get(rays()-1, stone).get<1>());
			point p2 = point(dx - get(rays()-1, stone).get<0>(), dy + get(rays()-1, stone).get<1>());
			lines.push_back(linestring({p1, p2}));
		}

		// Add the "horizontal" lines, odd stones
		for(int stone = 1 ; stone < stones() - 1 ; stone+=2)
		{
			for(int ray = 1 ; ray < rays() -1 ; ray += 2)
			{
				point p1 = point(dx + get(ray, stone).get<0>(), dy + get(ray, stone).get<1>());
				point p2 = point(dx + get(ray+1, stone).get<0>(), dy + get(ray+1, stone).get<1>());

				point p3 = point(dx - get(ray, stone).get<0>(), dy + get(ray, stone).get<1>());
				point p4 = point(dx - get(ray+1, stone).get<0>(), dy + get(ray+1, stone).get<1>());

				lines.push_back(linestring({p1, p2}));
				lines.push_back(linestring({p3, p4}));
			}

		}

	}

} // End of namespace
