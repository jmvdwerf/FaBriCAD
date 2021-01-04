#include "ArcLintel.h"

namespace fabricad::blocks {

  ArcLintel::ArcLintel()
  {
    // Set the default values
    brickHeight(5);
    brickWidth(10);
    arcLength(10);
  }

  float ArcLintel::brickHeight()
  {
    return brick_height_;
  }

  ArcLintel* ArcLintel::brickHeight(float height)
  {
    brick_height_ = height;
    return this;
  }

  float ArcLintel::brickWidth()
  {
    return brick_width_;
  }

  ArcLintel* ArcLintel::brickWidth(float width)
  {
    brick_width_ = width;
    return this;
  }

  float ArcLintel::arcLength()
  {
    return arc_length_;
  }

  ArcLintel* ArcLintel::arcLength(float length)
  {
    arc_length_ = length;
    return this;
  }

  std::string ArcLintel::toString(std::string indent)
  {
    std::string s = BasicBuildingBlock::toString(indent);
    return s +
      indent + "major axis  : " + std::to_string(a_) + "\n" +
      indent + "minor axis  : " + std::to_string(b_) + "\n" +
      indent + "arc length  : " + std::to_string(arcLength()) + "\n" +
      indent + "brick height: " + std::to_string(brickHeight()) + "\n" +
      indent + "brick width : " + std::to_string(brickWidth()) + "\n" +
      indent + "origin      : x: " + std::to_string(origin_.get<0>()) + "\n" +
      indent + "              y: " + std::to_string(origin_.get<1>()) + "\n" +
      indent + "porring     : x: " + std::to_string(porring_.get<0>()) + "\n" +
      indent + "              y: " + std::to_string(porring_.get<1>()) + "\n";
  }

  BasicBuildingBlock* ArcLintel::setShape(polygon const& shape)
  {
    BasicBuildingBlock::setShape(shape);

    box bb;
    bg::envelope(this->shape_, bb);

    // Calculate the inner ellipse
    a_ = (bb.max_corner().get<0>() - bb.min_corner().get<0>())/2;
    b_ =  bb.max_corner().get<1>() - bb.min_corner().get<1>();

    // Get the origin
    origin_.set<0>(bb.min_corner().get<0>());
    origin_.set<1>(bb.min_corner().get<1>());

    // Get the porring point
    porring_.set<0>(origin_.get<0>() + a_);
    float length = (a_ * a_) / b_;
    porring_.set<1>(origin_.get<1>() + b_ - length);

    return this;
  }

  void ArcLintel::render()
  {
		BasicBuildingBlock::render();

    cout << toString("");

    // We only calculate it for a quarter...
    float outer_length = ellipseCircumference(a_ + arcLength(), b_ + arcLength()) / 4;

    cout << "outer length: " << outer_length << std::endl;

    // Nr of rays is based on a quarter, minus the cap stone, a single brick stone
    // We use a floor instead of a ceil, since most of the times, it is better looking
    // to have slightly larger than slightly smaller stones...
    int nr_of_rays = (int) floor((outer_length-brickHeight()) / brickHeight());

    cout << "nr of rays  : " << nr_of_rays << std::endl;

    // As getUpperIntersectionEllipseLine requires the origin to be the center
    // of the ellipse, we first translate point porring_

    point p_rel;
    p_rel.set<0>(0);
    p_rel.set<1>(porring_.get<1>() - origin_.get<1>());

    cout << "porring(rel): x: " << porring_.get<0>() << std::endl;
    cout << "              y: " << porring_.get<1>() << std::endl;

    // Determine the number of points at each ray.
    // For simplicity, we divide each ray in half brick stones
    int nr_of_stones = ceil(arcLength() / (2 * brickWidth()));

    cout << "nr of stones: " << nr_of_stones << std::endl;

    // Array to store all the rays in the quarter, to the relative center.
    point rays[nr_of_rays][nr_of_stones];


    // First, determine the start angle
    float start_angle = atan(abs(p_rel.get<1>())/ a_);
    // The step size per stone
    float angle_step = ((M_PI / 2) - start_angle) / nr_of_rays;

    // Every line has form y = mx + c.
    // As every line passes point p_rel, we can rewrite this to:
    // So, moving the angle up, we get:
    // y = tan(a)*x + p_rel_y

    // Walk the arc, and store the points in a vector of rays.
    float angle = start_angle;
    float height = 0;

    for( int ray = 0 ; ray < nr_of_rays ; ray++ )
    {
      // Create a point for each half stone
      for(int stone = 0 ; stone < nr_of_stones ; stone++)
      {
        rays[ray][stone] = getUpperIntersectionEllipseLine(a_ + height, b_ + height, tan(angle), p_rel.get<1>());

        // Update the height for the next round, and take care not to be bigger
        // than the arc length itself.
        height += brickWidth() / 2;
        if (height > arcLength())
        {
          height = arcLength();
        }
      }
      angle += angle_step;
    }

    // Now that we have the array of rays, we can start drawing the lintel
    // Remember we have to translate the points in this array to the original
    // Center, which is the X-coordinate of the porring point, and the
    // Y-coordinate of the origin.

    // First, we do layer 0, the outside of the lintel
    this->layers_[0].polygons.clear();
    polygon outer;

    // Walk the first half
    for(int ray = 0 ; ray < nr_of_rays ; ray++)
    {
      bg::append(outer.outer(),
        point(
          porring_.get<0>() + rays[ray][0].get<0>(), // X point
          origin_.get<1>()  + rays[ray][0].get<1>() // Y point
        )
      );
    }
    // Walk the second half, i.e., interpret the ray with negative X coordinate
    for(int ray = nr_of_rays - 1 ; ray >= 0 ; ray--)
    {
      bg::append(outer.outer(),
        point(
          porring_.get<0>() - rays[ray][0].get<0>(), // X point
          origin_.get<1>()  + rays[ray][0].get<1>() // Y point
        )
      );
    }
    // Walk the third half
    for(int ray = 0 ; ray < nr_of_rays ; ray++) {
      bg::append(outer.outer(),
        point(
          porring_.get<0>() - rays[ray][nr_of_stones - 1].get<0>(), // X point
          origin_.get<1>()  + rays[ray][nr_of_stones - 1].get<1>() // Y point
        )
      );
    }
    // Walk the fourth half
    for(int ray = nr_of_rays - 1 ; ray >= 0 ; ray--)
    {
      bg::append(outer.outer(),
        point(
          porring_.get<0>() + rays[ray][nr_of_stones - 1].get<0>(), // X point
          origin_.get<1>()  + rays[ray][nr_of_stones - 1].get<1>() // Y point
        )
      );
    }

    // Store this shape as the main
    this->layers_[0].polygons.push_back(outer);

    // Now do all the brick lines
  }

} // End of Namespace

