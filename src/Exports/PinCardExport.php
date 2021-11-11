<?php

namespace Wasateam\Laravelapistone\Exports;

use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Wasateam\Laravelapistone\Models\PinCard;

class PinCardExport implements WithMapping, WithHeadings, FromCollection
{
  /**
   * @return \Illuminate\Support\Collection
   */
  public function collection()
  {
    return PinCard::all();
  }

  public function headings(): array
  {
    return ["id", "Created at", "Updated at", "PIN", "Plan", "Status", "User ID"];
  }

  public function map($model): array
  {
    $created_at = Carbon::parse($model->created_at)->format('Y-m-d');
    $updated_at = Carbon::parse($model->updated_at)->format('Y-m-d');
    return [
      $model->id,
      $created_at,
      $updated_at,
      $model->pin,
      $model->service_plan ? $model->service_plan->name : null,
      $model->status,
      $model->user_id,
    ];
  }
}
