<?php

namespace Wasateam\Laravelapistone\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdminFinancePaymentRequest extends Model
{
  use HasFactory;
  use \Illuminate\Database\Eloquent\SoftDeletes;

  public function admin()
  {
    return $this->belongsTo(Admin::class);
  }

  public function reviewer()
  {
    return $this->belongsTo(Admin::class, 'reviewer_id');
  }

  public function pocket_images()
  {
    return $this->belongsToMany(PocketImage::class, 'pocket_image_admin_finance_payment_request', 'admin_finance_payment_request_id', 'pocket_image_id');
  }

  protected $casts = [
    'paying_date'   => 'date',
    'verify_date'   => 'date',
    'complete_date' => 'date',
    'payload'       => \Wasateam\Laravelapistone\Casts\JsonCast::class,
  ];
}
