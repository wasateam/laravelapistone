<?php

namespace Wasateam\Laravelapistone\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShopOrder extends Model
{
    use HasFactory;

    public function area()
    {
        return $this->belongsTo(Area::class, 'areas_id');
    }

    public function area_sections() {
        return $this->belongsToMany(AreaSection::class, 'area_sections_id');
    }

    public function shop_order_shop_product()
    {
        return $this->hasMany(ShopOrderShopProduct::class, 'shop_order_id');
    }

    public function user() {
        return $this->belongsTo(User::class, 'shop_order_id');
    }
}