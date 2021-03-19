<?php

namespace Wasateam\Laravelapistone\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TulpaSectionTemplate_R1 extends JsonResource
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
        'id'             => $this->id,
        'name'           => $this->name,
        'component_name' => $this->component_name,
        'fields'         => $this->fields,
      ];
    } else if (config('stone.mode') == 'webapi') {
      return [
        'id'             => $this->id,
        'name'           => $this->name,
        'component_name' => $this->component_name,
        'fields'         => $this->fields,
      ];
    }
  }
}
