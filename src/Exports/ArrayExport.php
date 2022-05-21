<?php

namespace Wasateam\Laravelapistone\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ArrayExport implements WithHeadings, FromArray
{

  // protected $users;

  public function __construct($array, $headings)
  {
    $this->array    = $array;
    $this->headings = $headings;
  }

  /**
   * @return \Illuminate\Support\Collection
   */
  function array(): array
  {
    return $this->array;
  }

  public function headings(): array
  {
    return $this->headings;
  }
}
