<?php

namespace App\Http\Controllers;

use App\Models\MonitorProduct;
use App\Models\Product;
use App\Models\ReportId;
use Faker\Provider\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

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

   public function deleteProduct($id)
    {
        $DbProducts = MonitorProduct::where('product_id',$id)->first();
        if ($DbProducts){
            $delete = DB::table('user_products')
                ->where('user_id', \Auth::id())
                ->where('product_id', $id)
                ->delete();
            $isExistingUserWithIt = DB::table('user_products')
                ->where('product_id', $id)
                ->first();
            if (!$isExistingUserWithIt)
                $DbProducts->delete();
        }
        return $delete;
    }

    public function addProduct()
    {
        return view('addProduct');
    }

    public function saveProduct(Request $request)
    {
        // your additional operations before save here

        try {
            $productAsins = explode(',', $request->get('asin'));
            $productValidity = array();

            $product = array();
            foreach ($productAsins as $productAsin){
                $productData= array();
                if ( $this->isAsin($productAsin) )
                    $product[] = trim($productAsin);
                else{
                    $productData['asin'] = $productAsin;
                    $productData['status'] = 0;
                    $productData['data'] = "Invalid ASIN";
                    $productValidity[] = $productData;
                }
            }
            // dd($product);

            foreach ($product as $productAsin){
                $productData= array();
                $DbProducts = Product::where('asin',$productAsin)->first();

                if (!$DbProducts){
                    $request->merge(['asin'=> $productAsin]);
                    $asinWithData = amwsWithNameData($productAsin);
                    if ($asinWithData) {
                        $request->merge($asinWithData);

                        // echo "<td><a onClick=\"javascript: return confirm('Please confirm deletion');\" href='#'>x</a></td><tr>";
                        // dd($request);

//                        $redirect_location = '/admin/product';
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

//                        $newMonitorProduct = new MonitorProduct();
//                        $newMonitorProduct->asin = $productAsin;
//                        $newMonitorProduct->name = $asinWithData['name'];
////                        $newMonitorProduct->selling_qty = $asinWithData['selling_qty'];
//                        $newMonitorProduct->product_id = $newProduct->id;
////                        if ($asinWithData['selling_qty'] > 1)
//                            $newMonitorProduct->status = 1;
////                        else
////                            $newMonitorProduct->status = 0;
//                        $newMonitorProduct->save();

                        $productData['asin'] = $productAsin;
                        $productData['status'] = 1;
                        $productData['data'] = $newProduct;
                        $productValidity[] = $productData;
//                    dd($newProduct);
                        DB::table('user_products')->insert(
                            ['user_id' => \Auth::id(), 'product_id' => $newProduct->id]
                        );
                    }else{
                        $productData['asin'] = $productAsin;
                        $productData['status'] = 0;
                        $productData['data'] = "Not Found at Amazon";
                        $productValidity[] = $productData;
                    }
                } else {
                    $productData['asin'] = $productAsin;
                    $productData['status'] = 2;
                    $productData['data'] = "Previously Added to System";
                    $productValidity[] = $productData;
                    // dd($DbProducts->user()->orderBy('name')->get());
//                    $redirect_location = '/admin/product';
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
            return view('addedProductReport')->with('products', $productValidity);
        } catch(ClientException $exception) {
            $responseBody = $exception->getResponse()->getBody(true)->getContents();
            $response = json_decode($responseBody);
            return new
            JsonResponse((array)$response, 200, []);
        }
    }

    public function isAsin($string){
        $ptn = "/B[0-9]{2}[0-9A-Z]{7}|[0-9]{9}(X|0-9])/";
        if (preg_match($ptn, $string, $matches)){
            return true;

        }
        return false;

    }

    public function getMonitorAsin(){
        try {
            $asinList = MonitorProduct::all()
                ->pluck('asin')
                ->toArray();
            return response()->json(['message' => 'Here the ASIN list', 'data' => $asinList], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Internal error occur ' . $e->getMessage()], 500);
        }
    }

    public function saveAsinSellerInformation(Request $request)
    {
        try {
            $file = $request->file('seller');
            $extension = $file->getClientOriginalExtension();
            Storage::disk('local')->put('input.json',  \File::get($file));


            $data = Storage::disk('local')->get('input.json');
//            dd($data);
//            $data = $request->file('seller');
//            $seller = $data['data'];
            $sellerDatas = json_decode($data)->data;
//            dd($sellerDatas);
            $asins = array();
            $sellers = array();
            foreach ($sellerDatas as $sellerData){
                $DbSellerInfo = DB::table('seller_info')->where('seller_id',$sellerData->seller_id)->first();
                if (!$DbSellerInfo){
                    DB::table('seller_info')->insert(
                        ['seller_id' => $sellerData->seller_id, 'name' => $sellerData->seller_name, 'link' => $sellerData->seller_link]
                    );
                    $sellers[] = $sellerData->seller_id;
                }

                $DbSellerAsin = DB::table('seller_asin')->where('seller_id',$sellerData->seller_id)->where('asin',$sellerData->asin)->first();
                if (!$DbSellerAsin){
                    DB::table('seller_asin')->insert(
                        ['seller_id' => $sellerData->seller_id, 'asin' => $sellerData->asin]
                    );
                    $asins[] = $sellerData->asin;
                }


            }

            return response()->json(['message' => 'Monitor process updated with seller and ASIN.', 'asins' => $asins, 'sellers' => $sellers], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Internal error occur ' . $e->getMessage()], 500);
        }
    }

}
