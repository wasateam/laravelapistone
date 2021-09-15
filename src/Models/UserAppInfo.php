<?php

namespace Wasateam\Laravelapistone\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserAppInfo extends Model
{
  use HasFactory;
  use \Illuminate\Database\Eloquent\SoftDeletes;

  public function user()
  {
    return $this->belongsTo(User::class, 'user_id');
  }

  public function app()
  {
    return $this->belongsTo(App::class, 'app_id');
  }

  protected $casts = [
    'scopes' => \Wasateam\Laravelapistone\Casts\JsonCast::class,
  ];
}
