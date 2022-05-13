<?php

namespace Wasateam\Laravelapistone\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ShopProduct_R3 extends JsonResource
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
        'id' => $this->id,
      ];
    } else if (config('stone.mode') == 'webapi') {
      $res = [
        'id' => $this->id,
      ];
    }
    return $res;
  }
}
