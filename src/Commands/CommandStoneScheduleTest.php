<?php

namespace Wasateam\Laravelapistone\Commands;

use Illuminate\Console\Command;

class CommandStoneScheduleTest extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'stoneschedule:test';

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
        \Log::info('stone schedule test');
        return Command::SUCCESS;
    }
}
