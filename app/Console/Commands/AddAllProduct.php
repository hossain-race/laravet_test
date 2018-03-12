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
        $reportIds = \App\Models\ReportId::select('report_id')->where('status', 0)->pluck('report_id')->toArray();
        if (count($reportIds)>0){
            foreach ($reportIds as $reportId){
                $reportContents = amwsGetReportContent((int)$reportId);
                if ( count($reportContents)>0 ){

                    foreach ($reportContents as $reportContent){
                        $productAsin[] = $reportContent['asin'];

                        if (count($productAsin)==5){
                            $products = amwsWithHijackDataJob($productAsin);
                            $productAsin = array();
                            foreach ($products as $key => $value){
                                DB::table('products')
                                    ->where('asin', $key)
                                    ->update(['selling_qty' => $value]);
                            }
                        }
                    }
                }
                DB::table('user_reports')
                    ->where('report_id', $reportId)
                    ->update(['status' => 1]);

            }

        }

    }
}
