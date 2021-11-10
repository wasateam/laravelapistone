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
    public $resource_for_collection = 'Wasateam\Laravelapistone\Resources\ShopProductCollection';
    public $input_fields            = [
      'id',
      'no',
      'type',
      'receiver',
      'receiver_tel',
      'receiver_address',
      'receive_remark',
      'package_methods',
      'status',
      'remark_status',
      'deliver_way',
      'delivery_time',
      'delivery_remark',
      'customer_service_remark',
    ];
    public $search_fields = [
      'no',
      'uuid',
      'receiver_tel',
    ];
    public $filter_fields = [
      'status',
      'type', // 訂單種類
      'remark_status',
      // 發票狀態
      // 配送時段
    ];
    public $belongs_to = [
      'user'
    ];
    public $belongs_to_many = [
    ];
    public $filter_belongs_to_many = [
    ];
    public $order_fields = [
      'updated_at',
      'created_at',
    ];
    public $uuid = false;

    public function __construct()
    {
      if (config('stone.shop.uuid')) {
        $this->uuid = true;
      }
    }

    public function index(Request $request, $id = null)
    {
        return ModelHelper::ws_IndexHandler($this, $request, $id);
    }

    /**
      * Store
      *
      * @bodyParam type string 訂單類型 No-example
      * @bodyParam no string 訂單編號 No-example
      * @bodyParam receiver string 收件者 No-example
      * @bodyParam receiver_tel string 收件人電話 No-example
      * @bodyParam receiver_address string 收件人住址 No-example
      * @bodyParam receive_remark text 收件備註 No-example
      * @bodyParam package_methods string 包裝方式 No-example
      * @bodyParam status 訂單狀態 string No-example
      * @bodyParam remark_status string 訂單備註狀態 No-example
      * @bodyParam deliver_way string 物流方式 No-example
      * @bodyParam delivery_time time 配送時段 No-example
      * @bodyParam delivery_remark text 配送備註 No-example
      * @bodyParam customer_service_remark text 客服備註 No-example
      * 
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
      * update
      *
      * @bodyParam type string 訂單類型 No-example
      * @bodyParam no string 訂單編號 No-example
      * @bodyParam receiver string 收件者 No-example
      * @bodyParam receiver_tel string 收件人電話 No-example
      * @bodyParam receiver_address string 收件人住址 No-example
      * @bodyParam receive_remark text 收件備註 No-example
      * @bodyParam package_methods string 包裝方式 No-example
      * @bodyParam status 訂單狀態 string No-example
      * @bodyParam remark_status string 訂單備註狀態 No-example
      * @bodyParam deliver_way string 物流方式 No-example
      * @bodyParam delivery_time time 配送時段 No-example
      * @bodyParam delivery_remark text 配送備註 No-example
      * @bodyParam customer_service_remark text 客服備註 No-example
      * 
    */
    public function update(Request $request, $id)
    {
      return ModelHelper::ws_UpdateHandler($this, $request, $id);
    }

    /**
     * Delete
     *
     * @urlParam  shop_product required The ID of shop_product. Example: 2
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
      return Excel::download(new UserExport($shop_orders), 'shop_orders.xlsx');
    }
}
