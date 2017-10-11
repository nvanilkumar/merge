<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\NotificationService;
use App\Services\GroupService;
use App\Services\UserService;

class NotificationController extends Controller
{

    public function __construct(Request $request, NotificationService $notificationService)
    {
        $this->request = $request;
        $this->notificationService = $notificationService;
    }

    public function createNotificationView()
    {
        $this->groupService = new GroupService($this->request);
        $this->userService = new UserService($this->request);
        $groupList = $this->groupService->getGroupList();
        $this->request->request->add(['role_name' => "student"]);
        $usersList = $this->userService->getUserList();
        return view('notifications.notifications', ["title" => "Create Notification",
            "groupList" => $groupList,
            "usersList" => $usersList
        ]);
    }

    public function createNotification()
    {

        $this->notificationService->createNotification();
        return redirect('/notifications/list');
    }

    /**
     * To update the posted notification data
     * @return type
     */
    public function getNotifications()
    {
        $notifciationList = $this->notificationService->getNotificationDetails();
        return view('notifications.notificationList', ["title" => "Notification List",
            "notifciationList" => $notifciationList]);
    }

    /**
     * To update the posted notification data
     * @return type
     */
    public function notificationView($notificationId)
    {
        $this->request->request->add(['notification_id' => $notificationId]);
        $notifciationList = $this->notificationService->getNotificationUserDetails();

        return view('notifications.notificationView', ["title" => "Notification List",
            "notifciationList" => $notifciationList]);
    }

}
