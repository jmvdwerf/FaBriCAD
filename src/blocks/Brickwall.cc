

#include "Brickwall.h"


namespace fabricad::blocks
{
  Brickwall::Brickwall()
  {
    brick_height_ = 5.0;
    brick_width_ = 10.0;
    start_ = 0;
  }

  float Brickwall::getBrickHeight()
  {
    return brick_height_;
  }

  Brickwall* Brickwall::setBrickHeight(float height)
  {
    brick_height_ = height;
    return this;
  }

  float Brickwall::getBrickWidth()
  {
    return brick_width_;
  }

  Brickwall* Brickwall::setBrickWidth(float width)
  {
    brick_width_ = width;
    return this;
  }

  size_t Brickwall::getStartRow()
  {
    return start_;
  }

  Brickwall* Brickwall::setStartRow(size_t start)
  {
    start_ = start;
    return this;
  }

  std::string Brickwall::toString(std::string indent)
  {
    std::string s = BasicBuildingBlock::toString(indent);
    s = s + indent + "Bricks (w x h): " + std::to_string(getBrickWidth()) + ", " + std::to_string(getBrickHeight()) + "\n";
    s = s + indent + "Start at row  : " + std::to_string(getStartRow()) + "\n";

    return s;
  }

  void Brickwall::render()
  {
    // Create initial layer with shape
    BasicBuildingBlock::render();
    // Now, create second layer with bricks

    box bb;
    bg::envelope(this->shape_, bb);
    std::vector<linestring> lines;

    renderBricksFor(bb, lines);

    for(size_t i = 0 ; i < lines.size() ; i++)
    {
      std::vector<linestring> l;

      bg::intersection(lines[i], this->shape_, l);

      this->layers_[1].lines.insert(
            this->layers_[1].lines.end(),
            l.begin(),
            l.end()
      );
    }
  }

  void Brickwall::renderBricksFor(box const& bb, std::vector<linestring> &lines)
  {
    float minX = bb.min_corner().get<0>();
    float minY = bb.min_corner().get<1>();
    float maxX = bb.max_corner().get<0>() + getBrickWidth();
    float maxY = bb.max_corner().get<1>() + getBrickHeight();

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

      float start = minX + (1-((float) (counter % 2))/2) * getBrickWidth();
      for(float j = start ; j <= maxX ; j += getBrickWidth() )
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
