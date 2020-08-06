
#include "Blueprint.h"
#include "Project.h"

namespace fabricad::config
{
  Project::Project()
  {
    setName("Default Project");
    setDescription("Default project description");
    setVersion("0.1");
    setAuthor("Author");
    setLicense("MIT");
  }

  std::string Project::getVersion()
  {
    return version_;
  }

  Project* Project::setVersion(std::string version)
  {
    version_ = version;
    return this;
  }

  std::string Project::getAuthor()
  {
    return author_;
  }

  Project* Project::setAuthor(std::string author)
  {
    author_ = author;
    return this;
  }

  std::string Project::getLicense()
  {
    return license_;
  }

  Project* Project::setLicense(std::string license)
  {
    license_ = license;
    return this;
  }


  std::string Project::toString()
  {

    std::string s =
      "Name       : " + getName() + "\n" +
      "Description: " + getDescription() + "\n" +
      "Version    : " + getVersion() + "\n" +
      "License    : " + getLicense() + "\n" +
      "Author     : " + getAuthor() + "\n" +
      "---------------\n" +
      "Blueprints: " + std::to_string(prints_.size()) + "\n" +
      "---------------\n";

    size_t sz = prints_.size();
    for(size_t i = 0 ; i < sz ; ++i) {
      s += prints_[i]->toString() + "---------------\n";;
    }

    return s;
  }



  std::vector<Blueprint*> Project::getBlueprints()
  {
    return prints_;
  }

  Project* Project::addBlueprint(Blueprint* blueprint)
  {
    prints_.push_back(blueprint);
    return this;
  }


  Project::~Project()
  {
    // Remove all blueprints
    size_t size = prints_.size();
    for(size_t i = 0 ; i < size; ++i)
    {
      delete prints_[i];
    }
  }

}
