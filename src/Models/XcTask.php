<?php

namespace Wasateam\Laravelapistone\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class XcTask extends Model
{
  use HasFactory;
  use \Illuminate\Database\Eloquent\SoftDeletes;

  public function xc_work_type()
  {
    return $this->belongsTo(XcWorkType::class, 'xc_work_type_id');
  }

  public function xc_project()
  {
    return $this->belongsTo(XcProject::class, 'xc_project_id');
  }

  public function creator()
  {
    return $this->belongsTo(Admin::class, 'creator_id');
  }

  public function taker()
  {
    return $this->belongsTo(Admin::class, 'taker_id');
  }

  public function xc_task_template()
  {
    return $this->belongsTo(XcTaskTemplate::class, 'xc_task_template_id');
  }

  protected $casts = [
    'start_at'    => 'datetime',
    'reviewed_at' => 'datetime',
  ];
}
