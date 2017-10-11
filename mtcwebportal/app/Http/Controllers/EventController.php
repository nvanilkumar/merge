<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\EventService;
use App\Services\UserService;
use App\Services\GroupService;
use Session;

class EventController extends Controller
{

    public function __construct(Request $request, EventService $eventService)
    {
        $this->request = $request;
        $this->eventService = $eventService;
        $this->groupService = new GroupService($this->request);
        $this->userService = new UserService($this->request);
    }

    public function createEventView()
    {
        $groupList = $this->groupService->getGroupList();

        $this->request->request->add(['role_name' => "student"]);
        $usersList = $this->userService->getUserList();
        return view('events.event', ["title" => "Create Event",
            "event" => "",
            "groupList" => $groupList,
            "usersList" => $usersList
        ]);
    }

    public function updateEventView($event_id)
    {
        $this->request->request->add(['event_id' => $event_id]);
        $details = $this->eventService->getEvents();
        if ($details == NULL) {
            return redirect('/dashboard');
        }
        $event = (array) $details[0];
      
        $this->request->request->add(['role_name' => "student"]);
        $usersList = $this->userService->getUserList();
        
        $selectedUsers= $this->eventService->getEventUsers();
        $selectedUsers= $this->eventService->formatEventUsersList($selectedUsers);
 

        return view('events.eventEdit', ["title" => "Update Event",
            "event" => $event,
            "selectedUsers"      =>$selectedUsers,   
            "usersList" => $usersList
        ]);
    }

    public function createEvent()
    {
        $this->eventService->createEvent();
        return redirect('events/list');
    }

    /**
     * To update the posted notification data
     * @return type
     */
    public function updateEvent()
    {

        $this->eventService->updateEvent();

        return redirect('/events/update/' . $this->request->input("event_id"))
                ->with('status','Event updated successfully');;
    }

    /**
     * To Bring the Events list
     * @return type
     */
    public function getEventList()
    {
        $lists = $this->eventService->getEvents();
        return view('events.eventsList', ["title" => "All Events",
            "eventsList" => $lists]);
    }

    /**
     * To delete the specified event id
     * @return type
     */
    public function deleteEvent($event_id)
    {
        $this->request->request->add(['event_id' => $event_id]);
        $this->eventService->deleteEvent();
        Session::flash('message', 'Success! Record is deleted successfully.');
        return redirect('/events/list');
    }

}
