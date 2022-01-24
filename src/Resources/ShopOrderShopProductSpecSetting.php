<?php

namespace Wasateam\Laravelapistone\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ShopOrderShopProductSpecSetting extends JsonResource
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
        'id'                        => $this->id,
        'updated_at'                => $this->updated_at,
        'created_at'                => $this->created_at,
        'sq'                        => $this->sq,
        'name'                      => $this->name,
        'shop_order_shop_product'   => new ShopOrderShopProduct_R0($this->shop_order_shop_product),
        'shop_product_spec_setting' => new ShopProductSpecSetting_R0($this->shop_product_spec_setting),
      ];
    } else if (config('stone.mode') == 'webapi') {
      $res = [
        'id'                        => $this->id,
        'name'                      => $this->name,
        'shop_order_shop_product'   => new ShopOrderShopProduct_R0($this->shop_order_shop_product),
        'shop_product_spec_setting' => new ShopProductSpecSetting_R0($this->shop_product_spec_setting),
      ];
    }
    return $res;
  }
}
