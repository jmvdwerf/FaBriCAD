#ifndef _FABRICAD_CONFIG_PROJECT_H_
#define _FABRICAD_CONFIG_PROJECT_H_

#include <string>
#include <vector>

using namespace std;

#include "Blueprint.h"
#include "BaseElement.h"

namespace fabricad::config
{

  class Project : public BaseElement
  {
  public:
    Project();
    ~Project();
    // Getters and setters
    std::string getVersion();
    Project* setVersion(std::string version);

    std::string getLicense();
    Project* setLicense(std::string license);

    std::string getAuthor();
    Project* setAuthor(std::string author);

    std::vector<Blueprint*> getBlueprints();
    size_t getSize();
    Project* addBlueprint(Blueprint* blueprint);

    std::string toString();
  private:
    std::string version_;
    std::string license_;
    std::string author_;

    std::vector<Blueprint*> prints_;
  };

}

#endif
