
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

  void JsonReader::parseBaseElement(BaseElement* b, json &j)
  {
    for (json::iterator it = j.begin(); it != j.end(); ++it)
    {
      // First get all properties
      std::string key = it.key();
      std::transform(key.begin(), key.end(), key.begin(), ::tolower);
      if (key =="name") {
        b->setName(it.value());
      } else if( key == "description" ) {
        b->setDescription(it.value());
      } else if (key == "config") {
        for(json::iterator bit = it.value().begin(); bit != it.value().end() ; ++bit)
        {
          std::string config = bit.key();
          std::transform(config.begin(), config.end(), config.begin(), ::tolower);
          if (config == "thickness") {
            b->setThickness(bit.value());
          } else if (config == "linewidth") {
            b->setLineWidth(bit.value());
          } else if (config == "linedepth") {
            b->setLineDepth(bit.value());
          } else if (config == "color") {
            b->setColor(bit.value());
          } else if (config == "linecolor") {
            b->setLineColor(bit.value());
          }
        }
      }
    }
  }

  Project* JsonReader::parseProject(json &j)
  {
    Project* p = new Project();
    parseBaseElement(p, j);

    for (json::iterator it = j.begin(); it != j.end(); ++it)
    {
      // First get all properties
      std::string key = it.key();
      std::transform(key.begin(), key.end(), key.begin(), ::tolower);
      if( key == "license" ) {
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
    parseBaseElement(bp, j.value());

    for(json::iterator it = j.value().begin(); it != j.value().end(); ++it)
    {
      std::string key = it.key();
      std::transform(key.begin(), key.end(), key.begin(), ::tolower);
      if (key == "blocks") {
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
    std::transform(type.begin(), type.end(), type.begin(), ::tolower);

    if (type == "polygon") {
      return parsePolygon(j);
    } else if (type == "rectangle") {
      return parseRectangle(j);
    } else if (type == "singleton") {
      polygon p;
      bg::append(p.outer(), parsePoint(j["point"]));
      bg::correct(p);
      return p;
    } else if (type == "ellipse") {
      return parseEllipse(j);
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

  polygon JsonReader::parseEllipse(json &j)
  {

    float a = 1;
    float b = 1;
    point c;
    float sides = 24;

    for(json::iterator it = j.begin(); it != j.end(); ++it)
    {
      std::string key = it.key();
      std::transform(key.begin(), key.end(), key.begin(), ::tolower);

      if (key == "a") {
        a = it.value();
      } else if (key == "b") {
        b = it.value();
      } else if (key == "origin") {
        c = parsePoint(it.value());
      } else if (key == "sides") {
        sides = it.value();
      }
    }

    return ellipseToPolygon(c, a, b, sides);
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
      // } else if (type == "ellipslintel") {
      //  block = JsonReader::parseEllipsLintel(j.value());
      } else if (type == "arclintel") {
        block = JsonReader::parseArcLintel(j.value());
      } else if (type == "simpleroof") {
        block = JsonReader::parseSimpleRoof(j.value());
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
    parseBaseElement(block, j);

    for(json::iterator it = j.begin(); it != j.end(); ++it)
    {
      std::string key = it.key();
      std::transform(key.begin(), key.end(), key.begin(), ::tolower);

      if (key == "type") {
        block->setType(it.value());
      } else if (key == "shape") {
        block->setShape( parseGeometry(it.value()) );
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
      std::transform(key.begin(), key.end(), key.begin(), ::tolower);
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

  fabricad::blocks::ArcLintel* JsonReader::parseArcLintel(json &j)
  {
    fabricad::blocks::ArcLintel* lintel = new fabricad::blocks::ArcLintel();
    parseBaseElement(lintel, j);

    for(json::iterator it = j.begin(); it != j.end(); ++it)
    {
      std::string key = it.key();
      std::transform(key.begin(), key.end(), key.begin(), ::tolower);

      if (key == "config")
      {
        for(json::iterator cfg = it.value().begin() ; cfg != it.value().end() ; ++cfg)
        {
          std::string config = cfg.key();
          std::transform(config.begin(), config.end(), config.begin(), ::tolower);

          if (config == "brickheight") {
            lintel->brickHeight(cfg.value());
          } else if (config == "brickwidth") {
            lintel->brickWidth(cfg.value());
          } else if (config  == "arclength") {
            lintel->arcLength(cfg.value());
          } else if (config == "rays") {
            lintel->rays(cfg.value());
          }
        }
      } else if (key == "type") {
        lintel->setType(it.value());
      } else if (key == "shape") {
        lintel->setShape(parseGeometry(it.value() ) );
      }
    }
    return lintel;
  }



  /*
  fabricad::blocks::EllipsLintel* JsonReader::parseEllipsLintel(json &j)
  {
    fabricad::blocks::EllipsLintel* lintel = new fabricad::blocks::EllipsLintel();
    parseEllipsLintelParameters(lintel, j);
    return lintel;
  }
  */

  /*
  void JsonReader::parseEllipsLintelParameters(fabricad::blocks::EllipsLintel* lintel, json &j)
  {
    parseBaseElement(lintel, j);
    for(json::iterator it = j.begin(); it != j.end(); ++it)
    {
      std::string key = it.key();
      std::transform(key.begin(), key.end(), key.begin(), ::tolower);

      if (key == "config") {
        for(json::iterator props = it.value().begin(); props != it.value().end() ; ++props)
        {
          std::string propkey = props.key();
          std::transform(propkey.begin(), propkey.end(), propkey.begin(), ::tolower);
          if (propkey == "minoraxis" || propkey == "a") {
            lintel->minorAxis(props.value());
          } else if (propkey == "majoraxis" || propkey == "b") {
            lintel->majorAxis(props.value());
          } else if (propkey == "capstone") {
            for(json::iterator cpr = props.value().begin(); cpr != props.value().end(); ++cpr)
            {
              std::string cpkey = cpr.key();
              std::transform(cpkey.begin(), cpkey.end(), cpkey.begin(), ::tolower);
              if (cpkey == "width") {
                lintel->capstoneWidth(cpr.value());
              } else if (cpkey == "stones") {
                lintel->nrOfStonesInCapstone(cpr.value());
              } else if (cpkey == "usebond") {
                lintel->fillCapstone(cpr.value());
              }
            }
          } else if (propkey == "springers") {
            for(json::iterator cpr = props.value().begin(); cpr != props.value().end(); ++cpr)
            {
              std::string cpkey = cpr.key();
              std::transform(cpkey.begin(), cpkey.end(), cpkey.begin(), ::tolower);
              if (cpkey == "width") {
                lintel->springerWidth(cpr.value());
              } else if (cpkey == "stones") {
                lintel->nrOfStonesInSpringer(cpr.value());
              } else if (cpkey == "usebond") {
                lintel->fillSpringer(cpr.value());
              } else if (cpkey == "count") {
                lintel->springers(cpr.value());
              }
            }
          } else if (propkey == "brickwork") {
            for(json::iterator cpr = props.value().begin(); cpr != props.value().end(); ++cpr)
            {
              std::string cpkey = cpr.key();
              std::transform(cpkey.begin(), cpkey.end(), cpkey.begin(), ::tolower);
              if (cpkey == "maxwidth") {
                lintel->maxBrickWidth(cpr.value());
              } else if (cpkey == "stones") {
                lintel->stones(cpr.value());
              } else if (cpkey == "usebond") {
                lintel->useBond(cpr.value());
              } else if (cpkey == "height") {
                lintel->brickHeight(cpr.value());
              }
            }
          }
        }
      }

    }
  }
  */

  fabricad::blocks::SimpleRoof* JsonReader::parseSimpleRoof(json &j)
  {
    fabricad::blocks::SimpleRoof* roof = new fabricad::blocks::SimpleRoof();
    parseBaseElement(roof, j);
    parseBaseRoofElement(roof, j);

    return roof;
  }

  void JsonReader::parseBaseRoofElement(fabricad::blocks::SimpleRoof* roof, json &j) {
    for(json::iterator it = j.begin(); it != j.end(); ++it)
    {
      std::string key = it.key();
      std::transform(key.begin(), key.end(), key.begin(), ::tolower);
      if (key == "config") {
        for(json::iterator props = it.value().begin(); props != it.value().end() ; ++props)
        {
          std::string propkey = props.key();
          std::transform(propkey.begin(), propkey.end(), propkey.begin(), ::tolower);
          if (propkey == "tilewidth") {
            roof->setTileWidth(props.value());
          } else if (propkey == "tileheight") {
            roof->setTileHeight(props.value());
          } else if (propkey == "tiledepth") {
            roof->setTileDepth(props.value());
          } else if (propkey == "milldiameter") {
            roof->setMillDiameter(props.value());
          }
        }
      } else if (key == "shape") {
        roof->setShape(parseGeometry(it.value() ) );
      }

    }
  }

  void JsonReader::parseWallParameters(Brickwall* wall, json &j)
  {
    parseBaseElement(wall, j);
    for(json::iterator it = j.begin(); it != j.end(); ++it)
    {
      std::string key = it.key();
      std::transform(key.begin(), key.end(), key.begin(), ::tolower);
      if (key == "type") {
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
      }
    }
  }

}
