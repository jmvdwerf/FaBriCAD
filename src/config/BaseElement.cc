
#include "BaseElement.h"

namespace fabricad::config
{

  float BaseElement::getThickness()
  {
    return thickness_;
  }

  BaseElement* BaseElement::setThickness(float thickness)
  {
    thickness_ = thickness;
    return this;
  }

  float BaseElement::getLineWidth()
  {
    return lineWidth_;
  }

  BaseElement* BaseElement::setLineWidth(float lineWidth)
  {
    lineWidth_ = lineWidth;
    return this;
  }

  float BaseElement::getLineDepth()
  {
    return lineDepth_;
  }

  BaseElement* BaseElement::setLineDepth(float lineDepth)
  {
    lineDepth_ = lineDepth;
    return this;
  }


  std::string BaseElement::getName()
  {
    return name_;
  }

  BaseElement* BaseElement::setName(std::string name)
  {
    name_ = name;
    return this;
  }

  std::string BaseElement::getDescription()
  {
    return description_;
  }

  BaseElement* BaseElement::setDescription(std::string description)
  {
    description_ = description;
    return this;
  }

  std::string BaseElement::getColor()
  {
    return color_;
  }
  BaseElement* BaseElement::setColor(std::string color)
  {
    color_ = color;
    return this;
  }

  std::string BaseElement::getLineColor()
  {
    return linecolor_;
  }

  BaseElement* BaseElement::setLineColor(std::string linecolor)
  {
    linecolor_ = linecolor;
    return this;
  }

}
