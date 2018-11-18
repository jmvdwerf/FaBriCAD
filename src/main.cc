#include <iostream>
#include <fstream>
#include <vector>

#include <getopt.h>

#include "../include/json.hpp"

#include "config/Project.h"
#include "config/Blueprint.h"
#include "config/JsonReader.h"
#include "shapes/shapes.h"

#include "converter/Exporter.h"
#include "converter/TxtExporter.h"

namespace config=fabricad::config;

using json=nlohmann::json;

struct outputType {
  std::string type;
  std::string output;
};

std::string program;


void printHelp(std::string error)
{
  if (error.size() > 0) {
    std::cout << "[ERROR]: " << error << std::endl << std::endl;
  }
  std::cout << "Usage: " << program << " <input> (-T<output> <filename>)+" << std::endl;
  std::cout << std::endl;
  std::cout << "<input> is a .fabricad-json file. Output is either: " << std::endl;
  std::cout << "\tsvg\tfilename is the start string of each blueprint" << std::endl;
  std::cout << std::endl;
  std::cout << "(c) 2018 Jan Martijn van der Werf (janmartijn@vdwerf.eu)" << std::endl;
}

int main(int argc, char* argv[])
{
  std::vector<outputType> outputs;
  std::string filename;

  program = argv[0];

  size_t counter = 1;
  while(counter < argc)
  {
    std::string argument = argv[counter];
    std::cout << "Argument: " << argument << std::endl;
    if (argument.substr(0,2) == "-h") {
      printHelp("");
      return 0;
    }
    if (argument.substr(0,2) == "-T") {
      // Next letters are the output type
      // Next argument is the corresponding output type
      counter++;
      if (counter >= argc) {
        printHelp("No argument for -T");
        return 3;
      }
      outputs.push_back({argument.substr(2), argv[counter]});
    } else {
      filename = argument;
    }
    counter++;
  }

  if ((filename.empty()) || outputs.empty()) {
    printHelp("");
    return 2;
  }

  config::Project* p = config::JsonReader::read(filename);
  std::vector<config::Blueprint*> bp = p->getBlueprints();

  for(auto& t: outputs)
  {
    if (t.type == "txt") {
      fabricad::converter::Exporter* exp = new fabricad::converter::TxtExporter();
      exp->exportToFile(t.output, p);
    } else if (t.type == "svg") {
      std::cout << "Create output: " << t.type << std::endl;
      for(size_t i = 0 ; i < bp.size() ; i++)
      {
        std::cout << "  * " << bp[i]->getName() << std::endl;
        createSVGFile(t.output + "_" + bp[i]->getId() +".svg", bp[i]->getLayers());
      }
    } else {
      std::cout << "Unknown type: " << t.type << std::endl;
    }
  }

  return 0;
}
