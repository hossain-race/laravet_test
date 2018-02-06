<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

class HijackerController extends Controller
{
    public function __construct()
    {

    }

    public function refreshHijackerCheck()
    {
        $products = amwsWithHijackData();
        foreach ($products as $key => $value){
            DB::table('products')
                ->where('asin', $key)
                ->update(['selling_qty' => $value]);
        }
        return redirect('admin/hijackercheck');
    }

    public function addAllProduct()
    {
        $reportId = amwsGetReportId();
        return redirect('admin/product');
    }

}
