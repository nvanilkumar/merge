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
        $select = "select statuslookup.status_name, events.event_id,event_description,event_name,
                    FORMAT ( event_start_date, N'yyyy-MM-dd HH:mm:ss') AS event_start_date,
                    FORMAT ( event_end_date, N'yyyy-MM-dd HH:mm:ss') AS event_end_date
                   from events 
                   inner join event_users on events.event_id = event_users.event_id 
                   inner join statuslookup on  statuslookup.status_id = event_users.user_attend_status 
                   where (event_users.user_id = :user_id and events.event_end_date >= :event_end_date
                    and statuslookup.status_name =:status_name
                   ) 
                   order by events.event_start_date";
        $events = DB::select(DB::raw($select), $this->where);

        if (count($events) == 0) {
            return NULL;
        }

        return $events;
    }
    
    /**
     * To send the user related event status information
     * @return type
     */
    public function getUserEvents()
    {
        $select = "select count(user_attend_status) as statuscount, MAX(status_name) AS status_name
                   from events 
                   inner join event_users on events.event_id = event_users.event_id 
                   inner join statuslookup on  statuslookup.status_id = event_users.user_attend_status 
                   where (event_users.user_id = :user_id and events.event_end_date >= :event_end_date) 
                   group by user_attend_status";
        $events = DB::select(DB::raw($select), $this->where);

        if (count($events) == 0) {
            return NULL;
        }

        return $events;
    }
    
    /**
     * To bring the specific event related information
     * @return type 
     */
    public function getSpecificEvent()
    {
        $select = "select statuslookup.status_name, events.event_id,event_description,event_name,
                   FORMAT ( event_start_date, N'yyyy-MM-dd HH:mm:ss') AS event_start_date,
                   FORMAT ( event_end_date, N'yyyy-MM-dd HH:mm:ss') AS event_end_date
                   from events 
                   inner join event_users on events.event_id = event_users.event_id 
                   inner join statuslookup on  statuslookup.status_id = event_users.user_attend_status 
                   where (event_users.user_id = :user_id  and events.event_id = :event_id ) 
                   ";
        $events = DB::select(DB::raw($select), $this->where);

        if (count($events) == 0) {
            return NULL;
        }

        return $events;
    }

}
