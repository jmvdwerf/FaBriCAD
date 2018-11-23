
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

    box mBound;
    for(auto& item: print->getLayers())
    {
      std::vector<shapelayer> layers = item.second;
      for(int layer = 0 ; layer < 4 ; layer++)
      {
        for(int i = 0 ; i < layers[layer].polygons.size() ; i++)
        {
          box b;
          bg::envelope(layers[layer].polygons[i], b);
          bg::expand(mBound, b);
        }
        for(int i = 0 ; i < layers[layer].lines.size() ; i++)
        {
          box b;
          bg::envelope(layers[layer].lines[i], b);
          bg::expand(mBound, b);
        }
      }
    }

    return mBound;
  }

  void SvgExporter::handleBlockStart(fabricad::blocks::BasicBuildingBlock* block, std::string const& filename, std::ostream &out)
  {
    out << "\t<g id=\"" << block->getId() << "\" ";
    out << "inkscape:label=\"" << block->getName() << "\" ";
    out << "inkscape:groupmode=\"layer\" >" << std::endl;
  }

  void SvgExporter::handleBlockFinish(fabricad::blocks::BasicBuildingBlock* block, std::string const& filename, std::ostream &out)
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
    out << "<svg height=\""<< topY << "mm\" width=\"" << topX << "mm\" ";
    out << "viewBox=\"0 0 "<< topX << " " << topY << "\">" << std::endl;
  }

  void SvgExporter::handleBlueprintFinish(fabricad::config::Blueprint* print, std::string const& filename, std::ofstream &out)
  {
    out << "</svg>" << std::endl;
    out.close();
  }

  void SvgExporter::handleLayerStart(std::ostream &out, shapelayer const& layer)
  {
    out << "\t\t<g id=\"" << getCurrentBlock()->getId() << "_" << layer.id << "\" ";
    out << "inkscape:label=\"" << layer.name << "\" ";
    out << "inkscape:groupmode=\"layer\" >" << std::endl;
  }

  void SvgExporter::handleLayerFinish(std::ostream &out, shapelayer const& layer)
  {
    out << "\t\t</g>" << std::endl;
  }

  void SvgExporter::handlePolygon(std::ostream &out, polygon const& p)
  {
    std::vector<point> const& points = p.outer();
    out << "\t\t\t<polygon points=\"";
    for(size_t i = 0 ; i < points.size() ; ++i )
    {
      handlePoint(out, points[i]);
      out << " ";
    }
    out << "\" style=\"fill:"<< getColor() << ";stroke:black;stroke-width:1\" fill-opacity=\"0.5\" />";
    out << std::endl;
  }

  void SvgExporter::handleLinestring(std::ostream &out, linestring const& l)
  {
    out << "\t\t\t<polyline style=\"stroke:"<< getLineColor() << ";stroke-width:" << getLineWidth() << "\" points=\"";
    for(int i = 0 ; i < l.size() ; i++)
    {
      handlePoint(out, l[i]);
      out << " ";
    }
    out << + "\" />" << std::endl;
  }

  void SvgExporter::handlePoint(std::ostream &out, point const& p)
  {
    float x = p.get<0>() + 10;
    float y = top - p.get<1>() - 10;
    out << x << ", " << y;
  }

}
