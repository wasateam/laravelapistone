<?php

namespace Wasateam\Laravelapistone\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ShopProductImage extends JsonResource
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
        'url'          => $this->url,
        'name'         => $this->name,
        'sq'           => $this->sq,
        'shop_product' => new ShopProduct_R1($this->sq),
      ];
      if (config('stone.shop.uuid')) {
        $res['uuid'] = $this->uuid;
      }
    } else if (config('stone.mode') == 'webapi') {
      $res = [
        'url'          => $this->url,
        'name'         => $this->name,
        'sq'           => $this->sq,
        'shop_product' => new ShopProduct_R1($this->sq),
      ];
      if (config('stone.shop.uuid')) {
        $res['uuid'] = $this->uuid;
      }
    }
    return $res;
  }
}
