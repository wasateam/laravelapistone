<?php

namespace Wasateam\Laravelapistone\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class App extends JsonResource
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
        'uuid'          => $this->uuid,
        'key'           => $this->key,
        'name'          => $this->name,
        'url'           => $this->url,
        'description'   => $this->description,
        'avatar'        => $this->avatar,
        'is_public'     => $this->is_public,
        'created_admin' => new Admin_R1($this->created_admin),
      ];
    } else {
      $res = [
        'uuid'        => $this->uuid,
        'key'         => $this->key,
        'name'        => $this->name,
        'url'         => $this->url,
        'description' => $this->description,
        'avatar'      => $this->avatar,
        'is_public'   => $this->is_public,
      ];
    }
    return $res;
  }
}
