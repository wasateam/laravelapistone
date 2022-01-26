<?php

namespace Wasateam\Laravelapistone\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Wasateam\Laravelapistone\Helpers\ShopHelper;

class ShopProductCollection extends JsonResource
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
        'id'                                  => $this->id,
        'type'                                => $this->type,
        'order_type'                          => $this->order_type,
        'no'                                  => $this->no,
        'name'                                => $this->name,
        'subtitle'                            => $this->subtitle,
        'status'                              => $this->status,
        'on_time'                             => $this->on_time,
        'off_time'                            => $this->off_time,
        'is_active'                           => $this->is_active,
        'spec'                                => $this->spec,
        'cost'                                => $this->cost,
        'price'                               => $this->price,
        'discount_price'                      => $this->discount_price,
        'weight_capacity'                     => $this->weight_capacity,
        'weight_capacity_unit'                => $this->weight_capacity_unit,
        'show_weight_capacity'                => $this->show_weight_capacity,
        'tax'                                 => $this->tax,
        'stock_count'                         => $this->stock_count,
        'stock_alert_count'                   => $this->stock_alert_count,
        'max_buyable_count'                   => $this->max_buyable_count,
        'storage_space'                       => $this->storage_space,
        'cover_image'                         => $this->cover_image,
        'description'                         => $this->description,
        'ranking_score'                       => $this->ranking_score,
        'store_temperature'                   => $this->store_temperature,
        'freight'                             => $this->freight,
        'today_shop_order_shop_product_count' => $this->today_shop_order_shop_products->count(),
        'shop_product_cover_frame'            => new ShopProductCoverFrame_R1($this->shop_product_cover_frame)
      ];
      if (config('stone.shop.uuid')) {
        $res['uuid'] = $this->uuid;
      }
      if (config('stone.featured_class')) {
        $res['featured_classes'] = FeaturedClass_R0::collection($this->featured_classes);
      }
    } else if (config('stone.mode') == 'webapi') {
      $price       = ShopHelper::getShopProductPriceRange($this);
      $stock_count = ShopHelper::getShopProductAllStockCount($this);
      $res         = [
        'id'                          => $this->id,
        'type'                        => $this->type,
        'order_type'                  => $this->order_type,
        'no'                          => $this->no,
        'name'                        => $this->name,
        'subtitle'                    => $this->subtitle,
        'status'                      => $this->status,
        'spec'                        => $this->spec,
        'price'                       => $price,
        'discount_price'              => $this->discount_price,
        'weight_capacity'             => $this->show_weight_capacity ? $this->weight_capacity : null,
        'weight_capacity_unit'        => $this->show_weight_capacity ? $this->weight_capacity_unit : null,
        'show_weight_capacity'        => $this->show_weight_capacity,
        'tax'                         => $this->tax,
        'stock_count'                 => $stock_count,
        'max_buyable_count'           => $this->max_buyable_count,
        'cover_image'                 => $this->cover_image,
        'shop_product_cover_frame_id' => $this->shop_product_cover_frame_id,
        'description'                 => $this->description,
        'store_temperature'           => $this->store_temperature,
        'freight'                     => $this->freight,
        'shop_classes'                => ShopClass_R0::collection($this->shop_classes),
        'shop_subclasses'             => ShopSubclass_R_ShopProduct::collection($this->shop_subclasses),
        'shop_product_specs'          => ShopProductSpec_R1::collection($this->shop_product_specs),
        'shop_product_spec_settings'  => ShopProductSpecSetting_R1::collection($this->shop_product_spec_settings),
      ];
      if (config('stone.shop.uuid')) {
        $res['uuid'] = $this->uuid;
      }
      if (config('stone.featured_class')) {
        $res['featured_classes'] = FeaturedClass_R0::collection($this->featured_classes);
      }
    }
    return $res;
  }
}
