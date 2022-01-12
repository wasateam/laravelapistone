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
}
