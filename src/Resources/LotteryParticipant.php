<?php

namespace Wasateam\Laravelapistone\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class LotteryParticipant extends JsonResource
{
  /**
   * Transform the resource into an array.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
   */
  public function toArray($request)
  {
    if (config('stone.mode') == 'cms') {
      $res = [
        'id'             => $this->id,
        'updated_at'     => $this->updated_at,
        'created_at'     => $this->created_at,
        'id_number'      => $this->id_number,
        'name'           => $this->name,
        'gender'         => $this->gender,
        'birthday'       => $this->birthday,
        'email'          => $this->email,
        'mobile'         => $this->mobile,
        'uuid'           => $this->uuid,
        'qualifications' => $this->qualifications,
      ];
    } else if (config('stone.mode') == 'webapi') {
      $res = [
        'id'             => $this->id,
        'updated_at'     => $this->updated_at,
        'created_at'     => $this->created_at,
        'id_number'      => $this->id_number,
        'name'           => $this->name,
        'gender'         => $this->gender,
        'birthday'       => $this->birthday,
        'email'          => $this->email,
        'mobile'         => $this->mobile,
        'uuid'           => $this->uuid,
        'qualifications' => $this->qualifications,
      ];
    }
    return $res;
  }
}
