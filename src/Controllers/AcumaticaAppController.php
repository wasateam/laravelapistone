<?php

namespace Wasateam\Laravelapistone\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Wasateam\Laravelapistone\Helpers\ModelHelper;
use Wasateam\Laravelapistone\Models\AcumaticaApp;

/**
 * @group AcumaticaApp
 *
 * @authenticated
 *
 * APIs for acumatica_app
 */
class AcumaticaAppController extends Controller
{
  public $model        = 'Wasateam\Laravelapistone\Models\AcumaticaApp';
  public $name         = 'acumatica_app';
  public $resource     = 'Wasateam\Laravelapistone\Resources\AcumaticaApp';
  public $input_fields = [
    'client_id',
    'client_secret',
  ];
  public $order_fields = [
    'updated_at',
    'created_at',
  ];
  public $country_code = true;

  public function __construct()
  {
    if (config('stone.country_code')) {
      $this->input_fields[]  = 'country_code';
      $this->filter_fields[] = 'country_code';
    }
  }

  /**
   * Index
   * @queryParam search string No-example
   *
   */
  public function index(Request $request, $id = null)
  {
    return ModelHelper::ws_IndexHandler($this, $request, $id, true);
  }

  /**
   * Store
   *
   * @bodyParam client_id string No-example
   * @bodyParam client_secret string No-example
   * @bodyParam country_code string No-example
   */
  public function store(Request $request, $id = null)
  {
    return ModelHelper::ws_StoreHandler($this, $request, $id);
  }

  /**
   * Show
   *
   * @urlParam  acumatica_app required The ID of acumatica_app. Example: 1
   */
  public function show(Request $request, $id = null)
  {
    return ModelHelper::ws_ShowHandler($this, $request, $id);
  }

  /**
   * Update
   *
   * @urlParam  acumatica_app required The ID of acumatica_app. Example: 1
   * @bodyParam client_id string No-example
   * @bodyParam client_secret string No-example
   * @bodyParam country_code string No-example
   */
  public function update(Request $request, $id)
  {
    return ModelHelper::ws_UpdateHandler($this, $request, $id);
  }

  /**
   * Delete
   *
   * @urlParam  acumatica_app required The ID of acumatica_app. Example: 2
   */
  public function destroy($id)
  {
    return ModelHelper::ws_DestroyHandler($this, $id);
  }
}
