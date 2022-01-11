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
        'id'             => $this->id,
        'updated_at'     => $this->updated_at,
        'created_at'     => $this->created_at,
        'name'           => $this->name,
        'date'           => $this->date,
        'reviewed_at'    => $this->reviewed_at,
        'status'         => $this->status,
        'creator_remark' => $this->creator_remark,
        'taker_remark'   => $this->taker_remark,
        'xc_project'     => new XcProject_R1($this->xc_project),
        'creator'        => new Admin_R1($this->creator),
        'taker'          => new Admin_R1($this->taker),
      ];
    }
  }
}
