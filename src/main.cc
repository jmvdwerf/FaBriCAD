#include <iostream>
#include <fstream>

#include "../include/json.hpp"

#include "config/Project.h"
#include "config/Blueprint.h"
#include "config/JsonReader.h"

namespace config=fabricad::config;

using json=nlohmann::json;

int main()
{

  config::Project* p = config::JsonReader::read("examples/voorkant.fabricad");

  cout << p->toString();
  return 0;
}
