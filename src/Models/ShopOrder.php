<?php

namespace Wasateam\Laravelapistone\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShopOrder extends Model
{
    use HasFactory;

    public function areas()
    {
        return $this->belongsTo(Area::class, 'area_id');
    }
    public function area_sections() {
        return $this->belongsTo(AreaSection::class, 'area_section_id');
    }
}
