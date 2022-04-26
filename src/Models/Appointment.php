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

  public static function recent($user_id)
  {
    $now = \Carbon\Carbon::now();
    return static::where('user_id', $user_id)
      ->orderBy('date', 'desc')
      ->orderBy('start_time', 'desc')
      ->whereDate('date', '>=', $now)
      ->where('start_time', '>=', $now)
      ->where(function ($query) {
        $query->whereNotIn('status', ['cancel', 'complete']);
        $query->orWhereNull('status');
      })
      ->first();
  }

  protected $casts = [
    'start_time' => \Wasateam\Laravelapistone\Casts\TimeCast::class,
    'end_time'   => \Wasateam\Laravelapistone\Casts\TimeCast::class,
  ];
}
