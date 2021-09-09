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
      'id'            => $this->id,
      'created_at'    => $this->created_at,
      'updated_at'    => $this->updated_at,
      'start_time'    => $this->start_time,
      'end_time'      => $this->end_time,
      'date'          => $this->date,
      'tel'           => $this->tel,
      'email'         => $this->email,
      'type'          => $this->type,
      'remark'        => $this->remark,
      'user'          => new User_R1($this->user),
      'service_store' => new ServiceStore_R1($this->service_store),
    ];
  }
}
