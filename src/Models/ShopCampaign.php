<?php

namespace Wasateam\Laravelapistone\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShopCampaign extends Model
{
  use HasFactory;
  use \Illuminate\Database\Eloquent\SoftDeletes;

  public function shop_campaign_shop_products()
  {
    return $this->hasMany(ShopCampaignShopProduct::class, 'shop_campaign_id');
  }

  public function shop_campaign_shop_orders()
  {
    return $this->hasMany(ShopCampaignShopOrder::class, 'shop_campaign_id');
  }

  public function shop_products()
  {
    return $this->belongsToMany(ShopProduct::class, 'shop_product_shop_campaign', 'shop_campaign_id', 'shop_product_id');
  }
}
