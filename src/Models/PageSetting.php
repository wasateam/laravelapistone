<?php

namespace Wasateam\Laravelapistone\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PageSetting extends Model
{
  use HasFactory;
  use SoftDeletes;

  // public function news_banner_groups()
  // {
  //   return $this->belongsToMany(NewsBannerGroup::class, 'page_setting_news_banner_group', 'page_setting_id', 'news_banner_group_id');
  // }

  public function news_banners()
  {
    return $this->belongsToMany(NewsBanner::class, 'page_setting_news_banner', 'page_setting_id', 'news_banner_id')
      ->orderBy('sq')
      ->where('is_active', 1)
      ->where(function ($query) {
        $query->where(function ($query) {
          $today_date = Carbon::now();
          $query->where('start_date', '<=', $today_date);
          $query->where('end_date', '>=', $today_date);
        });
        $query->orWhere(function ($query) {
          $query->whereNull('start_date');
          $query->whereNull('end_date');
        });
      });
  }

  public function page_covers()
  {
    return $this->belongsToMany(PageCover::class, 'page_setting_page_cover', 'page_setting_id', 'page_cover_id')
      ->orderBy('updated_at', 'desc')
      ->where('is_active', 1)
      ->where(function ($query) {
        $query->where(function ($query) {
          $today_date = Carbon::now();
          $query->where('start_date', '<=', $today_date);
          $query->where('end_date', '>=', $today_date);
        });
        $query->orWhere(function ($query) {
          $query->whereNull('start_date');
          $query->whereNull('end_date');
        });
      });
  }
}
