<?php

namespace Wasateam\Laravelapistone\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class User_R1_CMS extends JsonResource
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
      'uuid'          => $this->uuid,
      'name'          => $this->name,
      'email'         => $this->email,
      'tel'           => $this->tel,
      'color'         => $this->color,
      'pocket_avatar' => new PocketImage_R1($this->pocket_avatar),
      'locale'        => new Locale_R1($this->locale),
    ];
  }
}
