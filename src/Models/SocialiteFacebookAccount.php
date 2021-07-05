<?php

namespace Wasateam\Laravelapistone\Models;

use Wasateam\Laravelapistone\Models\User;
use Illuminate\Database\Eloquent\Model;

class SocialiteFacebookAccount extends Model
{
  public function user()
  {
    return $this->belongsTo(User::class);
  }
}
