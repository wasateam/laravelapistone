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
    return $this->belongsToMany(ServiceStoreClose::class, 'service_store_close_service_store', 'service_store_id', 'service_store_close_id');
  }

  public function service_store_notis()
  {
    return $this->belongsToMany(ServiceStoreNoti::class, 'service_store_noti_service_store', 'service_store_id', 'service_store_noti_id');
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
    return $this->belongsToMany(AdminGroup::class, 'admin_group_service_store', 'service_store_id', 'admin_group_id');
  }

  protected $casts = [
    'business_hours'         => \Wasateam\Laravelapistone\Casts\JsonCast::class,
    'appointment_availables' => \Wasateam\Laravelapistone\Casts\JsonCast::class,
    'payload'                => \Wasateam\Laravelapistone\Casts\JsonCast::class,
  ];
}
