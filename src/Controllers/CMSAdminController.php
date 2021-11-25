<?php

namespace Wasateam\Laravelapistone\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Wasateam\Laravelapistone\Helpers\ModelHelper;

/**
 * @group Admin
 *
 * @authenticated
 ** name 名稱
 * email Email
 * email_verified_at Email 驗證時間
 * password 密碼
 * status 帳號狀態
 * avatar 大頭貼
 * settings 相關設定
 * scopes 使用權限
 * is_active 是否啟用
 * sequence 排列順序設定值
 * country_code 國家代碼
 *
 */
class CMSAdminController extends Controller
{
  public $model               = 'Wasateam\Laravelapistone\Models\Admin';
  public $name                = 'admin';
  public $resource            = 'Wasateam\Laravelapistone\Resources\Admin';
  public $validation_messages = [
    'password.min' => 'password too short.',
    'email.unique' => 'email has been token.',
  ];
  public $store_validation_rules = [
    'email'    => "required|string|email|unique:admins",
    'password' => 'required|string|min:6',
    'name'     => 'required|string|min:1|max:40',
  ];
  public $validation_rules = [
    'email' => "required|string|email|unique:admins",
    'name'  => 'required|string|min:1|max:40',
  ];
  public $input_fields = [
    'name',
    'email',
    'email_verified_at',
    'password',
    'status',
    'avatar',
    'settings',
    'scopes',
    'is_active',
    'sequence',
  ];
  public $filter_fields = [
    'is_active',
  ];
  public $search_fields = [
    'id',
    'name',
    'email',
  ];
  public $belongs_to_many        = [];
  public $filter_belongs_to_many = [];
  public $order_fields           = [
    'id',
    'updated_at',
    'created_at',
    'sequence',
  ];
  public $user_record_field = 'updated_admin_id';
  public $user_create_field = 'created_admin_id';

  public function __construct()
  {
    if (config('stone.admin_role')) {
      $this->belongs_to_many[]        = 'roles';
      $this->filter_belongs_to_many[] = 'roles';
    }
    if (config('stone.admin_group')) {
      if (config('stone.admin_blur')) {
        $this->belongs_to_many[]        = 'cmser_groups';
        $this->filter_belongs_to_many[] = 'cmser_groups';
      } else {
        $this->belongs_to_many[]        = 'admin_groups';
        $this->filter_belongs_to_many[] = 'admin_groups';
      }
    }
    if (config('stone.country_code')) {
      $this->input_fields[]  = 'country_code';
      $this->filter_fields[] = 'country_code';
    }
  }

  /**
   * Index
   * @queryParam search string No-example
   *
   */
  public function index(Request $request, $id = null)
  {
    return ModelHelper::ws_IndexHandler($this, $request, $id);
  }

  /**
   * Store
   *
   * @bodyParam sequence string Example:1
   * @bodyParam name string Example:name
   * @bodyParam email string Example:123@wasateam.com
   * @bodyParam email_verified_at datetime Example:2020-10-10
   * @bodyParam password string Example:123123
   * @bodyParam status integer Example:0
   * @bodyParam is_active integer Example:0
   * @bodyParam avatar string
   * @bodyParam settings string
   * @bodyParam admin_groups object Example:1
   * @bodyParam cmser_groups object Example:1
   * @bodyParam scopes object Example:[]
   * @bodyParam country_code string Example:tw
   */
  public function store(Request $request, $id = null)
  {
    return ModelHelper::ws_StoreHandler($this, $request, $id);
  }

  /**
   * Show
   *
   * @urlParam  admin required The ID of admin. Example: 1
   */
  public function show(Request $request, $id = null)
  {
    return ModelHelper::ws_ShowHandler($this, $request, $id);
  }

  /**
   * Update
   *
   * @urlParam  admin required The ID of admin. Example: 1
   * @bodyParam sequence string Example:1
   * @bodyParam name string Example:name
   * @bodyParam email string Example:123@wasateam.com
   * @bodyParam email_verified_at datetime Example:2020-10-10
   * @bodyParam password string Example:123123
   * @bodyParam status integer Example:0
   * @bodyParam is_active integer Example:0
   * @bodyParam avatar string
   * @bodyParam settings string
   * @bodyParam admin_groups object Example:1
   * @bodyParam cmser_groups object Example:1
   * @bodyParam scopes object Example:[]
   * @bodyParam country_code string Example:tw
   */
  public function update(Request $request, $id)
  {
    return ModelHelper::ws_UpdateHandler($this, $request, $id);
  }

  /**
   * Delete
   *
   * @urlParam  admin required The ID of admin. Example: 2
   */
  public function destroy($id)
  {
    return ModelHelper::ws_DestroyHandler($this, $id);
  }
}
