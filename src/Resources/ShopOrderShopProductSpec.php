<?php

namespace Wasateam\Laravelapistone\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ShopOrderShopProductSpec extends JsonResource
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
        'id'                                         => $this->id,
        'updated_at'                                 => $this->updated_at,
        'created_at'                                 => $this->created_at,
        'cost'                                       => $this->cost,
        'price'                                      => $this->price,
        'discount_price'                             => $this->discount_price,
        'freight'                                    => $this->freight,
        'stock_count'                                => $this->stock_count,
        'stock_alert_count'                          => $this->stock_alert_count,
        'max_buyable_count'                          => $this->max_buyable_count,
        'shop_order_shop_product'                    => new ShopOrderShopProduct_R0($this->shop_order_shop_product),
        'shop_product_spec'                          => new ShopProductSpec_R0($this->shop_product_spec),
        'shop_order_shop_product_spec_settings'      => ShopOrderShopProductSpecSetting_R0::collection($this->shop_order_shop_product_spec_settings),
        'shop_order_shop_product_spec_setting_items' => ShopOrderShopProductSpecSettingItem_R0::collection($this->shop_order_shop_product_spec_setting_items),
      ];
    } else if (config('stone.mode') == 'webapi') {
      $res = [
        'id'                                         => $this->id,
        'cost'                                       => $this->cost,
        'price'                                      => $this->price,
        'discount_price'                             => $this->discount_price,
        'freight'                                    => $this->freight,
        'stock_count'                                => $this->stock_count,
        'stock_alert_count'                          => $this->stock_alert_count,
        'max_buyable_count'                          => $this->max_buyable_count,
        'shop_product_spec'                          => new ShopProductSpec_R0($this->shop_product_spec),
        'shop_order_shop_product'                    => new ShopOrderShopProduct_R0($this->shop_order_shop_product),
        'shop_order_shop_product_spec_settings'      => ShopOrderShopProductSpecSetting_R0::collection($this->shop_order_shop_product_spec_settings),
        'shop_order_shop_product_spec_setting_items' => ShopOrderShopProductSpecSettingItem_R0::collection($this->shop_order_shop_product_spec_setting_items),
      ];
    }
    return $res;
  }
}
