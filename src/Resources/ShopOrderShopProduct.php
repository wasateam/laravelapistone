<?php

namespace Wasateam\Laravelapistone\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ShopOrderShopProduct extends JsonResource
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
        'type'                                       => $this->type,
        'created_at'                                 => $this->created_at,
        'updated_at'                                 => $this->updated_at,
        'name'                                       => $this->name,
        'subtitle'                                   => $this->subtitle,
        'price'                                      => $this->price,
        'spec'                                       => $this->spec,
        'weight_capacity'                            => $this->weight_capacity,
        'weight_capacity_unit'                       => $this->weight_capacity_unit,
        'cover_image'                                => $this->cover_image,
        'cost'                                       => $this->cost,
        'count'                                      => $this->count,
        'original_count'                             => $this->original_count,
        'order_type'                                 => $this->order_type,
        'freight'                                    => $this->freight,
        'shop_order'                                 => new ShopOrder_R1($this->shop_order),
        'shop_product'                               => new ShopProduct_R0($this->shop_product),
        'shop_cart_product'                          => new ShopCartProduct($this->shop_cart_product),
        'shop_return_records'                        => ShopReturnRecord_R0::collection($this->shop_return_records),
        'shop_order_shop_product_spec'               => new ShopOrderShopProductSpec_R1($this->shop_order_shop_product_spec),
        'shop_order_shop_product_spec_settings'      => ShopOrderShopProductSpecSetting_R0::collection($this->shop_order_shop_product_spec_settings),
        'shop_order_shop_product_spec_setting_items' => ShopOrderShopProductSpecSettingItem_R0::collection($this->shop_order_shop_product_spec_setting_items),
      ];
      if (config('stone.shop.discount_price')) {
        $res['discount_price'] = $this->discount_price;
      }
    } else if (config('stone.mode') == 'webapi') {
      $res = [
        'id'                                         => $this->id,
        'type'                                       => $this->type,
        'name'                                       => $this->name,
        'subtitle'                                   => $this->subtitle,
        'price'                                      => $this->price,
        'spec'                                       => $this->spec,
        'weight_capacity'                            => $this->weight_capacity,
        'weight_capacity_unit'                       => $this->weight_capacity_unit,
        'cover_image'                                => $this->cover_image,
        'cost'                                       => $this->cost,
        'count'                                      => $this->count,
        'original_count'                             => $this->original_count,
        'order_type'                                 => $this->order_type,
        'freight'                                    => $this->freight,
        'shop_order'                                 => new ShopOrder_R1($this->shop_order),
        'shop_product'                               => new ShopProduct_R0($this->shop_product),
        'shop_cart_product'                          => new ShopCartProduct($this->shop_cart_product),
        'shop_return_records'                        => ShopReturnRecord_R0::collection($this->shop_return_records),
        'shop_order_shop_product_spec'               => new ShopOrderShopProductSpec_R1($this->shop_order_shop_product_spec),
        'shop_order_shop_product_spec_settings'      => ShopOrderShopProductSpecSetting_R0::collection($this->shop_order_shop_product_spec_settings),
        'shop_order_shop_product_spec_setting_items' => ShopOrderShopProductSpecSettingItem_R0::collection($this->shop_order_shop_product_spec_setting_items),
      ];
      if (config('stone.shop.discount_price')) {
        $res['discount_price'] = $this->discount_price;
      }
    }
    return $res;
  }
}
