<?php

namespace Wasateam\Laravelapistone\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class SocialiteFacebookAccount extends Model
{
  public function user()
  {
    return $this->belongsTo(User::class);
  }
}
