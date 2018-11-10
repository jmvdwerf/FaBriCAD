#ifndef _FABRICAD_CONFIG_BLUEPRINT_H_
#define _FABRICAD_CONFIG_BLUEPRINT_H_

#include <string>
#include <vector>
#include "../blocks/BasicBuildingBlock.h"

using namespace std;
using namespace fabricad::blocks;

namespace fabricad::config
{
  class Blueprint
  {
  public:
    ~Blueprint();

    std::string getName();
    Blueprint* setName(std::string name);
    std::string getId();
    Blueprint* setId(std::string id);
    std::string getDescription();
    Blueprint* setDescription(std::string description);

    std::string toString();

    std::vector<BasicBuildingBlock*> getBlocks();
    size_t getSize();
    Blueprint* addBlock(BasicBuildingBlock* block);

  private:
    std::string name_;
    std::string description_;
    std::string id_;

    std::vector<BasicBuildingBlock*> blocks_;
  };


}

#endif