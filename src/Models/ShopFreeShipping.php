<?php

namespace Wasateam\Laravelapistone\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ShopFreeShipping extends Model
{
  use HasFactory;
  use SoftDeletes;

  protected $casts = [
    'start_date' => 'datetime',
    'end_date'   => 'datetime',
  ];
}
