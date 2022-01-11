<?php

namespace Wasateam\Laravelapistone\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Wasateam\Laravelapistone\Resources\User_R1;

class UserDevice_R1 extends JsonResource
{
  /**
   * Transform the resource into an array.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
   */
  public function toArray($request)
  {
    $res = [
      'id'            => $this->id,
      'type'          => $this->type,
      'is_diy'        => $this->is_diy,
      'serial_number' => $this->serial_number,
      'brand'         => $this->brand,
      'product_code'  => $this->product_code,
      'country_code'  => $this->country_code,
      'uuid'          => $this->uuid,
      'status'        => $this->status,
    ];

    return $res;
  }
}
