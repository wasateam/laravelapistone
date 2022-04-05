<?php

namespace Wasateam\Laravelapistone\Commands;

use Illuminate\Console\Command;
use Maatwebsite\Excel\Facades\Excel;
use Wasateam\Laravelapistone\Exports\SchemaExport;

class CommandStoneSchemaExport extends Command
{
  /**
   * The name and signature of the console command.
   *
   * @var string
   */
  protected $signature = 'stone:schemaexport';

  /**
   * The console command description.
   *
   * @var string
   */
  protected $description = 'Command description';

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
    Excel::store(new SchemaExport(), 'schema.xlsx');
    return 0;
  }
}
