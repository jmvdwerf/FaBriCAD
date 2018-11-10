
#include "Blueprint.h"

namespace fabricad::config
{
  std::string Blueprint::getName()
  {
    return name_;
  }

  Blueprint* Blueprint::setName(std::string name)
  {
    name_ = name;
    return this;
  }

  std::string Blueprint::getId()
  {
    return id_;
  }

  Blueprint* Blueprint::setId(std::string id)
  {
    id_ = id;
    return this;
  }

  std::string Blueprint::getDescription()
  {
    return description_;
  }

  Blueprint* Blueprint::setDescription(std::string description)
  {
    description_ = description;
    return this;
  }

  std::string Blueprint::toString()
  {
    std::string s =
      "id         : " + getId()          + "\n" +
      "Name       : " + getName()        + "\n" +
      "Description: " + getDescription() + "\n" +
      "Blocks     :\n";

    for(size_t i = 0 ; i < blocks_.size() ; ++i)
    {
      s += blocks_[i]->toString("    ");
      s += "    ----------\n";
    }

    return s;
  }

  std::vector<BasicBuildingBlock*> Blueprint::getBlocks()
  {
    return blocks_;
  }

  size_t Blueprint::getSize()
  {
    return blocks_.size();
  }

  Blueprint* Blueprint::addBlock(BasicBuildingBlock* block)
  {
    blocks_.push_back(block);
    return this;
  }

  Blueprint::~Blueprint()
  {
    // Remove all blueprints
    size_t size = blocks_.size();
    for(size_t i = 0 ; i < size; ++i)
    {
      delete blocks_[i];
    }
  }
}
