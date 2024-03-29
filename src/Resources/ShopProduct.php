<?php

namespace Wasateam\Laravelapistone\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ShopProduct extends JsonResource
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
        'created_at'                 => $this->created_at,
        'updated_at'                 => $this->updated_at,
        'id'                         => $this->id,
        'no'                         => $this->no,
        'type'                       => $this->type,
        'order_type'                 => $this->order_type,
        'name'                       => $this->name,
        'subtitle'                   => $this->subtitle,
        'status'                     => $this->status,
        'on_time'                    => $this->on_time,
        'off_time'                   => $this->off_time,
        'is_active'                  => $this->is_active,
        'spec'                       => $this->spec,
        'cost'                       => $this->cost,
        'price'                      => $this->price,
        'weight_capacity'            => $this->weight_capacity,
        'weight_capacity_unit'       => $this->weight_capacity_unit,
        'show_weight_capacity'       => $this->show_weight_capacity,
        'tax'                        => $this->tax,
        'stock_count'                => $this->stock_count,
        'stock_alert_count'          => $this->stock_alert_count,
        'max_buyable_count'          => $this->max_buyable_count,
        'storage_space'              => $this->storage_space,
        'freight'                    => $this->freight,
        'store_temperature'          => $this->store_temperature,
        'cover_image'                => $this->cover_image,
        'images'                     => $this->images,
        'description'                => $this->description,
        'ranking_score'              => $this->ranking_score,
        'purchaser'                  => $this->purchaser,
        'cold_chain_type'            => $this->cold_chain_type,
        'store_house_class'          => $this->store_house_class,
        'store_house_subclass'       => $this->store_house_subclass,
        'shop_product_cover_frame'   => new ShopProductCoverFrame_R1($this->shop_product_cover_frame),
        'suggests'                   => ShopProduct_R1::collection($this->suggests),
        'shop_classes'               => ShopClass_R2::collection($this->shop_classes),
        'shop_subclasses'            => ShopSubclass_R1::collection($this->shop_subclasses),
        'shop_product_specs'         => ShopProductSpec::collection($this->shop_product_specs),
        'shop_product_spec_settings' => ShopProductSpecSetting_R1::collection($this->shop_product_spec_settings),
        'shop_campaigns'             => ShopCampaign_R1::collection($this->shop_campaigns),
      ];
      if (config('stone.area')) {
        $res['areas']         = Area_R1::collection($this->areas);
        $res['area_sections'] = AreaSection_R1::collection($this->area_sections);
      }
      if (config('stone.featured_class')) {
        $res['featured_classes'] = FeaturedClass_R1::collection($this->featured_classes);
      }
      if (config('stone.shop.uuid')) {
        $res['uuid'] = $this->uuid;
      }
      if (config('stone.shop.discount_price')) {
        $res['discount_price'] = $this->discount_price;
      }
    } else if (config('stone.mode') == 'webapi') {
      $res = [
        'id'                         => $this->id,
        'no'                         => $this->no,
        'type'                       => $this->type,
        'order_type'                 => $this->order_type,
        'name'                       => $this->name,
        'subtitle'                   => $this->subtitle,
        'status'                     => $this->status,
        'spec'                       => $this->spec,
        'price'                      => $this->price,
        'weight_capacity'            => $this->show_weight_capacity ? $this->weight_capacity : null,
        'weight_capacity_unit'       => $this->show_weight_capacity ? $this->weight_capacity_unit : null,
        'show_weight_capacity'       => $this->show_weight_capacity,
        'tax'                        => $this->tax,
        'stock_count'                => $this->stock_count,
        'max_buyable_count'          => $this->max_buyable_count,
        'cover_image'                => $this->cover_image,
        'images'                     => $this->images,
        'description'                => $this->description,
        'freight'                    => $this->freight,
        'store_temperature'          => $this->store_temperature,
        'cold_chain_type'            => $this->cold_chain_type,
        'shop_product_cover_frame'   => new ShopProductCoverFrame_R1($this->shop_product_cover_frame),
        'suggests'                   => ShopProduct_R1::collection($this->suggests),
        'shop_classes'               => ShopClass_R1::collection($this->shop_classes),
        'shop_subclasses'            => ShopSubclass_R1::collection($this->shop_subclasses),
        'shop_product_specs'         => ShopProductSpec::collection($this->shop_product_specs),
        'shop_product_spec_settings' => ShopProductSpecSetting_R1::collection($this->shop_product_spec_settings),
        'shop_campaigns'             => ShopCampaign_R1::collection($this->shop_campaigns),
      ];
      if (config('stone.area')) {
        $res['areas']         = Area_R1::collection($this->areas);
        $res['area_sections'] = AreaSection_R1::collection($this->area_sections);
      }
      if (config('stone.featured_class')) {
        $res['featured_classes'] = FeaturedClass_R1::collection($this->featured_classes);
      }
      if (config('stone.shop.uuid')) {
        $res['uuid'] = $this->uuid;
      }
      if (config('stone.shop.discount_price')) {
        $res['discount_price'] = $this->discount_price;
      }
    }
    return $res;
  }
}
