

#include "EnglishBond.h"

namespace fabricad::blocks
{
  void EnglishBond::renderBricksFor(box const& bb, std::vector<linestring> &lines)
  {
    float minX = bb.min_corner().get<0>();
    float minY = bb.min_corner().get<1>();
    float maxX = bb.max_corner().get<0>();
    float maxY = bb.max_corner().get<1>();

    size_t counter = getStartRow();
    float prevY = minY;

    // Generate horizontal lines
    // Notice that we use <=, as we still want to draw vertical lines!
    for(float i = minY + getBrickHeight() ; i <= maxY ; i += getBrickHeight() )
    {
      point p1 = point(minX, i);
      point p2 = point(maxX, i);
      if (i < maxY) {
        // If it is  >=, we do not need to draw it, as it will fall outside of
        // the shape anyway.
        lines.push_back(linestring({p1, p2}));
      }

      // vertical lines
      bool halfStones = (((float) (counter % 2)) == 0);
      float start = halfStones ? minX + getBrickWidth() / 2 : minX  + (3 * getBrickWidth()) / 4;
      float step = halfStones? getBrickWidth()  / 2 : getBrickWidth();
      for(float j = start ; j < maxX ; j += step )
      {
        point py1 = point(j, prevY);
        point py2 = point(j, i);
        lines.push_back(linestring({py1, py2}));
      }
      prevY = i;
      counter++;
    }
  }
}
