
#include <iostream>

#include "JsonReader.h"

namespace fabricad::config
{

  Project* JsonReader::read(const std::string filename)
  {
    std::ifstream i(filename);
    json j;
    i >> j;

    return JsonReader::parseProject(j);
  }

  Project* JsonReader::parseProject(json &j)
  {
    Project* p = new Project();

    for (json::iterator it = j.begin(); it != j.end(); ++it)
    {

      // First get all properties
      std::string key = it.key();
      if (key =="name") {
          p->setName(it.value());
      } else if( key == "description" ) {
          p->setDescription(it.value());
      } else if( key == "license" ) {
          p->setLicense(it.value());
      } else if( key == "author" ) {
          p->setAuthor(it.value());
      } else if( key == "version" ) {
          p->setVersion(it.value());
      }

      // Do all the blueprints
      if (key == "blueprints") {
        for (json::iterator bpit = it.value().begin(); bpit != it.value().end(); ++bpit)
        {
          p->addBlueprint(JsonReader::parseBlueprint(bpit));
        }
      }
    }


    return p;
  }

  Blueprint* JsonReader::parseBlueprint(json::iterator &j)
  {
    Blueprint* bp = new Blueprint();
    bp->setId(j.key());

    for(json::iterator it = j.value().begin(); it != j.value().end(); ++it)
    {
      std::string key = it.key();

      if (key == "name") {
        bp->setName(it.value());
      } else if (key == "description") {
        bp->setDescription(it.value());
      } else if (key == "blocks") {
        for(json::iterator bit = it.value().begin(); bit != it.value().end() ; ++bit)
        {
          bp->addBlock(JsonReader::parseBlock(bit));
        }
      }
    }

    return bp;
  }


  polygon JsonReader::parseGeometry(json &j)
  {
    std::string type = "";
    if (j["type"].is_string()) {
      type = j["type"];
    }

    if (type == "polygon") {
      return parsePolygon(j);
    } else if (type == "rectangle") {
      return parseRectangle(j);
    } else if (type == "singleton") {
      polygon p;
      bg::append(p.outer(), parsePoint(j["point"]));
      bg::correct(p);
      return p;
    }

    polygon p1;
    bg::append(p1.outer(), point(0,0));
    bg::correct(p1);
    return p1;
  }

  polygon JsonReader::parsePolygon(json &j)
  {
    polygon p;
    for(json::iterator it = j["points"].begin(); it != j["points"].end(); ++it) {
      bg::append(p.outer(), parsePoint(it.value()));
    }
    bg::correct(p);
    return p;
  }

  polygon JsonReader::parseRectangle(json &j)
  {
    float x = j["origin"]["x"];
    float y = j["origin"]["y"];
    float h = j["height"];
    float w = j["width"];

    polygon p;
    bg::append(p.outer(), point(x    , y    ));
    bg::append(p.outer(), point(x + w, y    ));
    bg::append(p.outer(), point(x + w, y + h));
    bg::append(p.outer(), point(x    , y + h));

    bg::correct(p);
    return p;
  }

  point JsonReader::parsePoint(json &j)
  {
    return point(j["x"], j["y"]);
  }

  fabricad::blocks::BasicBuildingBlock* JsonReader::parseBlock(json::iterator &j)
  {
    fabricad::blocks::BasicBuildingBlock* block;

    if (j.value()["type"].is_string()) {
      std::string type = j.value()["type"];
      std::transform(type.begin(), type.end(), type.begin(), ::tolower);
      if (type == "brickwall") {
        block = JsonReader::parseBrickwall(j.value());
      } else if (type == "englishbond") {
        block = JsonReader::parseEnglishBondwall(j.value());
      } else if (type == "lintel") {
        block = JsonReader::parseLintel(j.value());
      } else {
        block = JsonReader::parseBasicBuildingBlock(j.value());
      }
    } else {
      block = JsonReader::parseBasicBuildingBlock(j.value());
    }

    block->setId(j.key());

    return block;
  }

  fabricad::blocks::BasicBuildingBlock* JsonReader::parseBasicBuildingBlock(json &j)
  {
    fabricad::blocks::BasicBuildingBlock* block = new fabricad::blocks::BasicBuildingBlock();

    for(json::iterator it = j.begin(); it != j.end(); ++it)
    {
      std::string key = it.key();

      if (key == "name") {
        block->setName(it.value());
      } else if (key == "type") {
        block->setType(it.value());
      } else if (key == "shape") {
        block->setShape( parseGeometry(it.value()) );
      } else if (key == "color") {
        block->setColor(it.value());
      } else if (key == "thickness") {
        block->setThickness(it.value());
      }
    }

    return block;
  }

  fabricad::blocks::Brickwall* JsonReader::parseBrickwall(json &j)
  {
    fabricad::blocks::Brickwall* wall = new fabricad::blocks::Brickwall();
    parseWallParameters(wall, j);

    return wall;
  }

  fabricad::blocks::EnglishBond* JsonReader::parseEnglishBondwall(json &j)
  {
    fabricad::blocks::EnglishBond* wall = new fabricad::blocks::EnglishBond();
    parseWallParameters(wall, j);

    return wall;
  }

  fabricad::blocks::Lintel* JsonReader::parseLintel(json &j)
  {
    fabricad::blocks::Lintel* lintel = new fabricad::blocks::Lintel();
    parseWallParameters(lintel, j);

    for(json::iterator it = j["config"].begin(); it != j["config"].end(); ++it)
    {
      std::string key = it.key();
      if (key == "center") {
        lintel->setCenter(parsePoint(it.value()));
      } else if (key == "strikingpoint") {
        lintel->setStrikingPoint(parsePoint(it.value()));
      } else if (key == "stones") {
        lintel->setStones(it.value());
      } else if (key == "height") {
        lintel->setHeight(it.value());
      } else if (key == "width") {
        lintel->setWidth(it.value());
      }
    }

    return lintel;
  }

  void JsonReader::parseWallParameters(Brickwall* wall, json &j)
  {
        for(json::iterator it = j.begin(); it != j.end(); ++it)
    {
      std::string key = it.key();

      if (key == "name") {
        wall->setName(it.value());
      } else if (key == "type") {
        wall->setType(it.value());
      } else if (key == "config") {
        for(json::iterator props = it.value().begin(); props != it.value().end() ; ++props)
        {
          std::string propkey = props.key();
          if (propkey == "brickheight") {
            wall->setBrickHeight(props.value());
          } else if (propkey == "brickwidth") {
            wall->setBrickWidth(props.value());
          } else if (propkey == "start") {
            wall->setStartRow(props.value());
          }
        }
      } else if (key == "shape") {
        wall->setShape(parseGeometry(it.value() ) );
      } else if (key == "color") {
        wall->setColor(it.value());
      } else if (key == "thickness") {
        wall->setThickness(it.value());
      }
    }
  }

}
