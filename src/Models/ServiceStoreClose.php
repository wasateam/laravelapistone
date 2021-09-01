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
    return $this->belongsToMany(ServiceStore::class, 'service_store_close_service_store', 'service_store_close_id', 'service_store_id');
  }

  public function updated_admin()
  {
    return $this->belongsTo(Admin::class, 'updated_admin_id');
  }

  public function created_admin()
  {
    return $this->belongsTo(Admin::class, 'created_admin_id');
  }

  public function admin_groups()
  {
    return $this->belongsToMany(AdminGroup::class, 'admin_group_service_store_close', 'service_store_close_id', 'admin_group_id');
  }
}
