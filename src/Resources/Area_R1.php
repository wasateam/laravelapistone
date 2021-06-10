<?php

namespace Wasateam\Laravelapistone\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Area_R1 extends JsonResource
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
      return [
        'id'            => $this->id,
        'name'          => $this->name,
        'area_sections' => AreaSection_R1::collection($this->area_sections),
      ];
    } else if (config('stone.mode') == 'webapi') {
      return [
        'id'            => $this->id,
        'name'          => $this->name,
        'area_sections' => AreaSection_R1::collection($this->area_sections),
      ];
    }
  }
}
