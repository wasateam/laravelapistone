<?php

namespace Wasateam\Laravelapistone\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Wasateam\Laravelapistone\Resources\User_R1;

class UserDevice extends JsonResource
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
      'created_at'    => $this->created_at,
      'updated_at'    => $this->updated_at,
      'type'          => $this->type,
      'is_diy'        => $this->is_diy,
      'serial_number' => $this->serial_number,
      'brand'         => $this->brand,
      'product_code'  => $this->product_code,
      'country_code'  => $this->country_code,
      'uuid'          => $this->uuid,
      'status'        => $this->status,
    ];

    if (config('stone.mode') == 'cms') {
      $res['user'] = new User_R1($this->user);
    }

    return $res;
  }
}
