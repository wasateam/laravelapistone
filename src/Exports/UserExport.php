<?php

namespace Wasateam\Laravelapistone\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Wasateam\Laravelapistone\Models\User;
use Carbon\Carbon;

class UserExport implements WithMapping, WithHeadings, FromCollection
{

  protected $users;

  public function __construct($users)
  {
    $this->users = $users;
  }

  /**
   * @return \Illuminate\Support\Collection
   */
  public function collection()
  {
    if ($this->users) {
      return User::find($this->users);
    } else {
      return User::all();
    }
  }

  public function headings(): array
  {
    $headings = [
      "會員編號",
      "名稱",
      "Email",
      "電話",
      "介紹",
      "建立時間",
      "最後更新時間",
    ];
    if (config('stone.user.is_bad')) {
      $headings[] = "黑名單";
    }
    if (config('stone.user.bonus_points')) {
      $headings[] = "紅利點數";
    }
    return $headings;
  }

  public function map($model): array
  {
    $created_at = Carbon::parse($model->created_at)->format('Y-m-d');
    $updated_at = Carbon::parse($model->updated_at)->format('Y-m-d');
    $map        = [
      $model->uuid,
      $model->name,
      $model->email,
      $model->tel,
      $model->description,
      $created_at,
      $updated_at,
    ];
    if (config('stone.user.is_bad')) {
      $map[] = $model->is_bad;
    }
    if (config('stone.user.bonus_points')) {
      $map[] = $model->bonus_points;
    }
    return $map;
  }
}
