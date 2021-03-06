<?php

namespace App\Models;

use App\Models\Product;
use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;

class MonitorProduct extends Model
{
    use CrudTrait;

    /*
   |--------------------------------------------------------------------------
   | GLOBAL VARIABLES
   |--------------------------------------------------------------------------
   */

    protected $table = 'monitor_products';
    protected $fillable = ['asin','status'];
//    public $timestamps = false;

    public function Product(){
        return $this->belongsTo(Product::class,'asin','asin');
    }

    public function SellerInfo(){
        return $this->belongsToMany(SellerInfo::class, 'seller_asin', 'asin', 'seller_id');
    }

    //protected $table = 'products';
    //protected $primaryKey = 'id';
    // public $timestamps = false;
    // protected $guarded = ['id'];
    // protected $fillable = [];
    // protected $hidden = [];
    // protected $dates = [];

    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | ACCESORS
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */
}

