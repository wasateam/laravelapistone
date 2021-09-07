<?php

namespace Wasateam\Laravelapistone\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Wasateam\Laravelapistone\Helpers\ModelHelper;
use Wasateam\Laravelapistone\Models\PinCard;

/**
 * @group PinCard
 *
 * @authenticated
 *
 * APIs for pin_card
 */
class PinCardController extends Controller
{
  public $model        = 'Wasateam\Laravelapistone\Models\PinCard';
  public $name         = 'pin_card';
  public $resource     = 'Wasateam\Laravelapistone\Resources\PinCard';
  public $input_fields = [
    // 'pin',
  ];
  public $belongs_to_many = [
    'service_plan',
  ];
  public $search_fields = [
    'pin',
  ];
  public $order_fields = [
    'updated_at',
    'created_at',
  ];
  public $user_record_field = 'updated_admin_id';
  public $user_create_field = 'created_admin_id';

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
   * @bodyParam pin string Example: FK1FA0I0Zg90
   * @bodyParam service_plan string Example: 1
   */
  public function store(Request $request, $id = null)
  {
    return ModelHelper::ws_StoreHandler($this, $request, $id);
  }

  /**
   * Show
   *
   * @urlParam  pin_card required The ID of pin_card. Example: 1
   */
  public function show(Request $request, $id = null)
  {
    return ModelHelper::ws_ShowHandler($this, $request, $id);
  }

  /**
   * Update
   *
   * @urlParam  pin_card required The ID of pin_card. Example: 1
   * @bodyParam pin string Example: FK1FA0I0Zg90
   * @bodyParam service_plan string Example: 1
   */
  public function update(Request $request, $id)
  {
    return ModelHelper::ws_UpdateHandler($this, $request, $id);
  }

  /**
   * Delete
   *
   * @urlParam  pin_card required The ID of pin_card. Example: 2
   */
  public function destroy($id)
  {
    return ModelHelper::ws_DestroyHandler($this, $id);
  }
}
