<?php

namespace Wasateam\Laravelapistone\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;

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
    $status = null;
    $today = Carbon::now()->format('Y-m-d');
    if($this->start_date > $today){
      $status = 'non-start';
    } else if ($this->start_date <= $today && $this->end_date > $today){
      $status = 'in-progress';
    } else if ($this->end_date <= $today) {
      $status = 'end';
    }
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
        "feedback_rate"     => $this->feedback_rate,
        "shop_products"    => ShopProduct_R1::collection($this->shop_products),
        "status"            => $status
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
        "feedback_rate"     => $this->feedback_rate,
        "shop_products"    => ShopProduct_R1::collection($this->shop_products),
        "status"            => $status
      ];
    }
    return $res;
  }
}
