#ifndef _FABRICAD_CONFIG_JSONREADER_H_
#define _FABRICAD_CONFIG_JSONREADER_H_

#include <fstream>
#include <string>

#include "Project.h"
#include "Blueprint.h"
#include "../blocks/BasicBuildingBlock.h"
#include "../blocks/Brickwall.h"
#include "../blocks/EnglishBond.h"
#include "../blocks/Lintel.h"
#include "../blocks/ArcLintel.h"
#include "../blocks/SimpleRoof.h"

#include "../../include/json.hpp"
using json=nlohmann::json;


namespace fabricad::config
{

  class JsonReader
  {
  public:
    static Project* read(const std::string filename);

  private:
    static Project* parseProject(json &j);
    static Blueprint* parseBlueprint(json::iterator &j);
    static fabricad::blocks::BasicBuildingBlock* parseBlock(json::iterator &j);
    static fabricad::blocks::BasicBuildingBlock* parseBasicBuildingBlock(json &j);
    static fabricad::blocks::Brickwall* parseBrickwall(json &j);
    static fabricad::blocks::EnglishBond* parseEnglishBondwall(json &j);
    static fabricad::blocks::Lintel* parseLintel(json &j);
    static fabricad::blocks::ArcLintel* parseArcLintel(json &j);
    static fabricad::blocks::SimpleRoof* parseSimpleRoof(json &j);
    // static fabricad::blocks::EllipsLintel* parseEllipsLintel(json &j);
    static void parseBaseRoofElement(fabricad::blocks::SimpleRoof* roof, json &j);
    static void parseWallParameters(Brickwall *wall, json &j);
    static void parseBaseElement(BaseElement* b, json &j);
    // static void parseEllipsLintelParameters(fabricad::blocks::EllipsLintel* lintel, json &j);

    static polygon parseGeometry(json &j);
    static point parsePoint(json &j);
    static polygon parsePolygon(json &j);
    static polygon parseRectangle(json &j);
    static polygon parseEllipse(json &j);

  };

}

#endif
