<?php

namespace Wasateam\Laravelapistone\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ServiceStore extends JsonResource
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
        'id'                   => $this->id,
        'updated_admin'        => new Admin_R1($this->updated_admin),
        'created_admin'        => new Admin_R1($this->created_admin),
        'updated_at'           => $this->updated_at,
        'name'                 => $this->name,
        'type'                 => $this->type,
        'cover_image'          => $this->cover_image,
        'tel'                  => $this->tel,
        'address'              => $this->address,
        'des'                  => $this->des,
        'business_hours'       => $this->business_hours,
        'lat'                  => $this->lat,
        'lng'                  => $this->lng,
        'is_active'            => $this->is_active,
        'payload'              => $this->payload,
        'parking_info'         => $this->parking_info,
        'transportation_info'  => $this->transportation_info,
        'service_store_closes' => ServiceStoreClose_R1::collection($this->service_store_closes),
        'service_store_notis'  => ServiceStoreNoti_R1::collection($this->service_store_notis),
      ];
      if (config('stone.admin_group')) {
        $res['admin_groups'] = AdminGroup_R1::collection($this->admin_groups);
      }
      return $res;
    } else if (config('stone.mode') == 'webapi') {
      return [
        'id'                   => $this->id,
        'name'                 => $this->name,
        'type'                 => $this->type,
        'cover_image'          => $this->cover_image,
        'tel'                  => $this->tel,
        'address'              => $this->address,
        'des'                  => $this->des,
        'business_hours'       => $this->business_hours,
        'lat'                  => $this->lat,
        'lng'                  => $this->lng,
        'payload'              => $this->payload,
        'parking_info'         => $this->parking_info,
        'transportation_info'  => $this->transportation_info,
        'service_store_closes' => ServiceStoreClose_R1::collection($this->service_store_closes),
        'service_store_notis'  => ServiceStoreNoti_R1::collection($this->service_store_notis),
      ];
    }
  }
}
