#ifndef _FABRICAD_BLOCKS_ARCLINTEL_H_
#define _FABRICAD_BLOCKS_ARCLINTEL_H_

#include "BasicBuildingBlock.h"


/**
 * This is a classical circle-based arc for a lintel
 *
 * The bricks:
 *   - width & height are as normal - horizontal - bricks (i.e., in the center position, a brick is vertically positioned)
 *   - For now, we only support the basic brickwall as a pattern
 *
 * For the lintel:
 *   - The shape specifies the width and height of the space *under* the lintel, i.e., the lower arc.
 *
 *  <       2a        >
 *  ________c_________<RU
 * ^|        |        |
 * b|        |        |
 *  |________|________|<RB
 *  ^        |
 *  origin_ O|
 *           |
 *           * porring_ P
 *
 *  Porring point is determined as follows:
 *    - Draw a line from C to RB
 *    - Draw a line ortogonal to line C-RB from point RU
 *    - Then tan(d) = b/a gives the angle between lines C-RB and O-RB.
 *    - This is the same angle as between lines C-P and RU-P.
 *    - Then line C-P has length: l === a / tan(d) === a / (b / a) === a^2 / b.
 *    - Then point P is determined by: P_x = O_x + a, P_y = O_x + b - l.
 *
 * The inner ellipse is given by:
 *    - semi-major axis: a
 *    - semi-minor axis: b
 * The outer ellipse is given by:
 *    - semi-major axis: a + arcLength;
 *    - semi-minor axis: a + arcLength;
 * For the number of bricks in the lintel, we use the circumference of the outer axis.
 */
namespace fabricad::blocks
{
  class ArcLintel : public BasicBuildingBlock
  {
  public:
    ArcLintel();

    float brickHeight();
    ArcLintel* brickHeight(float height);

    float brickWidth();
    ArcLintel* brickWidth(float width);

    float arcLength();
    ArcLintel* arcLength(float length);

    int rays();
    ArcLintel* rays(int rays);

    std::string toString(std::string indent) override;

    // Override of the setShape() to directly set all the other variables as well.
    BasicBuildingBlock* setShape(polygon const& shape) override;

  protected:
  	void render() override;

    // polygon ArcLintel::createOuterPolygon(point[][] points, int rows, int cols);
    // polygon ArcLintel::createOuterPolygon(point[][] points, point offset, int rows, int cols);
    // polygon ArcLintel::createOuterPolygon(point[][] points, float dx, float dy, int rows, int cols);
  private:

  	point origin_;

    float brick_height_; // The height of the brick
    float brick_width_;  // The width of the brick

    float arc_length_; // The length of the arc, i.e., how large the lintel will be

    int rays_; // The number of stones (rays) in the arc lintel

    float b_; // The height of the rectangle        -> semi-minor axis of the ellipse
    float a_; // Half of the width of the rectangle -> semi-major axis of the ellipse

    point porring_; // The porring point of the lintel
  };
}
#endif
