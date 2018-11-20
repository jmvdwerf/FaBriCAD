#ifndef _FABRICAD_CONVERTER_SVG_EXPORTER_H_
#define _FABRICAD_CONVERTER_SVG_EXPORTER_H_


#include "Exporter.h"

namespace fabricad::converter
{

  class SvgExporter : public Exporter
  {
  public:
    SvgExporter();

  protected:
    /**
     * @brief      Creates a new out file for each blueprint
     *
     * @param      print     The print
     * @param      filename  The filename
     * @param      out       The out
     */
    void handleBlueprintStart(fabricad::config::Blueprint* print, std::string const& filename, std::ofstream &out) override;
    void handleBlueprintFinish(fabricad::config::Blueprint* print, std::string const& filename, std::ofstream &out) override;

    void handleLayerStart(std::ofstream &out, shapelayer const& layer) override;
    void handleLayerFinish(std::ofstream &out, shapelayer const& layer) override;

    void handlePolygon(std::ofstream &out, polygon const& p) override;
    void handleLinestring(std::ofstream &out, linestring const& l) override;
    void handlePoint(std::ofstream &out, point const& p) override;

  private:
    float top;
    box determineEnvelope(fabricad::config::Blueprint* print);
  };
}

#endif
