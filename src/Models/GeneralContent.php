<?php

namespace Wasateam\Laravelapistone\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GeneralContent extends Model
{
  use HasFactory;
  use \Illuminate\Database\Eloquent\SoftDeletes;

  protected $casts = [
    'content' => \Wasateam\Laravelapistone\Casts\PayloadCast::class,
  ];
}
