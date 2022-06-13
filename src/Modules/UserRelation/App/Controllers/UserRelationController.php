<?php

namespace Wasateam\Laravelapistone\Modules\UserRelation\App\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Wasateam\Laravelapistone\Helpers\ModelHelper;
use Wasateam\Laravelapistone\Models\UserRelation;

/**
 * @group UserRelation 使用者關係
 *
 *
 * @authenticated
 *
 * user 使用者
 * target 目標使用者
 * relation 關係
 * ~ 1 喜歡
 * ~ 2 被喜歡
 * ~ 3 互相喜歡
 *
 */
class UserRelationController extends Controller
{
  public $model        = 'Wasateam\Laravelapistone\Modules\UserRelation\App\Models\UserRelation';
  public $name         = 'user_relation';
  public $resource     = 'Wasateam\Laravelapistone\Modules\UserRelation\App\Http\Resources\UserRelation';
  public $input_fields = [
    'relation',
  ];
  public $belongs_to = [
    'user',
    'target',
  ];
  public $order_fields = [
    'updated_at',
    'created_at',
  ];
  public $filter_time_fields = [
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
   * @urlParam  user_relation required The ID of user_relation. Example: 1
   */
  public function show(Request $request, $id = null)
  {
    return ModelHelper::ws_ShowHandler($this, $request, $id);
  }
}
