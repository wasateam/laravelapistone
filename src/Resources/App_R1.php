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
    $res = [
      'uuid'      => $this->uuid,
      'key'       => $this->key,
      'name'      => $this->name,
      'url'       => $this->url,
      'avatar'    => $this->avatar,
      'is_public' => $this->is_public,
    ];

    return $res;
  }
}
