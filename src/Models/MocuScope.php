<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MocuScope extends Model
{
  use HasFactory;
  use SoftDeletes;

  protected $casts = [
    'scopes' => \Wasateam\Laravelapistone\Casts\JsonCast::class,
  ];
}
