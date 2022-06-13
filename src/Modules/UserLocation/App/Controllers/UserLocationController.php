<?php

namespace Wasateam\Laravelapistone\Modules\UserLocation\App\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Wasateam\Laravelapistone\Helpers\ModelHelper;
use Wasateam\Laravelapistone\Models\UserLocation;

/**
 * @group UserLocation 使用者位置
 *
 *
 * @authenticated
 *
 * lat 緯度
 * lng 經度
 * user 使用者
 *
 */
class UserLocationController extends Controller
{
  public $model        = 'Wasateam\Laravelapistone\Modules\UserLocation\App\Models\UserLocation';
  public $name         = 'user_location';
  public $resource     = 'Wasateam\Laravelapistone\Modules\UserLocation\App\Http\Resources\UserLocation';
  public $input_fields = [
    'lat',
    'lng',
  ];
  public $belongs_to = [
    'user',
  ];
  public $order_fields = [
    'updated_at',
    'created_at',
  ];
  public $filter_time_fields = [
    'birthday',
  ];

  public function __construct()
  {
    if (config('stone.country_code')) {
    }
  }

  /**
   * Index
   * @queryParam search string No-example
   *
   */
  public function index(Request $request, $id = null)
  {
    return ModelHelper::ws_IndexHandler($this, $request, $id, true);
  }

  /**
   * Show
   *
   * @urlParam  user_location required The ID of user_location. Example: 1
   */
  public function show(Request $request, $id = null)
  {
    return ModelHelper::ws_ShowHandler($this, $request, $id);
  }

  /**
   * Store User My
   *
   * @bodyParam lat 緯度 float Example: 25.055190
   * @bodyParam lng 經度 float Example: 121.532771
   */
  public function store_user_my(Request $request, $id = null)
  {
    return ModelHelper::ws_StoreHandler($this, $request, $id, function ($model) {
      $model->user_id = \Auth::user()->id;
      $model->save();
    });
  }

  /**
   * Show User My Recent
   *
   */
  public function show_user_my_recent()
  {
    $user = \Auth::user();
    if (!$user->user_location_recent) {
      return null;
    } else {
      return new \Wasateam\Laravelapistone\Modules\UserLocation\App\Http\Resources\UserLocation_R1($user->user_location_recent);
    }
  }
}
