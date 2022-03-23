<?php

namespace Wasateam\Laravelapistone\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class ModelImport implements ToCollection
{

  public function __construct($map)
  {
    $this->map = $map;
  }

  /**
   * @param Collection $collection
   */
  public function collection(Collection $rows)
  {
      $this->map($rows);
    }
  }
}
