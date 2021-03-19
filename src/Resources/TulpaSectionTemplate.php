<?php

namespace Wasateam\Laravelapistone\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TulpaSectionTemplate extends JsonResource
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
        'updated_admin'  => new Admin_R1($this->updated_admin),
        'created_at'     => $this->created_at,
        'updated_at'     => $this->updated_at,
        'name'           => $this->name,
        'component_name' => $this->component_name,
        'remark'         => $this->remark,
        'fields'         => $this->fields,
      ];
    } else if (config('stone.mode') == 'webapi') {
      return [
        'id'             => $this->id,
        'component_name' => $this->component_name,
        'fields'         => $this->fields,
      ];
    }
  }
}
