<?php

namespace Wasateam\Laravelapistone\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class NewsBannerGroup extends Model
{
  use HasFactory;
  use SoftDeletes;

  public function news_banners()
  {
    return $this->belongsToMany(ShopProduct::class, 'news_banner_news_banner_group', 'news_banner_group_id', 'news_banner_id')->withPivot('news_banner_sq')->orderBy('news_banner_sq');
  }

  public function page_settings()
  {
    return $this->belongsToMany(PageSetting::class, 'page_setting_news_banner_group', 'news_banner_group_id', 'page_setting_id');
  }
}
