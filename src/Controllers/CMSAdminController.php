<?php

namespace Wasateam\Laravelapistone\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Wasateam\Laravelapistone\Helpers\ModelHelper;

/**
 * @group Admin
 *
 * @authenticated
 *
 * APIs for admin
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
  public $validation_rules = [
    'email'    => "required|string|email|unique:admins",
    'password' => 'required|string|confirmed|min:6',
    'name'     => 'required|string|min:1|max:40',
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
  ];
  public $search_fields = [
    'id',
    'name',
    'email',
  ];
  public $belongs_to_many = [
    'roles',
  ];
  public $order_fields = [
    'id',
    'updated_at',
    'created_at',
  ];
  public $user_record_field = 'updated_admin_id';
  public $user_create_field = 'created_admin_id';

  /**
   * Index
   * @urlParam search string No-example
   *
   */
  public function index(Request $request, $id = null)
  {
    return ModelHelper::ws_IndexHandler($this, $request, $id);
  }

  /**
   * Store
   *
   * @bodyParam name string No-example
   * @bodyParam email string No-example
   * @bodyParam email_verified_at datetime No-example
   * @bodyParam password string No-example
   * @bodyParam status integer No-example
   * @bodyParam avatar string No-example
   * @bodyParam settings string No-example
   * @bodyParam scopes object No-example
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
   * @bodyParam name string No-example
   * @bodyParam email string No-example
   * @bodyParam email_verified_at datetime No-example
   * @bodyParam password string No-example
   * @bodyParam status integer No-example
   * @bodyParam avatar string No-example
   * @bodyParam settings string No-example
   * @bodyParam scopes object No-example
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
