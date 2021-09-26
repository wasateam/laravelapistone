<?php

namespace Wasateam\Laravelapistone\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserServicePlanItem extends Model
{
  use HasFactory;
  use \Illuminate\Database\Eloquent\SoftDeletes;

  public function user()
  {
    return $this->belongsTo(User::class, 'user_id');
  }

  public function service_plan_item()
  {
    return $this->belongsTo(ServicePlanItem::class, 'service_plan_item_id');
  }

  protected $casts = [
    'content' => \Wasateam\Laravelapistone\Casts\JsonCast::class,
  ];
}
