<?php

namespace Wasateam\Laravelapistone\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PageSetting extends Model
{
  use HasFactory;
  use SoftDeletes;

  public function news_banner_groups()
  {
    return $this->belongsToMany(NewsBannerGroup::class, 'page_setting_news_banner_group', 'page_setting_id', 'news_banner_group_id');
  }
}
