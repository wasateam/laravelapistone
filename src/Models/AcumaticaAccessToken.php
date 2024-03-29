<?php

namespace Wasateam\Laravelapistone\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AcumaticaAccessToken extends Model
{
  use HasFactory;
  use \Illuminate\Database\Eloquent\SoftDeletes;

  public function acumatica_app()
  {
    return $this->belongsTo(AcumaticaApp::class, 'acumatica_app_id');
  }
}
