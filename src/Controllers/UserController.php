<?php

namespace Wasateam\Laravelapistone\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Wasateam\Laravelapistone\Helpers\ModelHelper;

/**
 * @group User
 *
 * @authenticated
 *
 * APIs for user
 */
class UserController extends Controller
{
  public $model               = 'Wasateam\Laravelapistone\Models\User';
  public $name                = 'user';
  public $resource            = 'Wasateam\Laravelapistone\Resources\User';
  public $validation_messages = [
    'password.min' => 'password too short.',
    'email.unique' => 'email has been token.',
  ];
  public $validation_rules = [
    'email'    => "required|string|email|unique:users",
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
    'description',
    'scopes',
    'tel',
    'payload',
  ];
  public $search_fields = [
    'name',
    'email',
  ];
  public $order_fields = [
    'updated_at',
    'created_at',
  ];
  public $user_record_field = 'updated_admin_id';

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
   * @bodyParam description string No-example
   * @bodyParam scopes object No-example
   * @bodyParam tel string No-example
   * @bodyParam payload object No-example
   */
  public function store(Request $request, $id = null)
  {
    return ModelHelper::ws_StoreHandler($this, $request, $id);
  }

  /**
   * Show
   *
   * @urlParam  user required The ID of user. Example: 1
   */
  public function show(Request $request, $id = null)
  {
    return ModelHelper::ws_ShowHandler($this, $request, $id);
  }

  /**
   * Update
   *
   * @urlParam  user required The ID of user. Example: 1
   * @bodyParam name string No-example
   * @bodyParam email string No-example
   * @bodyParam email_verified_at datetime No-example
   * @bodyParam password string No-example
   * @bodyParam status integer No-example
   * @bodyParam avatar string No-example
   * @bodyParam settings string No-example
   * @bodyParam description string No-example
   * @bodyParam scopes object No-example
   * @bodyParam tel string No-example
   * @bodyParam payload object No-example
   */
  public function update(Request $request, $id)
  {
    return ModelHelper::ws_UpdateHandler($this, $request, $id);
  }

  /**
   * Delete
   *
   * @urlParam  user required The ID of user. Example: 2
   */
  public function destroy($id)
  {
    return ModelHelper::ws_DestroyHandler($this, $id);
  }
}
