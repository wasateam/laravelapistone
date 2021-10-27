<?php

namespace Wasateam\Laravelapistone\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ShopShipAreaSetting extends JsonResource
{
  /**
   * Transform the resource into an array.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return array
   */
  public function toArray($request)
  {
    if (config('stone.mode') == 'cms') {
      $res = [
        'is_all_area_section' => $this->is_all_area_section,
        'ship_ways'           => $this->ship_ways,
        'area'                => new Area_R1($this->area),
        'area_sections'       => AreaSection_R1::collection($this->area_sections),
      ];
    } else if (config('stone.mode') == 'webapi') {
      $res = [
        'is_all_area_section' => $this->is_all_area_section,
        'ship_ways'           => $this->ship_ways,
        'area'                => new Area_R1($this->area),
        'area_sections'       => AreaSection_R1::collection($this->area_sections),
      ];
    }
    return $res;
  }
}
