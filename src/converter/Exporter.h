#ifndef _FABRICAD_CONVERTER_EXPORTER_H_
#define _FABRICAD_CONVERTER_EXPORTER_H_


#include <string>
#include <ostream>
#include <fstream>

#include "../config/Project.h"
#include "../config/Blueprint.h"
#include "../blocks/BasicBuildingBlock.h"
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
    virtual void handleProjectStart(fabricad::config::Project* project, std::string const& filename, std::ostream &out);
    virtual void handleProjectFinish(fabricad::config::Project* project, std::string const& filename, std::ostream &out);

    virtual void handleBlueprintStart(fabricad::config::Blueprint* print, std::string const& filename, std::ofstream &out);
    virtual void handleBlueprintLoop(fabricad::config::Blueprint* print, std::string const& filename, std::ofstream &out);
    virtual void handleBlueprintFinish(fabricad::config::Blueprint* print, std::string const& filename, std::ofstream &out);

    virtual void handleBlockStart(fabricad::blocks::BasicBuildingBlock*, std::string const& filename, std::ostream &out);
    virtual void handleBlockFinish(fabricad::blocks::BasicBuildingBlock*, std::string const& filename, std::ostream &out);

    virtual void handleLayerStart(std::ostream &out, shapelayer const& layer);
    virtual void handleLayerFinish(std::ostream &out, shapelayer const& layer);

    virtual void handlePolygon(std::ostream &out, polygon const& p) = 0;
    virtual void handleLinestring(std::ostream &out, linestring const& l) = 0;
    virtual void handlePoint(std::ostream &out, point const& p) = 0;

    bool createInitialFile = true;
    bool reverseOrderPolygons = false;
    bool reverseOrderLines = false;

    fabricad::config::Blueprint* getCurrentBlueprint();
    fabricad::config::Project* getCurrentProject();
    fabricad::blocks::BasicBuildingBlock* getCurrentBlock();

    void handleBlueprint(fabricad::config::Blueprint* print, std::string const& filename, std::ofstream &out);
    void handleBlock(std::pair<fabricad::blocks::BasicBuildingBlock*, std::vector<shapelayer>> const& blockItem, std::string const& filename, std::ostream &out);
    void handleLayer(std::ostream &out, shapelayer const& layer);

    std::string getColor();
    std::string getLineColor();
    float getThickness();
    float getLineDepth();
    float getLineWidth();

    std::string color_ = "royalblue";
    std::string linecolor_ = "red";

    float thickness_ = 8;
    float linewidth_ = 1;
    float linedepth_ = 0.4;

  private:
    fabricad::config::Blueprint* currentBlueprint;
    fabricad::config::Project* currentProject;
    fabricad::blocks::BasicBuildingBlock* currentBlock;

  };

}

#endif
