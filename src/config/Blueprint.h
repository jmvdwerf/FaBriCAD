#ifndef _FABRICAD_CONFIG_BLUEPRINT_H_
#define _FABRICAD_CONFIG_BLUEPRINT_H_

#include <string>
#include <vector>
#include <map>
#include "../blocks/BasicBuildingBlock.h"
#include "BaseElement.h"

using namespace std;
using namespace fabricad::blocks;

namespace fabricad::config
{
  class Blueprint : public BaseElement
  {
  public:
    ~Blueprint();

    std::string getId();
    Blueprint* setId(std::string id);

    std::string toString();

    std::vector<BasicBuildingBlock*> getBlocks();
    size_t getSize();
    Blueprint* addBlock(BasicBuildingBlock* block);

    // shapelayer getLayer(size_t layer);
    std::map<BasicBuildingBlock*,std::vector<shapelayer>> getLayers();

  private:
    void render();
    void initializeLayerSet(std::vector<shapelayer> &layers);

    std::string id_ = "";

    std::vector<BasicBuildingBlock*> blocks_;

    std::map<BasicBuildingBlock*, std::vector<shapelayer>> shapeElements_;

  };


}

#endif
