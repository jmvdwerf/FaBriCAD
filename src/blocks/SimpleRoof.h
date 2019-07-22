#ifndef _FABRICAD_BLOCKS_SIMPLEROOF_H_
#define _FABRICAD_BLOCKS_SIMPLEROOF_H_

#include "BasicRoof.h"

namespace fabricad::blocks
{

  class SimpleRoof : public BasicRoof
  {
    public:
      SimpleRoof();

    protected:
      virtual void renderRoof() override;
  };


}

#endif
