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
      $res = [
        'id'            => $this->id,
        'updated_admin' => new Admin_R1($this->updated_admin),
        'sequence'      => $this->sequence,
        'created_at'    => $this->created_at,
        'updated_at'    => $this->updated_at,
        'name'          => $this->name,
        'area_sections' => AreaSection_R1::collection($this->area_sections),
      ];
      if (config('stone.country_code')) {
        $res['country_code'] = $this->country_code;
      }
    } else if (config('stone.mode') == 'webapi') {
      $res = [
        'id'            => $this->id,
        'name'          => $this->name,
        'area_sections' => AreaSection_R1::collection($this->area_sections),
      ];
      if (config('stone.country_code')) {
        $res['country_code'] = $this->country_code;
      }
    }
    return $res;
  }
}
