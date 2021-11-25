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
    $res = [
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

    if (config('stone.appointment')) {
      $res['appointment_availables'] = $this->appointment_availables;
    }

    if (config('stone.area')) {
      $res['area'] = new Area_R1($this->area);
    }
    return $res;
  }
}
