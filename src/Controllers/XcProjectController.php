<?php

namespace Wasateam\Laravelapistone\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Wasateam\Laravelapistone\Helpers\ModelHelper;
use Wasateam\Laravelapistone\Models\XcProject;

/**
 * @group XcProject 專案 (管理後台)
 *
 * @authenticated
 *
 * url 連結
 * name 名稱
 * slack_webhook_url Slack Webhook Url
 * status 狀態
 * ~ prepare 準備中
 * ~ ing 進行中
 * ~ suspend 暫停
 * ~ complete 結案
 * slack_team_id Slack Team ID
 * slack_channel_id Slack Channel Id
 * invision Invision 帳號
 * zeplin Zeplin 帳號
 * gitlab Gitlab 帳號
 * github Github 帳號
 * google_drive Google Drive 帳號
 * remark 備註
 * links 相關連結
 * infos 相關資訊
 * payload Payload
 * xc_project_members 專案成員
 *
 */
class XcProjectController extends Controller
{
  public $model                   = 'Wasateam\Laravelapistone\Models\XcProject';
  public $name                    = 'xc_project';
  public $resource                = 'Wasateam\Laravelapistone\Resources\XcProject';
  public $resource_for_collection = 'Wasateam\Laravelapistone\Resources\XcProjectCollection';
  public $input_fields            = [
    'url',
    'name',
    'slack_webhook_url',
    'status',
    'slack_team_id',
    'slack_channel_id',
    'zeplin',
    'gitlab',
    'github',
    'google_drive',
    'remark',
    'links',
    'infos',
    'payload',
  ];
  public $belongs_to_many = [
  ];
  public $filter_belongs_to_many = [
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
   * url 連結
   * name 名稱
   * slack_webhook_url Slack Webhook Url
   * status 狀態
   * slack_team_id Slack Team ID
   * slack_channel_id Slack Channel Id
   * invision Invision 帳號
   * zeplin Zeplin 帳號
   * gitlab Gitlab 帳號
   * github Github 帳號
   * google_drive Google Drive 帳號
   * remark 備註
   * links 相關連結
   * infos 相關資訊
   *
   */
  public function store(Request $request, $id = null)
  {
    return ModelHelper::ws_StoreHandler($this, $request, $id);
  }

  /**
   * Show
   *
   * @urlParam  xc_project required The ID of xc_project. Example: 1
   */
  public function show(Request $request, $id = null)
  {
    return ModelHelper::ws_ShowHandler($this, $request, $id);
  }

  /**
   * Update
   *
   * @urlParam  xc_project required The ID of xc_project. Example: 1
   * url 連結
   * name 名稱
   * slack_webhook_url Slack Webhook Url
   * status 狀態
   * slack_team_id Slack Team ID
   * slack_channel_id Slack Channel Id
   * invision Invision 帳號
   * zeplin Zeplin 帳號
   * gitlab Gitlab 帳號
   * github Github 帳號
   * google_drive Google Drive 帳號
   * remark 備註
   * links 相關連結
   * infos 相關資訊
   */
  public function update(Request $request, $id)
  {
    return ModelHelper::ws_UpdateHandler($this, $request, $id);
  }

  /**
   * Delete
   *
   * @urlParam  xc_project required The ID of xc_project. Example: 2
   */
  public function destroy($id)
  {
    return ModelHelper::ws_DestroyHandler($this, $id);
  }
}
