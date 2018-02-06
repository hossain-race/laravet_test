<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class AddAllProduct extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'addall:product';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Add All Product for perticular user';

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
     * @return mixed
     */
    public function handle()
    {
        $reportContent = amwsGetReportContent(125785017568);
        if ( count($reportContent)>0 ){

        }
    }
}
