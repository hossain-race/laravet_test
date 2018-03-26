<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ReportId;
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
        ReportId::create(
            [
                'user_id' => \Auth::id(),
                'report_id' => $reportId
            ]);
        return redirect('admin/product');
    }

    public function addProduct()
    {
        return view('addProduct');
    }

    public function saveProduct(Request $request)
    {
        try {
            $productAsins = explode(',',$request->get('asin'));
            foreach ($productAsins as $productAsin){
                $DbProducts = Product::where('asin',$productAsin)->first();

                if (!$DbProducts){
                    $request->merge(['asin'=>$productAsin]);
                    $asinWithData = amwsWithNameData($productAsin);
                    $asinWithData['asin'] = $productAsin;
//                    dd($asinWithData);
                    if ($asinWithData){}
                    $request->merge($asinWithData);
                    //        echo "<td><a onClick=\"javascript: return confirm('Please confirm deletion');\" href='#'>x</a></td><tr>";
//            dd($request);

                    $redirect_location = '/admin/product';
                    // your additional operations after save here
                    //         use $this->data['entry'] or $this->crud->entry
//                    $product = DB::table('products')->insert(
//                        $asinWithData
//                    );
                    $newProduct = new Product();
                    $newProduct->asin = $productAsin;
                    $newProduct->name = $asinWithData['name'];
                    $newProduct->selling_qty = $asinWithData['selling_qty'];
                    $newProduct->save();
//                    dd($newProduct);
                    DB::table('user_products')->insert(
                        ['user_id' => \Auth::id(), 'product_id' => $newProduct->id]
                    );
                }else{
//                    dd($DbProducts->user()->orderBy('name')->get());
                    $redirect_location = '/admin/product';
                    // your additional operations after save here
                    //         use $this->data['entry'] or $this->crud->entry
                    $DbUserProducts = DB::table('user_products')->where('product_id',$DbProducts->id)->where('user_id',\Auth::id())->first();
                    if (!$DbUserProducts){
                        DB::table('user_products')->insert(
                            ['user_id' => \Auth::id(), 'product_id' => $DbProducts->id]
                        );
                    }

                }

            }

            return redirect($redirect_location);
        } catch (ClientException $exception) {
            $responseBody = $exception->getResponse()->getBody(true)->getContents();
            $response = json_decode($responseBody);
            return new JsonResponse((array)$response, 200, []);
        }
    }

}
