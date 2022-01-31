<?php

namespace Wasateam\Laravelapistone\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ShopOrder_R2 extends JsonResource
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
        'no' => $this->no,
      ];
    } else if (config('stone.mode') == 'webapi') {
      $res = [
        'id' => $this->id,
        'no' => $this->no,
      ];
    }
    return $res;
  }
}
