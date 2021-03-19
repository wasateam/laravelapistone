<?php

namespace Wasateam\Laravelapistone\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PocketImage_R1 extends JsonResource
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
        'signed'     => $this->signed,
        'url'        => $this->url,
        'signed_url' => $this->signed_url,
        'name'       => $this->name,
      ];
    } else
    if (config('stone.mode') == 'webapi') {
      return [
        'id'         => $this->id,
        'signed'     => $this->signed,
        'url'        => $this->url,
        'signed_url' => $this->signed_url,
      ];
    }
  }
}
