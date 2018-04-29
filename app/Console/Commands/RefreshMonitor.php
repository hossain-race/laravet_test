<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RefreshMonitor extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'monitor:refreshproduct';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Refresh Monitor ASIN';

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
        try
        {
            $uniqueAsins = DB::table('products')
                ->join('user_products', 'products.id', '=', 'user_products.product_id')
                ->groupBy('products.asin')
                ->pluck('products.asin');
            $monitorTruncate = DB::table('monitor_products')->truncate();

            foreach ($uniqueAsins as $uniqueAsin)
                $monitorInsert = DB::table('monitor_products')->insert(
                    [ 'asin' => $uniqueAsin]
                );
        }catch (\Exception $e){
            dd($e->getMessage());
        }



    }
}
