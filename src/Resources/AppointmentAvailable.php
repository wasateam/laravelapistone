<?php

namespace Wasateam\Laravelapistone\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AppointmentAvailable extends JsonResource
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
      'created_at'    => $this->created_at,
      'updated_at'    => $this->updated_at,
      'start_time'    => $this->start_time,
      'end_time'      => $this->end_time,
      'service_store' => new ServiceStore_R1($this->service_store),
    ];
  }
}
