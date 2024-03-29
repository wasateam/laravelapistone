<?php

namespace Wasateam\Laravelapistone\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Admin_R1 extends JsonResource
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
      'name'          => $this->name,
      'country_code'  => $this->country_code,
      'color'         => $this->color,
      'payload'       => $this->payload,
      'pocket_avatar' => new PocketImage_R1($this->pocket_avatar),
    ];
  }
}
