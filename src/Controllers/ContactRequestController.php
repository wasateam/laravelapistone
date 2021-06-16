<?php

namespace Wasateam\Laravelapistone\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Wasateam\Laravelapistone\Helpers\ModelHelper;

/**
 * @group  ContactRequest
 *
 * APIs for contact_request
 *
 * @authenticated
 */
class ContactRequestController extends Controller
{
  public $model        = 'Wasateam\Laravelapistone\Models\ContactRequest';
  public $name         = 'contact_request';
  public $resource     = 'Wasateam\Laravelapistone\Resources\ContactRequest';
  public $input_fields = [
    "name",
    "email",
    "tel",
    "remark",
    "company_name",
    "budget",
    "payload",
    "ip",
  ];

  /**
   * Index
   *
   */
  public function index(Request $request, $id = null)
  {
    if (config('stone.mode') == 'cms') {
      return ModelHelper::ws_IndexHandler($this, $request, $id);
    } else if (config('stone.mode') == 'webapi') {
      return ModelHelper::ws_IndexHandler($this, $request, $id);
    }
  }

  /**
   * Store
   *
   * @bodyParam type string Example: Subject 1
   * @bodyParam name string Example: wasa
   * @bodyParam email string Example: wasa@wasateam.com
   * @bodyParam tel string Example: 0999123123
   * @bodyParam remark string Example: Remark here
   * @bodyParam company_name string Example: Wasateam.co
   * @bodyParam budget string Example: 123123
   * @bodyParam payload object No-example
   */
  public function store(Request $request, $id = null)
  {
    if (config('stone.mode') == 'cms') {
      return ModelHelper::ws_StoreHandler($this, $request, $id);
    } else if (config('stone.mode') == 'webapi') {
      return ModelHelper::ws_StoreHandler($this, $request, $id, function ($model) {
        $model->ip = \Request::ip();
        $model->save();
      });
    }
  }

  /**
   * Show
   *
   * @urlParam  contact_request required The ID of contact_request. Example: 1
   */
  public function show(Request $request, $id = null)
  {
    return ModelHelper::ws_ShowHandler($this, $request, $id);
  }

  /**
   * Update
   *
   * @urlParam contact_request Example: 1
   * @bodyParam type string Example: Subject 1
   * @bodyParam name string Example: wasa
   * @bodyParam email string Example: wasa@wasateam.com
   * @bodyParam tel string Example: 0999123123
   * @bodyParam remark string Example: Remark here
   * @bodyParam company_name string Example: Wasateam.co
   * @bodyParam budget string Example: 123123
   * @bodyParam payload object No-example
   */
  public function update(Request $request, $id)
  {
    if (config('stone.mode') == 'cms') {
      return ModelHelper::ws_UpdateHandler($this, $request, $id);
    } else if (config('stone.mode') == 'webapi') {
      return ModelHelper::ws_UpdateHandler($this, $request, $id, [], function ($model) {
        $model->ip = \Request::ip();
        $model->save();
      });
    }
  }

  /**
   * Delete
   *
   * @urlParam contact_request Example: 2
   */
  public function destroy($id)
  {
    if (config('stone.mode') == 'cms') {
      return ModelHelper::ws_DestroyHandler($this, $id);
    } else if (config('stone.mode') == 'webapi') {
      return null;
    }
  }
}
