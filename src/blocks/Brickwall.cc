
#include "Brickwall.h"


namespace fabricad::blocks
{
  Brickwall::Brickwall()
  {
    horizontal_ = true;
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

  bool Brickwall::horizontal()
  {
    return horizontal_;
  }

  Brickwall* Brickwall::setHorizontal(bool horizontal)
  {
    horizontal_ = horizontal;
    return this;
  }

  int Brickwall::getStartRow()
  {
    return start_;
  }

  Brickwall* Brickwall::setStartRow(int start)
  {
    start_ = start;
    return this;
  }

  std::string Brickwall::toString(std::string indent)
  {
    std::string s = BasicBuildingBlock::toString(indent);
    s = s + indent + "Bricks (w x h): " + std::to_string(getBrickWidth()) + ", " + std::to_string(getBrickHeight()) + "\n";
    s = s + indent + "Horizontal    : " + std::to_string(horizontal()) + "\n";
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


    float minX = bb.min_corner().get<0>();
    float minY = bb.min_corner().get<1>();
    float maxX = bb.max_corner().get<0>();
    float maxY = bb.max_corner().get<1>();


    int counter = getStartRow();
    float prevY = minY;
    // Generate horizontal lines
    for(float i = minY + getBrickHeight() ; i < maxY ; i += getBrickHeight() )
    {
      point p1 = point(minX, i);
      point p2 = point(maxX, i);

      // calculate the intersection with the shape, and add the resulting
      // lines to layer 1
      std::vector<linestring> lines;
      bg::intersection(linestring({p1, p2}), this->shape_, lines);

      // calculate the vertical lines
      //start = $startX + (1-($counter % 2)/2) * $this->getBrickWidth();
      float start = minX + (1-((float) (counter % 2))/2) * getBrickWidth();
      for(float j = start ; j < maxX ; j += getBrickWidth() )
      {
        point py1 = point(j, prevY);
        point py2 = point(j, i);
        bg::intersection(linestring({py1, py2}), this->shape_, lines);
      }

      this->layers_[1].lines.insert(
            this->layers_[1].lines.end(),
            lines.begin(),
            lines.end()
      );
      prevY = i;
      counter++;
    }

  }

}
