<?php

namespace Wasateam\Laravelapistone\Modules\Otp\App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Wasateam\Laravelapistone\Models\User;

class Otp extends Model
{
  use HasFactory;
  use \Illuminate\Database\Eloquent\SoftDeletes;

  public function user()
  {
    return $this->belongsTo(User::class, 'user_id');
  }

  public function scopeActive($query)
  {
    return $query->where('is_active', 1);
  }

  public function scopeNotExpired($query)
  {
    return $query->where('expired_at', '>', \Carbon\Carbon::now());
  }

  public function scopeValid($query)
  {
    return $query->active()->notExpired();
  }

  public function scopeUserActive($query, $user_id)
  {
    return $query->valid()->where('user_id', $user_id);
  }
}
