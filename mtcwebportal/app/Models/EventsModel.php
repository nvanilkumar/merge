<?php

namespace App\Models;

use DB;

class EventsModel extends CommonModel
{

    /**
     * To send the selected events
     * @return type 
     */
    public function getEvents()
    {
        $select = "select users.*,statuslookup.status_name
                   from events 
                   inner join event_users on events.event_id = event_users.event_id 
                   inner join users on users.user_id = event_users.user_id
                   inner join statuslookup on  statuslookup.status_id = event_users.user_attend_status 
                   where ( events.event_id = :event_id) 
                   order by events.event_start_date";
        $events = DB::select(DB::raw($select), $this->where);

        if (count($events) == 0) {
            return NULL;
        }

        return $events;
    }

}
