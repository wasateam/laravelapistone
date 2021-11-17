<?php

namespace Wasateam\Laravelapistone\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserAddress extends JsonResource
{
  /**
   * Transform the resource into an array.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
   */
  public function toArray($request)
  {
    return [
      "id"           => $this->id,
      'created_at'   => $this->created_at,
      'updated_at'   => $this->updated_at,
      'address'      => $this->address,
      'type'         => $this->type,
      'user'         => new User_R1($this->user),
      'area'         => new Area($this->area),
      'area_section' => new AreaSection($this->area_section),
    ];
  }
}
