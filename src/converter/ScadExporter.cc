
#include <vector>
#include "ScadExporter.h"

namespace fabricad::converter
{
  ScadExporter::ScadExporter()
  {
    reverseOrderPolygons = true;
  }

  void ScadExporter::handleBlueprintStart(fabricad::config::Blueprint* print, std::string const& filename, std::ofstream &out)
  {
    out << "/*" << std::endl << " * Blueprint: " << print->getName() << std::endl;
    out << " * description: " << print->getDescription() << std::endl;
    out << " */" << std::endl;
    out << "module " << print->getId() << "()" << std::endl;
    out << "{" << std::endl << "\tdifference()" << std::endl << "\t{" << std::endl;
  }

  void ScadExporter::handleBlueprintFinish(fabricad::config::Blueprint* print, std::string const& filename, std::ofstream &out)
  {
    out << "\t} // END OF: difference()" << std::endl;
    out << "} // END OF: " << print->getName() << std::endl << std::endl;
  }

  /*
  void ScadExporter::handleLayerStart(std::ofstream &out, shapelayer const& layer)
  {
    out << "\t<g id=\"" << layer.id << "\" ";
    out << "inkscape:label=\"" << layer.name << "\" ";
    out << "inkscape:groupmode=\"layer\" >" << std::endl;
  }

  void ScadExporter::handleLayerFinish(std::ofstream &out, shapelayer const& layer)
  {
    out << "</g>" << std::endl;
  }
  */

  void ScadExporter::handlePolygon(std::ofstream &out, polygon const& p)
  {
    std::vector<point> const& points = p.outer();
    out << "\tpolygon( [ " << std::endl;
    out << "\t\t";
    handlePoint(out, points[0]);
    for(size_t i = 1 ; i < points.size() ; ++i )
    {
      out << "," << std::endl << "\t\t";
      handlePoint(out, points[i]);
    }
    out << std::endl << "\t] );" << std::endl << std::endl;
  }

  void ScadExporter::handleLinestring(std::ofstream &out, linestring const& l)
  {
    /*
    out << "<polyline style=\"stroke:red;stroke-width:1\" points=\"";
    for(int i = 0 ; i < l.size() ; i++)
    {
      handlePoint(out, l[i]);
      out << " ";
    }
    out << + "\" />";
    */
  }

  void ScadExporter::handlePoint(std::ofstream &out, point const& p)
  {
    float x = p.get<0>();
    float y = p.get<1>();
    out << "[" << x << ", " << y << "]";
  }

}
