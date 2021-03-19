<?php

namespace Wasateam\Laravelapistone\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TulpaSectionTemplate_R0 extends JsonResource
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
        'id' => $this->id,
      ];
    } else if (config('stone.mode') == 'webapi') {
      return [
        'id' => $this->id,
      ];
    }
  }
}
