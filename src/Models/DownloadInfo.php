<?php

namespace Wasateam\Laravelapistone\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DownloadInfo extends Model
{
  use HasFactory;
  use \Illuminate\Database\Eloquent\SoftDeletes;

  protected $casts = [
    'url' => \Wasateam\Laravelapistone\Casts\UrlCast::class,
  ];
}
