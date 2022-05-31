<?php

namespace Wasateam\Laravelapistone\Modules\XcBillingStatement\App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class XcBillingStatement extends Model
{
  use HasFactory;
  use \Illuminate\Database\Eloquent\SoftDeletes;

  public function admin()
  {
    return $this->belongsTo(User::class, 'admin_id');
  }

  public function reviewer()
  {
    return $this->belongsTo(User::class, 'reviewer_id');
  }

  protected $casts = [
    'images' => \Wasateam\Laravelapistone\Casts\UrlsCast::class,
  ];
}
