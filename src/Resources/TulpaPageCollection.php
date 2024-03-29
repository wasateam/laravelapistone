<?php

namespace Wasateam\Laravelapistone\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TulpaPageCollection extends JsonResource
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
        'id'                  => $this->id,
        'created_at'          => $this->created_at,
        'updated_at'          => $this->updated_at,
        'name'                => $this->name,
        'route'               => $this->route,
        'title'               => $this->title,
        'description'         => $this->description,
        'og_image'            => $this->og_image,
        'is_active'           => $this->is_active,
        'tags'                => $this->tags,
        'remark'              => $this->remark,
        'status'              => $this->status,
        'content'             => $this->content,
        'canonical_url'       => $this->canonical_url,
        'country_code'        => $this->country_code,
        'tulpa_page_template' => new TulpaPageTemplate_R1($this->tulpa_page_template),
        'tulpa_cross_items'   => TulpaCrossItem_R1::collection($this->tulpa_cross_items),
      ];
      if (config('stone.admin_group')) {
        if (config('stone.admin_blur')) {
          $res['cmser_groups'] = AdminGroup_R1::collection($this->cmser_groups);
        } else {
          $res['admin_groups'] = AdminGroup_R1::collection($this->admin_groups);
        }
      }
      return $res;
    } else if (config('stone.mode') == 'webapi') {
      $res = [
        'id'            => $this->id,
        'route'         => $this->route,
        'title'         => $this->title,
        'description'   => $this->description,
        'og_image'      => $this->og_image,
        'content'       => $this->content,
        'canonical_url' => $this->canonical_url,
        'country_code'  => $this->country_code,
      ];
      return $res;
    }
  }
}
