
#include "BasicBuildingBlock.h"

namespace fabricad::blocks {

  BasicBuildingBlock::BasicBuildingBlock()
  {
    id_   = "";
    name_ = "";
    type_ = "";
    color_ = "royalblue";
    thickness_ = 0;
  }

  std::string BasicBuildingBlock::getColor()
  {
    return color_;
  }

  BasicBuildingBlock* BasicBuildingBlock::setColor(std::string color)
  {
    color_ = color;
    return this;
  }

  size_t BasicBuildingBlock::getThickness()
  {
    return thickness_;
  }

  BasicBuildingBlock* BasicBuildingBlock::setThickness(size_t thickness)
  {
    thickness_ = thickness;
    return this;
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

  polygon BasicBuildingBlock::getShape()
  {
    return shape_;
  }

  BasicBuildingBlock* BasicBuildingBlock::setShape(polygon const &shape)
  {
    shape_ = shape;
    return this;
  }

  shapelayer BasicBuildingBlock::getLayer(size_t layer)
  {
    if (layers_.empty()) {
      render();
    }

    return layers_.at(layer);
  }

  std::vector<shapelayer> BasicBuildingBlock::getLayers()
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
    shapelayer l;
    l.polygons.push_back(shape_);
    l.id = getId();
    l.name = getName();
    layers_.push_back(l);

    shapelayer b;
    b.id = getId() + "_bricks";
    b.name = getName() + ": Bricks";
    layers_.push_back(b);

    shapelayer c;
    c.id = getId() + "_cutouts";
    c.name = getName() + ": Cut outs";
    layers_.push_back(c);

    shapelayer d;
    d.id = getId() + "_other";
    d.name = getName() + ": Other elements";
    layers_.push_back(d);

  }

}
