<?php

namespace Wasateam\Laravelapistone\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserAddress extends Model
{
  use HasFactory;
  use SoftDeletes;

  public function user()
  {
    return $this->belongsTo(User::class, 'user_id');
  }
}
