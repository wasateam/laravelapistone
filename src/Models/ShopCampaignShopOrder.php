<?php

namespace Wasateam\Laravelapistone\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ShopCampaignShopOrder extends Model
{
  use HasFactory;
  use SoftDeletes;

  public function user()
  {
    return $this->belongsTo(User::class, 'user_id');
  }

  public function shop_order()
  {
    return $this->belongsTo(User::class, 'shop_order_id');
  }

  public function shop_campaign()
  {
    return $this->belongsTo(ShopCampaign::class, 'shop_campaign_id');
  }
}
