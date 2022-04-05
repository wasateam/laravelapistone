<?php

namespace Wasateam\Laravelapistone\Exports;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class SchemaSheetExport implements FromCollection, WithTitle, WithHeadings
{

  protected $table_name;
  protected $columns = [
    'COLUMN_NAME',
    'ORDINAL_POSITION',
    'COLUMN_DEFAULT',
    'IS_NULLABLE',
    'DATA_TYPE',
    'CHARACTER_MAXIMUM_LENGTH',
    'CHARACTER_OCTET_LENGTH',
    'NUMERIC_PRECISION',
    'NUMERIC_SCALE',
    'DATETIME_PRECISION',
    'CHARACTER_SET_NAME',
    'COLLATION_NAME',
    'COLUMN_TYPE',
    'COLUMN_KEY',
    'EXTRA',
    'COLUMN_COMMENT',
  ];

  public function __construct($table_name)
  {
    $this->table_name = $table_name;
  }

  public function title(): string
  {
    return $this->table_name;
  }

  public function headings(): array
  {
    return $this->columns;
  }

  /**
   * @return \Illuminate\Support\Collection
   */
  public function collection()
  {
    return DB::table('information_schema.columns')
      ->select(
        'COLUMN_NAME',
        'ORDINAL_POSITION',
        'COLUMN_DEFAULT',
        'IS_NULLABLE',
        'DATA_TYPE',
        'CHARACTER_MAXIMUM_LENGTH',
        'CHARACTER_OCTET_LENGTH',
        'NUMERIC_PRECISION',
        'NUMERIC_SCALE',
        'DATETIME_PRECISION',
        'CHARACTER_SET_NAME',
        'COLLATION_NAME',
        'COLUMN_TYPE',
        'COLUMN_KEY',
        'EXTRA',
        'COLUMN_COMMENT',
      )
      ->where('table_schema', 'showroom')
      ->where('TABLE_NAME', $this->table_name)
      ->get();
  }
}
