<?php

namespace Wasateam\Laravelapistone\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Showcase extends Model
{
  use HasFactory;
  use \Illuminate\Database\Eloquent\SoftDeletes;

  protected $casts = [
    'tags'    => \Wasateam\Laravelapistone\Casts\JsonCast::class,
    'content' => \Wasateam\Laravelapistone\Casts\PayloadCast::class,
  ];
}
