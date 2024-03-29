<?php

namespace Wasateam\Laravelapistone\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class App_R1 extends JsonResource
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
        'id'         => $this->id,
        'created_at' => $this->created_at,
        'updated_at' => $this->updated_at,
        'uuid'       => $this->uuid,
        'key'        => $this->key,
        'name'       => $this->name,
        'url'        => $this->url,
        'avatar'     => $this->avatar,
        'is_public'  => $this->is_public,
      ];
    } else {
      $res = [
        'uuid'       => $this->uuid,
        'created_at' => $this->created_at,
        'key'        => $this->key,
        'name'       => $this->name,
        'url'        => $this->url,
        'avatar'     => $this->avatar,
        'is_public'  => $this->is_public,
      ];
    }
    return $res;
  }
}
