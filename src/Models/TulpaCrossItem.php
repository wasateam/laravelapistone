<?php

namespace Wasateam\Laravelapistone\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TulpaCrossItem extends Model
{
  use HasFactory;
  use \Illuminate\Database\Eloquent\SoftDeletes;

  public function admin_groups()
  {
    return $this->belongsToMany(AdminGroup::class, 'admin_group_tulpa_page', 'tulpa_page_id', 'admin_group_id');
  }

  public function tulpa_section()
  {
    return $this->belongsTo(TulpaSection::class, 'tulpa_section_id');
  }

  protected $casts = [
    'content' => \Wasateam\Laravelapistone\Casts\JsonCast::class,
  ];
}
