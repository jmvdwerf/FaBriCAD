
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
    for(auto& item: print->getLayers())
    {
      std::vector<shapelayer> layers = item.second;
      for(int layer = 0 ; layer < 4 ; layer++)
      {
        for(int i = 0 ; i < layers[layer].polygons.size() ; i++)
        {
          box b;
          bg::envelope(layers[layer].polygons[i], b);
          bg::expand(maxBound, b);
        }
        for(int i = 0 ; i < layers[layer].lines.size() ; i++)
        {
          box b;
          bg::envelope(layers[layer].lines[i], b);
          bg::expand(maxBound, b);
        }
      }
    }

    return maxBound;
  }

  void SvgExporter::handleBlockStart(fabricad::blocks::BasicBuildingBlock* block, std::string const& filename, std::ofstream &out)
  {
    out << "\t<g id=\"" << block->getId() << "\" ";
    out << "inkscape:label=\"" << block->getName() << "\" ";
    out << "inkscape:groupmode=\"layer\" >" << std::endl;
  }

  void SvgExporter::handleBlockFinish(fabricad::blocks::BasicBuildingBlock* block, std::string const& filename, std::ofstream &out)
  {
    out << "\t</g>" << std::endl;
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
    out << "\t\t<g id=\"" << getCurrentBlock()->getId() << "_" << layer.id << "\" ";
    out << "inkscape:label=\"" << layer.name << "\" ";
    out << "inkscape:groupmode=\"layer\" >" << std::endl;
  }

  void SvgExporter::handleLayerFinish(std::ofstream &out, shapelayer const& layer)
  {
    out << "\t\t</g>" << std::endl;
  }

  void SvgExporter::handlePolygon(std::ofstream &out, polygon const& p)
  {
    std::vector<point> const& points = p.outer();
    out << "\t\t\t<polygon points=\"";
    for(size_t i = 0 ; i < points.size() ; ++i )
    {
      handlePoint(out, points[i]);
      out << " ";
    }
    out << "\" style=\"fill:"<< getCurrentBlock()->getColor() << ";stroke:black;stroke-width:1\" fill-opacity=\"0.5\" />";
    out << std::endl;
  }

  void SvgExporter::handleLinestring(std::ofstream &out, linestring const& l)
  {
    out << "\t\t\t<polyline style=\"stroke:red;stroke-width:1\" points=\"";
    for(int i = 0 ; i < l.size() ; i++)
    {
      handlePoint(out, l[i]);
      out << " ";
    }
    out << + "\" />" << std::endl;
  }

  void SvgExporter::handlePoint(std::ofstream &out, point const& p)
  {
    float x = p.get<0>() + 10;
    float y = top - p.get<1>();
    out << x << ", " << y;
  }

}
