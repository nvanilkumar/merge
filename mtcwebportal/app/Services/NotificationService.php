<?php

namespace App\Services;

use Illuminate\Http\Request;
use App\Models\NotificationModel;
use Validator;
use App\Helpers\DateHelper;
use Config;

class NotificationService
{

    protected $request;
    protected $linksModel;

    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->notificationModel = new NotificationModel();
        $this->notificationModel->setTableName("notifications");
    }

    /**
     * To create the notification
     * @return type
     */
    public function createNotification()
    {
        //validations
        if ($this->notificationValidator()->fails()) {
            return redirect()->back()
                            ->withErrors($this->notificationValidator())
                            ->withInput();
        }

        $message = $this->request->input("message");
        if (is_array($message)) {
            $message = implode(" ", $message);
        }

        $insertArray = [
            "message" => $message,
            "created_by" => $this->request->session()->get('user_id'),
            "status" => "active"
        ];

        $this->notificationModel->setInsertDataWithDates($insertArray);
        $notificationId = $this->notificationModel->insertData();

        //send nofitifcations
        $this->getDeviceTokens($notificationId);
        return $notificationId;
    }

    /**
     * Create user related validation Rules
     * @return type
     */
    public function notificationValidator()
    {
        return Validator::make($this->request->all(), [
                    'message' => 'required',
        ]);
    }

    /**
     * To get the notification details 
     */
    public function getNotificationDetails()
    {
        $whereArray = [];

        $notificationId = $this->request->input("notification_id");
        if ($notificationId) {
            $whereArray[] = ["notification_id", '=', $notificationId];
        }

        $this->notificationModel->setWhere($whereArray);
        $details = $this->notificationModel->getOrderByData("notification_id");

        return $details;
    }

    /**
     * It will call the send push notification method 200 tokens at a time
     * @param type $message
     * @param type $deviceTokens
     */
    public function proecessAndroidFCMPushNotifications($message, $deviceTokens)
    {
        //fcm limit is there it process only 1000 devices at a time
        if (count($deviceTokens) < 1000) {
            $this->sendAndroidFCMPushNotifications($message, $deviceTokens);
        }

        //Processing 1000 tokens at a time
        if (count($deviceTokens) > 1000) {
            $splitTokens = array_chunk($deviceTokens, 1000);
            foreach ($splitTokens as $splitToken) {
                $this->sendAndroidFCMPushNotifications($message, $splitToken);
            }
        }
    }

    /**
     * To send the push notification using the Fcm url 
     * it accept the 200 device tokens & message to push
     * @param type $message
     * @param type $deviceTokens
     */
    public function sendAndroidFCMPushNotifications($message, $deviceTokens)
    {
        $fcmUrl = Config::get('notifications.fcm.url'); //fcm sent url
        $fcmProjectKey = Config::get('notifications.fcm.app_key'); //Fcm Console application key
        //Preparing the payload array
        $payLoad = [
            'registration_ids' => $deviceTokens,
            'notification' => $message,
            'data' => $message
        ];
        //encode the payload to json
        $payLoad = json_encode($payLoad);

        //Preaparing the curl url header
        $headers = array(
            'Authorization: key=' . $fcmProjectKey,
            'Content-Type: application/json'
        );

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $fcmUrl);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payLoad);

        $result = curl_exec($ch);
