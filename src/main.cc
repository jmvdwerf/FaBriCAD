#define _USE_MATH_DEFINES
#include <cmath>

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
#include "converter/SvgExporter.h"
#include "converter/ScadExporter.h"

#include <boost/filesystem.hpp>

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
  std::vector<std::string> filenames;

  program = argv[0];

  size_t level = 20;

  size_t counter = 1;
  while(counter < argc)
  {
    std::string argument = argv[counter];

    if (argument.substr(0,2) == "-h") {
      printHelp("");
      return 0;
    } else if (argument == "--maxlevel" || argument == "-L") {
      counter++;
      if (counter >= argc) {
        printHelp("No argument for --maxlevel");
        return 4;
      }
      // std::cout << "Set max level to: " << argv[counter] << std::endl;
      level = std::stoi(argv[counter]);
    } else if (argument.substr(0,2) == "-T") {
      // Next letters are the output type
      // Next argument is the corresponding output type
      counter++;
      if (counter >= argc) {
        printHelp("No argument for -T");
        return 3;
      }
      outputs.push_back({argument.substr(2), argv[counter]});
    } else {
      // std::cout << "Added file: " << argument << std::endl ;
      filenames.push_back(argument);
    }


    counter++;
  }

  if ((filenames.empty()) || outputs.empty()) {
    printHelp("");
    return 2;
  }

  for(auto& filename: filenames)
  {
    config::Project* p = config::JsonReader::read(filename);

    for(auto& t: outputs)
    {
      // First check if output dir exists
      boost::filesystem::path outputdir(t.output.c_str());
      if (!outputdir.parent_path().empty()) {
        boost::filesystem::create_directories(outputdir.parent_path());
      }

      fabricad::converter::Exporter* exp;
      if (t.type == "txt") {
        exp = new fabricad::converter::TxtExporter();
      } else if (t.type == "svg") {
        exp = new fabricad::converter::SvgExporter();
      } else if (t.type == "scad") {
        exp = new fabricad::converter::ScadExporter();
      } else {
        std::cout << "Unknown type: " << t.type << std::endl;
        return 3;
      }

      exp->setMaxLevel(level);
      exp->exportToFile(t.output, p);

    }
  }

  return 0;
}
