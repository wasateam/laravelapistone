<?php

namespace Wasateam\Laravelapistone\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Appointment extends JsonResource
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
      'id'                    => $this->id,
      'created_at'            => $this->created_at,
      'updated_at'            => $this->updated_at,
      'start_time'            => $this->start_time,
      'end_time'              => $this->end_time,
      'date'                  => $this->date,
      'user'                  => new User_R1($this->user),
      'appointment_available' => new AppointmentAvailable_R1($this->appointment_available),
    ];
  }
}
