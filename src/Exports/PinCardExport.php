<?php

namespace Wasateam\Laravelapistone\Exports;

use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithMapping;
use Wasateam\Laravelapistone\Models\PinCard;

class PinCardExport implements FromQuery, WithMapping
{
  /**
   * @return \Illuminate\Support\Collection
   */
  public function collection()
  {
    return PinCard::all();
  }

  public function headings()
  {
    return ["id", "Created at", "Updated at", "PIN", "Plan", "Status", "User ID"];
  }

  public function map($model)
  {
    $created_at = Carbon::parse($model->created_at)->format('Y-m-d');
    $updated_at = Carbon::parse($model->updated_at)->format('Y-m-d');
    return [
      [
        $model->id,
      ],
      [
        $created_at,
      ],
      [
        $updated_at,
      ],
      [
        $model->pin,
      ],
      [
        $model->service_plan ? $model->service_plan->name : null,
      ],
      [
        $model->status,
      ],
      [
        $model->user_id,
      ],
    ];
  }
}
