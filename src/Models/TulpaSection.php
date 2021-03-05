<?php

namespace Wasateam\Laravelapistone\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Wasateam\Laravelapistone\Models\Admin;
use Wasateam\Laravelapistone\Models\TulpaSectionTemplate;

class TulpaSection extends Model
{
  use HasFactory;
  use \Illuminate\Database\Eloquent\SoftDeletes;

  public function updated_admin()
  {
    return $this->belongsTo(Admin::class, 'updated_admin_id');
  }

  public function tulpa_section_template()
  {
    return $this->belongsTo(TulpaSectionTemplate::class);
  }

  protected $casts = [
    'content' => \Wasateam\Laravelapistone\Casts\JsonCast::class,
    'tags'    => \Wasateam\Laravelapistone\Casts\JsonCast::class,
  ];
}
