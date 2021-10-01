<?php

namespace Wasateam\Laravelapistone\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SocialiteGoogleAccount extends JsonResource
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
        'id'               => $this->id,
        'created_at'       => $this->created_at,
        'updated_at'       => $this->updated_at,
        'provider_user_id' => $this->provider_user_id,
        'provider'         => $this->provider,
        'user'             => new User_R1($this->user),
      ];
      return $res;
    }
  }
}
