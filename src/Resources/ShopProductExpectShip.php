<?php

namespace Wasateam\Laravelapistone\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ShopProductExpectShip extends JsonResource
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
        'id'   => $this->id,
        'name' => $this->name,
        'url'  => $this->url,
      ];
      if (config('stone.shop.uuid')) {
        $res['uuid'] = $this->uuid;
      }
    } else if (config('stone.mode') == 'webapi') {
      $res = [
        'id'   => $this->id,
        'name' => $this->name,
        'url'  => $this->url,
      ];
      if (config('stone.shop.uuid')) {
        $res['uuid'] = $this->uuid;
      }
    }
    return $res;
  }
}
