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
    size_t getStartRow();
    Brickwall* setStartRow(size_t start);

    std::string toString(std::string indent) override;
  protected:
    virtual void render() override;
    virtual void renderBricksFor(box const& bb, std::vector<linestring> &lines);
  private:
    float brick_height_;
    float brick_width_;
    size_t start_;
  };
}

#endif
