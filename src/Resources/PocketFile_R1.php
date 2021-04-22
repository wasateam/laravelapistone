<?php

namespace Wasateam\Laravelapistone\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PocketFile_R1 extends JsonResource
{
  /**
   * Transform the resource into an array.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return array
   */
  public function toArray($request)
  {
    $last_version = isset($this->last_version) ? $this->last_version : null;
    if (config('stone.mode') == 'cms') {
      return [
        'id'         => $this->id,
        'signed'     => $last_version ? $last_version->signed : null,
        'url'        => $last_version ? $last_version->url : null,
        'signed_url' => $last_version ? $last_version->signed_url : null,
        'name'       => $last_version ? $last_version->name : null,
      ];
    } else if (config('stone.mode') == 'webapi') {
      return [
        'id'         => $this->id,
        'signed'     => $last_version ? $last_version->signed : null,
        'url'        => $last_version ? $last_version->url : null,
        'signed_url' => $last_version ? $last_version->signed_url : null,
      ];
    }
  }
}
