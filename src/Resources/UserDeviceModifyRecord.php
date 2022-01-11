<?php

namespace Wasateam\Laravelapistone\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Wasateam\Laravelapistone\Resources\User_R1;

class UserDeviceModifyRecord extends JsonResource
{
  /**
   * Transform the resource into an array.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
   */
  public function toArray($request)
  {
    $res = [
      'id'          => $this->id,
      'created_at'  => $this->created_at,
      'updated_at'  => $this->updated_at,
      'action'      => $this->action,
      'remark'      => $this->remark,
      'payload'     => $this->payload,
      'user_device' => new UserDevice_R1($this->user_device),
    ];

    if (config('stone.mode') == 'cms') {
      $res['user'] = new User_R1($this->user);
    }

    return $res;
  }
}
