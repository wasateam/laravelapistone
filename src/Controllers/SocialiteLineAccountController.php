<?php

namespace Wasateam\Laravelapistone\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Wasateam\Laravelapistone\Helpers\ModelHelper;

/**
 * @group SocialiteLineAccount
 *
 * @authenticated
 *
 * APIs for SocialiteLineAccount
 */
class SocialiteLineAccountController extends Controller
{
  public $model        = 'Wasateam\Laravelapistone\Models\SocialiteLineAccount';
  public $name         = 'socialite_line_account';
  public $resource     = 'Wasateam\Laravelapistone\Resources\SocialiteLineAccount';
  public $input_fields = [
    'provider_user_id',
    'provider',
  ];
  public $belongs_to = [
    'user',
  ];
  public $order_fields = [
    'id',
    'updated_at',
    'created_at',
  ];

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
   * @bodyParam provider_user_id string No-example
   * @bodyParam provider string No-example
   * @bodyParam user id No-example
   */
  public function store(Request $request, $id = null)
  {
    return ModelHelper::ws_StoreHandler($this, $request, $id);
  }

  /**
   * Show
   *
   * @urlParam  socialite_line_account required The ID of socialite_line_account. Example: 1
   */
  public function show(Request $request, $id = null)
  {
    return ModelHelper::ws_ShowHandler($this, $request, $id);
  }

  /**
   * Update
   *
   * @urlParam  socialite_line_account required The ID of socialite_line_account. Example: 1
   * @bodyParam provider_user_id string No-example
   * @bodyParam provider string No-example
   * @bodyParam user id No-example
   */
  public function update(Request $request, $id)
  {
    return ModelHelper::ws_UpdateHandler($this, $request, $id);
  }

  /**
   * Delete
   *
   * @urlParam  socialite_line_account required The ID of socialite_line_account. Example: 2
   */
  public function destroy($id)
  {
    return ModelHelper::ws_DestroyHandler($this, $id);
  }
}
