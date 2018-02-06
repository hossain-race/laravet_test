<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class UpdateSellerQuantity extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'updatesellerquantity:sellerquantity';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'update seller quantity';

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
        $products = amwsWithHijackData();
        foreach ($products as $key => $value){
            DB::table('products')
                ->where('asin', $key)
                ->update(['selling_qty' => $value]);
        }
    }
}
