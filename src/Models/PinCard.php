<?php

namespace Wasateam\Laravelapistone\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PinCard extends Model
{
  use HasFactory;
  use \Illuminate\Database\Eloquent\SoftDeletes;

  public function created_admin()
  {
    return $this->belongsTo(Admin::class, 'created_admin_id');
  }

  public function service_plan()
  {
    return $this->belongsTo(ServicePlan::class, 'service_plan_id');
  }
}
