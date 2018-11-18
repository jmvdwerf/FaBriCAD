#include <cmath>

#include "Lintel.h"

namespace fabricad::blocks
{

  point Lintel::getCenter()
  {
    return center_;
  }

  Lintel* Lintel::setCenter(point center)
  {
    center_ = center;
    return this;
  }

  point Lintel::getStrikingPoint()
  {
    return strikingpoint_;
  }

  Lintel* Lintel::setStrikingPoint(point strikingPoint)
  {
    strikingpoint_ = strikingPoint;
    return this;
  }

  float Lintel::getHeight()
  {
    return height_;
  }

  Lintel* Lintel::setHeight(float height)
  {
    height_ = height;
    return this;
  }

  float Lintel::getWidth()
  {
    return width_;
  }

  Lintel* Lintel::setWidth(float width)
  {
    width_ = width;
    return this;
  }

  size_t Lintel::getStones()
  {
    return stones_;
  }

  Lintel* Lintel::setStones(size_t stones)
  {
    stones_ = stones;
    return this;
  }

  /*
   For rendering, we use the following principles:

   topR  >._____________. < topL
           \           /
  origin >  ._________.  < left
             \   |   /|
              \  |  / |
               \ |a/  |
                \|/...|
                 .   < striking point

    polygon outside = origin, topR, topL, left, origin

    angle a:
    maxangle = atan( (1/2 width) / strikingpoint_.get<1>() );

    Step angle:
    step = maxangle / (#stones + 1/2)

    start angle: step / 2

    For calculating the different points on the Lintel:
    ____ < dX, dY
    |  /
    | / s
    |/
    a

    sin a = o / s => o = s . sin a
    cos a = a / s => a = s . cos a

    This is done by getDxFor and getDyFor.
   */
  void Lintel::render()
  {
    BasicBuildingBlock::render();
    this->layers_[0].polygons.clear();

    point porring = point(
      center_.get<0>() + strikingpoint_.get<0>(),
      center_.get<1>() + strikingpoint_.get<1>()
    );

    //calculate the angle from
    float maxAngle = atan( ((float) width_) / (-1 * strikingpoint_.get<1>()) );
    float stepAngle = maxAngle / (stones_ + 0.5);
    float maxX = getPointFor(maxAngle, -1 * strikingpoint_.get<1>() + height_);

    float  cx = center_.get<0>();
    float  cy = center_.get<1>();
    float  px = porring.get<0>();
    float  py = porring.get<1>();
    float top = center_.get<1>() + height_;
    float bot = center_.get<1>();

    point left   = point(center_.get<0>() - width_, bot);
    point leftT  = point(center_.get<0>() - maxX  , top);
    point right  = point(center_.get<0>() + width_, bot);
    point rightT = point(center_.get<0>() + maxX  , top);

    polygon outside;
    bg::append(outside.outer(), left  );
    bg::append(outside.outer(), leftT );
    bg::append(outside.outer(), rightT);
    bg::append(outside.outer(), right );
    bg::append(outside.outer(), left  );
    bg::correct(outside);
    this->layers_[0].polygons.push_back(outside);

    // Create the vertical lines
    size_t counter = 0; // used for alternating the stone pattern

    float prevAngle = -1 * stepAngle / 2;
    for(float angle = stepAngle / 2 ; angle <= maxAngle ; angle += stepAngle)
    {
      float dXB = getPointFor(angle, -1 * strikingpoint_.get<1>() );
      float dXT = getPointFor(angle, -1 * strikingpoint_.get<1>() + height_);

      linestring l1 = {point(cx+dXB, bot), point(cx+dXT, top) };
      linestring l2 = {point(cx-dXB, bot), point(cx-dXT, top) };

      linestring radius = {porring, point(cx+dXB, top)};
      linestring hidden = {porring, point(cx+dXB, bot)};

      if (angle < maxAngle) {
        this->layers_[1].lines.push_back(l1);
        this->layers_[1].lines.push_back(l2);
      }

      float startL = bg::length(hidden);
      float length = bg::length(radius);

      // create the "horizontal" lines
      startL += ((counter % 2) == 0) ? getBrickHeight() : getBrickHeight() / 2;
      for(float h = startL ; h <= length ; h += getBrickHeight() )
      {
        // h is the "schuine zijde", and we have the angle, so we can calculate
        // the y value
        float dX1 = getDxFor(prevAngle, h ) ;
        float dY1 = getDyFor(prevAngle, h ) ;
        float dX2 = getDxFor(angle, h);
        float dY2 = getDyFor(angle, h);

        linestring h1 = {point(px + dX1, py + dY1), point(px + dX2, py + dY2)};
        this->layers_[1].lines.push_back(h1);

        if (prevAngle > 0) {
          linestring h2 = {point(px - dX1, py + dY1), point(px - dX2, py + dY2)};
          this->layers_[1].lines.push_back(h2);
        }

      }
      prevAngle = angle;
      counter++;
    }
  }
}
