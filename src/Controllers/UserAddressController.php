<?php

namespace Wasateam\Laravelapistone\Controllers;

use App\Http\Controllers\Controller;
use Auth;
use Illuminate\Http\Request;
use Wasateam\Laravelapistone\Helpers\ModelHelper;
use Wasateam\Laravelapistone\Models\User;
use Wasateam\Laravelapistone\Models\UserAddress;

/**
 * @group UserAddress
 *
 * @authenticated
 *
 * APIs for user_address
 */
class UserAddressController extends Controller
{
  public $model        = 'Wasateam\Laravelapistone\Models\UserAddress';
  public $name         = 'user_address';
  public $resource     = 'Wasateam\Laravelapistone\Resources\UserAddress';
  public $input_fields = [
    'address',
  ];
  public $belongs_to = [
    'user',
    'area',
    'area_section',
  ];
  public $filter_belongs_to = [
    'user',
  ];
  public $order_fields = [
    'updated_at',
    'created_at',
  ];

  /**
   * Index
   *
   * @queryParam  user int No-example 1
   *
   */
  public function index(Request $request, $id = null)
  {
    return ModelHelper::ws_IndexHandler($this, $request, $id);
  }

  /**
   * Store
   *
   * @bodyParam  address string Example:address
   * @bodyParam  user int Example: 1
   * @bodyParam  area int Example: 1
   * @bodyParam  area_section int Example: 1
   */
  public function store(Request $request, $id = null)
  {
    if (!$request->has('user')) {
      return response()->json([
        'message' => 'need user;',
      ], 400);
    }
    $user = User::where('id', $request->user)->first();
    if (!$user) {
      return response()->json([
        'message' => 'no user;',
      ], 400);
    }
    if (count($user->addresses) == 3) {
      return response()->json([
        'message' => 'max address',
      ], 400);
    }
    return ModelHelper::ws_StoreHandler($this, $request, $id);
  }

  /**
   * Show
   *
   * @urlParam  user_address required The ID of user_address. Example: 1
   * @queryParam  user int No-example 1
   */
  public function show(Request $request, $id = null)
  {
    return ModelHelper::ws_ShowHandler($this, $request, $id);
  }

  /**
   * Update
   *
   * @urlParam  user_address required The ID of user_address. Example: 1
   * @bodyParam  address string Example:address
   * @bodyParam  user int Example: 1
   * @bodyParam  area int Example: 1
   * @bodyParam  area_section int Example: 1
   */
  public function update(Request $request, $id)
  {
    if (config('stone.mode') == 'cms') {
      return ModelHelper::ws_UpdateHandler($this, $request, $id);
    } else if (config('stone.mode') == 'webapi') {
      $user_address = UserAddress::where('user_id', $id)->first();
      if ($user_address->id != Auth::user()->id) {
        return response()->json([
          'message' => 'Invalid Scope(s)',
        ], 400);
      }
      return ModelHelper::ws_UpdateHandler($this, $request, $id);
    }
  }

  /**
   * Delete
   *
   * @urlParam  user_address required The ID of user_address. Example: 2
   * @queryParam  user int No-example 1
   */
  public function destroy($id)
  {
    return ModelHelper::ws_DestroyHandler($this, $id);
  }
}
