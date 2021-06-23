<?php

namespace Wasateam\Laravelapistone\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdminScope extends Model
{
    use HasFactory;
    use \Illuminate\Database\Eloquent\SoftDeletes;
  
    public function updated_admin()
    {
      return $this->belongsTo(Admin::class, 'updated_admin_id');
    }
}
