<?php

namespace Wasateam\Laravelapistone\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CalendarHighlight extends JsonResource
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
        'start_at'   => $this->start_at,
        'start_end'  => $this->start_end,
        'type'       => $this->type,
        'name'       => $this->name,
        'app'        => new App_R1($this->app),
      ];
      if (config('stone.admin_group')) {
        if (config('stone.admin_blur')) {
          $res['cmser_groups'] = AdminGroup_R1::collection($this->cmser_groups);
        } else {
          $res['admin_groups'] = AdminGroup_R1::collection($this->admin_groups);
        }
      }
    } else if (config('stone.mode') == 'webapi') {
      $res = [
        'id'        => $this->id,
        'start_at'  => $this->start_at,
        'start_end' => $this->start_end,
        'type'      => $this->type,
        'name'      => $this->name,
      ];
    }
    return $res;
  }
}
