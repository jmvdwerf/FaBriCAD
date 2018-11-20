
#include <boost/range/adaptor/reversed.hpp>

#include "Exporter.h"


namespace fabricad::converter
{

  void Exporter::exportToFile(std::string const& filename, fabricad::config::Project* project)
  {
    currentProject = project;

    std::ofstream out;
    if (createInitialFile) {
      out.open(filename.c_str(), std::ofstream::out);
    }
    handleProjectStart(project, filename, out);

    for(fabricad::config::Blueprint* bp: project->getBlueprints())
    {
      handleBlueprint(bp, filename, out);
    }

    handleProjectFinish(project, filename, out);

    currentProject = NULL;
  }

  void Exporter::handleBlueprint(fabricad::config::Blueprint* print, std::string const& filename, std::ofstream &out)
  {
    currentBlueprint = print;

    handleBlueprintStart(print, filename, out);

    handleBlueprintLoop(print, filename, out);

    handleBlueprintFinish(print, filename, out);

    currentBlueprint = NULL;
  }

  void Exporter::handleBlueprintLoop(fabricad::config::Blueprint* print, std::string const& filename, std::ofstream &out)
  {
    for(auto &layer: print->getLayers())
    {
      handleBlock(layer, filename, out);
    }
  }

  void Exporter::handleBlock(std::pair<fabricad::blocks::BasicBuildingBlock*, std::vector<shapelayer>> const& blockItem, std::string const& filename, std::ostream &out)
  {
    currentBlock = blockItem.first;

    handleBlockStart(blockItem.first, filename, out);

    for(auto& layer: blockItem.second)
    {
      handleLayer(out, layer);
    }

    handleBlockFinish(blockItem.first, filename, out);

    currentBlock = NULL;
  }

  void Exporter::handleBlockStart(fabricad::blocks::BasicBuildingBlock*, std::string const& filename, std::ostream &out)
  {
  }

  void Exporter::handleBlockFinish(fabricad::blocks::BasicBuildingBlock*, std::string const& filename, std::ostream &out)
  {
  }

  void Exporter::handleProjectStart(fabricad::config::Project* project, std::string const& filename, std::ostream &out)
  {
  }

  void Exporter::handleProjectFinish(fabricad::config::Project* project, std::string const& filename, std::ostream &out)
  {
  }


  void Exporter::handleBlueprintStart(fabricad::config::Blueprint* print, std::string const& filename, std::ofstream &out)
  {
  }

  void Exporter::handleBlueprintFinish(fabricad::config::Blueprint* print, std::string const& filename, std::ofstream &out)
  {
  }


  void Exporter::handleLayer(std::ostream &out, shapelayer const& layer)
  {
    handleLayerStart(out, layer);
    if (reverseOrderPolygons) {
      for(auto& p : boost::adaptors::reverse(layer.polygons))
      {
        handlePolygon(out, p);
      }
    } else {
      for(auto& p: layer.polygons)
      {
        handlePolygon(out, p);
      }
    }
    if (reverseOrderLines) {
      for(auto& l: boost::adaptors::reverse(layer.lines))
      {
        handleLinestring(out, l);
      }
    } else {
      for(auto& l: layer.lines)
      {
        handleLinestring(out, l);
      }
    }
    handleLayerFinish(out, layer);
  }

  void Exporter::handleLayerStart(std::ostream &out, shapelayer const& layer)
  {
  }

  void Exporter::handleLayerFinish(std::ostream &out, shapelayer const& layer)
  {
  }

  fabricad::config::Blueprint* Exporter::getCurrentBlueprint()
  {
    return currentBlueprint;
  }

  fabricad::config::Project* Exporter::getCurrentProject()
  {
    return currentProject;
  }

  fabricad::blocks::BasicBuildingBlock* Exporter::getCurrentBlock()
  {
    return currentBlock;
  }
}
