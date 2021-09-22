<?php

namespace Wasateam\Laravelapistone\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TulpaCrossItem extends JsonResource
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
      'created_at'    => $this->created_at,
      'updated_at'    => $this->updated_at,
      'name'          => $this->name,
      'position'      => $this->position,
      'content'       => $this->content,
      'tulpa_section' => new TulpaSection_R1($this->tulpa_section),
    ];
    if (config('stone.admin_group')) {
      if (config('stone.admin_blur')) {
        $res['cmser_groups'] = AdminGroup_R1::collection($this->cmser_groups);
      } else {
        $res['admin_groups'] = AdminGroup_R1::collection($this->admin_groups);
      }
    }
    return $res;
  }
}
