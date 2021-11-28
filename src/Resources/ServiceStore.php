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
        'parking_infos'        => $this->parking_infos,
        'parking_link'         => $this->parking_link,
        'parking_image'        => $this->parking_image,
        'transportation_info'  => $this->transportation_info,
        'work_on_holiday'      => $this->work_on_holiday,
        'service_at_night'     => $this->service_at_night,
        'service_store_closes' => ServiceStoreClose_R1::collection($this->service_store_closes),
        'service_store_notis'  => ServiceStoreNoti_R1::collection($this->service_store_notis),
      ];
      if (config('stone.admin_group')) {
        if (config('stone.admin_blur')) {
          $res['cmser_groups'] = AdminGroup_R1::collection($this->cmser_groups);
        } else {
          $res['admin_groups'] = AdminGroup_R1::collection($this->admin_groups);
        }
      }
      if (config('stone.appointment')) {
        $res['appointment_availables'] = $this->appointment_availables;
        $res['appointments']           = Appointment_R1::collection($this->appointments);
      }
      if (config('stone.area')) {
        $res['area'] = new Area_R1($this->area);
      }
      if (config('stone.country_code')) {
        $res['country_code'] = $this->country_code;
      }
    } else if (config('stone.mode') == 'webapi') {
      $res = [
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
        'parking_infos'        => $this->parking_infos,
        'parking_link'         => $this->parking_link,
        'parking_image'        => $this->parking_image,
        'transportation_info'  => $this->transportation_info,
        'work_on_holiday'      => $this->work_on_holiday,
        'service_at_night'     => $this->service_at_night,
        'service_store_closes' => ServiceStoreClose_R1::collection($this->service_store_closes),
        'service_store_notis'  => ServiceStoreNoti_R1::collection($this->service_store_notis),
      ];
      if (config('stone.appointment')) {
        $res['appointment_availables'] = $this->appointment_availables;
        $res['appointments']           = Appointment_R0::collection($this->appointments);
      }
      if (config('stone.area')) {
        $res['area'] = new Area_R1($this->area);
      }
      if (config('stone.country_code')) {
        $res['country_code'] = $this->country_code;
      }
    }
    return $res;
  }
}
