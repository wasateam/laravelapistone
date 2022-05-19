<?php

namespace Wasateam\Laravelapistone\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Wasateam\Laravelapistone\Helpers\ModelHelper;
use Wasateam\Laravelapistone\Models\XcProjectMember;

/**
 * @group XcProjectMember 專案 (管理後台)
 *
 * @authenticated
 *
 * scopes Scopes
 * xc_project 專案
 * admin 後台使用者
 *
 */
class XcProjectMemberController extends Controller
{
  public $model        = 'Wasateam\Laravelapistone\Models\XcProjectMember';
  public $name         = 'xc_project_member';
  public $resource     = 'Wasateam\Laravelapistone\Resources\XcProjectMember';
  public $input_fields = [
    'scopes',
  ];
  public $belongs_to = [
    'xc_project',
    'admin',
  ];
  public $filter_belongs_to = [
    'xc_project',
    'admin',
  ];
  public $order_fields = [
    'updated_at',
    'created_at',
  ];

  public function __construct()
  {
  }

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
   * scopes Scopes
   * xc_project 專案
   * admin 後台使用者
   *
   */
  public function store(Request $request, $id = null)
  {
    return ModelHelper::ws_StoreHandler($this, $request, $id);
  }

  /**
   * Show
   *
   * @urlParam  xc_project_member required The ID of xc_project_member. Example: 1
   */
  public function show(Request $request, $id = null)
  {
    return ModelHelper::ws_ShowHandler($this, $request, $id);
  }

  /**
   * Update
   *
   * @urlParam  xc_project_member required The ID of xc_project_member. Example: 1
   * scopes Scopes
   * xc_project 專案
   * admin 後台使用者
   */
  public function update(Request $request, $id)
  {
    return ModelHelper::ws_UpdateHandler($this, $request, $id);
  }

  /**
   * Delete
   *
   * @urlParam  xc_project_member required The ID of xc_project_member. Example: 2
   */
  public function destroy($id)
  {
    return ModelHelper::ws_DestroyHandler($this, $id);
  }
}
