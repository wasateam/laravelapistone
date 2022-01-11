<?php

namespace Wasateam\Laravelapistone\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class XcProject extends Model
{
  use HasFactory;
  use \Illuminate\Database\Eloquent\SoftDeletes;

  public function members()
  {
    return $this->belongsToMany(Admin::class, 'xc_project_member', 'xc_project_id', 'member_id');
  }

  public function managers()
  {
    return $this->belongsToMany(Admin::class, 'xc_project_manager', 'xc_project_id', 'manager_id');
  }
}
