#ifndef _FABRICAD_BLOCKS_BASICROOF_H_
#define _FABRICAD_BLOCKS_BASICROOF_H_

#include "BasicBuildingBlock.h"

namespace fabricad::blocks
{

  /**
   * @brief      BasicRoof creates the roof structure
   *
   * For a tile:
   *   - depth: how "deep" a tile sinks into the roof (i.e., the height of the saw tooth)
   *   - height: the height of the tile
   *   - width: the width of the tile, i.e., how many tiles can be drawn?
   *
   *   - millinglength: the size of the mill used for milling the lines.
   */
  class BasicRoof : public BasicBuildingBlock
  {
    public:
      BasicRoof();

      // This function prepares the roof to be rendered, and makes a final
      // call to the virtual renderRoof method.
      void render();

      BasicRoof* setTileWidth(float w);
      float getTileWidth();

      BasicRoof* setTileHeight(float h);
      float getTileHeight();

      BasicRoof* setTileDepth(float d);
      float getTileDepth();

      BasicRoof* setMillDiameter(float d);
      float getMillDiameter();
    protected:
      // function to build a sawtooth of a given length, starting at point (x,y)
      // and adds it to the vector lines
      void generateSawTooth(float length, float width, float height, float x, float y, std::vector<linestring> &lines);

      // This function needs to be implemented by all children of BasicRoof
      // It is called as last function of render().
      virtual void renderRoof();

    private:
      float tile_height_;
      float tile_width_;
      float tile_depth_;
      float mill_diameter_;
  };
}

#endif
