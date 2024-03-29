<?php

namespace Wasateam\Laravelapistone\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AdminRole extends JsonResource
{
  /**
   * Transform the resource into an array.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return array
   */
  public function toArray($request)
  {
    return [
      'id'            => $this->id,
      'name'          => $this->name,
      'is_default'    => $this->is_default,
      'scopes'        => $this->scopes,
      'created_at'    => $this->created_at,
      'updated_at'    => $this->updated_at,
    ];
  }
}
