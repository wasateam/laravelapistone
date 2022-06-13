<?php

namespace Wasateam\Laravelapistone\Modules\UserRelation\App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Wasateam\Laravelapistone\Models\User;

class UserRelation extends Model
{
  use HasFactory;
  use \Illuminate\Database\Eloquent\SoftDeletes;

  public function user()
  {
    return $this->belongsTo(User::class, 'user_id');
  }
  public function target()
  {
    return $this->belongsTo(User::class, 'target_id');
  }
}
