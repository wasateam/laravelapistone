<?php

namespace Wasateam\Laravelapistone\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Wasateam\Laravelapistone\Models\ShopProduct;
use Wasateam\Laravelapistone\Models\ShopProductImportRecord;

class ShopProductImport implements ToCollection
{
  /**
   * @param Collection $collection
   */
  public function collection(Collection $rows)
  {
    if (config('shop.shop_product')) {
      if (config('shop.shop_product.import')) {
        $datas = $rows;
        foreach ($datas as $data) {
          if ($data[0] == '系統流水號') {
            continue;
          }
          if ($data[0]) {
            $shop_product = ShopProduct::find($data[0]);
            if ($shop_product) {

              $import_record                  = new ShopProductImportRecord;
              $import_record->shop_product_id = $shop_product->id;
              $import_record->cost            = $data[8];
              $import_record->price           = $data[9];
              $import_record->stock_count     = $data[10];
              $import_record->storage_space   = $data[11];
              $import_record->save();
              if (config('shop.shop_product.import.no')) {
                $shop_product->no = $data[4];
              }
              if (config('shop.shop_product.import.name')) {
                $shop_product->name = $data[5];
              }
              if (config('shop.shop_product.import.cost')) {
                $shop_product->cost = $data[8];
              }
              if (config('shop.shop_product.import.price')) {
                $shop_product->price = $data[9];
              }
              if (config('shop.shop_product.import.stock_count')) {
                $shop_product->stock_count = $data[10];
              }
              if (config('shop.shop_product.import.storage_space')) {
                $shop_product->storage_space = $data[11];
              }
              $shop_product->save();
            }
          }
        }
      }
    }
  }
}
