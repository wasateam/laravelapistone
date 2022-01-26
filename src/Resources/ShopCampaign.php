<?php

namespace Wasateam\Laravelapistone\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ShopCampaign extends JsonResource
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
        "id"               => $this->id,
        "created_at"       => $this->created_at,
        "updated_at"       => $this->updated_at,
        "name"             => $this->name,
        "type"             => $this->type,
        "start_date"       => $this->start_date,
        "end_date"         => $this->end_date,
        "discount_code"    => $this->discount_code,
        "condition"        => $this->condition,
        "full_amount"      => $this->full_amount,
        "discount_percent" => $this->discount_percent,
        "discount_amount"  => $this->discount_amount,
        "limit"            => $this->limit,
        "is_active"        => $this->is_active,
        "discount_way"     => $this->discount_way,
        'shop_product'     => $this->shop_products,
      ];
    } else if (config('stone.mode') == 'webapi') {
      $res = [
        "id"               => $this->id,
        "name"             => $this->name,
        "type"             => $this->type,
        "start_date"       => $this->start_date,
        "end_date"         => $this->end_date,
        "discount_code"    => $this->discount_code,
        "condition"        => $this->condition,
        "full_amount"      => $this->full_amount,
        "discount_percent" => $this->discount_percent,
        "discount_amount"  => $this->discount_amount,
        "limit"            => $this->limit,
        "is_active"        => $this->is_active,
        "discount_way"     => $this->discount_way,
        'shop_product'     => $this->shop_products,
      ];
    }
    return $res;
  }
}
