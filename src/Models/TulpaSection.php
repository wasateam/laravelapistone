<?php

namespace Wasateam\Laravelapistone\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Wasateam\Laravelapistone\Models\Admin;

class TulpaSection extends Model
{
  use HasFactory;
  use \Illuminate\Database\Eloquent\SoftDeletes;

  public function updated_admin()
  {
    return $this->belongsTo(Admin::class, 'updated_admin_id');
  }

  public function created_admin()
  {
    return $this->belongsTo(Admin::class, 'created_admin_id');
  }

  protected $casts = [
    'fields'  => \Wasateam\Laravelapistone\Casts\JsonCast::class,
    'tags'    => \Wasateam\Laravelapistone\Casts\JsonCast::class,
    'content' => \Wasateam\Laravelapistone\Casts\PayloadCast::class,
  ];
}
