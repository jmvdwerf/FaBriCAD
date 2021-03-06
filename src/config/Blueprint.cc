
#include <iostream>

#include "Blueprint.h"

namespace fabricad::config
{

  std::string Blueprint::getId()
  {
    return id_;
  }

  Blueprint* Blueprint::setId(std::string id)
  {
    id_ = id;
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

/*
  shapelayer Blueprint::getLayer(size_t layer)
  {
    if (layers_.empty()) {
      render();
    }

    return layers_.at(layer);
  }
*/
  std::map<BasicBuildingBlock*, std::vector<shapelayer>> Blueprint::getLayers()
  {
    if (shapeElements_.empty()) {
      render();
    }
    return shapeElements_;
  }

  void Blueprint::render()
  {
    shapeElements_.clear();

    std::vector<polygon> tocheck;

    // Walk the blocks, and add all shapes
    // We walk the list backwards, so that we can check with the
    // previous shapes what to exclude
    // for(size_t index = 0 ; index < blocks_.size() ; index++)
    for(auto& block: blocks_)
    {
      // size_t index = blocks_.size() -1 - i;
      // std::cout << "Working on: " << block->getName() << std::endl;
      std::vector<shapelayer> layers;
      initializeLayerSet(layers);

      for(size_t layer = 0 ; layer < 4 ; layer++) {

        size_t l = 3 - layer;
        if (!block->getLayer(l).lines.empty()) {
          // here we want to calculate the difference of each element
          // with the elements of the first layer, as that layer contains
          // the outside shapes.
          std::vector<linestring> lines;
          calculateDifference(block->getLayer(l).lines, tocheck, &lines);
          linestringmerge(&layers[l].lines, lines);
        }

        if (!block->getLayer(l).polygons.empty()) {
          // Polygons we always add, independent of whether they overlap
          // or not.
          polygonmerge(&layers[l].polygons, block->getLayer(l).polygons);

          if (l == 0) {
            polygonmerge(&tocheck, block->getLayer(l).polygons);
          }
        }
      }
      // Add block-layers pair to the shapeElements
      shapeElements_.insert( std::pair<BasicBuildingBlock*, std::vector<shapelayer>>(block, layers));
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

  void Blueprint::initializeLayerSet(std::vector<shapelayer> &layers)
  {
    shapelayer shapes;
    shapes.id = "shapes";
    shapes.name = "Shapes";
    layers.push_back(shapes);

    shapelayer bricks;
    bricks.id = "bricks";
    bricks.name = "Brickwork";
    layers.push_back(bricks);

    shapelayer cutout;
    cutout.id = "cutout";
    cutout.name = "Cutouts";
    layers.push_back(cutout);

    shapelayer other;
    other.id = "other";
    other.name = "Other shapes";
    layers.push_back(other);
  }
}
