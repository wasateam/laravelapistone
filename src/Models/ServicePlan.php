<?php

namespace Wasateam\Laravelapistone\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServicePlan extends Model
{
  use HasFactory;
  use \Illuminate\Database\Eloquent\SoftDeletes;

  protected $casts = [
    'payload' => \Wasateam\Laravelapistone\Casts\JsonCast::class,
  ];
}
