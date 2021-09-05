<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
  use HasFactory;
  use \Illuminate\Database\Eloquent\SoftDeletes;

  public function appointmemnt_available()
  {
    return $this->belongsTo(AppointmentAvailable::class, 'appointmemnt_available_id');
  }

  public function user()
  {
    return $this->belongsTo(User::class, 'user_id');
  }
}
