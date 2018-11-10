#ifndef _FABRICAD_BLOCKS_BRICKWALL_H_
#define _FABRICAD_BLOCKS_BRICKWALL_H_


#include "BasicBuildingBlock.h"

namespace fabricad::blocks
{

  class Brickwall : public BasicBuildingBlock
  {
  public:
    Brickwall();

    float getBrickHeight();
    Brickwall* setBrickHeight(float height);
    float getBrickWidth();
    Brickwall* setBrickWidth(float width);
    float getAngle();
    Brickwall* setAngle(float angle);
    int getStartRow();
    Brickwall* setStartRow(int start);

    std::string toString(std::string indent) override;
  private:
    float brick_height_;
    float brick_width_;
    float angle_;
    int start_;
  };
}

#endif
