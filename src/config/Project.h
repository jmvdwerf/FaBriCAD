#ifndef _FABRICAD_CONFIG_PROJECT_H_
#define _FABRICAD_CONFIG_PROJECT_H_

#include <string>
#include <vector>

using namespace std;

#include "Blueprint.h"

namespace fabricad::config
{

  class Project
  {
  public:
    Project();
    ~Project();
    // Getters and setters
    std::string getName();
    std::string getDescription();
    std::string getVersion();
    std::string getLicense();
    std::string getAuthor();
    Project* setName(std::string name);
    Project* setDescription(std::string description);
    Project* setVersion(std::string version);
    Project* setLicense(std::string license);
    Project* setAuthor(std::string author);

    std::vector<Blueprint*> getBlueprints();
    size_t getSize();
    Project* addBlueprint(Blueprint* blueprint);

    std::string toString();
  private:
    std::string name_;
    std::string description_;
    std::string version_;
    std::string license_;
    std::string author_;

    std::vector<Blueprint*> prints_;
  };

}

#endif
