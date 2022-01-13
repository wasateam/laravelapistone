<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ShopProductSpecSetting_R1 extends JsonResource
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
        'id'   => $this->id,
        'name' => $this->name,
      ];
    } else if (config('stone.mode') == 'webapi') {
      $res = [
        'id'   => $this->id,
        'name' => $this->name,
      ];
    }
    return $res;
  }
}
