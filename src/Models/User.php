<?php

namespace Wasateam\Laravelapistone\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
  use HasFactory, Notifiable, HasApiTokens;
  use \Illuminate\Database\Eloquent\SoftDeletes;

  public function updated_admin()
  {
    return $this->belongsTo(Admin::class, 'updated_admin_id');
  }

  public function pocket_avatar()
  {
    return $this->belongsTo(PocketImage::class, 'pocket_avatar_id');
  }

  public function locale()
  {
    return $this->belongsTo(Locale::class, 'locale_id');
  }

  public function user_device_tokens()
  {
    return $this->hasMany(UserDeviceToken::class, 'user_id')->where('is_active', 1);
  }

  public function getDeviceTokensAttribute()
  {
    return $this->user_device_tokens->pluck(['device_token']);
  }

  public function app_roles()
  {
    return $this->belongsToMany(UserAppRole::class, 'user_app_role', 'user_id', 'app_role_id');
  }

  public function apps()
  {
    return $this->belongsToMany(App::class, 'user_app_info', 'user_id', 'app_id');
  }

  public function user_app_infos()
  {
    return $this->hasMany(UserAppInfo::class, 'user_id');
  }

  public function service_plans()
  {
    return $this->belongsToMany(ServicePlan::class, 'user_service_plans', 'user_id', 'service_plan_id');
  }

  public function socialite_google_accounts()
  {
    return $this->hasMany(SocialiteGoogleAccount::class, 'user_id');
  }

  public function socialite_facebook_accounts()
  {
    return $this->hasMany(SocialiteFacebookAccount::class, 'user_id');
  }

  public function socialite_line_accounts()
  {
    return $this->hasMany(SocialiteLineAccount::class, 'user_id');
  }

  public function shop_order()
  {
    return $this->hasMany(ShopOrder::class, 'shop_order_id');
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
    '',
  ];
}
