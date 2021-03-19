<?php

namespace Wasateam\Laravelapistone\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PocketImage extends JsonResource
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
        'updated_admin' => new Admin_R1($this->updated_admin),
        'created_admin' => new Admin_R1($this->created_admin),
        'created_at'    => $this->created_at,
        'updated_at'    => $this->updated_at,
        'signed'        => $this->signed,
        'url'           => $this->url,
        'signed_url'    => $this->signed_url,
        'name'          => $this->name,
        'tags'          => $this->tags,
      ];
    } else if (config('stone.mode') == 'webapi') {
      return [
        'id'         => $this->id,
        'signed'     => $this->signed,
        'url'        => $this->url,
        'signed_url' => $this->signed_url,
      ];
    }
  }
}
