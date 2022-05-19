<?php

namespace Wasateam\Laravelapistone\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class XcMilestone extends JsonResource
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
        'updated_at' => $this->updated_at,
        'created_at' => $this->created_at,
        'name'       => $this->name,
        'content'    => $this->content,
        'start_date' => $this->start_date,
        'days'       => $this->days,
        'done_at'    => $this->done_at,
        'remark'     => $this->remark,
        'xc_project' => new XcProject_R1($this->xc_project),
        'creator'    => new Admin_R1($this->creator),
      ];
    }
  }
}
