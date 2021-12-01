<?php

namespace Wasateam\Laravelapistone\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AdminMy extends JsonResource
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
      'id'                => $this->id,
      'name'              => $this->name,
      'email'             => $this->email,
      'avatar'            => $this->avatar,
      'scopes'            => AuthHelper::getUserScopes($this, 'admin'),
      'locale'            => $this->locale,
      'created_at'        => $this->created_at,
      'updated_at'        => $this->updated_at,
      'email_verified_at' => $this->email_verified_at,
      'country_code'      => $this->country_code,
      'color'             => $this->color,
      'payload'           => $this->payload,
      'pocket_avatar'     => new PocketImage_R1($this->pocket_avatar),
    ];

    if (config('stone.admin_group')) {
      if (config('stone.admin_blur')) {
        $res['cmser_groups'] = AdminGroup_R1::collection($this->cmser_groups);
      } else {
        $res['admin_groups'] = AdminGroup_R1::collection($this->admin_groups);
      }
    }

    return $res;
  }
}
