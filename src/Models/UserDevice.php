<?php

namespace Wasateam\Laravelapistone\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use \Wasateam\Laravelapistone\Models\User;


class UserDevice extends Model
{
  use HasFactory;
  use \Illuminate\Database\Eloquent\SoftDeletes;

  public function user()
  {
    return $this->belongsTo(User::class, 'user_id');
  }

  public function user_service_plans()
  {
    return $this->belongsToMany(UserServicePlan::class, 'user_device_user_service_plan', 'user_device_id', 'user_service_plan_id');
  }
}
