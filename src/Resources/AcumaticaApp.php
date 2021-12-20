<?php

namespace Wasateam\Laravelapistone\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AcumaticaApp extends JsonResource
{
  /**
   * Transform the resource into an array.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return array
   */
  public function toArray($request)
  {
    $res = [
      'id'            => $this->id,
      'client_id'     => $this->client_id,
      'client_secret' => $this->client_secret,
      'country_code'  => $this->country_code,
      'updated_at'    => $this->updated_at,
      'created_at'    => $this->created_at,
    ];

    return $res;
  }
}
