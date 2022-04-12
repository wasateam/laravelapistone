<?php

namespace Wasateam\Laravelapistone\Controllers;

use App\Http\Controllers\Controller;
use Google\Cloud\PubSub\MessageBuilder;
use Google\Cloud\PubSub\PubSubClient;
use Illuminate\Http\Request;
use Wasateam\Laravelapistone\Helpers\ModelHelper;
use Wasateam\Laravelapistone\Services\LotteryParticipantService;

/**
 * @group LotteryParticipant
 *
 * @authenticated
 *
 * id_number 身分證字號
 * name 名稱
 * gender 性別
 * birthday 生日
 * email
 * mobile 聯絡手機
 * uuid
 * qualifications 抽獎資格
 */
class LotteryParticipantController extends Controller
{
  public $model        = 'Wasateam\Laravelapistone\Models\LotteryParticipant';
  public $name         = 'lottery_participant';
  public $resource     = 'Wasateam\Laravelapistone\Resources\LotteryParticipant';
  public $input_fields = [
    'id_number',
    'name',
    'gender',
    'birthday',
    'email',
    'mobile',
  ];
  public $order_fields = [
    'updated_at',
    'created_at',
  ];
  public $uuid = true;

  public function __construct()
  {
    if (config('stone.mode') == 'cms') {
      $this->input_fields[] = 'qualifications';
    }
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
   * @bodyParam id_number string No-example
   * @bodyParam name string No-example
   * @bodyParam gender string No-example
   * @bodyParam birthday string No-example
   * @bodyParam email string No-example
   * @bodyParam mobile string No-example
   * @bodyParam qualifications string No-example
   */
  public function store(Request $request, $id = null)
  {
    if (config('stone.mode') == 'cms') {
      return ModelHelper::ws_StoreHandler($this, $request, $id);
    } else {
      LotteryParticipantService::store($request, $this, $id);
    }
  }

  /**
   * Show
   *
   * @urlParam  lottery_participant required The ID of lottery_participant. Example: 1
   */
  public function show(Request $request, $id = null)
  {
    return ModelHelper::ws_ShowHandler($this, $request, $id);
  }

  /**
   * Update
   *
   * @urlParam  lottery_participant required The ID of lottery_participant. Example: 1
   * @bodyParam id_number string No-example
   * @bodyParam name string No-example
   * @bodyParam gender string No-example
   * @bodyParam birthday string No-example
   * @bodyParam email string No-example
   * @bodyParam mobile string No-example
   * @bodyParam qualifications string No-example
   */
  public function update(Request $request, $id)
  {
    return ModelHelper::ws_UpdateHandler($this, $request, $id);
  }

  /**
   * Delete
   *
   * @urlParam  lottery_participant required The ID of lottery_participant. Example: 2
   */
  public function destroy($id)
  {
    return ModelHelper::ws_DestroyHandler($this, $id);
  }
}
