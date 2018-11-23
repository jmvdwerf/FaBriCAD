#ifndef _FABRICAD_CONFIG_BASEELEMENT_H_
#define _FABRICAD_CONFIG_BASEELEMENT_H_

#include <string>

namespace fabricad::config
{

  class BaseElement
  {
  public:
    std::string getName();
    BaseElement* setName(std::string name);

    std::string getDescription();
    BaseElement* setDescription(std::string description);

    float getLineWidth();
    BaseElement* setLineWidth(float lineWidth);

    float getLineDepth();
    BaseElement* setLineDepth(float lineDepth);

    float getThickness();
    BaseElement* setThickness(float thickness);

    std::string getColor();
    BaseElement* setColor(std::string color);

    std::string getLineColor();
    BaseElement* setLineColor(std::string linecolor);

  private:
    std::string name_ = "";
    std::string description_ = "";

    std::string color_ = "royalblue";
    std::string linecolor_ = "red";

    float thickness_ = 0.0;
    float lineWidth_ = 1;
    float lineDepth_ = 0.4;

  };

}
#endif
