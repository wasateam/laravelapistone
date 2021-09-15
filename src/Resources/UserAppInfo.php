<?php

namespace Wasateam\Laravelapistone\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserAppInfo extends JsonResource
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
        'id'         => $this->id,
        'created_at' => $this->created_at,
        'updated_at' => $this->updated_at,
        'scopes'     => $this->scopes,
        'status'     => $this->status,
        'user'       => new User_R1($this->user),
        'app'        => new App_R1($this->app),
      ];
    } else if (config('stone.mode') == 'webapi') {
      return [
        'scopes' => $this->scopes,
        'status' => $this->status,
        'app'    => new App_R0($this->app),
      ];
    }
  }
}
