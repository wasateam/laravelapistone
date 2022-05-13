<?php

namespace Wasateam\Laravelapistone\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ShopClassCollection extends JsonResource
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
        'id'              => $this->id,
        'created_at'      => $this->created_at,
        'updated_at'      => $this->updated_at,
        'name'            => $this->name,
        'sq'              => $this->sq,
        'type'            => $this->type,
        'icon'            => $this->icon,
        'is_active'       => $this->is_active,
        'shop_subclasses' => ShopSubclass_R1::collection($this->shop_subclasses),
        'order_type'      => $this->order_type,
      ];
      if (config('stone.shop.uuid')) {
        $res['uuid'] = $this->uuid;
      }
    } else if (config('stone.mode') == 'webapi') {
      $res = [
        'id'              => $this->id,
        'name'            => $this->name,
        'type'            => $this->type,
        'icon'            => $this->icon,
        'shop_subclasses' => ShopSubclass_R1::collection($this->shop_subclasses),
      ];
      if (config('stone.shop.uuid')) {
        $res['uuid'] = $this->uuid;
      }
    }
    return $res;
  }
}
