<?php

namespace Wasateam\Laravelapistone\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Wasateam\Laravelapistone\Helpers\ModelHelper;

/**
 * @group Test
 *
 * @authenticated
 *
 * APIs for test
 */

class TestController extends Controller
{
    public $model                   = 'Wasateam\Laravelapistone\Models\Test';
    public $name                    = 'test';
    public $resource                = 'Wasateam\Laravelapistone\Resources\Test';
    public $resource_for_collection = 'Wasateam\Laravelapistone\Resources\TestCollection';
    public $input_fields            = [
      'name',
    ];
    public $belongs_to = [
    ];
    public $order_fields = [
      'id',
      'title',
      'description',
      'updated_at',
      'created_at',
    ];

    public function index(Request $request, $id = null)
    {
      return ModelHelper::ws_IndexHandler($this, $request, $id);
    }

    public function store(Request $request, $id = null)
    {
      return ModelHelper::ws_StoreHandler($this, $request, $id);
    }

    public function show(Request $request, $id = null)
    {
      return ModelHelper::ws_ShowHandler($this, $request, $id);
    }

    public function update(Request $request, $id)
    {
      return ModelHelper::ws_UpdateHandler($this, $request, $id);
    }

    public function destroy($id)
    {
      return ModelHelper::ws_DestroyHandler($this, $id);
    }
}
