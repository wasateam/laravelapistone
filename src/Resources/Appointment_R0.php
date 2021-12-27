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
    $res = [
      'start_time' => $this->start_time,
      'end_time'   => $this->end_time,
      'start_at'   => $this->start_at,
      'end_at'     => $this->end_at,
      'date'       => $this->date,
    ];

    if (config('stone.country_code')) {
      $res['country_code'] = $this->country_code;
    }

    return $res;
  }
}