//            var_dump($result) ;
//	echo 'Curl error: ' . curl_error($ch);
        curl_close($ch);
        if ($result === false) {
            return false;
        }

        return true;
    }

    /**
     * To Send the IOS device related push notifications
     * @param type $tokens
     * @param type $messageArray
     */
    public function sendIOSPushNotifications($tokens, $messageArray)
    {
        $apnsHost = Config::get('notifications.apn.host');
        $apnsCert = Config::get('notifications.apn.cert');
        $apnsPort = Config::get('notifications.apn.port');
        $apnsPassword = Config::get('notifications.apn.cert_pass');

        //Create the socket connection
        try {
            $streamContext = stream_context_create();
            stream_context_set_option($streamContext, 'ssl', 'local_cert', $apnsCert);
            stream_context_set_option($streamContext, 'ssl', 'passphrase', $apnsPassword);

            $apns = stream_socket_client('ssl://' . $apnsHost . ':' . $apnsPort, $error, $errorString, 2, STREAM_CLIENT_CONNECT, $streamContext
            );

            //repeat the below steps to send same message to multiple devices  
            foreach ($tokens as $token) {

                $payload = [];
                $payload['aps'] = $messageArray;
                $output = json_encode($payload);

                $token = pack('H*', str_replace(' ', '', $token));
                $apnsMessage = chr(0) . chr(0) . chr(32) . $token . chr(0) . chr(strlen($output)) . $output;
                fwrite($apns, $apnsMessage);
            }
            //end for one device
            @socket_close($apns);
            fclose($apns);
        } catch (\Exception $e) {
            return $e->getMessage();
        }



        return true;
    }

    /**
     * To Bring the selected groups related users device tokens
     * @param type $notificationId
     * @return type
     */
    public function getDeviceTokens($notificationId)
    {
        $where = ["groupList" => "", "userList" => ""];
        $groupList = $this->request->input("group_list");
        if (count($groupList) > 0) {
            $groupStr = implode(',', $groupList);
            $where["groupList"] = $groupStr;
        }

        $userList = $this->request->input("users_list");
        if (count($userList) > 0) {
            $userStr = implode(',', $userList);
            $where ["userList"] = $userStr;
        }

        $this->notificationModel->setWhere($where);
        $tokens = $this->notificationModel->getGroupUsersDeviceTokens();

        $this->processTokens($tokens, $notificationId);

        return $tokens;
    }

    /**
     * To Process notification related tokens & user ids
     * @param type $tokens
     * @param type $notificationId
     */
    public function processTokens($tokens, $notificationId)
    {
        $iosTokens = [];
        $androidTokens = [];
        $message = $this->request->input("message");
        $userIds = [];
        if (count($tokens) > 0) {
            foreach ($tokens as $token) {
                $userIds[] = $token->user_id;
                if ($token->status_name == "android") {
                    $androidTokens[] = $token->device_token_id;
                } else {
                    $iosTokens[] = $token->device_token_id;
                }
            }

            //insert the notification users when notification type not equal to chat
            $this->insertNotificationUsers($userIds, $notificationId);

            //call the ios push notifications
            if (count($iosTokens) > 0) {

                $iosMessage = $this->setIOSMessage($message);
                $this->sendIOSPushNotifications($iosTokens, $iosMessage);
            }

            //call to android notifications
            if (count($androidTokens) > 0) {
                $androidMessage = $this->setAndroidMessage($message);
                $this->proecessAndroidFCMPushNotifications($androidMessage, $androidTokens);
            }
        }
    }

    /**
     * To Prepares the ios push notification message array
     * @param type $message
     */
    public function setIOSMessage($message)
    {
        $iosMessage = "";
        if (is_array($message)) {//set the event & survery related message
            $iosMessage = $message;
        } else {
            //Normal notificatin related message
            $iosMessage = ['alert' => "Received New Message From MTC",
                'badge' => 1,
                "type" => "general",
                "msg" => $message
            ];
        }
        return $iosMessage;
    }

    public function setAndroidMessage($message)
    {
        $andoridMessage = ['title' => 'MTC Notification',
            'body' => "Received New Message From MTC",
            'type' => "general",
            'msg' => $message,
        ];

        //to set event & survey related message
        if (is_array($message)) {
            $andoridMessage = ["title" => "New Event From MTC",
                "body" => "Received New Event From MTC",
                "type" => "event",
                "id" => $message["id"],
                "click_action" => "OPEN_NOTIFICATION"];

            if ($message["type"] == "survey") {
                $andoridMessage = ["title" => "MTC Survey",
                    "body" => "Received New Survey",
                    "type" => $message["type"],
                    "id" => $message["id"],
                    "survey_code" => $message["survey_code"],
                    "click_action" => "OPEN_NOTIFICATION"];
            }
        }
        return $andoridMessage;
    }

    /**
     * To Insert the notification related users list
     * @param type $selectedUsers brings the users list
     * @param type $notificationId latest inserted notification id
     */
    public function insertNotificationUsers($selectedUsers, $notificationId)
    {
        if (count($selectedUsers) > 0) {
            $insertArray = [];
            foreach ($selectedUsers as $userId) {
                $insertArray[] = [
                    "notification_id" => $notificationId,
                    "user_id" => $userId,
                    "created_at" => DateHelper::todayDateTime(),
                    "updated_at" => DateHelper::todayDateTime(),
                ];
            }

            $this->notificationModel->setTableName("notification_users");
            $this->notificationModel->setInsertUpdateData($insertArray);
            $this->notificationModel->bulkInsert();

            return true;
        }
        return false;
    }

    public function getNotificationUserDetails()
    {
        $whereArray = [
            'notification_id' => $this->request->input("notification_id"),
        ];
        $this->notificationModel->setWhere($whereArray);
        $notification = $this->notificationModel->getNotificationUserDetails();
        return $notification;
    }

}
