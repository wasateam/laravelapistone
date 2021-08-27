<?php

namespace Wasateam\Laravelapistone\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceStoreClose extends Model
{
  use HasFactory;
  use \Illuminate\Database\Eloquent\SoftDeletes;

  public function service_stores()
  {
    return $this->belongsToMany(ServiceStore::class);
  }

  public function updated_admin()
  {
    return $this->belongsTo(Admin::class, 'updated_admin_id');
  }

  public function created_admin()
  {
    return $this->belongsTo(Admin::class, 'created_admin_id');
  }
}
