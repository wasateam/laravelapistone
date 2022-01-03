<?php

namespace Wasateam\Laravelapistone\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ShopCampaignShopOrder extends JsonResource
{
  /**
   * Transform the resource into an array.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
   */
  public function toArray($request)
  {
    return [
      'id'            => $this->id,
      'created_at'    => $this->created_at,
      'updated_at'    => $this->updated_at,
      'shop_order'    => new ShopOrder_R1($this->shop_order),
      'shop_campaign' => new ShopCampaign_R1($this->shop_campaign),
    ];
  }
}
