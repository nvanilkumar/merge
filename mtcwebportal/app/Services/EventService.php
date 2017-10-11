<?php

namespace App\Services;

use Illuminate\Http\Request;
use App\Models\EventsModel;
use Validator;
use App\Services\NotificationService;
use App\Services\GroupService;
use App\Helpers\DateHelper;

class EventService
{

    protected $request;
    protected $eventsModel;

    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->eventsModel = new EventsModel();
        $this->eventsModel->setTableName("events");
    }

    /**
     * To Bring the group list
     * @return type
     */
    public function getEvents()
    {
        $eventId = $this->request->input("event_id");
        $whereArray = [];
        if ($eventId) {
            $whereArray = [
                ["event_id", '=', $eventId]
            ];
        }

        $this->eventsModel->setWhere($whereArray);
        $events = $this->eventsModel->getOrderByData("event_id");
        return $events;
    }

    /**
     * To Bring the user specific events list
     */
    public function getEventUsers()
    {
        $whereArray = [
            'event_id' => $this->request->input("event_id"),
        ];

        $this->eventsModel->setWhere($whereArray);
        $events = $this->eventsModel->getEvents();
        return $events;
    }

    /**
     * To create the event
     */
    public function createEvent()
    {
        //validations
        if ($this->eventValidator()->fails()) {
            return redirect()->back()
                            ->withErrors($this->eventValidator())
                            ->withInput();
        }

        $insertArray = [
            "event_name" => $this->request->input("event_name"),
            "event_description" => $this->request->input("event_description"),
            "event_start_date" => DateHelper::convertToDateTime($this->request->input("event_start_date")),
            "event_end_date" => DateHelper::convertToDateTime($this->request->input("event_end_date")),
            "status" => "active"
        ];
        
        $this->eventsModel->setInsertDataWithDates($insertArray);
        $eventId = $this->eventsModel->insertData();

        //Assign users to the event
        //Bring the groups related users list
        $selectedUsers = ($this->request->input("users_list")) ? $this->request->input("users_list") : [];
        if ($this->request->input("group_list")) {
            $groupService = new GroupService($this->request);
            $groupusersList = $groupService->getMultiGroupUsers();

            if (count($groupusersList) > 0) {
                foreach ($groupusersList as $user) {
                    $selectedUsers[] = $user->user_id;
                }
            }

            $selectedUsers = array_unique($selectedUsers);
        }
        $this->insertEventUsers($selectedUsers, $eventId);

        //send the notification
        $notificationService = new NotificationService($this->request);
        $this->request->request->add(['message' => $this->prepareEventNotificationMessage($eventId)]);
        $notificationService->createNotification();

        return true;
    }

    /**
     * delete group related validations
     * @return type
     */
    public function eventValidator()
    {
        return Validator::make($this->request->all(), [
                    'event_name' => 'required',
                    'event_description' => 'required',
                    'event_start_date' => 'required',
                    'event_end_date' => 'required',
        ]);
    }

    /**
     * To Insert the recored related to event id & user id
     * @param type $selectedUsers
     * @param type $eventId
     */
    public function insertEventUsers($selectedUsers, $eventId)
    {
        if (count($selectedUsers) > 0) {
            $insertArray = [];
            foreach ($selectedUsers as $userId) {
                $insertArray[] = [
                    "event_id" => $eventId,
                    "user_id" => $userId,
                    "created_at" => DateHelper::todayDateTime(),
                    "updated_at" => DateHelper::todayDateTime(),
                ];
            }

            $this->eventsModel->setTableName("event_users");
            $this->eventsModel->setInsertUpdateData($insertArray);
            $this->eventsModel->bulkInsert();
        }
        return true;
    }

    /**
     * To delete the group id
     */
    public function deleteEvent()
    {
        //validations
        if ($this->deletedEventValidator()->fails()) {
            return redirect()->back()
                            ->withErrors($this->deletedEventValidator())
                            ->withInput();
        }

        $eventId = $this->request->input("event_id");
        //delete logic
        $whereArray = [
            "event_id" => $eventId,
        ];
        $this->eventsModel->setWhere($whereArray);
        $this->eventsModel->deleteData();

        //delete the group users
        $this->deleteEventUsers($eventId);

        return TRUE;
    }

    /**
     * delete event related validations
     * @return type
     */
    public function deletedEventValidator()
    {
        return Validator::make($this->request->all(), [
                    'event_id' => 'required|integer'
        ]);
    }

    /**
     * To delete the Specific event related Users
     * @param type $eventId
     */
    public function deleteEventUsers($eventId)
    {
        $whereArray = [
            "event_id" => $eventId,
        ];
        $this->eventsModel->setTableName("event_users");
        $this->eventsModel->setWhere($whereArray);
        $this->eventsModel->deleteData();
    }

    /**
     * To Update the user related details
     */
    public function updateEvent()
    {
        //validations
        if ($this->updateEventValidator()->fails()) {
            return redirect()->back()
                            ->withErrors($this->updateEventValidator())
                            ->withInput();
        }

        //update logic
        $eventId = $this->request->input("event_id");
        $updateArray = [
            "event_name" => $this->request->input("event_name"),
            "event_description" => $this->request->input("event_description"),
            "event_start_date" => DateHelper::convertToDateTime($this->request->input("event_start_date")),
            "event_end_date" => DateHelper::convertToDateTime($this->request->input("event_end_date")),
        ];

        $whereArray = [
            ["event_id", '=', $eventId]
        ];

        $this->eventsModel->setUpdateDataWithDates($updateArray);
        $this->eventsModel->setWhere($whereArray);
        $this->eventsModel->updateData();

        //update users logic
        $selectedUsers = ($this->request->input("users_list")) ? $this->request->input("users_list") : [];
        $selectedUsers = array_unique($selectedUsers);

        $this->insertEventUsers($selectedUsers, $eventId);

        //send the notification
        $notificationService = new NotificationService($this->request);
        $this->request->request->add(['message' => $this->prepareEventNotificationMessage($eventId)]);
        $notificationService->createNotification();

        return true;
    }

    /**
     * Create user related validation Rules
     * @return type
     */
    public function updateEventValidator()
    {
        return Validator::make($this->request->all(), [
                    'event_name' => 'required',
                    'event_description' => 'required',
                    'event_start_date' => 'required',
                    'event_end_date' => 'required',
        ]);
    }

    /**
     * To Formated the selected user list related to particular event
     * @param type $list
     * @return string
     */
    public function formatEventUsersList($list)
    {
        $formattedList = [];
        if (count($list) > 0) {
            foreach ($list as $user) {
                $formattedList["user_ids"][] = $user->user_id;
                $status = $user->status_name;
                $userName = $user->first_name . " " . $user->last_name;
                if ($status == "accepted") {
                    $formattedList["accepted"][] = $userName;
                } else if ($status == "pending") {
                    $formattedList["pending"][] = $userName;
                } else {
                    $formattedList["rejected"][] = $userName;
                }
            }
        }
        return $formattedList;
    }

    /**
     * To Prepare the Event related notification message text
     * @return string
     */
    public function prepareEventNotificationMessage($eventId)
    {
        $message = ["alert" => "Received New Event From MTC",
            "type" => "event",
            "id" => $eventId,
            'badge' => 1
        ];

       
        return $message;
    }

}
