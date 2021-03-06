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
    return [
      'id'         => $this->id,
      'name'       => $this->name,
      'email'      => $this->email,
      'locale'     => $this->locale,
      'created_at' => $this->created_at,
      'updated_at' => $this->updated_at,
    ];
  }
}
