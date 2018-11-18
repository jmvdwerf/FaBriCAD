#ifndef _FABRICAD_CONVERTER_TXTEXPORTER_H_
#define _FABRICAD_CONVERTER_TXTEXPORTER_H_

#include "Exporter.h"

namespace fabricad::converter
{

  class TxtExporter : public Exporter
  {
    void handleProjectStart(fabricad::config::Project* project, std::string const& filename, std::ofstream &out) override;
    void handleProjectFinish(fabricad::config::Project* project, std::string const& filename, std::ofstream &out) override;

    void handleBlueprintStart(fabricad::config::Blueprint* print, std::string const& filename, std::ofstream &out) override;
    void handleBlueprintFinish(fabricad::config::Blueprint* print, std::string const& filename, std::ofstream &out) override;

    void handleLayerStart(std::ofstream &out, shapelayer const& layer) override;
    void handleLayerFinish(std::ofstream &out, shapelayer const& layer) override;

    void handlePolygon(std::ofstream &out, polygon const& p) override;
    void handleLinestring(std::ofstream &out, linestring const& l) override;
    void handlePoint(std::ofstream &out, point const& p) override;
  };
}
#endif
