
#include <boost/range/adaptor/reversed.hpp>
#include <boost/filesystem.hpp>
#include "Exporter.h"


namespace fabricad::converter
{

  void Exporter::exportToFile(std::string const& filename, fabricad::config::Project* project)
  {
    currentProject = project;

    std::ofstream out;
    if (createInitialFile) {
      // if file name exists, rename by adding a number to it

      boost::filesystem::path file(filename.c_str());
      std::string newfilename = filename;
      /*
      if (boost::filesystem::exists(file)) {
        std::string ext(file.extension().c_str());
        std::string startfilename = filename.substr(0, filename.rfind(ext));
        size_t n = 1;
        newfilename = startfilename + "-" + std::to_string(n) + ext;
        while(boost::filesystem::exists(newfilename))
        {
          ++n;
          newfilename = startfilename + "-" + std::to_string(n) + ext;
        }
      }
      */
      out.open(newfilename.c_str(), std::ofstream::out);
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

  std::string Exporter::getColor()
  {
    if (getCurrentBlock()->getColor().empty()) {
      if (getCurrentBlueprint()->getColor().empty()) {
        if(getCurrentProject()->getColor().empty()) {
          return color_;
        } else {
          return getCurrentProject()->getColor();
        }
      } else {
        return getCurrentBlueprint()->getColor();
      }
    } else {
      return getCurrentBlock()->getColor();
    }
  }

  std::string Exporter::getLineColor()
  {
    if (getCurrentBlock()->getLineColor().empty()) {
      if (getCurrentBlueprint()->getLineColor().empty()) {
        if(getCurrentProject()->getLineColor().empty()) {
          return linecolor_;
        } else {
          return getCurrentProject()->getLineColor();
        }
      } else {
        return getCurrentBlueprint()->getLineColor();
      }
    } else {
      return getCurrentBlock()->getLineColor();
    }
  }

  float Exporter::getThickness()
  {
    if (getCurrentBlock()->getThickness() == 0.0) {
      if (getCurrentBlueprint()->getThickness() == 0.0) {
        if(getCurrentProject()->getThickness() == 0.0) {
          return thickness_;
        } else {
          return getCurrentProject()->getThickness();
        }
      } else {
        return getCurrentBlueprint()->getThickness();
      }
    } else {
      return getCurrentBlock()->getThickness();
    }
  }

  float Exporter::getLineDepth()
  {
    if (getCurrentBlock()->getLineDepth() == 0) {
      if (getCurrentBlueprint()->getLineDepth() == 0) {
        if(getCurrentProject()->getLineDepth() == 0.0) {
          return linedepth_;
        } else {
          return getCurrentProject()->getLineDepth();
        }
      } else {
        return getCurrentBlueprint()->getLineDepth();
      }
    } else {
      return getCurrentBlock()->getLineDepth();
    }
  }

  float Exporter::getLineWidth()
  {
    if (getCurrentBlock()->getLineWidth() == 0) {
      if (getCurrentBlueprint()->getLineWidth() == 0) {
        if(getCurrentProject()->getLineWidth() == 0.0) {
          return linewidth_;
        } else {
          return getCurrentProject()->getLineWidth();
        }
      } else {
        return getCurrentBlueprint()->getLineWidth();
      }
    } else {
      return getCurrentBlock()->getLineWidth();
    }
  }

}
