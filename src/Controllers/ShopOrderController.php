<?php

namespace Wasateam\Laravelapistone\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Wasateam\Laravelapistone\Helpers\ModelHelper;


class ShopOrderController extends Controller
{
    public $model                   = 'Wasateam\Laravelapistone\Models\ShopOrder';
    public $name                    = 'shop_order';
    public $resource                = 'Wasateam\Laravelapistone\Resources\ShopOrder';
    // public $resource_for_collection = 'Wasateam\Laravelapistone\Resources\ShopProductCollection';
    public $input_fields            = [
      'id',
      'no',
      'type',
      'selected',
      'order_time',
      'receiver',
      'receiver_tel',
      'receiver_address',
      'receive_remark',
      'package_methods',
      'order_status',
      'logistics_methods',
      'delivery_time',
      'delivery_remark',
      'shipment_status',
      'shipment_date',
      'customer_service',
    ];
    public $search_fields = [
        // 'no',
        // 'uuid',
    ];
    public $belongs_to = [
    ];
    public $filter_fields = [
    ];
    public $belongs_to_many = [
    ];
    public $filter_belongs_to_many = [
    ];
    public $order_fields = [
    ];
    public $uuid = false;

    public function index()
    {
        return ModelHelper::ws_IndexHandler($this, $request, $id);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $id = null)
    {
        return ModelHelper::ws_StoreHandler($this, $request, $id);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ShopOrder  $shopOrder
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id = null)
    {
      return ModelHelper::ws_ShowHandler($this, $request, $id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ShopOrder  $shopOrder
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
      return ModelHelper::ws_UpdateHandler($this, $request, $id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ShopOrder  $shopOrder
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
      return ModelHelper::ws_DestroyHandler($this, $id);
    }

      /**
   * Export Excel Signedurl
   *
   */
  public function export_excel_signedurl(Request $request)
  {
    $shop_orders = $request->has('shop_orders') ? $request->shop_orders : null;
    return URL::temporarySignedRoute(
      'user_export_excel',
      now()->addMinutes(30),
      ['shop_orders' => $shop_orders]
    );
  }

  /**
   * Export Excel
   *
   */
  public function export_excel(Request $request)
  {
    $shop_orders = $request->has('shop_orders') ? $request->shop_orders : null;
    return Excel::download(new UserExport($shop_orders), 'user.xlsx');
  }
}
