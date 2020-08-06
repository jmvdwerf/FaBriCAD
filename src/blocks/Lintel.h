#ifndef _FABRICAD_BLOCKS_LINTEL_H_
#define _FABRICAD_BLOCKS_LINTEL_H_

#include "Brickwall.h"

namespace fabricad::blocks
{

  class Lintel : public Brickwall
  {
  public:
    point getCenter();
    Lintel* setCenter(point center);

    float getHeight();
    Lintel* setHeight(float height);

    float getWidth();
    Lintel* setWidth(float width);

    size_t getStones();
    Lintel* setStones(size_t stones);

    point getStrikingPoint();
    Lintel* setStrikingPoint(point strikingPoint);

  protected:
    void render() override;

  private:
    point strikingpoint_;
    point center_;

    float height_;
    float width_;
    size_t stones_;
  };

}

#endif
