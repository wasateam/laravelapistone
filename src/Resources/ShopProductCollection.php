<?php

namespace Wasateam\Laravelapistone\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

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
        'tax'                                 => $this->tax,
        'stock_count'                         => $this->stock_count,
        'stock_alert_count'                   => $this->stock_alert_count,
        'max_buyable_count'                   => $this->max_buyable_count,
        'storage_space'                       => $this->storage_space,
        'cover_image'                         => $this->cover_image,
        'description'                         => $this->description,
        'ranking_score'                       => $this->ranking_score,
        'today_shop_order_shop_product_count' => $this->today_shop_order_shop_products->count(),
      ];
      if (config('stone.shop.uuid')) {
        $res['uuid'] = $this->uuid;
      }
      if (config('stone.featured_class')) {
        $res['featured_classes'] = FeaturedClass_R0::collection($this->featured_classes);
      }
    } else if (config('stone.mode') == 'webapi') {
      $res = [
        'id'                          => $this->id,
        'type'                        => $this->type,
        'no'                          => $this->no,
        'name'                        => $this->name,
        'subtitle'                    => $this->subtitle,
        'status'                      => $this->status,
        'spec'                        => $this->spec,
        'price'                       => $this->price,
        'discount_price'              => $this->discount_price,
        'weight_capacity'             => $this->weight_capacity,
        'weight_capacity_unit'        => $this->weight_capacity_unit,
        'tax'                         => $this->tax,
        'stock_count'                 => $this->stock_count,
        'max_buyable_count'           => $this->max_buyable_count,
        'cover_image'                 => $this->cover_image,
        'shop_product_cover_frame_id' => $this->shop_product_cover_frame_id,
        'description'                 => $this->description,
        'store_temperature'           => $this->store_temperature,
        'shop_classes'                => ShopClass_R0::collection($this->shop_classes),
        'shop_subclasses'             => ShopSubclass_R0::collection($this->shop_subclasses),
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
