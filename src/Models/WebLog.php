<?php

namespace Wasateam\Laravelapistone\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Wasateam\Laravelapistone\Models\User;

class WebLog extends Model
{
  use HasFactory;

  public function user()
  {
    return $this->belongsTo(User::class, 'user_id');
  }

  protected $casts = [
    'payload' => \Wasateam\Laravelapistone\Casts\JsonCast::class,
  ];
}
