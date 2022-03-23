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
    $datas = $rows;
    foreach ($datas as $data) {
      if ($data[0] == '系統流水號') {
        continue;
      }
      if ($data[2]) {
        $shop_product = ShopProduct::where('no', $data[2])->first();
        if ($shop_product) {
          $import_record                  = new ShopProductImportRecord;
          $import_record->shop_product_id = $shop_product->id;
          $import_record->no              = $data[2];
          $import_record->stock_count     = $data[8];
          $import_record->storage_space   = $data[9];
          $import_record->save();
          $shop_product->stock_count   = $data[8];
          $shop_product->storage_space = $data[9];
          $shop_product->save();
        }
      }
    }
  }
}
