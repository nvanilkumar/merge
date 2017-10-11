<?php

namespace App\Models;

use DB;

class LinksModel extends CommonModel
{

    public function getLinks()
    {
        $links = DB::table('links')
                ->orderBy('menu_position', 'asc')
                ->get();
        return $links;
    }

}
