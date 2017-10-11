<?php

namespace App\Models;

use DB;

class UsersModel extends CommonModel
{

    public function getUsers()
    {
        $users = DB::table('users')
                ->select('users.*','roles.role_name')
                ->join('role_users', 'users.user_id', '=', 'role_users.user_id')
                ->join('roles', 'roles.role_id', '=', 'role_users.role_id')
                ->where($this->where)
                ->get();
        
        if(count($users) == 0){
            return NULL;
        }
        
        return $users;
    }

    public function usertokens()
    {
//        print_r($this->where);exit;

        $users = DB::table('users')
                ->join('user_tokens', 'users.user_id', '=', 'user_tokens.user_id')
                ->select('user_tokens.*')
                ->where($this->where)
                ->get();

        return $users;
    }

    public function usertokensupdate()
    {

        $this->setTable('user_tokens');
        $this->setInsertUpdateData($this->insertUpdateArray);
        $this->setWhere($this->where);
        $this->updateData();


        return true;
    }

}
