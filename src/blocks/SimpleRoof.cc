#include "SimpleRoof.h"


namespace fabricad::blocks
{
  SimpleRoof::SimpleRoof()
  {
  }

  void SimpleRoof::renderRoof()
  {
    // Determine bounding box
    box bb;
    bg::envelope(this->shape_, bb);

    float minX = bb.min_corner().get<0>();
    float minY = bb.min_corner().get<1>();
    float maxX = bb.max_corner().get<0>();
    float maxY = bb.max_corner().get<1>();


    for(float x = minX ; x < maxX ; x += getTileWidth() ) {
      for(float y = minY; y < maxY ; y+= getTileHeight()) {
        linestring line;
        bg::append(line, point(x, y));
        bg::append(line, point(x, y + getMillingLength()));


        std::vector<linestring> l;
        bg::intersection(line, this->shape_, l);
        this->layers_[1].lines.insert(
            this->layers_[1].lines.end(),
            l.begin(),
            l.end()
        );
      }
    }
  }

}
