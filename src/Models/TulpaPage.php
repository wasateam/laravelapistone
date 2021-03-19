<?php

namespace Wasateam\Laravelapistone\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Wasateam\Laravelapistone\Models\Admin;
use Wasateam\Laravelapistone\Models\TulpaSection;

class TulpaPage extends Model
{
  use HasFactory;
  use \Illuminate\Database\Eloquent\SoftDeletes;

  public function updated_admin()
  {
    return $this->belongsTo(Admin::class, 'updated_admin_id');
  }

  public function tulpa_sections()
  {
    return $this->belongsToMany(TulpaSection::class, 'tulpa_page_tulpa_section', 'tulpa_page_id', 'tulpa_section_id');
  }

  protected $casts = [
    'tags'             => \Wasateam\Laravelapistone\Casts\JsonCast::class,
    'section_sequence' => \Wasateam\Laravelapistone\Casts\JsonCast::class,
    'content'          => \Wasateam\Laravelapistone\Casts\JsonCast::class,
  ];
}
