<?php

namespace Wasateam\Laravelapistone\Modules\UserPosition\App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserPosition_R1 extends JsonResource
{
  public function toArray($request)
  {
    if (config('stone.mode') == 'cms') {
      $res = [
        'id'         => $this->id,
        'lat'        => $this->lat,
        'lng'        => $this->lng,
        'created_at' => $this->created_at,
        'updated_at' => $this->updated_at,
        'user'       => new \Wasateam\Laravelapistone\Resources\User_R1($this->user),
      ];
    }
    if (config('stone.mode') == 'webapi') {
      $res = [
        'lat'        => $this->lat,
        'lng'        => $this->lng,
        'created_at' => $this->created_at,
      ];
    }
    return $res;
  }
}
