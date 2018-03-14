<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Auth;
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

                    $productWithData = array();
                    foreach ($reportContents as $reportContent){
                        $productAsin = array();
                        $asinNo = $reportContent['asin'];
                        //////////
                        $DbProducts = \App\Models\Product::where('asin',$asinNo)->first();

                        if (!$DbProducts){

                            $asinWithData = amwsWithNameData($asinNo);
                            if ($asinWithData){}
                            $productAsin = $asinWithData;
                            $productAsin['asin'] = $asinNo;

                            $newId = DB::table('products')->insertGetId(
                                $productAsin
                            );

                            DB::table('user_products')->insert(
                                ['user_id' => 1, 'product_id' => $newId]
                            );
                        }else{
                            $DbUserProducts = DB::table('user_products')->where('product_id',$DbProducts->id)->where('user_id',1)->first();
                            if (!$DbUserProducts){
                                DB::table('user_products')->insert(
                                    ['user_id' => 1, 'product_id' => $DbProducts->id]
                                );
                            }

                        }
                        ///




//                        if (count($productAsin)==5){
//                            $products = amwsWithHijackDataJob($productAsin);
//                            $productAsin = [];
//                            foreach ($products as $key => $value){
//                                DB::table('products')
//                                    ->where('asin', $key)
//                                    ->update(['selling_qty' => $value]);
//                            }
//                        }
                    }
                }
                DB::table('user_reports')
                    ->where('report_id', $reportId)
                    ->update(['status' => 1]);

            }

        }

    }
}
