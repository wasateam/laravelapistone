<?php

namespace Wasateam\Laravelapistone\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Area extends JsonResource
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
        'updated_admin' => new Admin($this->updated_admin),
        'sequence'      => $this->sequence,
        'updated_at'    => $this->updated_at,
        'name'          => $this->name,
        'area_sections' => AreaSection_R1::collection($this->area_sections),
      ];
    } else if (config('stone.mode') == 'webapi') {
      return [
        'id'            => $this->id,
        'sequence'      => $this->sequence,
        'updated_at'    => $this->updated_at,
        'name'          => $this->name,
        'area_sections' => AreaSection_R1::collection($this->area_sections),
      ];
    }
  }
}
