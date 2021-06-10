<?php

namespace Wasateam\Laravelapistone\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Wasateam\Laravelapistone\Models\Admin;
use Wasateam\Laravelapistone\Models\Area;
use Wasateam\Laravelapistone\Models\SystemSubclass;

class SystemClass extends Model
{
  use HasFactory;
  use \Illuminate\Database\Eloquent\SoftDeletes;

  public function updated_admin()
  {
    return $this->belongsTo(Admin::class, 'updated_admin_id');
  }

  public function area()
  {
    return $this->belongsTo(Area::class);
  }

  public function system_subclasses()
  {
    return $this->hasMany(SystemSubclass::class);
  }
}
