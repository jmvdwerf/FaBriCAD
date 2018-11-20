#ifndef _FABRICAD_CONVERTER_EXPORTER_H_
#define _FABRICAD_CONVERTER_EXPORTER_H_


#include <string>
#include <fstream>

#include "../config/Project.h"
#include "../config/Blueprint.h"

#include "../shapes/shapes.h"

namespace fabricad::converter
{

  /**
   * @brief      Exporter class is a base class for export functionality
   * It implements a visitor pattern on Project.
   * Methods to override configurations are:
   *   -  handleProjectStart
   *   -  handleProjectFinish
   *   -  handleBlueprintStart
   *   -  handleBlueprintFinish
   *
   * Notice that the above classes can use the *Start methods to create a new
   * File handle out.
   *
   * For handling shapes, one needs to override:
   *   -  handleLayerStart
   *   -  handleLayerFinish
   *
   * And exporting the shapes themselves:
   *   -  handlePolygon
   *   -  handleLinestring
   *   -  handlePoint
   *
   * As an example, the current implementation exports to TXT.
   */
  class Exporter
  {
  public:
    /**
     * @brief      Exports a project to a given file name
     *
     * @param      filename  The filename
     * @param      p         { parameter_description }
     */
    void exportToFile(std::string const& filename, fabricad::config::Project* p);

  protected:
    virtual void handleProjectStart(fabricad::config::Project* project, std::string const& filename, std::ofstream &out);
    virtual void handleProjectFinish(fabricad::config::Project* project, std::string const& filename, std::ofstream &out);

    virtual void handleBlueprintStart(fabricad::config::Blueprint* print, std::string const& filename, std::ofstream &out);
    virtual void handleBlueprintFinish(fabricad::config::Blueprint* print, std::string const& filename, std::ofstream &out);

    virtual void handleLayerStart(std::ofstream &out, shapelayer const& layer);
    virtual void handleLayerFinish(std::ofstream &out, shapelayer const& layer);

    virtual void handlePolygon(std::ofstream &out, polygon const& p) = 0;
    virtual void handleLinestring(std::ofstream &out, linestring const& l) = 0;
    virtual void handlePoint(std::ofstream &out, point const& p) = 0;
    bool createInitialFile = true;
    bool reverseOrderPolygons = false;
    bool reverseOrderLines = false;

    fabricad::config::Blueprint* getCurrentBlueprint();
    fabricad::config::Project* getCurrentProject();

  private:
    void handleBlueprint(fabricad::config::Blueprint* print, std::string const& filename, std::ofstream &out);
    void handleLayer(std::ofstream &out, shapelayer const& layer);

    fabricad::config::Blueprint* currentBlueprint;
    fabricad::config::Project* currentProject;
  };

}

#endif
