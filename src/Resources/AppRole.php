<?php

namespace Wasateam\Laravelapistone\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AppRole extends JsonResource
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
        'id'         => $this->id,
        'created_at' => $this->created_at,
        'updated_at' => $this->updated_at,
        "name"       => $this->name,
        "scopes"     => $this->scopes,
        "app"        => new App_R1($this->app),
      ];
    } else {
      $res = [
        'id'         => $this->id,
        'created_at' => $this->created_at,
        'updated_at' => $this->updated_at,
        "name"       => $this->name,
        "scopes"     => $this->scopes,
        "app_id"     => $this->app_id,
      ];
    }
    return $res;
  }
}
