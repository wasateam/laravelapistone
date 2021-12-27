<?php

namespace Wasateam\Laravelapistone\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Appointment_R1 extends JsonResource
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
      'id'         => $this->id,
      'created_at' => $this->created_at,
      'updated_at' => $this->updated_at,
      'start_time' => $this->start_time,
      'end_time'   => $this->end_time,
      'start_at'   => $this->start_at,
      'end_at'     => $this->end_at,
      'date'       => $this->date,
      'tel'        => $this->tel,
      'email'      => $this->email,
      'type'       => $this->type,
      'remark'     => $this->remark,
      'user'       => new User_R1($this->user),
    ];

    if (config('stone.country_code')) {
      $res['country_code'] = $this->country_code;
    }

    return $res;
  }
}
