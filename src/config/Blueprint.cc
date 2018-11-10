
#include <iostream>

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


  layer Blueprint::getLayer(size_t l)
  {
    if (layers_.empty()) {
      render();
    }

    // check bounds
    if (l < 0 || l >= layers_.size() ) {
      layer e;
      return e;
    }

    return layers_.at(l);
  }

  std::vector<layer> Blueprint::getLayers()
  {
    if (layers_.empty()) {
      render();
    }
    return layers_;
  }

  void Blueprint::render()
  {
    layers_.clear();

    layer shapes;
    shapes.id = "shapes";
    shapes.name = "Shapes";
    layers_.push_back(shapes);

    layer bricks;
    bricks.id = "bricks";
    bricks.name = "Brickwork";
    layers_.push_back(bricks);

    layer cutout;
    cutout.id = "cutout";
    cutout.name = "Cutouts";
    layers_.push_back(cutout);

    layer other;
    other.id = "other";
    other.name = "Other shapes";
    layers_.push_back(other);

    // Walk the blocks, and add all shapes
    // If an old shape is contained in a new shape, it should be removed
    for(size_t i = 0 ; i < blocks_.size() ; i++)
    {
      for(size_t l = 0 ; l < 4 ; l++) {
        layer items = blocks_[i]->getLayer(l);
        if (!items.elements.empty()) {
          layers_[l].elements.insert(layers_[l].elements.end(), items.elements.begin(), items.elements.end());
        }
      }
    }
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
