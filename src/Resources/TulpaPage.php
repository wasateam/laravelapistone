<?php

namespace Wasateam\Laravelapistone\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TulpaPage extends JsonResource
{
  /**
   * Transform the resource into an array.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return array
   */
  public function toArray($request)
  {
    return [
      'id'             => $this->id,
      'updated_admin'  => new Admin_R1($this->updated_admin),
      'created_at'     => $this->created_at,
      'updated_at'     => $this->updated_at,
      'route'          => $this->route,
      'title'          => $this->title,
      'description'    => $this->description,
      'og_image'       => $this->og_image,
      'is_active'      => $this->is_active,
      'tags'           => $this->tags,
      'remark'         => $this->remark,
      'status'         => $this->status,
      'tulpa_sections' => $this->tulpa_sections,
    ];
  }
}
