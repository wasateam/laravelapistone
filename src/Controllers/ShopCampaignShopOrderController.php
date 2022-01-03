<?php

namespace Wasateam\Laravelapistone\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Wasateam\Laravelapistone\Helpers\ModelHelper;

/**
 * @group ShopCampaignShopOrder 促銷活動訂單
 *
 * ShopCampaignShopOrder 促銷活動訂單 API
 *
 * user 會員
 * shop_campaign 促銷活動
 * shop_order 訂單
 *
 * @authenticated
 */
class ShopCampaignShopOrderController extends Controller
{
  public $model                   = 'Wasateam\Laravelapistone\Models\ShopCampaignShopOrder';
  public $name                    = 'shop_campaign_shop_order';
  public $resource                = 'Wasateam\Laravelapistone\Resources\ShopCampaignShopOrder';
  public $resource_for_collection = 'Wasateam\Laravelapistone\Resources\ShopCampaignShopOrderCollection';
  public $input_fields            = [];
  public $filter_fields           = [
  ];
  public $belongs_to        = [];
  public $filter_belongs_to = [
    'shop_order',
  ];

  public function __construct()
  {
    if (config('stone.mode') == 'cms') {
      $this->belongs_to[]        = 'user';
      $this->belongs_to[]        = 'shop_campaign';
      $this->belongs_to[]        = 'shop_order';
      $this->filter_belongs_to[] = 'shop_campaign';
    }
  }

  /**
   * Index
   * @queryParam shop_campaign ids 活動  No-example
   * @queryParam shop_order ids 訂單  No-example
   * @queryParam user ids 人員  No-example
   *
   */
  public function index(Request $request, $id = null)
  {
    return ModelHelper::ws_IndexHandler($this, $request, $id, true);
  }

  /**
   * Store
   *
   * @bodyParam user int 購物車 Example:1
   * @bodyParam shop_order int 產品 Example:1
   * @bodyParam shop_campaign int 購物車產品 Example:1
   */
  public function store(Request $request, $id = null)
  {
    return ModelHelper::ws_StoreHandler($this, $request, $id);
  }

  /**
   * Show
   *
   * @urlParam  shop_campaign_shop_order required The ID of shop_campaign_shop_order. Example: 1
   */
  public function show(Request $request, $id = null)
  {
    return ModelHelper::ws_ShowHandler($this, $request, $id);
  }

  /**
   * Update
   *
   * @urlParam  shop_campaign_shop_order required The ID of shop_campaign_shop_order. Example: 1
   * @bodyParam @bodyParam user int 購物車 Example:1
   * @bodyParam shop_order int 產品 Example:1
   * @bodyParam shop_campaign int 購物車產品 Example:1
   */
  public function update(Request $request, $id)
  {
    return ModelHelper::ws_UpdateHandler($this, $request, $id);
  }

  /**
   * Delete
   *
   * @urlParam  shop_campaign_shop_order required The ID of shop_campaign_shop_order. Example: 2
   */
  public function destroy($id)
  {
    return ModelHelper::ws_DestroyHandler($this, $id);
  }

}
