#ifndef _FABRICAD_BLOCKS_ENGLISHBOND_H_
#define _FABRICAD_BLOCKS_ENGLISHBOND_H_

#include "Brickwall.h"

namespace fabricad::blocks
{

  class EnglishBond : public Brickwall
  {
  protected:
    void renderBricksFor(box const& bb, std::vector<linestring> &lines) override;
  };
}
#endif
