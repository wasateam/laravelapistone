<?php

namespace Wasateam\Laravelapistone\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class XcProjectMember extends Model
{
  use HasFactory;
  use \Illuminate\Database\Eloquent\SoftDeletes;

  public function xc_project()
  {
    return $this->belongsTo(XcProject::class);
  }

  public function admin()
  {
    return $this->belongsToMany(Admin::class);
  }
}
