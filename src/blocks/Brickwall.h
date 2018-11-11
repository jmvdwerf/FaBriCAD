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
    bool horizontal();
    Brickwall* setHorizontal(bool horizontal);
    int getStartRow();
    Brickwall* setStartRow(int start);

    std::string toString(std::string indent) override;
  protected:
    void render() override;

  private:
    float brick_height_;
    float brick_width_;
    bool horizontal_;
    int start_;
  };
}

#endif
