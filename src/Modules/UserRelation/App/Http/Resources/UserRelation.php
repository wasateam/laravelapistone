<?php

namespace Wasateam\Laravelapistone\Modules\UserRelation\App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserRelation extends JsonResource
{
  public function toArray($request)
  {
    if (config('stone.mode') == 'cms') {
      $res = [
        'id'         => $this->id,
        'relation'   => $this->relation,
        'created_at' => $this->created_at,
        'updated_at' => $this->updated_at,
        'user'       => new \Wasateam\Laravelapistone\Resources\User_R1($this->user),
        'target'     => new \Wasateam\Laravelapistone\Resources\User_R1($this->target),
      ];
    }
    if (config('stone.mode') == 'webapi') {
      $res = [
        'created_at' => $this->created_at,
      ];
    }
    return $res;
  }
}
