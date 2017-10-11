<?php

namespace App\Models;

use DB;

class NotificationModel extends CommonModel
{

    /**
     * To Bring the group users related device tokens
     * @return type
     */
    public function getGroupUsersDeviceTokens()
    {
        $tokens=NULL;
        $select = "";
        // Group and user list exist
        if (strlen($this->where['groupList']) > 0) {
            $select = "select DISTINCT device_token_id,status_name,status,group_users.user_id
                from user_tokens
                inner join statuslookup on statuslookup.status_id=user_tokens.device_type
                inner join group_users on group_users.user_id=user_tokens.user_id
                where user_tokens.status='active' and group_users.group_id in (" . $this->where['groupList'] . " )";
        }

        //user select
        if (strlen($this->where['userList']) > 0) {
            $userSelect = "select DISTINCT device_token_id,status_name,user_tokens.status,user_id 
                from user_tokens
                inner join statuslookup on statuslookup.status_id=user_tokens.device_type
               
                where user_tokens.status='active' and user_tokens.user_id in (" . $this->where['userList'] . " )";

            if (strlen($select) > 0) {
                $select .= " UNION " . $userSelect;
            } else {
                $select = $userSelect;
            }
        }

        if (strlen($select) > 0) {
            $tokens = DB::select(DB::raw($select));
        }


        if (count($tokens) == 0) {
            return NULL;
        }
        return $tokens;
    }

    public function getNotificationUserDetails()
    {
        $select = "select message,users.first_name,users.last_name
                from notifications
                left join notification_users on notification_users.notification_id=notifications.notification_id
                left join users on users.user_id=notification_users.user_id 
                where notifications.notification_id=:notification_id";

        $tokens = DB::select(DB::raw($select), $this->where);

        if (count($tokens) == 0) {
            return NULL;
        }
        return $tokens;
    }

}
