<?php

namespace Wasateam\Laravelapistone\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
  use HasFactory;
  use \Illuminate\Database\Eloquent\SoftDeletes;

  public function user()
  {
    return $this->belongsTo(User::class, 'user_id');
  }

  public function service_store()
  {
    return $this->belongsTo(ServiceStore::class, 'service_store_id');
  }

  protected $casts = [
    'start_time' => \Wasateam\Laravelapistone\Casts\TimeCast::class,
    'end_time'   => \Wasateam\Laravelapistone\Casts\TimeCast::class,
  ];
}
