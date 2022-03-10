<?php

namespace Wasateam\Laravelapistone\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ShopProductSpecSettingItem_R2 extends JsonResource
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
        'id'                        => $this->id,
        'name'                      => $this->name,
        'shop_product_spec_setting' => new ShopProductSpecSetting_R2($this->shop_product_spec_setting),
      ];
    } else if (config('stone.mode') == 'webapi') {
      $res = [
        'id'                        => $this->id,
        'name'                      => $this->name,
        'shop_product_spec_setting' => new ShopProductSpecSetting_R2($this->shop_product_spec_setting),
      ];
    }
    return $res;
  }
}
