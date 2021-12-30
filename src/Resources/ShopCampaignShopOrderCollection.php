<?php
namespace Wasateam\Laravelapistone\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ShopCampaignShopOrderCollection extends JsonResource
{
  /**
   * Transform the resource collection into an array.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
   */
  public function toArray($request)
  {
    return [
      'id'            => $this->id,
      'shop_order'    => new ShopOrder_R1($this->shop_order),
      'shop_campaign' => new ShopCampaign_R1($this->shop_campaign),
    ];
  }
}
