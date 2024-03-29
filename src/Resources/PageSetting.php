<?php

namespace Wasateam\Laravelapistone\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PageSetting extends JsonResource
{
  /**
   * Transform the resource into an array.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
   */
  public function toArray($request)
  {
    if (config('stone.mode') == 'cms') {
      $res = [
        "id"           => $this->id,
        "created_at"   => $this->created_at,
        "updated_at"   => $this->updated_at,
        "route"        => $this->route,
        "name"         => $this->name,
        'news_banners' => NewsBanner_R1::collection($this->news_banners),
        'page_covers'  => PageCover_R1::collection($this->page_covers),
      ];
    } else if (config('stone.mode') == 'webapi') {
      $res = [
        "id"           => $this->id,
        "route"        => $this->route,
        "name"         => $this->name,
        'news_banners' => NewsBanner_R1::collection($this->news_banners),
        'page_covers'  => PageCover_R1::collection($this->page_covers),
      ];
    }
    return $res;
  }
}
