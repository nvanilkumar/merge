<?php

namespace App\Models;

use DB;

class GroupsModel extends CommonModel
{

    /**
     * To bring the group details 
     * with created user details
     * @return type
     */
    public function getGroupList()
    {
        $records = DB::table($this->dbTable)
                ->select('groups.*', 'users.first_name', 'users.last_name')
                ->join('users', 'users.user_id', '=', 'groups.created_by')
                ->where($this->where)
                ->orderBy("groups.group_id", 'DESC')
                ->get();

        if (count($records) == 0) {
            return null;
        }

        return $records;
    }

    /**
     * To Bring the group related users list depend up on the condtion
     * @return type
     */
    public function getGroupUsers()
    {
        $records = DB::table($this->dbTable)
                ->select('groups.*', 'users.*')
                ->join('users', 'users.user_id', '=', 'group_users.user_id')
                ->join('groups', 'groups.group_id', '=', 'group_users.group_id')
                ->where($this->where)
                ->orderBy("users.user_id", 'DESC')
                ->get();

        if (count($records) == 0) {
            return null;
        }

        return $records;
    }
    /**
     * To Bring the Multiple groups related users list depend up on the condtion
     * @return type
     */
    public function getMultiGroupUsers()
    {
        $records = DB::table($this->dbTable)
                ->select('groups.*', 'users.*')
                ->join('users', 'users.user_id', '=', 'group_users.user_id')
                ->join('groups', 'groups.group_id', '=', 'group_users.group_id')
                ->whereIn($this->where)
                ->orderBy("users.user_id", 'DESC')
                ->get();

        if (count($records) == 0) {
            return null;
        }

        return $records;
    }

}
