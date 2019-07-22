#include "BasicRoof.h"


namespace fabricad::blocks
{

  BasicRoof::BasicRoof()
  {
    tile_height_ = 10;
    tile_width_ = 7;
    tile_depth_ = 1;
    milling_length_ = 9;
  }

  BasicRoof* BasicRoof::setTileWidth(float w)
  {
    tile_width_ = w;
    return this;
  }

  float BasicRoof::getTileWidth()
  {
    return tile_width_;
  }

  BasicRoof* BasicRoof::setTileHeight(float h)
  {
    tile_height_ = h;
    return this;
  }

  float BasicRoof::getTileHeight()
  {
    return tile_height_;
  }

  BasicRoof* BasicRoof::setTileDepth(float w)
  {
    tile_depth_ = w;
    return this;
  }

  float BasicRoof::getTileDepth()
  {
    return tile_depth_;
  }

  BasicRoof* BasicRoof::setMillingLength(float w)
  {
    milling_length_ = w;
    return this;
  }

  float BasicRoof::getMillingLength()
  {
    return milling_length_;
  }

  void BasicRoof::generateSawTooth(float length, float width, float height, float x, float y, std::vector<linestring> &lines)
  {
    int steps = length  / width;
    float cur_x = x;

    linestring saw;

    for(int i = 0 ; i < steps ; i++)
    {
      bg::append(saw, point(cur_x, y));
      cur_x += width;
      bg::append(saw, point(cur_x, y + height));
    }

    // Check if cur_x is smaller than x + length. If so, we need to add a
    // little piece to the saw, of length ( x+length - cur_x )
    if (cur_x < x + length)
    {
      // Apply Pythagoras to calculate the last height
      bg::append(saw, point(cur_x, y));
      float endy = (x + length - cur_x) * (height / width);
      bg::append(saw, point(x + length, y + endy));
    }

    // Append saw to lines
    lines.push_back(saw);
  }

  void BasicRoof::render()
  {
    // Create initial layer with shape
    BasicBuildingBlock::render();

    // Determine bounding box
    box bb;
    bg::envelope(this->shape_, bb);

    float minX = bb.min_corner().get<0>();
    float minY = bb.min_corner().get<1>();
    float maxX = bb.max_corner().get<0>();
    float maxY = bb.max_corner().get<1>();

    float length = maxX - minX;
    float height = maxY - minY;

    // Add the saw tooth to the bricks layer
    generateSawTooth(height, getTileHeight(), getTileDepth(), minX, maxY + 10, this->layers_[1].lines);

    // Add the line for generating the roof in CAM-tools to the brick layer

    linestring line;
    bg::append(line, point(minX, maxY));
    bg::append(line, point(minX+length, maxY));

    this->layers_[1].lines.push_back(line);

    // Build the remainder of the roof
    renderRoof();
  }

  void BasicRoof::renderRoof()
  {}

}
