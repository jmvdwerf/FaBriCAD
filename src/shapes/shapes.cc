
#include "shapes.h"
#include <fstream>
#include <iostream>


std::vector<polygon> split(polygon p1, polygon p2)
{
  std::vector<polygon> out;
  bg::intersection(p1, p2, out);
  bg::sym_difference(p1, p2, out);

  return out;
}


std::string shapeToString(polygon const& g, float topY)
{
  std::string svg = "<polygon points=\"";
  for(auto it = boost::begin(bg::exterior_ring(g)); it != boost::end(bg::exterior_ring(g)); ++it)
  {
      float x = bg::get<0>(*it);
      float y = topY - bg::get<1>(*it);
      svg += std::to_string(x) + ", " + std::to_string(y) + " ";
  }
  svg += "\" style=\"fill:royalblue;stroke:black;stroke-width:1\" fill-opacity=\"0.5\" />";
  return svg;
}

std::string shapeToString(linestring const& g, float topY)
{
  std::string svg = "<polyline style=\"stroke:red;stroke-width:1\" points=\"";
  for(int i = 0 ; i < g.size() ; i++)
  {
    float x = g[i].get<0>();
    float y = topY - g[i].get<1>();
    svg += std::to_string(x) + ", " + std::to_string(y) + " ";
  }
  return svg + "\" />";
}

void createSVGFile(const std::string filename, std::vector<shapelayer> const &layers)
{
  box maxBound;
  for(int l = 0 ; l < layers.size() ; l++)
  {
    for(int i = 0 ; i < layers[l].polygons.size() ; i++)
    {
      box b;
      bg::envelope(layers[l].polygons[i], b);
      bg::expand(maxBound, b);
    }
    for(int i = 0 ; i < layers[l].lines.size() ; i++)
    {
      box b;
      bg::envelope(layers[l].lines[i], b);
      bg::expand(maxBound, b);
    }
  }

  float topX = maxBound.max_corner().get<0>() + 20;
  float topY = maxBound.max_corner().get<1>() + 20;

  std::ofstream svg(filename.c_str());

  svg << "<?xml version=\"1.0\" encoding=\"UTF-8\" standalone=\"no\"?>" << std::endl;
  svg << "<svg height=\""<< topX << "mm\" width=\"" << topY << "mm\" ";
  svg << "viewBox=\"0 0 "<< topX << " " << topY << "\">" << std::endl;

  for(int l = 0 ; l < layers.size() ; l++)
  {
    svg << "\t<g id=\"" << layers[l].id << "\" ";
    svg << "inkscape:label=\"" << layers[l].name << "\" ";
    svg << "inkscape:groupmode=\"layer\" >" << std::endl;

    for(int i = 0 ; i < layers[l].polygons.size() ; i++)
    {
      svg << "\t\t" << shapeToString(layers[l].polygons[i], topY) << std::endl;
    }
    for(int i = 0 ; i < layers[l].lines.size() ; i++)
    {
      svg << "\t\t" << shapeToString(layers[l].lines[i], topY) << std::endl;
    }
    svg << "</g>" << std::endl;
  }

  svg << "</svg>" << std::endl;
}


void createSVGFile(const std::string filename, shapelayer const &layer) //, double width, double height)
{
  box maxBound;
  for(int i = 0 ; i < layer.polygons.size() ; i++)
  {
    box b;
    bg::envelope(layer.polygons[i], b);
    bg::expand(maxBound, b);
  }
  for(int i = 0 ; i < layer.lines.size() ; i++)
  {
    box b;
    bg::envelope(layer.lines[i], b);
    bg::expand(maxBound, b);
  }
  float topX = maxBound.max_corner().get<0>() + 20;
  float topY = maxBound.max_corner().get<1>() + 20;

  std::ofstream svg(filename.c_str());
  svg << "<?xml version=\"1.0\" encoding=\"UTF-8\" standalone=\"no\"?>" << std::endl;
  svg << "<svg height=\""<< topX << "mm\" width=\"" << topY << "mm\" ";
  svg << "viewBox=\"0 0 "<< topX << " " << topY << "\">" << std::endl;

  for(int i = 0 ; i < layer.polygons.size() ; i++)
  {
    svg << "\t\t" << shapeToString(layer.polygons[i], topY) << std::endl;
  }
  for(int i = 0 ; i < layer.lines.size() ; i++)
  {
    svg << "\t\t" << shapeToString(layer.lines[i], topY) << std::endl;
  }

  svg << "</svg>" << std::endl;
}

void polygonmerge(std::vector<polygon> *orig, std::vector<polygon> const& toadd)
{
  orig->insert( orig->end(), toadd.begin(), toadd.end());
}

void linestringmerge(std::vector<linestring> *orig, std::vector<linestring> const& toadd)
{
  orig->insert( orig->end(), toadd.begin(), toadd.end());
}

void calculateDifference(std::vector<linestring> items, std::vector<polygon> const& overlap, std::vector<linestring> *list)
{
  for(size_t i = 0 ; i < items.size(); i++)
  {
    std::vector<linestring> diff = calculateLineDifference(items[i], overlap);
    list->insert(list->end(), diff.begin(), diff.end());
  }
}

std::vector<linestring> calculateLineDifference(linestring line, std::vector<polygon> const& overlap)
{
  // Ik heb een shape S en een overlap A. Voor deze shape bereken ik het
  // verschil van S met A. Dat geeft mij een verzameling nieuwe elementen,
  // die niet overlappen met A. Vervolgens controleer ik deze nieuwe set S'
  // op overlap met het volgende element, B. Dit herhaal ik totdat ik alle
  // elementen in de overlap heb gehad.
  std::vector<linestring> q;
  q.push_back(line);
  for(size_t i = 0 ; i < overlap.size() ; i++)
  {
    //bg::correct(overlap.at(i));
    std::vector<linestring> newQ;
    while(!q.empty())
    {
      linestring l = q.front();
      q.erase(q.begin());
      bg::difference(l, overlap.at(i), newQ);
    }
    q.insert(q.begin(), newQ.begin(), newQ.end());
  }

  return q;
}
