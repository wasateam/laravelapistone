<?php

namespace Wasateam\Laravelapistone\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Wasateam\Laravelapistone\Helpers\ModelHelper;

/**
 * @group SocialiteGoogleAccount
 *
 * @authenticated
 *
 * APIs for SocialiteGoogleAccount
 */
class SocialiteGoogleAccountController extends Controller
{
  public $model        = 'Wasateam\Laravelapistone\Models\SocialiteGoogleAccount';
  public $name         = 'SocialiteGoogleAccount';
  public $resource     = 'Wasateam\Laravelapistone\Resources\SocialiteGoogleAccount';
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
   * @urlParam  socialite_google_account required The ID of socialite_google_account. Example: 1
   */
  public function show(Request $request, $id = null)
  {
    return ModelHelper::ws_ShowHandler($this, $request, $id);
  }

  /**
   * Update
   *
   * @urlParam  socialite_google_account required The ID of socialite_google_account. Example: 1
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
   * @urlParam  socialite_google_account required The ID of socialite_google_account. Example: 2
   */
  public function destroy($id)
  {
    return ModelHelper::ws_DestroyHandler($this, $id);
  }
}
