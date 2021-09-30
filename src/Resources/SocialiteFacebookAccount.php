<?php

namespace Wasateam\Laravelapistone\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SocialiteFacebookAccount extends JsonResource
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
        'provider_user_id' => $this->provider_user_id,
        'provider'         => $this->provider,
        'user_id'          => new User_R1($this->user_id),
      ];
      return $res;
    }
  }
}
