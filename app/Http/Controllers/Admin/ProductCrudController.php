<?php

namespace App\Http\Controllers\Admin;

use App\Models\Product;
use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\ProductRequest as StoreRequest;
use App\Http\Requests\ProductRequest as UpdateRequest;
use Illuminate\Support\Facades\DB;

class ProductCrudController extends CrudController
{
    public function setup()
    {

        /*
        |--------------------------------------------------------------------------
        | BASIC CRUD INFORMATION
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\Product');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/product');
        $this->crud->setEntityNameStrings('product', 'products');

        /*
        |--------------------------------------------------------------------------
        | BASIC CRUD INFORMATION
        |--------------------------------------------------------------------------
        */

        $this->crud->addFields([
            [
                'name'         =>'asin',
                'label'        => 'Asin ( For Bulk Add input as asin1,asin2, ... , asinN )'
            ]
        ], 'update/create/both');

        $this->crud->setFromDb();
        $this->crud->removeColumns(['price','quantity','selling_qty','sku','product_owner']); // remove an array of columns from the stack


        // ------ CRUD FIELDS
//         $this->crud->addField('user_id', 'update/create/both');
         $this->crud->removeFields(['name','price','quantity','selling_qty','sku','product_owner'], 'update/create/both');
        // $this->crud->removeField('name', 'update/create/both');
        // $this->crud->removeFields($array_of_names, 'update/create/both');

        // ------ CRUD COLUMNS
        // $this->crud->addColumn(); // add a single column, at the end of the stack
        // $this->crud->addColumns(); // add multiple columns, at the end of the stack
        // $this->crud->removeColumn('column_name'); // remove a column from the stack
        // $this->crud->removeColumns(['column_name_1', 'column_name_2']); // remove an array of columns from the stack
        // $this->crud->setColumnDetails('column_name', ['attribute' => 'value']); // adjusts the properties of the passed in column (by name)
        // $this->crud->setColumnsDetails(['column_1', 'column_2'], ['attribute' => 'value']);

        // ------ CRUD BUTTONS
        // possible positions: 'beginning' and 'end'; defaults to 'beginning' for the 'line' stack, 'end' for the others;
        // $this->crud->addButton($stack, $name, $type, $content, $position); // add a button; possible types are: view, model_function
        // $this->crud->addButtonFromModelFunction($stack, $name, $model_function_name, $position); // add a button whose HTML is returned by a method in the CRUD model
        // $this->crud->addButtonFromView($stack, $name, $view, $position); // add a button whose HTML is in a view placed at resources\views\vendor\backpack\crud\buttons
        // $this->crud->removeButton($name);
        // $this->crud->removeButtonFromStack($name, $stack);
        // $this->crud->removeAllButtons();
        // $this->crud->removeAllButtonsFromStack('line');
        $this->crud->removeButton('update');
        $this->crud->removeButton('create');
//        $this->crud->removeButton('delete');
        // ------ CRUD ACCESS
        $this->crud->allowAccess(allowPermissions());
        $this->crud->denyAccess(denyPermissions());
        // $this->crud->allowAccess(['list', 'create', 'update', 'reorder', 'delete']);
        // $this->crud->denyAccess(['list', 'create', 'update', 'reorder', 'delete']);

        // ------ CRUD REORDER
        // $this->crud->enableReorder('label_name', MAX_TREE_LEVEL);
        // NOTE: you also need to do allow access to the right users: $this->crud->allowAccess('reorder');

        // ------ CRUD DETAILS ROW
        // $this->crud->enableDetailsRow();
        // NOTE: you also need to do allow access to the right users: $this->crud->allowAccess('details_row');
        // NOTE: you also need to do overwrite the showDetailsRow($id) method in your EntityCrudController to show whatever you'd like in the details row OR overwrite the views/backpack/crud/details_row.blade.php

        // ------ REVISIONS
        // You also need to use \Venturecraft\Revisionable\RevisionableTrait;
        // Please check out: https://laravel-backpack.readme.io/docs/crud#revisions
        // $this->crud->allowAccess('revisions');

        // ------ AJAX TABLE VIEW
        // Please note the drawbacks of this though:
        // - 1-n and n-n columns are not searchable
        // - date and datetime columns won't be sortable anymore
        // $this->crud->enableAjaxTable();

        // ------ DATATABLE EXPORT BUTTONS
        // Show export to PDF, CSV, XLS and Print buttons on the table view.
        // Does not work well with AJAX datatables.
        // $this->crud->enableExportButtons();

        // ------ ADVANCED QUERIES
        // $this->crud->addClause('active');
        // $this->crud->addClause('type', 'car');
        // $this->crud->addClause('where', 'name', '==', 'car');
        // $this->crud->addClause('whereName', 'car');
         $this->crud->addClause('whereHas', 'User', function($query) {
             $query->where('user_id',\Auth::id());
         });
        // $this->crud->addClause('withoutGlobalScopes');
        // $this->crud->addClause('withoutGlobalScope', VisibleScope::class);
        // $this->crud->with(); // eager load relationships
        // $this->crud->orderBy();
        $this->crud->orderBy('id','desc');
        // $this->crud->groupBy();
         $this->crud->limit(100);
    }

    public function store(StoreRequest $request)
    {
        // your additional operations before save here
        try {
            $productAsins = explode(',',$request->get('asin'));
            foreach ($productAsins as $productAsin){
                $DbProducts = Product::where('asin',$productAsin)->first();

                if (!$DbProducts){
                    $request->merge(['asin'=>$productAsin]);
                    $asinWithData = amwsWithNameData($productAsin);
                    if ($asinWithData){}
                    $request->merge($asinWithData);
                    //        echo "<td><a onClick=\"javascript: return confirm('Please confirm deletion');\" href='#'>x</a></td><tr>";
//            dd($request);

                    $redirect_location = parent::storeCrud($request);
                    // your additional operations after save here
                    //         use $this->data['entry'] or $this->crud->entry
                    DB::table('user_products')->insert(
                        ['user_id' => \Auth::id(), 'product_id' => $this->data['entry']->id]
                    );
                }else{
//                    dd($DbProducts->user()->orderBy('name')->get());
                    $redirect_location = parent::performSaveAction($DbProducts->id);
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

            return $redirect_location;
        } catch (ClientException $exception) {
            $responseBody = $exception->getResponse()->getBody(true)->getContents();
            $response = json_decode($responseBody);
            return new JsonResponse((array)$response, 200, []);
        }
    }

    public function update(UpdateRequest $request)
    {
        // your additional operations before save here
        $redirect_location = parent::updateCrud($request);
        // your additional operations after save here
        // use $this->data['entry'] or $this->crud->entry
        return $redirect_location;
    }

    public function destroy($id)
    {
        // your additional operations before save here
        DB::table('user_products')->where(
            ['user_id' => \Auth::id(), 'product_id' => $id]
        )->delete();
//        $redirect_location = parent::updateCrud($request);
        // your additional operations after save here
        // use $this->data['entry'] or $this->crud->entry
//        return redirect('admin/product');
//        return redirect()->to('admin/product');
    }
}