<?php

namespace Wasateam\Laravelapistone\Controllers;

use App\Http\Controllers\Controller;
use Auth;
use Illuminate\Http\Request;
use Wasateam\Laravelapistone\Helpers\GcsHelper;
use Wasateam\Laravelapistone\Helpers\ModelHelper;

/**
 * @group PocketFile
 *
 * @authenticated
 *
 * APIs for PocketFile
 */
class PocketFileController extends Controller
{
  public $model                   = 'Wasateam\Laravelapistone\Models\PocketFile';
  public $name                    = 'pocket_file';
  public $resource                = 'Wasateam\Laravelapistone\Resources\PocketFile';
  public $resource_for_collection = 'Wasateam\Laravelapistone\Resources\PocketFileCollection';
  public $version_controller      = "Wasateam\Laravelapistone\Controllers\PocketFileVersionController";
  public $input_fields            = [
    // 'url',
    // 'signed_url',
    // 'name',
    // 'tags',
    // 'signed',
  ];
  public $belongs_to = [
    // 'created_user',
    // 'created_admin',
  ];
  public $order_fields = [
    'updated_at',
    'created_at',
  ];
  public $user_record_field = 'updated_admin_id';

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
   * @bodyParam url string Example: url_of_file
   * @bodyParam signed_url string Example: signed_url_of_file
   * @bodyParam name string Example: my_file
   * @bodyParam tags object Example: ["tagA","tagB"]
   * @bodyParam signed boolean Example: 0
   * @bodyParam created_user int Example: 1
   * @bodyParam created_admin int Example: 1
   */
  public function store(Request $request, $id = null)
  {
    return ModelHelper::ws_StoreHandler($this, $request, $id, function ($model) {
      $admin                   = Auth::user();
      $model->created_admin_id = $admin->id;
      $model->save();
    }, null, true, $this->version_controller);
  }

  /**
   * Show
   *
   * @urlParam  pocket_file required The ID of pocket_file. Example: 1
   */
  public function show(Request $request, $id = null)
  {
    return ModelHelper::ws_ShowHandler($this, $request, $id);
  }

  /**
   * Update
   *
   * @urlParam  pocket_file required The ID of pocket_file. Example: 1
   * @bodyParam signed_url string Example: signed_url_of_file
   * @bodyParam url string Example: url_of_file
   * @bodyParam name string Example: my_file
   * @bodyParam tags object Example: ["tagA","tagB"]
   * @bodyParam signed boolean Example: 0
   * @bodyParam created_user int Example: 1
   * @bodyParam created_admin int Example: 1
   */
  public function update(Request $request, $id)
  {
    return ModelHelper::ws_UpdateHandler($this, $request, $id);
  }

  /**
   * Delete
   *
   * @urlParam  pocket_file required The ID of pocket_file. Example: 2
   */
  public function destroy($id)
  {
    return ModelHelper::ws_DestroyHandler($this, $id);
  }

  /**
   * Get Upload Url
   *
   */
  public function get_upload_url(Request $request)
  {
    $name = $request->name;
    $storage_service = config('stone.storage.service');
    if ($storage_service == 'gcs') {
      return GcsHelper::getUploadSignedUrlByNameAndPath($name, 'pocket_file');
    }
  }

  /**
   * Public
   * @urlParam id string Example: 1
   *
   */
  public function public_url($id)
  {
    $admin = Auth::user();
    $model = $this->model::find($id);
    $mode  = config('stone.mode');
    $acl   = config('stone.storage.acl');
    if ($mode == 'cms' && $model->created_admin_id != $user->id) {
      return response()->json([
        'message' => ':(',
      ], 400);
    }
    if ($mode == 'webapi' && $model->created_user_id != $user->id) {
      return response()->json([
        'message' => ':(',
      ], 400);
    }
    if ($model->last_version->signed) {
      return response()->json([
        'message' => ':(',
      ], 400);
    } else {
      GcsHelper::makeUrlPublic($model->last_version->url);
      return response()->json([
        'message' => ':)',
      ], 200);
    }
  }
}
