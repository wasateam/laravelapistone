<?php

namespace Wasateam\Laravelapistone\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class NewsBanner_R1 extends JsonResource
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
        'id'          => $this->id,
        'sq'          => $this->sq,
        'name'        => $this->name,
        'bg_img_1440' => $this->bg_img_1440,
        'bg_img_768'  => $this->bg_img_768,
        'bg_img_320'  => $this->bg_img_320,
        'link'        => $this->link,
        'title'       => $this->title,
        'title_color' => $this->title_color,
        'des'         => $this->des,
        'des_color'   => $this->des_color,
        'start_date'  => $this->start_date,
        'end_date'    => $this->end_date,
        'is_active'   => $this->is_active,
      ];
    } else if (config('stone.mode') == 'webapi') {
      $res = [
        'id'          => $this->id,
        'sq'          => $this->sq,
        'bg_img_1440' => $this->bg_img_1440,
        'bg_img_768'  => $this->bg_img_768,
        'bg_img_320'  => $this->bg_img_320,
        'link'        => $this->link,
        'title'       => $this->title,
        'title_color' => $this->title_color,
        'des'         => $this->des,
        'des_color'   => $this->des_color,
      ];
    }
    return $res;
  }
}
