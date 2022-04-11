<?php

namespace Wasateam\Laravelapistone\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Wasateam\Laravelapistone\Helpers\ModelHelper;

/**
 * @group LotteryWinningRecord
 *
 * @authenticated
 *
 * lottery_participant 參加者
 * lottery_prize 獎項
 */
class LotteryWinningRecordController extends Controller
{
  public $model        = 'Wasateam\Laravelapistone\Models\LotteryWinningRecord';
  public $name         = 'lottery_winning_record';
  public $resource     = 'Wasateam\Laravelapistone\Resources\LotteryWinningRecord';
  public $input_fields = [
  ];
  public $belongs_to = [
    'lottery_participant',
    'lottery_prize',
  ];
  public $order_fields = [
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
   * @bodyParam lottery_participant id No-example
   * @bodyParam lottery_prize id No-example
   */
  public function store(Request $request, $id = null)
  {
    return ModelHelper::ws_StoreHandler($this, $request, $id);
  }

  /**
   * Show
   *
   * @urlParam  lottery_winning_record required The ID of lottery_winning_record. Example: 1
   */
  public function show(Request $request, $id = null)
  {
    return ModelHelper::ws_ShowHandler($this, $request, $id);
  }

  /**
   * Update
   *
   * @urlParam  lottery_winning_record required The ID of lottery_winning_record. Example: 1
   * @bodyParam lottery_participant id No-example
   * @bodyParam lottery_prize id No-example
   */
  public function update(Request $request, $id)
  {
    return ModelHelper::ws_UpdateHandler($this, $request, $id);
  }

  /**
   * Delete
   *
   * @urlParam  lottery_winning_record required The ID of lottery_winning_record. Example: 2
   */
  public function destroy($id)
  {
    return ModelHelper::ws_DestroyHandler($this, $id);
  }
}
