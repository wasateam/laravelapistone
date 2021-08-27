<?php

namespace Wasateam\Laravelapistone\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceStore extends Model
{
  use HasFactory;
  use \Illuminate\Database\Eloquent\SoftDeletes;

  public function service_store_closes()
  {
    return $this->belongsToMany(ServiceStoreClose::class);
  }

  public function service_store_notis()
  {
    return $this->belongsToMany(ServiceStoreNoti::class);
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
