<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Models\Challanges;

class ChangeStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'change_status:challange';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'change status of the challange';

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
        date_default_timezone_set('Africa/Cairo');

        // change status from upcoming to voting state (feature) 
        Challanges::where('start_date', date('Y-m-d H:i:00'))->update(array('status' => 2));

        // change status from voting (feature) state to submision state (feature)
        Challanges::where('end_date', date('Y-m-d H:i:00'))->update(array('status' => 3));

        // change status from submision state (feature) to completed state 
        Challanges::where('end_date', date('Y-m-d H:i:00', strtotime('-3 hour')))->update(array('status' => 4));
    }
}
