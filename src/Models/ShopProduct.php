<?php

namespace Wasateam\Laravelapistone\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShopProduct extends Model
{
  use HasFactory;
  use \Illuminate\Database\Eloquent\SoftDeletes;

  public function featured_classes()
  {
    return $this->belongsToMany(FeaturedClass::class, 'featured_class_shop_product', 'shop_product_id', 'featured_class_id');
  }

  public function areas()
  {
    return $this->belongsToMany(Area::class, 'shop_product_area', 'shop_product_id', 'area_id');
  }

  public function area_sections()
  {
    return $this->belongsToMany(AreaSection::class, 'shop_product_area_section', 'shop_product_id', 'area_section_id');
  }

  public function shop_product_cover_frame()
  {
    $today_date = Carbon::today();
    if (config('stone.mode') == 'cms') {
      return $this->belongsTo(ShopProductCoverFrame::class, 'shop_product_cover_frame_id');
    } else if (config('stone.mode') == 'webapi') {
      return $this->belongsTo(ShopProductCoverFrame::class, 'shop_product_cover_frame_id')
        ->where('is_active', 1)
        ->where(function ($query) use ($today_date) {
          $query->where(function ($query) use ($today_date) {
            $query->whereDate('end_date', '>=', $today_date);
            $query->whereDate('start_date', '<=', $today_date);
          });
          $query->orWhere(function ($query) {
            $query->whereNull('end_date');
            $query->whereNull('start_date');
          });
        });
    }
  }

  public function shop_product_expect_ships()
  {
    return $this->hasMany(ShopProductExpectShip::class, 'shop_product_id');
  }

  public function suggests()
  {
    return $this->belongsToMany(ShopProduct::class, 'shop_product_suggest', 'shop_product_id', 'suggest_id');
  }

  public function shop_classes()
  {
    return $this->belongsToMany(ShopClass::class, 'shop_product_shop_class', 'shop_product_id', 'shop_class_id');
  }

  public function shop_subclasses()
  {
    return $this->belongsToMany(ShopSubclass::class, 'shop_product_shop_subclass', 'shop_product_id', 'shop_subclass_id')->withPivot('sq');
  }

  public function shop_order_shop_products()
  {
    return $this->hasMany(ShopOrderShopProduct::class, 'shop_product_id');
  }

  public function today_shop_order_shop_products()
  {
    $target_day = Carbon::today();
    return $this->hasMany(ShopOrderShopProduct::class, 'shop_product_id')->whereDate('created_at', $target_day);
  }

  public function users()
  {
    return $this->belongsToMany(User::class, 'user_shop_product', 'shop_product_id', 'user_id');
  }

  public function has_stock_shop_product_specs()
  {
    return $this->hasMany(ShopProductSpec::class, 'shop_product_id')->where('stock_count', '>', 0);
  }

  public function shop_product_specs()
  {
    return $this->hasMany(ShopProductSpec::class, 'shop_product_id');
  }

  public function shop_product_spec_settings()
  {
    return $this->hasMany(ShopProductSpecSetting::class, 'shop_product_id');
  }

  public function shop_product_spec_setting_items()
  {
    return $this->hasMany(ShopProductSpecSettingItem::class, 'shop_product_id');
  }

  public function shop_campaigns()
  {
    return $this->belongsToMany(ShopCampaign::class, 'shop_product_shop_campaign', 'shop_product_id', 'shop_campaign_id');
  }

  public function getIsOnShelfAttribute()
  {
    if (!$this->is_active) {
      return 0;
    }
    if ($this->on_time && $this->on_time >= \Carbon\Carbon::now()) {
      return 0;
    }
    if ($this->end_time && $this->end_time <= \Carbon\Carbon::now()) {
      return 0;
    }
    return 1;
  }

  public function scopeOnshelf($query)
  {
    return $query
      ->where('is_active', 1)
      ->where(function ($query) {
        $query->where(function ($query) {
          $query->where('on_time', '<=', \Carbon\Carbon::now());
        });
        $query->orWhereNull('on_time');
      })
      ->where(function ($query) {
        $query->where(function ($query) {
          $query->where('off_time', '>=', \Carbon\Carbon::now());
        });
        $query->orWhereNull('off_time');
      });
  }

  public function scopeNostocklast($query)
  {
    return $query
      ->orderByRaw('stock_count = 0');
  }

  public function scopeCollected($query, $user_id)
  {
    return $query->whereHas('users', function ($query) use ($user_id) {
      $query->where('users.id', $user_id);
    });
  }

  protected $casts = [
    'description' => \Wasateam\Laravelapistone\Casts\HtmlCast::class,
    'cover_image' => \Wasateam\Laravelapistone\Casts\UrlCast::class,
    'images'      => \Wasateam\Laravelapistone\Casts\UrlsCast::class,
  ];
}
