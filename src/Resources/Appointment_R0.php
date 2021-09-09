<?php

namespace Wasateam\Laravelapistone\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Appointment_R0 extends JsonResource
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
      'start_time' => $this->start_time,
      'end_time'   => $this->end_time,
      'date'       => $this->date,
      'tel'        => $this->tel,
      'email'      => $this->email,
      'type'       => $this->type,
      'remark'     => $this->remark,
    ];
  }
}
