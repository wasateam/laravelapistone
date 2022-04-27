<?php

namespace Wasateam\Laravelapistone\Controllers;

use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use Wasateam\Laravelapistone\Models\ShopProduct;

/**
 * @group Facebook 相關服務
 *
 */
class FacebookController extends Controller
{
  /**
   *  (忽略) 僅供給 Facebook 抓取商品目錄
   *
   */
  public function catalogs_url_upload_shop_product()
  {
    $collection = ShopProduct::onshelf()->get();

    $headings = [
      'id',
      'title',
      'description',
      'availability',
      'condition',
      'price',
      'link',
      'image_link',
      'brand',
      'google_product_category',
      'fb_product_category',
      'quantity_to_sell_on_facebook',
      'sale_price',
      'sale_price_effective_date',
      'item_group_id',
      'gender',
      'color',
      'size',
      'age_group',
      'material',
      'pattern',
      'shipping',
      'shipping_weight',
    ];

    return Excel::download(new \Wasateam\Laravelapistone\Exports\ModelExport(
      $collection,
      $headings,
      function ($model) {
        $id                           = $model->id;
        $title                        = $model->name;
        $description                  = $model->subtitle ? $model->subtitle : $model->name;
        $availability                 = $model->stock_count > 0 ? 'in stock' : 'out of stock';
        $condition                    = 'new';
        $price                        = $model->price ? $model->price : '0';
        $link                         = env('WEB_URL') . '/shop/next-day/' . $model->id;
        $image_link                   = $model->cover_image;
        $brand                        = config('stone.facebook.catalogs.brand') ? config('stone.facebook.catalogs.brand') : '';
        $google_product_category      = '';
        $fb_product_category          = '';
        $quantity_to_sell_on_facebook = '';
        $sale_price                   = '';
        $sale_price_effective_date    = '';
        $item_group_id                = '';
        $gender                       = '';
        $color                        = '';
        $size                         = '';
        $age_group                    = '';
        $material                     = '';
        $pattern                      = '';
        $shipping                     = '';
        $shipping_weight              = '';
        return [
          $id,
          $title,
          $description,
          $availability,
          $condition,
          $price,
          $link,
          $image_link,
          $brand,
          $google_product_category,
          $fb_product_category,
          $quantity_to_sell_on_facebook,
          $sale_price,
          $sale_price_effective_date,
          $item_group_id,
          $gender,
          $color,
          $size,
          $age_group,
          $material,
          $pattern,
          $shipping,
          $shipping_weight,
        ];
      }
    ), 'shop_products.csv', \Maatwebsite\Excel\Excel::CSV);
  }
}
