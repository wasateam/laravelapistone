<?php

namespace Wasateam\Laravelapistone\Modules\UserLocation\App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Wasateam\Laravelapistone\Models\User;

class UserLocation extends Model
{
  use HasFactory;
  use \Illuminate\Database\Eloquent\SoftDeletes;

  public function user()
  {
    return $this->belongsTo(User::class, 'user_id');
  }
  public function scopeIsWithinMaxDistance($query, $km = 5, $lat, $lng)
  {
    $haversine = "(6371 * acos(cos(radians(" . $lat . "))
                    * cos(radians(`latitude`))
                    * cos(radians(`longitude`)
                    - radians(" . $lng . "))
                    + sin(radians(" . $lat . "))
                    * sin(radians(`latitude`))))";

    return $query->selectRaw("{$haversine} AS distance")
      ->whereRaw("{$haversine} < ?", [$radius]);
  }
}
