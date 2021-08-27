<?php

namespace Wasateam\Laravelapistone\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ServiceStore_R1 extends JsonResource
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
      'id'             => $this->id,
      'name'           => $this->name,
      'type'           => $this->type,
      'cover_image'    => $this->cover_image,
      'tel'            => $this->tel,
      'address'        => $this->address,
      'des'            => $this->des,
      'business_hours' => $this->business_hours,
      'lat'            => $this->lat,
      'lng'            => $this->lng,
      'is_active'      => $this->is_active,
      'payload'        => $this->payload,
    ];
  }
}
