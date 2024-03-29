<?php

namespace Wasateam\Laravelapistone\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Wasateam\Laravelapistone\Models\Admin;
use Wasateam\Laravelapistone\Models\User;

class PocketFileVersion extends Model
{
  use HasFactory;
  use \Illuminate\Database\Eloquent\SoftDeletes;

  public function updated_admin()
  {
    return $this->belongsTo(Admin::class, 'updated_admin_id');
  }

  public function created_admin()
  {
    return $this->belongsTo(Admin::class, 'created_admin_id');
  }

  public function created_user()
  {
    return $this->belongsTo(User::class, 'created_user_id');
  }

  protected $casts = [
    'signed_url' => \Wasateam\Laravelapistone\Casts\PocketFileSignedUrlCast::class,
    'url'        => \Wasateam\Laravelapistone\Casts\UrlCast::class,
    'tags'       => \Wasateam\Laravelapistone\Casts\JsonCast::class,
  ];
}
