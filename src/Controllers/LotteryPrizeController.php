<?php

namespace Wasateam\Laravelapistone\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Wasateam\Laravelapistone\Helpers\ModelHelper;

/**
 * @group LotteryPrize
 *
 * @authenticated
 *
 * name 名稱
 * uuid
 * count 數量
 */
class LotteryPrizeController extends Controller
{
  public $model        = 'Wasateam\Laravelapistone\Models\LotteryPrize';
  public $name         = 'lottery_prize';
  public $resource     = 'Wasateam\Laravelapistone\Resources\LotteryPrize';
  public $input_fields = [
    'name',
    'uuid',
    'count',
  ];
  public $order_fields = [
    'updated_at',
    'created_at',
  ];
  public $uuid = true;

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
   * @bodyParam name string No-example
   * @bodyParam uuid string No-example
   * @bodyParam count string No-example
   */
  public function store(Request $request, $id = null)
  {
    return ModelHelper::ws_StoreHandler($this, $request, $id);
  }

  /**
   * Show
   *
   * @urlParam  lottery_prize required The ID of lottery_prize. Example: 1
   */
  public function show(Request $request, $id = null)
  {
    return ModelHelper::ws_ShowHandler($this, $request, $id);
  }

  /**
   * Update
   *
   * @urlParam  lottery_prize required The ID of lottery_prize. Example: 1
   * @bodyParam name string No-example
   * @bodyParam uuid string No-example
   * @bodyParam count string No-example
   */
  public function update(Request $request, $id)
  {
    return ModelHelper::ws_UpdateHandler($this, $request, $id);
  }

  /**
   * Delete
   *
   * @urlParam  lottery_prize required The ID of lottery_prize. Example: 2
   */
  public function destroy($id)
  {
    return ModelHelper::ws_DestroyHandler($this, $id);
  }
}
