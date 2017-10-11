<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;
class Items extends Model
{
    //
     protected $fillable = ['title', 'description'];
     
     public function itemslist(){
         
         $results = DB::select("SELECT * FROM tblContact");
         return $results;
     }
}
