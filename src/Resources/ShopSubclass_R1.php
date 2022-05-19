<?php

namespace Wasateam\Laravelapistone\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ShopSubclass_R1 extends JsonResource
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
        'id'         => $this->id,
        'created_at' => $this->created_at,
        'updated_at' => $this->updated_at,
        'name'       => $this->name,
        'sq'         => $this->sq,
        'type'       => $this->type,
        'icon'       => $this->icon,
        'shop_class' => new ShopClass_R0($this->shop_class),
      ];
      if (config('stone.shop.uuid')) {
        $res['uuid'] = $this->uuid;
      }
    } else if (config('stone.mode') == 'webapi') {
      $res = [
        'id'                      => $this->id,
        'name'                    => $this->name,
        'icon'                    => $this->icon,
      ];
      if (config('stone.shop.uuid')) {
        $res['uuid'] = $this->uuid;
      }
    }
    return $res;
  }
}
