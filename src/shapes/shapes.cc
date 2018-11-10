
#include "shapes.h"
#include <fstream>

std::vector<polygon> split(polygon p1, polygon p2)
{
  std::vector<polygon> out;
  bg::intersection(p1, p2, out);
  bg::sym_difference(p1, p2, out);

  return out;
}


class SvgVisitor : public boost::static_visitor<std::string>
{
  public:

    SvgVisitor(float topY) {
      top_ = topY;
    }

    std::string operator()(polygon const& g) const
    {
      std::string svg = "<polygon points=\"";
      for(auto it = boost::begin(bg::exterior_ring(g)); it != boost::end(bg::exterior_ring(g)); ++it)
      {
          float x = bg::get<0>(*it);
          float y = top_ - bg::get<1>(*it);
          svg += std::to_string(x) + ", " + std::to_string(y) + " ";
      }
      svg += "\" style=\"fill:royalblue;stroke:black;stroke-width:1\" fill-opacity=\"0.5\" />";
      return svg;
    }

    std::string operator()(box const& g) const
    {
      float x = g.min_corner().get<0>();
      float dy = g.min_corner().get<1>();
      float w = g.max_corner().get<0>() - x;
      float h = g.max_corner().get<1>() - dy;

      float y = top_ - dy;

      std::string svg = "<rect style=\"fill:royalblue;stroke:black;stroke-width:1\" fill-opacity=\"0.5\" ";
      svg += "x=\"" + std::to_string(x) + "\" y=\"" + std::to_string(y) + "\" width=\"" + std::to_string(w) + "\" height=\"" + std::to_string(h) + "\" />";
      return svg;
    }

    std::string operator()(linestring const& g) const
    {
      std::string svg = "<polyline style=\"stroke:red;stroke-width:1\" points=\"";
      for(int i = 0 ; i < g.size() ; i++)
      {
        float x = g[i].get<0>();
        float y = top_ - g[i].get<1>();
        svg += std::to_string(x) + ", " + std::to_string(y) + " ";
      }
      return svg + "\" />";
    }

    std::string operator()(point const& g) const
    {
      return "";
    }
  private:
    double top_ = 100;
};

void createSVGFile(std::string filename, std::vector<layer> layers)
{
  box maxBound;
  for(int l = 0 ; l < layers.size() ; l++)
  {
    for(int i = 0 ; i < layers[l].elements.size() ; i++)
    {
      box b;
      bg::envelope(layers[l].elements[i], b);
      bg::expand(maxBound, b);
    }
  }

  float topX = maxBound.max_corner().get<0>() + 20;
  float topY = maxBound.max_corner().get<1>() + 20;

  SvgVisitor sv = SvgVisitor(topY);

  std::ofstream svg(filename.c_str());

  svg << "<?xml version=\"1.0\" encoding=\"UTF-8\" standalone=\"no\"?>" << std::endl;
  svg << "<svg height=\""<< topX << "mm\" width=\"" << topY << "mm\" ";
  svg << "viewBox=\"0 0 "<< topX << " " << topY << "\">" << std::endl;

  for(int l = 0 ; l < layers.size() ; l++)
  {
    svg << "\t<g id=\"" << layers[l].id << "\" ";
    svg << "inkscape:label=\"" << layers[l].name << "\" ";
    svg << "inkscape:groupmode=\"layer\" >" << std::endl;

    for(int i = 0 ; i < layers[l].elements.size() ; i++)
    {
      svg << "\t\t" << boost::apply_visitor(sv, layers[l].elements[i]) << std::endl;
    }
    svg << "</g>" << std::endl;
  }

  svg << "</svg>" << std::endl;
}


void createSVGFile(std::string filename, std::vector<geometry> elements) //, double width, double height)
{
  box maxBound;
  for(int i = 0 ; i < elements.size() ; i++)
  {
    box b;
    bg::envelope(elements[i], b);
    bg::expand(maxBound, b);
  }
  float topX = maxBound.max_corner().get<0>() + 20;
  float topY = maxBound.max_corner().get<1>() + 20;

  SvgVisitor sv = SvgVisitor(topY);

  std::ofstream svg(filename.c_str());
  svg << "<?xml version=\"1.0\" encoding=\"UTF-8\" standalone=\"no\"?>" << std::endl;
  svg << "<svg height=\""<< topX << "mm\" width=\"" << topY << "mm\" ";
  svg << "viewBox=\"0 0 "<< topX << " " << topY << "\">" << std::endl;

  for(int i = 0 ; i < elements.size() ; i++)
  {
    svg << "\t" << boost::apply_visitor(sv, elements[i]) << std::endl;
  }

  svg << "</svg>" << std::endl;
}
