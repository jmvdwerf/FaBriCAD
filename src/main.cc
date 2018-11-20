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
  std::string filename;

  program = argv[0];

  size_t counter = 1;
  while(counter < argc)
  {
    std::string argument = argv[counter];

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

  for(auto& t: outputs)
  {
    // First check if output dir exists
    boost::filesystem::path outputdir(t.output.c_str());
    if (!outputdir.parent_path().empty()) {
      boost::filesystem::create_directories(outputdir.parent_path());
    }

    if (t.type == "txt") {
      fabricad::converter::Exporter* exp = new fabricad::converter::TxtExporter();
      exp->exportToFile(t.output, p);
    } else if (t.type == "svg") {
      fabricad::converter::Exporter* exp = new fabricad::converter::SvgExporter();
      exp->exportToFile(t.output, p);
    } else if (t.type == "scad") {
      fabricad::converter::Exporter* exp = new fabricad::converter::ScadExporter();
      exp->exportToFile(t.output, p);
    } else {
      std::cout << "Unknown type: " << t.type << std::endl;
    }
  }

  return 0;
}
