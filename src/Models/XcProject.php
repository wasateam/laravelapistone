<?php

namespace Wasateam\Laravelapistone\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class XcProject extends Model
{
  use HasFactory;
  use \Illuminate\Database\Eloquent\SoftDeletes;

  // public function xc_project_members()
  // {
  //   return $this->hasMany(XcProjectMember::class);
  // }

  protected $casts = [
    'url'          => \Wasateam\Laravelapistone\Casts\PostEncodeCast::class,
    'gitlab'       => \Wasateam\Laravelapistone\Casts\PostEncodeCast::class,
    'github'       => \Wasateam\Laravelapistone\Casts\PostEncodeCast::class,
    'google_drive' => \Wasateam\Laravelapistone\Casts\PostEncodeCast::class,
    'links'        => \Wasateam\Laravelapistone\Casts\PayloadCast::class,
    'infos'        => \Wasateam\Laravelapistone\Casts\PayloadCast::class,
  ];
}
