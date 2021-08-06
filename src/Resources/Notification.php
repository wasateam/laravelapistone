<?php

namespace Wasateam\Laravelapistone\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Notification extends JsonResource
{
  /**
   * Transform the resource into an array.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return array
   */
  public function toArray($request)
  {
    $res = [
      'id'              => $this->id,
      'created_at'      => $this->created_at,
      'updated_at'      => $this->updated_at,
      'notifiable_type' => $this->notifiable_type,
      'notifiable_id'   => $this->notifiable_id,
      'data'            => $this->data,
      'read_at'         => $this->read_at,
    ];

    if (config('stone.mode') == 'cms') {
      $res['admin'] = new Admin_R1($this->admin);
    } else if (config('stone.mode') == 'webapi') {
      $res['user'] = new User_R1($this->user);
    }

    return $res;
  }
}
