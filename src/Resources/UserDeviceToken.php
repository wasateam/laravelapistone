<?php

namespace Wasateam\Laravelapistone\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserDeviceToken extends JsonResource
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
      return [
        'id'            => $this->id,
        'updated_admin' => new Admin_R1($this->updated_admin),
        'created_admin' => new Admin_R1($this->created_admin),
        'created_at'    => $this->created_at,
        'updated_at'    => $this->updated_at,
        'is_active'     => $this->is_active,
        'device_token'  => $this->device_token,
        'remark'        => $this->remark,
        'user'          => new User_R1($this->user),
      ];
    } else if (config('stone.mode') == 'webapi') {
      return [
        'is_active'    => $this->is_active,
        'device_token' => $this->device_token,
      ];
    }
  }
}
