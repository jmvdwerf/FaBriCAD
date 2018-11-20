
#include <vector>
#include "SvgExporter.h"

namespace fabricad::converter
{
  SvgExporter::SvgExporter()
  {
    createInitialFile = false;
  }

  box SvgExporter::determineEnvelope(fabricad::config::Blueprint* print)
  {
    box maxBound;
    for(int l = 0 ; l < 4 ; l++)
    {
      for(int i = 0 ; i < print->getLayer(l).polygons.size() ; i++)
      {
        box b;
        bg::envelope(print->getLayer(l).polygons[i], b);
        bg::expand(maxBound, b);
      }
      for(int i = 0 ; i < print->getLayer(l).lines.size() ; i++)
      {
        box b;
        bg::envelope(print->getLayer(l).lines[i], b);
        bg::expand(maxBound, b);
      }
    }

    return maxBound;
  }

  void SvgExporter::handleBlueprintStart(fabricad::config::Blueprint* print, std::string const& filename, std::ofstream &out)
  {
    // Create a new out stream based on filename and Blueprint ID
    out.close();
    std::string file = filename + "_" + print->getId() + ".svg";
    // TODO: Check that file path exists, and if not, create it.

    out.open(file, std::ofstream::out);

    box maxBound = determineEnvelope(print);

    float topX = maxBound.max_corner().get<0>() + 20;
    float topY = maxBound.max_corner().get<1>() + 20;

    top = topY;

    out << "<?xml version=\"1.0\" encoding=\"UTF-8\" standalone=\"no\"?>" << std::endl;
    out << "<svg height=\""<< topX << "mm\" width=\"" << topY << "mm\" ";
    out << "viewBox=\"0 0 "<< topX << " " << topY << "\">" << std::endl;
  }

  void SvgExporter::handleBlueprintFinish(fabricad::config::Blueprint* print, std::string const& filename, std::ofstream &out)
  {
    out << "</svg>" << std::endl;
    out.close();
  }

  void SvgExporter::handleLayerStart(std::ofstream &out, shapelayer const& layer)
  {
    out << "\t<g id=\"" << layer.id << "\" ";
    out << "inkscape:label=\"" << layer.name << "\" ";
    out << "inkscape:groupmode=\"layer\" >" << std::endl;
  }

  void SvgExporter::handleLayerFinish(std::ofstream &out, shapelayer const& layer)
  {
    out << "</g>" << std::endl;
  }

  void SvgExporter::handlePolygon(std::ofstream &out, polygon const& p)
  {
    std::vector<point> const& points = p.outer();
    out << "<polygon points=\"";
    for(size_t i = 0 ; i < points.size() ; ++i )
    {
      handlePoint(out, points[i]);
      out << " ";
    }
    out << "\" style=\"fill:royalblue;stroke:black;stroke-width:1\" fill-opacity=\"0.5\" />";
  }

  void SvgExporter::handleLinestring(std::ofstream &out, linestring const& l)
  {
    out << "<polyline style=\"stroke:red;stroke-width:1\" points=\"";
    for(int i = 0 ; i < l.size() ; i++)
    {
      handlePoint(out, l[i]);
      out << " ";
    }
    out << + "\" />";
  }

  void SvgExporter::handlePoint(std::ofstream &out, point const& p)
  {
    float x = p.get<0>();
    float y = top - p.get<1>();
    out << x << ", " << y;
  }

}
