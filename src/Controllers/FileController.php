<?php

namespace Wasateam\Laravelapistone\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Wasateam\Laravelapistone\Helpers\ModelHelper;

/**
 * @group File
 *
 * @authenticated
 *
 * APIs for File
 */
class FileController extends Controller
{
  public $model        = 'Wasateam\Laravelapistone\Models\File';
  public $name         = 'file';
  public $resource     = 'Wasateam\Laravelapistone\Resources\File';
  public $input_fields = [
    'url',
    'name',
    'tags',
  ];
  public $belongs_to = [
    'created_user',
    'created_admin',
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
   * @bodyParam url string Example: url_of_file
   * @bodyParam name string Example: my_file
   * @bodyParam tags object Example: ["tagA","tagB"]
   * @bodyParam created_user int Example: 1
   * @bodyParam created_admin int Example: 1
   */
  public function store(Request $request, $id = null)
  {
    return ModelHelper::ws_StoreHandler($this, $request, $id);
  }

  /**
   * Show
   *
   * @urlParam  file required The ID of file. Example: 1
   */
  public function show(Request $request, $id = null)
  {
    return ModelHelper::ws_ShowHandler($this, $request, $id);
  }

  /**
   * Update
   *
   * @urlParam  file required The ID of file. Example: 1
   * @bodyParam url string Example: url_of_file
   * @bodyParam name string Example: my_file
   * @bodyParam tags object Example: ["tagA","tagB"]
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
   * @urlParam  file required The ID of file. Example: 2
   */
  public function destroy($id)
  {
    return ModelHelper::ws_DestroyHandler($this, $id);
  }

  /**
   * Get Upload Url
   *
   */
  public function get_upload_url()
  {
    return 'ok';
  }
}
