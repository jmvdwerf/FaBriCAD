
#include "TxtExporter.h"


namespace fabricad::converter
{
  void TxtExporter::handleProjectStart(fabricad::config::Project* project, std::string const& filename, std::ostream &out)
  {
    out << "Start project" << std::endl;
    out << "  Name : " << project->getName() << std::endl;
    out << "  Descr: " << project->getDescription() << std::endl;
  }

  void TxtExporter::handleProjectFinish(fabricad::config::Project* project, std::string const& filename, std::ostream &out)
  {
    out << "Finish project: " <<  project->getName() << std::endl;
  }

  void TxtExporter::handleBlueprintStart(fabricad::config::Blueprint* print, std::string const& filename, std::ofstream &out)
  {
    out << "  Blueprint: " << print->getName() << std::endl;
  }

  void TxtExporter::handleBlueprintFinish(fabricad::config::Blueprint* print, std::string const& filename, std::ofstream &out)
  {
    out << "  Finished blueprint: " << print->getName() << std::endl;
  }

  void TxtExporter::handleLayerStart(std::ostream &out, shapelayer const& layer)
  {
    out << "    Start Layer: " << layer.name << std::endl;
  }

  void TxtExporter::handleLayerFinish(std::ostream &out, shapelayer const& layer)
  {
    out << "    End Layer: " << layer.name << std::endl << std::endl;
  }

  void TxtExporter::handlePolygon(std::ostream &out, polygon const& polygon)
  {
    out << "      POLYGON: [";
    std::vector<point> const& points = polygon.outer();
    for(size_t i = 0 ; i < points.size() ; ++i )
    {
      handlePoint(out, points[i]);
      out << " ";
    }
    out << "];" << std::endl;
  }

  void TxtExporter::handleLinestring(std::ostream &out, linestring const& line)
  {
    out << "      LINESTRING: [";
    for(size_t i = 0 ; i < line.size() ; i++)
    {
      handlePoint(out, line[i]);
      out << " ";
    }
    out << "];" << std::endl;
  }

  void TxtExporter::handlePoint(std::ostream &out, point const& p)
  {
    out << p.get<0>() << ", " << p.get<1>();
  }
}
