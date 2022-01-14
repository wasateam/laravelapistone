<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ShopProductSpec extends JsonResource
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
        'no'                              => $this->no,
        'on_time'                         => $this->on_time,
        'off_time'                        => $this->off_time,
        'cost'                            => $this->cost,
        'price'                           => $this->price,
        'discount_price'                  => $this->discount_price,
        'freight'                         => $this->freight,
        'stock_count'                     => $this->stock_count,
        'stock_alert_count'               => $this->stock_alert_count,
        'storage_space'                   => $this->storage_space,
        'max_buyable_count'               => $this->max_buyable_count,
        'shop_product'                    => new ShopProduct_R0($this->shop_product),
        'shop_product_spec_settings'      => ShopProductSpecSetting_R1::collection($this->shop_product_spec_settings),
        'shop_product_spec_setting_items' => ShopProductSpecSettingItem_R1::collection($this->shop_product_spec_setting_items),
      ];
    } else if (config('stone.mode') == 'webapi') {
      $res = [
        'id'                              => $this->id,
        'no'                              => $this->no,
        'price'                           => $this->price,
        'discount_price'                  => $this->discount_price,
        'freight'                         => $this->freight,
        'stock_count'                     => $this->stock_count,
        'max_buyable_count'               => $this->max_buyable_count,
        'shop_product'                    => new ShopProduct_R0($this->shop_product),
        'shop_product_spec_settings'      => ShopProductSpecSetting_R1::collection($this->shop_product_spec_settings),
        'shop_product_spec_setting_items' => ShopProductSpecSettingItem_R1::collection($this->shop_product_spec_setting_items),
      ];
    }
    return $res;
  }
}
