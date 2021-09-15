<?php

namespace Wasateam\Laravelapistone\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class App_R0 extends JsonResource
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
        'id'        => $this->id,
        'uuid'      => $this->uuid,
        'key'       => $this->key,
        'name'      => $this->name,
        'url'       => $this->url,
        'is_public' => $this->is_public,
      ];
    } else {
      $res = [
        'uuid'      => $this->uuid,
        'key'       => $this->key,
        'name'      => $this->name,
        'url'       => $this->url,
        'is_public' => $this->is_public,
      ];
    }
    return $res;
  }
}
