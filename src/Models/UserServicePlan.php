<?php

namespace Wasateam\Laravelapistone\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserServicePlan extends Model
{
  use HasFactory;
  use \Illuminate\Database\Eloquent\SoftDeletes;

  public function user()
  {
    return $this->belongsTo(User::class, 'user_id');
  }

  public function service_plan()
  {
    return $this->belongsTo(ServicePlan::class, 'service_plan_id');
  }

  public function user_service_plan_items()
  {
    return $this->hasMany(UserServicePlanItem::class, 'user_service_plan_id');
  }

  public function pin_card()
  {
    return $this->belongsTo(PinCard::class, 'pin_card_id');
  }
}
