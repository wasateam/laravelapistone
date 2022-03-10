<?php

namespace Wasateam\Laravelapistone\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PageCover extends Model
{
  use HasFactory;
  use SoftDeletes;

  public function page_settings()
  {
    return $this->belongsToMany(PageSetting::class, 'page_setting_page_cover', 'page_cover_id', 'page_setting_id');
  }

  protected $casts = [
    'image' => \Wasateam\Laravelapistone\Casts\UrlCast::class,
    'link' => \Wasateam\Laravelapistone\Casts\UrlCast::class,
  ];
}
