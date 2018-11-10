
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

  layer BasicBuildingBlock::getLayer(size_t l)
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

  std::vector<layer> BasicBuildingBlock::getLayers()
  {
    if (layers_.empty()) {
      render();
    }

    return layers_;
  }

  /**
   * Most simple render, just add our shape to the
   * first layer
   */
  void BasicBuildingBlock::render()
  {
    layers_.clear();
    layer l;
    l.elements.push_back(shape_);
    l.id = getId();
    l.name = getName();
    layers_.push_back(l);

    layer b;
    b.id = getId() + "_bricks";
    b.name = getName() + ": Bricks";
    layers_.push_back(b);

    layer c;
    c.id = getId() + "_cutouts";
    c.name = getName() + ": Cut outs";
    layers_.push_back(c);

    layer d;
    d.id = getId() + "_other";
    d.name = getName() + ": Other elements";
    layers_.push_back(d);

  }

}
