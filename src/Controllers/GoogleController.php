<?php

namespace Wasateam\Laravelapistone\Controllers;

use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use Wasateam\Laravelapistone\Models\ShopProduct;

/**
 * @group Google 相關服務
 *
 */
class GoogleController extends Controller
{
  /**
   *  (忽略) 僅供給 Google 抓取商品目錄
   *
   */
  public function merchant_products_url_upload_shop_product()
  {
    $collection = ShopProduct::onshelf()->get();

    $headings = [
      'id',
      'title',
      'description',
      'price',
      'condition',
      'link',
      'availability',
      'image_link',
    ];

    return Excel::download(new \Wasateam\Laravelapistone\Exports\ModelExport(
      $collection,
      $headings,
      function ($model) {
        $id           = $model->id;
        $title        = $model->name;
        $description  = $model->subtitle ? $model->subtitle : $model->name;
        $availability = $model->stock_count > 0 ? 'in stock' : 'out of stock';
        $condition    = 'new';
        $price        = $model->price ? $model->price : '0';
        $link         = env('WEB_URL') . '/shop/next-day/' . $model->id;
        $image_link   = $model->cover_image;
        return [
          $id,
          $title,
          $description,
          $price,
          $condition,
          $link,
          $availability,
          $image_link,
        ];
      }
    ), 'shop_products.txt', \Maatwebsite\Excel\Excel::TSV);
  }
}
