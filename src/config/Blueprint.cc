
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


  shapelayer Blueprint::getLayer(size_t layer)
  {
    if (layers_.empty()) {
      render();
    }

    return layers_.at(layer);
  }

  std::vector<shapelayer> Blueprint::getLayers()
  {
    if (layers_.empty()) {
      render();
    }
    return layers_;
  }

  void Blueprint::render()
  {
    layers_.clear();

    shapelayer shapes;
    shapes.id = "shapes";
    shapes.name = "Shapes";
    layers_.push_back(shapes);

    shapelayer bricks;
    bricks.id = "bricks";
    bricks.name = "Brickwork";
    layers_.push_back(bricks);

    shapelayer cutout;
    cutout.id = "cutout";
    cutout.name = "Cutouts";
    layers_.push_back(cutout);

    shapelayer other;
    other.id = "other";
    other.name = "Other shapes";
    layers_.push_back(other);

    // Walk the blocks, and add all shapes
    // We walk the list backwards, so that we can check with the
    // previous shapes what to exclude
    for(size_t index = 0 ; index < blocks_.size() ; index++)
    {
      // size_t index = blocks_.size() -1 - i;
      std::cout << "Working on   : " << blocks_[index]->getName() << std::endl;

      for(size_t layer = 0 ; layer < 4 ; layer++) {
        size_t l = 3 - layer;
        if (!blocks_[index]->getLayer(l).lines.empty()) {
          // here we want to calculate the difference of each element
          // with the elements of the first layer, as that layer contains
          // the outside shapes.
          std::vector<linestring> lines;
          std::cout << "I start with : " << blocks_[index]->getLayer(l).lines.size() << " elements" << std::endl;
          calculateDifference(blocks_[index]->getLayer(l).lines, layers_[0].polygons, &lines);
          std::cout << "I end up with: " << lines.size() << " elements" << std::endl;
          linestringmerge(&layers_[l].lines, lines);
        }

        if (!blocks_[index]->getLayer(l).polygons.empty()) {
          // Polugons we always add, independent of whether they overlap
          // or not.
          polygonmerge(&layers_[l].polygons, blocks_[index]->getLayer(l).polygons);
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
