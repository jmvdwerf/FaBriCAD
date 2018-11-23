#ifndef _FABRICAD_CONVERTER_SCAD_EXPORTER_H_
#define _FABRICAD_CONVERTER_SCAD_EXPORTER_H_


#include "Exporter.h"

namespace fabricad::converter
{

  class ScadExporter : public Exporter
  {
  public:
    ScadExporter();

  protected:
    void handleBlueprintStart(fabricad::config::Blueprint* print, std::string const& filename, std::ofstream &out) override;
    void handleBlueprintLoop(fabricad::config::Blueprint* print, std::string const& filename, std::ofstream &out) override;
    void handleBlueprintFinish(fabricad::config::Blueprint* print, std::string const& filename, std::ofstream &out) override;

    // void handleLayerStart(std::ostream &out, shapelayer const& layer) override;
    // void handleLayerFinish(std::ostream &out, shapelayer const& layer) override;

    void handlePolygon(std::ostream &out, polygon const& p) override;
    void handleLinestring(std::ostream &out, linestring const& l) override;
    void handlePoint(std::ostream &out, point const& p) override;

    // float getLineDepth(fabricad::blocks:BasicBuildingBlock* block);
    // float getLineWidth(fabricad::blocks:BasicBuildingBlock* block);
  };
}

#endif
