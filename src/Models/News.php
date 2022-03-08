<?php

namespace Wasateam\Laravelapistone\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Wasateam\Laravelapistone\Models\Admin;
use Wasateam\Laravelapistone\Models\PocketImage;
use Wasateam\Laravelapistone\Models\TulpaSection;

class News extends Model
{
  use HasFactory;
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

  public function cover_image()
  {
    return $this->belongsTo(PocketImage::class, 'cover_image_id');
  }

  protected $casts = [
    'content' => \Wasateam\Laravelapistone\Casts\HtmlCast::class,
    'tags'    => \Wasateam\Laravelapistone\Casts\JsonCast::class,
  ];
}
