<?php

namespace Wasateam\Laravelapistone\Modules\XcBillingStatement\App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Wasateam\Laravelapistone\Models\Admin;

class XcBillingStatement extends Model
{
  use HasFactory;
  use \Illuminate\Database\Eloquent\SoftDeletes;

  public function admin()
  {
    return $this->belongsTo(Admin::class, 'admin_id');
  }

  public function reviewer()
  {
    return $this->belongsTo(Admin::class, 'reviewer_id');
  }

  protected $casts = [
    'images' => \Wasateam\Laravelapistone\Casts\UrlsCast::class,
  ];
}
