<?php

namespace Wasateam\Laravelapistone\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServicePlanItem extends Model
{
  use HasFactory;
  use \Illuminate\Database\Eloquent\SoftDeletes;

  protected $casts = [
    'items'             => \Wasateam\Laravelapistone\Casts\JsonCast::class,
  ];
}
