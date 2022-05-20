<?php

namespace Wasateam\Laravelapistone\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ShopProduct_R4 extends JsonResource
{
  /**
   * Transform the resource into an array.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return array
   */
  public function toArray($request)
  {
    if (config('stone.mode') == 'cms') {
      $res = [
        'no'                   => $this->no,
        'storage_space'        => $this->storage_space,
        'weight_capacity_unit' => $this->weight_capacity_unit,
      ];
    }
    return $res;
  }
}
