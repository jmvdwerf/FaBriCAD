#ifndef _FABRICAD_BLOCKS_BASICBUILDINGBLOCK_H_
#define _FABRICAD_BLOCKS_BASICBUILDINGBLOCK_H_

#include <string>

#include "../shapes/shapes.h"

using namespace std;



namespace fabricad::blocks {


  /*
   * The getRenderedLayers() returns a vector of vectors. Each layer is a vector
   * of geometric elements. The first layer (layer 0) is used for the cutout
   * shapes, e.g., borders
   * the second layer for internal details, e.g. brickwork.
   * In this way, only using layer 0 should result in the complete sketch of the
   * house, whereas adding the other layers add details.
   * For houses, we use the following convention:
   *    - Layer 0: cutouts (borders and windows)
   *    - Layer 1: brick work
   *    - Layer 2: window and door details.
   *      Layer 3: all other things
   *
   * It is the responsibility of the Block to ensure that layers have no overlapping
   * lines..
   */
  class BasicBuildingBlock {

  public:
    BasicBuildingBlock();

    std::string getId();
    BasicBuildingBlock* setId(std::string id);

    std::string getName();
    BasicBuildingBlock* setName(std::string name);

    std::string getType();
    BasicBuildingBlock* setType(std::string type);

    std::string toString();
    virtual std::string toString(std::string indent);

    geometry getShape();
    BasicBuildingBlock* setShape(geometry shape);

    /**
     * This function is the function to obtain all
     * geometric objects of a layer
     *
     * If render() has not yet been called, it first does so.
     *
     */
    std::vector<geometry> getLayer(size_t layer);

  protected:
    /**
     * Renders the objects. It should clear the layers_ before creating
     * the layers
     */
    virtual void render();
    std::vector<std::vector<geometry>> layers_;

  private:
    std::string id_;
    std::string name_;
    std::string type_;

    geometry shape_;
  };

}

#endif
