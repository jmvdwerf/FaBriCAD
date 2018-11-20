
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
    out << "{" << std::endl;
  }

  void ScadExporter::handleBlueprintFinish(fabricad::config::Blueprint* print, std::string const& filename, std::ofstream &out)
  {
    out << "} // END OF: " << print->getName() << std::endl << std::endl;
  }

  void ScadExporter::handleBlueprintLoop(fabricad::config::Blueprint* print, std::string const& filename, std::ofstream &out)
  {
    ostringstream unite;
    ostringstream differ;

    for(auto &layer: print->getLayers())
    {
      if (layer.first->getType() == "cutout") {
        handleBlock(layer, filename, differ);
      } else {
        handleBlock(layer, filename, unite);
      }
    }
    if (getCurrentBlueprint()->getThickness() > 0) {
      out << "\tlinear_extrude(height=" << getCurrentBlueprint()->getThickness() << ") { ";
    }
    out << "\tdifference() {" << std::endl << "\t\tunion() {" << std::endl;
    out << unite.str() << "\t\t} // union" << std::endl;
    out << differ.str() << std::endl;
    out << "\t} // difference" << std::endl;
    if (getCurrentBlueprint()->getThickness() > 0) {
      out << "\t} // extrude" << std::endl;
    }
  }

  void ScadExporter::handlePolygon(std::ostream &out, polygon const& p)
  {
    std::vector<point> const& points = p.outer();
    out << "\t\tcolor(\""<< getCurrentBlock()->getColor() << "\") { ";
    out << "polygon( [ " << std::endl;
    out << "\t\t\t";
    handlePoint(out, points[0]);
    for(size_t i = 1 ; i < points.size() ; ++i )
    {
      out << "," << std::endl << "\t\t\t";
      handlePoint(out, points[i]);
    }
    out << std::endl << "\t\t] ); }" << std::endl << std::endl;
  }

  void ScadExporter::handleLinestring(std::ostream &out, linestring const& l)
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

  void ScadExporter::handlePoint(std::ostream &out, point const& p)
  {
    float x = p.get<0>();
    float y = p.get<1>();
    out << "[" << x << ", " << y << "]";
  }

}
