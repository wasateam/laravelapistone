<?php

namespace Wasateam\Laravelapistone\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserDeviceModifyRecord extends Model
{
  use HasFactory;
  use \Illuminate\Database\Eloquent\SoftDeletes;

  public function user_device()
  {
    return $this->belongsTo(UserDevice::class, 'user_device_id');
  }

  public function user()
  {
    return $this->belongsTo(User::class, 'user_id');
  }

  protected $casts = [
    'payload' => \Wasateam\Laravelapistone\Casts\JsonCast::class,
  ];
}
