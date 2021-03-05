<?php

namespace Wasateam\Laravelapistone\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class Admin extends Authenticatable
{
  use HasFactory, Notifiable, HasApiTokens;
  use \Illuminate\Database\Eloquent\SoftDeletes;

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
    'name',
    'email',
    'password',
  ];

  /**
   * The attributes that should be hidden for arrays.
   *
   * @var array
   */
  protected $hidden = [
    'password',
    'remember_token',
  ];

  /**
   * The attributes that should be cast to native types.
   *
   * @var array
   */
  protected $casts = [
    'email_verified_at' => 'datetime',
    'password'          => \Wasateam\Laravelapistone\Casts\PasswordCast::class,
    'scopes'            => \Wasateam\Laravelapistone\Casts\JsonCast::class,
    // 'avatar'            => \Wasateam\Laravelapistone\Casts\SignedUrlAuthCast::class,
  ];
}
