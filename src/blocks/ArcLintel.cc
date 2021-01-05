#include "ArcLintel.h"
#include "RayedBrickArch.h"


namespace fabricad::blocks {

  ArcLintel::ArcLintel()
  {
    // Set the default values
    brickHeight(5);
    brickWidth(10);
    arcLength(10);
    rays(2);
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

  int ArcLintel::rays()
  {
    // We add one, as also the initial ray should be counted...
    return rays_ + 1;
  }

  ArcLintel* ArcLintel::rays(int rays)
  {
    rays_ = rays;
    return this;
  }

  std::string ArcLintel::toString(std::string indent)
  {
    std::string s = BasicBuildingBlock::toString(indent);
    return s +
      indent + "major axis  : " + std::to_string(a_) + "\n" +
      indent + "minor axis  : " + std::to_string(b_) + "\n" +
      indent + "arc length  : " + std::to_string(arcLength()) + "\n" +
      indent + "rays        : " + std::to_string(rays()) + "\n" +
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

    // As getUpperIntersectionEllipseLine requires the origin to be the center
    // of the ellipse, we first translate point porring_

    point p_rel;
    p_rel.set<0>(0);
    p_rel.set<1>(porring_.get<1>() - origin_.get<1>());

    cout << "porring(rel): x: " << p_rel.get<0>() << std::endl;
    cout << "              y: " << p_rel.get<1>() << std::endl;

    // Determine the number of points at each ray.
    // For simplicity, we divide each ray in half brick stones
    int nr_of_stones = 2 * ceil(arcLength() / brickWidth());

    // First, determine the start angle
    float start_angle = atan(abs(p_rel.get<1>())/ a_);
    // The step size per stone
    float angle_step = ((M_PI / 2) - start_angle) / (rays()-0.5);

    // Every line has form y = mx + c.
    // As every line passes point p_rel, we can rewrite this to:
    // So, moving the angle up, we get:
    // y = tan(a)*x + p_rel_y

    RayedBrickArch* arc = new RayedBrickArch(rays(), nr_of_stones);

    // Walk the arc, and store the points in a vector of rays.
    float angle = start_angle;

    // TODO CHECK THIS LOOP HERE
    for( int ray = 0 ; ray < rays() ; ray++ )
    {
      float height = 0;
      // Create a point for each half stone
      for(int stone = 0 ; stone < nr_of_stones ; stone++)
      {
        arc->set(ray, stone, getUpperIntersectionEllipseLine(a_ + height, b_ + height, tan(angle), p_rel.get<1>()) );

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
    // Notice that we walk from left to right!
    this->layers_[0].polygons.clear();
    // Store this shape as the main
    this->layers_[0].polygons.push_back(arc->outerPolygon(porring_.get<0>(), origin_.get<1>()));

    // Now do all the brick lines
    arc->addBrickWork(this->layers_[1].lines, porring_.get<0>(), origin_.get<1>());
  }

} // End of Namespace

