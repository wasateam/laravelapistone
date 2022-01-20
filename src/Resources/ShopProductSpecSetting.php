<?php

namespace Wasateam\Laravelapistone\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ShopProductSpecSetting extends JsonResource
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
        'id'                              => $this->id,
        'updated_at'                      => $this->updated_at,
        'created_at'                      => $this->created_at,
        'sq'                              => $this->sq,
        'name'                            => $this->name,
        'shop_product'                    => new ShopProduct_R0($this->shop_product),
        'shop_product_spec_setting_items' => ShopProductSpecSettingItem_R1::collection($this->shop_product_spec_setting_items),
      ];
    } else if (config('stone.mode') == 'webapi') {
      $res = [
        'id'                              => $this->id,
        'name'                            => $this->name,
        'shop_product'                    => new ShopProduct_R0($this->shop_product),
        'shop_product_spec_setting_items' => ShopProductSpecSettingItem_R1::collection($this->shop_product_spec_setting_items),
      ];
    }
    return $res;
  }
}
