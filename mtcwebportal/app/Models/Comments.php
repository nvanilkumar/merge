<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;
class Comments extends Model
{
    //
     protected $fillable = ['message'];
     public $timestamps = false;
     public function itemslist(){
         
         $results = DB::select("SELECT * FROM tblContact");
         return $results;
     }
}
