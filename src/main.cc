#include <iostream>
#include <fstream>
#include <vector>

#include "../include/json.hpp"

#include "config/Project.h"
#include "config/Blueprint.h"
#include "config/JsonReader.h"
#include "shapes/shapes.h"

namespace config=fabricad::config;

using json=nlohmann::json;

int main()
{

  config::Project* p = config::JsonReader::read("examples/dorpshuis1/voorkant.fabricad");

  std::vector<config::Blueprint*> bp = p->getBlueprints();

  for(size_t i = 0 ; i < bp.size() ; i++)
  {
    cout << "Blueprint: " << bp[i]->getName() << std::endl;
    cout << "==========" << std::endl;

    createSVGFile(bp[i]->getId() +".svg", bp[i]->getLayers());
  }

  return 0;
}
