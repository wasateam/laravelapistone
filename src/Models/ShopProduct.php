<?php

namespace Wasateam\Laravelapistone\Models;

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
    return $this->belongsTo(ShopProductCoverFrame::class, 'shop_product_cover_frame_id');
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
    return $this->belongsToMany(ShopSubclass::class, 'shop_product_shop_subclass', 'shop_product_id', 'shop_subclass_id');
  }

  public function shop_order_shop_product()
  {
    return $this->hasMany(ShopOrderShopProduct::class, 'shop_product_id');
  }

  protected $casts = [
    'cover_image' => \Wasateam\Laravelapistone\Casts\UrlCast::class,
    'images'      => \Wasateam\Laravelapistone\Casts\UrlsCast::class,
  ];
}
