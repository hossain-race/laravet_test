<?php

namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\ProductRequest as StoreRequest;
use App\Http\Requests\ProductRequest as UpdateRequest;
use Illuminate\Support\Facades\DB;

class ProductHijackCrudController extends ProductCrudController
{
    public
    function setup()
    {
        parent::setup();


        // get the user_id parameter

        // set a different route for the admin panel buttons
//        dd(amwswithdata());
        $this->crud->setRoute("admin/hijackercheck");
        $this->crud->removeColumns(['sku', 'quantity', 'price']);
//            $this->crud->addColumns(['selling_qty']);
        $this->crud->removeButton('create');
        $this->crud->removeButton('update');
        $this->crud->removeButton('delete');
        $this->crud->orderBy('selling_qty','desc');

        // show only that user's posts
    }

    public
    function store(StoreRequest $request)
    {
        return parent::storeCrud();
    }

    public
    function update(UpdateRequest $request)
    {
        return parent::updateCrud();
    }

}
