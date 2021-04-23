<?php

namespace Wasateam\Laravelapistone\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PocketFile extends JsonResource
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
        'id'            => $this->id,
        'updated_admin' => new Admin_R1($this->updated_admin),
        'created_admin' => new Admin_R1($this->created_admin),
        'created_at'    => $this->created_at,
        'updated_at'    => $this->updated_at,
        'signed'        => $last_version->signed,
        'url'           => $last_version->url,
        'signed_url'    => $last_version->signed_url,
        'name'          => $last_version->name,
        'tags'          => $last_version->tags,
      ];
    } else if (config('stone.mode') == 'webapi') {
      return [
        'id'         => $this->id,
        'signed'     => $last_version->signed,
        'url'        => $last_version->url,
        'signed_url' => $last_version->signed_url,
        'name'       => $last_version->name,
      ];
    }
  }
}
