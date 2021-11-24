<?php

namespace Wasateam\Laravelapistone\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ContactRequestNotifyMail extends JsonResource
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
        'id'           => $this->id,
        'created_at'   => $this->created_at,
        'updated_at'   => $this->updated_at,
        'is_active'    => $this->is_active,
        'name'         => $this->name,
        'mail'         => $this->mail,
        'remark'       => $this->remark,
        'country_code' => $this->country_code,
      ];
    } else if (config('stone.mode') == 'webapi') {
      return [
        'id'           => $this->id,
        'created_at'   => $this->created_at,
        'updated_at'   => $this->updated_at,
        'is_active'    => $this->is_active,
        'name'         => $this->name,
        'mail'         => $this->mail,
        'remark'       => $this->remark,
        'country_code' => $this->country_code,
      ];
    }
  }
}
