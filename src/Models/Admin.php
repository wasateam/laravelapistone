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

  public function updated_admin()
  {
    return $this->belongsTo(Admin::class, 'updated_admin_id');
  }

  public function created_admin()
  {
    return $this->belongsTo(Admin::class, 'created_admin_id');
  }

  public function pocket_avatar()
  {
    return $this->belongsTo(PocketImage::class, 'pocket_avatar_id');
  }

  public function roles()
  {
    return $this->belongsToMany(AdminRole::class, 'admin_role_admin', 'admin_id', 'admin_role_id');
  }

  // public function admin_groups()
  // // {
  // //   return $this->belongsToMany(AdminGroup::class, 'admin_group_admin', 'admin_id', 'admin_group_id');
  // // }

  public function cmser_groups()
  {
    return $this->belongsToMany(AdminGroup::class, 'admin_group_admin', 'admin_id', 'admin_group_id');
  }

  public function cmser_groups()
  {
    return $this->belongsToMany(AdminGroup::class, 'admin_group_admin', 'admin_id', 'admin_group_id');
  }

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
