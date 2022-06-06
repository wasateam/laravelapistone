<?php

namespace Wasateam\Laravelapistone\Modules\Otp\App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Otp extends JsonResource
{
  public function toArray($request)
  {
    if (config('stone.mode') == 'cms') {
      $res = [
        'id'         => $this->id,
        'is_active'  => $this->is_active,
        'content'    => $this->content,
        'usage'      => $this->usage,
        'expired_at' => $this->expired_at,
        'created_at' => $this->created_at,
        'updated_at' => $this->updated_at,
        'user'       => new \Wasateam\Laravelapistone\Resources\User_R1($this->user),
      ];
    }
    if (config('stone.mode') == 'webapi') {
    }
    return $res;
  }
}
