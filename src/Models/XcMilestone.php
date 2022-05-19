<?php

namespace Wasateam\Laravelapistone\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class XcMilestone extends Model
{
  use HasFactory;
  use \Illuminate\Database\Eloquent\SoftDeletes;

  public function xc_project()
  {
    return $this->belongsTo(ErpProject::class, 'xc_project_id');
  }

  public function creator()
  {
    return $this->belongsTo(Admin::class, 'creator_id');
  }

  protected $casts = [
    'start_date' => 'date',
    'done_at'    => 'datetime',
  ];
}
