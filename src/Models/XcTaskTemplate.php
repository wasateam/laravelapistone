<?php

namespace Wasateam\Laravelapistone\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class XcTaskTemplate extends Model
{
  use HasFactory;
  use \Illuminate\Database\Eloquent\SoftDeletes;

  public function xc_work_type()
  {
    return $this->belongsTo(XcWorkType::class, 'xc_work_type_id');
  }
}
