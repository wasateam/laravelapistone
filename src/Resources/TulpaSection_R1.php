<?php

namespace Wasateam\Laravelapistone\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TulpaSection_R1 extends JsonResource
{
  /**
   * Transform the resource into an array.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return array
   */
  public function toArray($request)
  {
    if (config('stone.mode' == 'cms')) {
      return [
        'id'                     => $this->id,
        'name'                   => $this->name,
        'content'                => $this->content,
        'tulpa_section_template' => new TulpaSectionTemplate_R1($this->tulpa_section_template),
      ];
    } else if (config('stone.mode' == 'webapi')) {
      return [
        'id'                     => $this->id,
        'name'                   => $this->name,
        'content'                => $this->content,
        'tulpa_section_template' => new TulpaSectionTemplate_R1($this->tulpa_section_template),
      ];
    }
  }
}
