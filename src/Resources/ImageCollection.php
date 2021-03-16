<?php

namespace Wasateam\Laravelapistone\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ImageCollection extends JsonResource
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
      'id'         => $this->id,
      'signed'     => $this->signed,
      'url'        => $this->url,
      'signed_url' => $this->signed_url,
      'name'       => $this->name,
      'tags'       => $this->tags,
    ];
  }
}
