<?php

namespace App\Models;

use DB;

class UsersModel extends CommonModel
{

    public function getUsers($columnName = NULL)
    {
        $users = DB::table('users')
                ->select('users.*', 'roles.role_name')
                ->join('role_users', 'users.user_id', '=', 'role_users.user_id')
                ->join('roles', 'roles.role_id', '=', 'role_users.role_id')
                ->where($this->where);
        if ($columnName != NULL) {
            $users = $users->orderBy($columnName, 'DESC');
        }
        $users = $users->get();

        if (count($users) == 0) {
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

    public function getRoleId($roleName)
    {

        $this->setTableName('roles');
        $whereArray = ["role_name" => $roleName];
        $this->setWhere($whereArray);
        $roleDetails = $this->getData();

        if (count($roleDetails) == 0) {
            return null;
        }
        return $roleDetails[0]->role_id;
    }

    public function dashboardDetails()
    {

        $select = "select (select count(user_id)  from users) as usercount,
                    (select count(group_id)  from groups) as groupcount,
                    (select count(link_id)  from links) as linkcount,
                    (select count(topic_id)  from topics) as topiccount,
                    (select count(event_id) from events where events.event_start_date >= :eventStartDate) as eventcount,
                    (select count(survey_id) from surveys where surveys.survey_start_date >= :surveyStartDate) as surveycount,
                    (select count(comment_id)  from comments where status='active' group by status) as activecomments,
		    (select count(comment_id) from comments where status='review' group by status) as reviewcomments
                 ";
        $details = DB::select(DB::raw($select), $this->where);

        if (count($details) == 0) {
            return NULL;
        }

        return $details;
    }

}
