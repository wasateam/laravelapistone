<?php

namespace Wasateam\Laravelapistone\Exports;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Wasateam\Laravelapistone\Exports\SchemaSheetExport;

class SchemaExport implements WithMultipleSheets
{
  use Exportable;

  /**
   * @return array
   */
  public function sheets(): array
  {

    $tables = DB::table('information_schema.tables')
      ->select('table_name')
      ->where('table_schema', env('DB_DATABASE'))
      ->get();

    foreach ($tables as $table) {
      $sheets[] = new SchemaSheetExport($table->table_name);
    }

    return $sheets;
  }
}
