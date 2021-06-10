<?php

namespace Wasateam\Laravelapistone\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SystemSubclass_R1 extends JsonResource
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
        'id'         => $this->id,
        'sequence'   => $this->sequence,
        'updated_at' => $this->updated_at,
        'name'       => $this->name,
      ];
    } else if (config('stone.mode') == 'webapi') {
      return [
        'id'   => $this->id,
        'name' => $this->name,
      ];
    }
  }
}
