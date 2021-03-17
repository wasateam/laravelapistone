<?php

namespace Wasateam\Laravelapistone\Controllers;

use App\Http\Controllers\Controller;
use Auth;
use Illuminate\Http\Request;
use Wasateam\Laravelapistone\Helpers\GcsHelper;
use Wasateam\Laravelapistone\Helpers\ModelHelper;

/**
 * @group PocketImage
 *
 * @authenticated
 *
 * APIs for PocketImage
 */
class PocketImageController extends Controller
{
  public $model                   = 'Wasateam\Laravelapistone\Models\PocketImage';
  public $name                    = 'pocket_image';
  public $resource                = 'Wasateam\Laravelapistone\Resources\PocketImage';
  public $resource_for_collection = 'Wasateam\Laravelapistone\Resources\PocketImageCollection';
  public $input_fields            = [
    'url',
    'signed_url',
    'name',
    'tags',
    'signed',
  ];
  public $belongs_to = [
    // 'created_user',
    // 'created_admin',
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
   * @bodyParam url string Example: url_of_image
   * @bodyParam signed_url string Example: signed_url_of_image
   * @bodyParam name string Example: my_image
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
    });
  }

  /**
   * Show
   *
   * @urlParam  pocket_image required The ID of pocket_image. Example: 1
   */
  public function show(Request $request, $id = null)
  {
    return ModelHelper::ws_ShowHandler($this, $request, $id);
  }

  /**
   * Update
   *
   * @urlParam  pocket_image required The ID of pocket_image. Example: 1
   * @bodyParam signed_url string Example: signed_url_of_image
   * @bodyParam url string Example: url_of_image
   * @bodyParam name string Example: my_image
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
   * @urlParam  pocket_image required The ID of pocket_image. Example: 2
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
    return GcsHelper::getUploadSignedUrlByNameAndPath($name, 'image');
  }
}
