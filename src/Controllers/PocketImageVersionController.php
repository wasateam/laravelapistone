<?php

namespace Wasateam\Laravelapistone\Controllers;

use App\Http\Controllers\Controller;
use Auth;
use Illuminate\Http\Request;
use Wasateam\Laravelapistone\Helpers\ModelHelper;

/**
 * @group PocketImageVersion
 *
 * @authenticated
 *
 * APIs for PocketImageVersion
 */
class PocketImageVersionController extends Controller
{
  public $model                   = 'Wasateam\Laravelapistone\Models\PocketImageVersion';
  public $name                    = 'pocket_image_version';
  public $resource                = 'Wasateam\Laravelapistone\Resources\PocketImageVersion';
  public $resource_for_collection = 'Wasateam\Laravelapistone\Resources\PocketImageVersionCollection';
  public $input_fields            = [
    'url',
    'signed_url',
    'name',
    'tags',
    'signed',
    'size',
    'is_eternal',
  ];
  public $belongs_to = [
    'created_user',
    'created_admin',
  ];
  public $order_fields = [
    'updated_at',
    'created_at',
  ];
  public $user_record_field = 'updated_admin_id';
  public $parent_model      = 'Wasateam\Laravelapistone\Models\PocketImage';
  public $parent_id_field   = 'pocket_image_id';

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
   * @bodyParam url string Example: url_of_image
   * @bodyParam signed_url string Example: signed_url_of_image
   * @bodyParam name string Example: my_image
   * @bodyParam tags object Example: ["tagA","tagB"]
   * @bodyParam signed boolean Example: 0
   * @bodyParam size integer Example: 10
   * @bodyParam is_eternal boolean Example: 0
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
   * @urlParam  pocket_image_version required The ID of pocket_image_version. Example: 1
   */
  public function show(Request $request, $id = null)
  {
    return ModelHelper::ws_ShowHandler($this, $request, $id);
  }

  /**
   * Update
   *
   * @urlParam  pocket_image_version required The ID of pocket_image_version. Example: 1
   * @bodyParam signed_url string Example: signed_url_of_image
   * @bodyParam url string Example: url_of_image
   * @bodyParam name string Example: my_image
   * @bodyParam tags object Example: ["tagA","tagB"]
   * @bodyParam signed boolean Example: 0
   * @bodyParam size integer Example: 10
   * @bodyParam is_eternal boolean Example: 0
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
   * @urlParam  pocket_image_version required The ID of pocket_image_version. Example: 2
   */
  public function destroy($id)
  {
    return ModelHelper::ws_DestroyHandler($this, $id);
  }
}
