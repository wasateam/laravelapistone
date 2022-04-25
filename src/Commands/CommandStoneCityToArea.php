<?php

namespace Wasateam\Laravelapistone\Commands;

use Illuminate\Console\Command;
use Wasateam\Laravelapistone\Helpers\SpareHelper;
use Wasateam\Laravelapistone\Models\Area;
use Wasateam\Laravelapistone\Models\AreaSection;

/**
 * Generate Admin with boss scope
 *
 */
class CommandStoneCityToArea extends Command
{
  /**
   * The name and signature of the console command.
   *
   * @var string
   */
  protected $signature = 'stone:citytoarea';

  /**
   * The console command description.
   *
   * @var string
   */
  protected $description = 'Stone works';

  /**
   * Create a new command instance.
   *
   * @return void
   */
  public function __construct()
  {
    parent::__construct();
  }

  /**
   * Execute the console command.
   *
   * @return int
   */
  public function handle()
  {
    $cities = SpareHelper::tw_districts();
    foreach ($cities as $city => $district_arr) {
      $area = Area::where('name', $city)->first();
      if (!$area) {
        $area               = new Area;
        $area->country_code = 'tw';
        $area->name         = $city;
        $area->save();
      }
      foreach ($district_arr as $district) {
        $area_section = AreaSection::where('name', $district)->first();
        if (!$area_section) {
          $area_section               = new AreaSection;
          $area_section->area_id      = $area->id;
          $area_section->name         = $district;
          $area_section->save();
        }
      }
    }
    return 0;
  }
}
