<?php

namespace Wasateam\Laravelapistone\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Image extends JsonResource
{
  /**
   * Transform the resource into an array.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return array
   */
  public function toArray($request)
  {
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
  }
}
