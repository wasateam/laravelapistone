<?php

namespace Wasateam\Laravelapistone\Controllers;

use App\Http\Controllers\Controller;
use Auth;
use Illuminate\Http\Request;
use Wasateam\Laravelapistone\Helpers\ModelHelper;

/**
 * @group PocketFileVersion
 *
 * @authenticated
 *
 * APIs for PocketFileVersion
 */
class PocketFileVersionController extends Controller
{
  public $model                   = 'Wasateam\Laravelapistone\Models\PocketFileVersion';
  public $name                    = 'pocket_file_version';
  public $resource                = 'Wasateam\Laravelapistone\Resources\PocketFileVersion';
  public $resource_for_collection = 'Wasateam\Laravelapistone\Resources\PocketFileVersionCollection';
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
  public $parent_model      = 'Wasateam\Laravelapistone\Models\PocketFile';
  public $parent_id_field   = 'pocket_file_id';

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
   * @bodyParam url string Example: url_of_file
   * @bodyParam signed_url string Example: signed_url_of_file
   * @bodyParam name string Example: my_file
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
   * @urlParam  pocket_file_version required The ID of pocket_file_version. Example: 1
   */
  public function show(Request $request, $id = null)
  {
    return ModelHelper::ws_ShowHandler($this, $request, $id);
  }

  /**
   * Update
   *
   * @urlParam  pocket_file_version required The ID of pocket_file_version. Example: 1
   * @bodyParam signed_url string Example: signed_url_of_file
   * @bodyParam url string Example: url_of_file
   * @bodyParam name string Example: my_file
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
   * @urlParam  pocket_file_version required The ID of pocket_file_version. Example: 2
   */
  public function destroy($id)
  {
    return ModelHelper::ws_DestroyHandler($this, $id);
  }
}
