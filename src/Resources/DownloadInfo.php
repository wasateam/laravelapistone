<?php

namespace Wasateam\Laravelapistone\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class DownloadInfo extends JsonResource
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
      return [
        'id'         => $this->id,
        'created_at' => $this->created_at,
        'updated_at' => $this->updated_at,
        'clone_type' => $this->clone_type,
        'url'        => $this->url,
        'year'       => $this->year,
        'name'       => $this->name,
      ];
    } else if (config('stone.mode') == 'webapi') {
      return [
        'id'         => $this->id,
        'clone_type' => $this->clone_type,
        'url'        => $this->url,
        'year'       => $this->year,
        'name'       => $this->name,
      ];
    }
  }
}
