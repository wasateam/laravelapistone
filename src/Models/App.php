<?php

namespace Wasateam\Laravelapistone\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Wasateam\Laravelapistone\Models\Admin;

class App extends Model
{
  use HasFactory;
  use \Illuminate\Database\Eloquent\SoftDeletes;

  public function created_admin()
  {
    return $this->belongsTo(Admin::class, 'updated_admin_id');
  }

  public function app_roles()
  {
    return $this->hasMany(AppRole::class, 'app_id');
  }
}
