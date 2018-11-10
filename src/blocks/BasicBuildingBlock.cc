
#include "BasicBuildingBlock.h"

namespace fabricad::blocks {

  BasicBuildingBlock::BasicBuildingBlock()
  {
    id_   = "";
    name_ = "";
    type_ = "";
  }

  string BasicBuildingBlock::getId()
  {
    return id_;
  }

  BasicBuildingBlock* BasicBuildingBlock::setId(string id)
  {
    id_ = id;
    return this;
  }

  string BasicBuildingBlock::getName()
  {
    return name_;
  }


  BasicBuildingBlock* BasicBuildingBlock::setName(string name)
  {
    name_ = name;
    return this;
  }

  string BasicBuildingBlock::getType()
  {
    return type_;
  }


  BasicBuildingBlock* BasicBuildingBlock::setType(string type)
  {
    type_ = type;
    return this;
  }


  std::string BasicBuildingBlock::toString()
  {
    return toString("");
  }

  std::string BasicBuildingBlock::toString(std::string indent)
  {
    return
      indent + "Name: " + getName() + "\n" +
      indent + "Type: " + getType() + "\n" ;
  }

  geometry BasicBuildingBlock::getShape()
  {
    return shape_;
  }

  BasicBuildingBlock* BasicBuildingBlock::setShape(geometry shape)
  {
    shape_ = shape;
    return this;
  }

  std::vector<geometry> BasicBuildingBlock::getLayer(size_t layer)
  {
    if (layers_.empty()) {
      render();
    }

    // check bounds
    if (layer < 0 || layer >= layers_.size() ) {
      return {};
    }

    return layers_.at(layer);
  }

  /**
   * Most simple render, just add our shape to the
   * first layer
   */
  void BasicBuildingBlock::render()
  {
    layers_.clear();
    std::vector<geometry> layer;
    layer.push_back(shape_);
    layers_.push_back(layer);
  }

}
