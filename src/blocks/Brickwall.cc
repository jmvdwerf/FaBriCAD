
#include "Brickwall.h"


namespace fabricad::blocks
{
  Brickwall::Brickwall()
  {
    angle_ = 0;
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

  float Brickwall::getAngle()
  {
    return angle_;
  }

  Brickwall* Brickwall::setAngle(float angle)
  {
    angle_ = angle;
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
    s = s + indent + "At angle      : " + std::to_string(getAngle()) + "\n";
    s = s + indent + "Start at row  : " + std::to_string(getStartRow()) + "\n";

    return s;
  }

}
