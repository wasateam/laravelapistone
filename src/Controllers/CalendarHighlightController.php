<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Wasateam\Laravelapistone\Helpers\ModelHelper;

/**
 * @group CalendarHighlight
 *
 * @authenticated
 *
 * APIs for calendar_highlight
 */
class CalendarHighlightController extends Controller
{
  public $model        = 'App\Models\CalendarHighlight';
  public $name         = 'calendar_highlight';
  public $resource     = 'App\Http\Resources\CalendarHighlight';
  public $input_fields = [
    'start_at',
    'start_end',
    'type',
    'name',
  ];
  public $validation_messages = [];
  public $validation_rules    = [];
  public $belongs_to          = [
    'app',
  ];
  public $filter_belongs_to = [];
  public $order_fields      = [
    'updated_at',
    'created_at',
  ];
  public $mocu_filters = [];

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
   * @bodyParam start_at datetime Example: 1991-03-13 10:10:00
   * @bodyParam start_end datetime Example: 1991-03-13 10:10:00
   * @bodyParam type string Example: additional_off_day
   * @bodyParam name string Example: 世界女僕日
   * @bodyParam app string No-example
   */
  public function store(Request $request, $id = null)
  {
    return ModelHelper::ws_IndexHandler($this, $request, $id);
  }

  /**
   * Show
   *
   * @urlParam  calendar_highlight required The ID of calendar_highlight. Example: 1
   */
  public function show(Request $request, $id = null)
  {
    return ModelHelper::ws_ShowHandler($this, $request, $id);
  }

  /**
   * Update
   *
   * @urlParam  calendar_highlight required The ID of calendar_highlight. Example: 1
   * @bodyParam start_at datetime Example: 1991-03-13 10:10:00
   * @bodyParam start_end datetime Example: 1991-03-13 10:10:00
   * @bodyParam type string Example: additional_off_day
   * @bodyParam name string Example: 世界女僕日
   * @bodyParam app string No-example
   */
  public function update(Request $request, $id)
  {
    return ModelHelper::ws_UpdateHandler($this, $request, $id);
  }

  /**
   * Delete
   *
   * @urlParam  calendar_highlight required The ID of calendar_highlight. Example: 2
   */
  public function destroy($id)
  {
    return ModelHelper::ws_DestroyHandler($this, $id);
  }
}
