<?php

namespace Wasateam\Laravelapistone\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NewsBanner extends Model
{
  use HasFactory;
  use \Illuminate\Database\Eloquent\SoftDeletes;

  // public function news_banner_groups()
  // {
  //   return $this->belongsToMany(NewsBannerGroup::class, 'news_banner_news_banner_group', 'news_banner_id', 'news_banner_group_id')->withPivot('news_banner_sq');
  // }

  public function page_settings()
  {
    return $this->belongsToMany(PageSetting::class, 'page_setting_news_banner', 'news_banner_id', 'page_setting_id');
  }

  protected $casts = [
    'bg_img_1440' => \Wasateam\Laravelapistone\Casts\UrlCast::class,
    'bg_img_768'  => \Wasateam\Laravelapistone\Casts\UrlCast::class,
    'bg_img_320'  => \Wasateam\Laravelapistone\Casts\UrlCast::class,
    'link'        => \Wasateam\Laravelapistone\Casts\UrlCast::class,
    // 'start_date'  => 'datetime',
    // 'end_date'    => 'datetime',
  ];
}
