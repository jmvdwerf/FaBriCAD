
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

    linestr.clear();

    for(auto &layer: print->getLayers())
    {
      if (layer.first->getType() == "cutout") {
        handleBlock(layer, filename, differ);
      } else {
        handleBlock(layer, filename, unite);
      }
    }
    /*
    if (getThickness() > 0) {
      out << "\tlinear_extrude(height=" << getThickness() << ") { ";
    }
    */
    out << "\tdifference() {" << std::endl << "\t\tunion() {" << std::endl;
    out << unite.str() << "\t\t} // union" << std::endl;
    out << differ.str() << std::endl;
    out << linestr.str() << std::endl;
    out << "\t} // difference" << std::endl;
    /*
    if (getThickness() > 0) {
      out << "\t} // extrude" << std::endl;
    }*/
  }

  void ScadExporter::handlePolygon(std::ostream &out, polygon const& p)
  {
    std::vector<point> const& points = p.outer();
    out << "\t\tcolor(\""<< getColor() << "\") { ";
    if (getThickness() > 0) {
      out << "linear_extrude(height=" << getThickness() << ") { ";
    }
    out << "polygon( [ " << std::endl;
    out << "\t\t\t";
    handlePoint(out, points[0]);
    for(size_t i = 1 ; i < points.size() ; ++i )
    {
      out << "," << std::endl << "\t\t\t";
      handlePoint(out, points[i]);
    }
    out << std::endl << "\t\t] ); }";
    if (getThickness() > 0) {
      out << " } ";
    }
    out << std::endl << std::endl;
  }

  void ScadExporter::handleLinestring(std::ostream &out, linestring const& l)
  {
    /*
    linestr << "\t\t// add line " << std::endl;
    std::vector<point> front;
    std::vector<point> back;
    float ln = getLineWidth() / 2;
    for(int i = 0 ; i < l.size() ; i++)
    {
      front.push_back(increasePoint(l[i], ln));
      back.insert(back.begin(), increasePoint(l[i], -1 * ln));
    }
    polygon poly;
    for(auto& p: front)
    {
      bg::append(poly.outer(), p);
    }
    for(auto& p: back)
    {
      bg::append(poly.outer(), p);
    }

    linestr << "\t\ttranslate([0,0," << (getThickness() - getLineDepth()) << "]) {" << std::endl;
    handlePolygon(linestr, poly);
    linestr << "\t\t} // translate line" << std::endl;
    */
  }

  void ScadExporter::handlePoint(std::ostream &out, point const& p)
  {
    float x = p.get<0>();
    float y = p.get<1>();
    out << "[" << x << ", " << y << "]";
  }

}
