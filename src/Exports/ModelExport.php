<?php

namespace Wasateam\Laravelapistone\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ModelExport implements WithMapping, WithHeadings, FromCollection
{

  // protected $users;

  public function __construct($collection, $headings, $map)
  {
    $this->collection = $collection;
    $this->headings   = $headings;
    $this->map        = $map;
  }

  /**
   * @return \Illuminate\Support\Collection
   */
  public function collection()
  {
    return $this->collection;
  }

  public function headings(): array
  {
    return $this->headings;
  }

  public function map($model): array
  {
    return ($this->map)($model);
  }
}
