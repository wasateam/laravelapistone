<?php

namespace Wasateam\Laravelapistone\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CmsLog extends Model
{
  use HasFactory;

  protected $casts = [
    'payload' => \Wasateam\Laravelapistone\Casts\JsonCast::class,
  ];
}
