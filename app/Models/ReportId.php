<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;

class ReportId extends Model
{
    use CrudTrait;

    /*
   |--------------------------------------------------------------------------
   | GLOBAL VARIABLES
   |--------------------------------------------------------------------------
   */

    protected $table = 'user_reports';
    protected $fillable = ['user_id','report_id'];
//    public $timestamps = false;


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

