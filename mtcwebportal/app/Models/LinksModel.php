<?php

namespace App\Models;

use DB;

class LinksModel extends CommonModel
{
  
    /**
     * To Bring the max menu postion
     * @return type
     */
    public function getMaxMenuPosition()
    {
         $value=DB::table('links')
                 ->max('menu_position');
         return $value;
    }        
    

}
