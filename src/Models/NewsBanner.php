<?php

namespace Wasateam\Laravelapistone\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NewsBanner extends Model
{
  use HasFactory;
  use \Illuminate\Database\Eloquent\SoftDeletes;

  protected $casts = [
    'bg_img_1440' => \Wasateam\Laravelapistone\Casts\UrlCast::class,
    'bg_img_768'  => \Wasateam\Laravelapistone\Casts\UrlCast::class,
    'bg_img_320'  => \Wasateam\Laravelapistone\Casts\UrlCast::class,
    'link'        => \Wasateam\Laravelapistone\Casts\UrlCast::class,
  ];
}
