<?php

namespace App\Services;

use Illuminate\Http\Request;
use App\Models\UsersModel;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use App\Services\EmailService;
use App\Models\EventsModel;
use App\Models\SurveysModel;
use App\Models\TopicsModel;
use DateHelper;

class UserService
{

    protected $request;
    protected $usersModel;

    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->usersModel = new UsersModel();
    }

    /**
     * To insert the device token information
     */
    public function deviceTokenCreate()
    {
        $responseMessage = array();
        //check the record
        $whereArray = [
            ['users.user_id', '=', $this->request->input("user_id")],
        ];
        $this->usersModel->setWhere($whereArray);
        $tokensList = $this->usersModel->usertokens();
//print_r($tokensList);exit;
        //change status
        if (count($tokensList) > 0) {

            foreach ($tokensList as $token) {

                //Active token found
                if (($token->device_token_id == $this->request->input("device_token_id")) && ($token->user_id == $this->request->input("user_id")) &&
                        ($token->status == "active") && ($token->device_type == $this->request->input("status_id"))) {
                    $responseMessage["statusCode"] = STATUS_UPDATED;
                    $responseMessage["response"]['status'] = "success";
                    $responseMessage["response"]['message'] = config('mts-config.devicetoken.record_created');

                    return $responseMessage;
                }

                //In active token found
                if (($token->device_token_id == $this->request->input("device_token_id")) &&
                        ($token->user_id == $this->request->input("user_id")) &&
                        ($token->status != "active") && ($token->device_type == $this->request->input("status_id"))) {

                    $this->userTokensInactivate();
                    $this->userTokensActivate();
                    $responseMessage["statusCode"] = STATUS_UPDATED;
                    $responseMessage["response"]['status'] = "success";
                    $responseMessage["response"]['message'] = config('mts-config.devicetoken.record_created');

                    return $responseMessage;
                }
            }

            //Current token not found deactivate all previous tokens
            $this->userTokensInactivate();
        }

        //insert the record

        $this->usersModel->setTableName("user_tokens");
        $insertArray = array(
            "device_token_id" => $this->request->input("device_token_id"),
            "device_type" => $this->request->input("status_id"),
            "user_id" => $this->request->input("user_id"),
            "status" => "active"
        );
        $this->usersModel->setInsertUpdateData($insertArray);
        $insert_id = $this->usersModel->insertData();

        $responseMessage["statusCode"] = STATUS_CREATED;
        $responseMessage["response"]['status'] = "success";
        $responseMessage["response"]['message'] = config('mts-config.devicetoken.record_created');
        $responseMessage["response"]['insert_id'] = $insert_id;
        return $responseMessage;
    }

    /**
     * To inactivate the all user tokens
     */
    public function userTokensInactivate()
    {
        //inactivate all
        $whereArray = [
            ["user_id", '=', $this->request->input("user_id")]
        ];
        $updateArray = [
            "status" => "inactive"
        ];

        $this->usersModel->setTableName("user_tokens");
        $this->usersModel->setInsertUpdateData($updateArray);
        $this->usersModel->setWhere($whereArray);
        $this->usersModel->usertokensupdate();
    }

    /**
     * To activate the user tokens
     */
    public function userTokensActivate()
    {
        $updateArray = [
            "status" => "active"
        ];
        $whereArray = [
            ["user_id", '=', $this->request->input("user_id")],
            ["device_token_id", '=', $this->request->input("device_token_id")],
            ["device_type", "=", $this->request->input("status_id")]
        ];
        
        $this->usersModel->setTableName("user_tokens");
        $this->usersModel->setInsertUpdateData($updateArray);
        $this->usersModel->setWhere($whereArray);
        $this->usersModel->usertokensupdate();
    }

    /**
     * To validate the login related details and 
     * send the response message
     * @return type
     */
    public function loginCheck()
    {
        //validatons for login
        $responseMessage = array();
        $username = $this->request->input("username");

        //User roles
        $user_role = "student";
        if ($this->request->input("user_role")) {
            $user_role = "staff";
        }
        $where = [["username", "=", $username],
            ["roles.role_name", "=", $user_role]
        ];

        $this->usersModel->setWhere($where);
        $list = $this->usersModel->getUsers();

        if ($list == null) {
            throw new NotFoundHttpException(config('mts-config.login.failed'), null, 10056);
        }


        $hashedPassword = $list[0]->password;
        $status = Hash::check($this->request->input("password"), $hashedPassword);
        if ($status) {
            $responseMessage["statusCode"] = STATUS_OK;
            $responseMessage["response"]['status'] = TRUE;
            $responseMessage["response"]['message'] = config('mts-config.login.success');
            $responseMessage["response"]["user_id"] = $list[0]->user_id;
            $responseMessage["response"]["first_name"] = $list[0]->first_name;
            $responseMessage["response"]["last_name"] = $list[0]->last_name;
            $responseMessage["response"]["user_chat_channel"] = "user-id-" . $list[0]->user_id;
        } else {

            $responseMessage["statusCode"] = STATUS_UNAUTHORIZED;
            $responseMessage["response"]['status'] = FALSE;
            $responseMessage["response"]['message'] = config('mts-config.login.failed');
        }



        return $responseMessage;
    }

    /**
     * To send forgot password email link to specified user
     * @return type
     */
    public function forgotPassword()
    {
        $responseMessage = array();
        $userName = $this->request->input("user_name");
        $where = [["username", "=", $userName]];
        $this->usersModel->setWhere($where);
        $user = $this->usersModel->getUsers();

        if ($user == null) {
            throw new NotFoundHttpException(config('mts-config.forgot_passowrd.failed') . $userName, null, 10056);
        }

        $emailId = $user[0]->email;
        $name = $user[0]->first_name . " " . $user[0]->last_name;
        $username = $user[0]->username;
        $userId = $user[0]->user_id;
        $token = str_random(64);
        $url_link = "http://52.15.173.171?&name=" . $username . "&token=" . $token;

        $emailService = new EmailService();
        $emailStatus = $emailService->sendForgotPasswordEmail($emailId, $name, $url_link);

        if ($emailStatus) {
            $this->userSaltStringUpdate($token, $userId);
            $responseMessage["statusCode"] = STATUS_OK;
            $responseMessage["response"]['status'] = TRUE;
            $responseMessage["response"]['message'] = config('mts-config.forgot_passowrd.success');
        } else {
            $responseMessage["statusCode"] = STATUS_PERMISSION_DENIED;
            $responseMessage["response"]['status'] = FALSE;
            $responseMessage["response"]['message'] = config('mts-config.forgot_passowrd.email_fail');
        }
        return $responseMessage;
    }

    /**
     * To change the user password
     * @return type
     */
    public function changePassword()
    {
        $responseMessage = array();
        $userId = $this->request->input("user_id");
        $where = [["users.user_id", "=", $userId]];
        $this->usersModel->setWhere($where);
        $user = $this->usersModel->getUsers();

        if ($user == null) {
            throw new NotFoundHttpException(config('mts-config.change_password.user_id_failed'), null, 10056);
        }


        $oldPassword = $this->request->input("old_password");
        $oldHashedPassword = $user[0]->password;
        $status = Hash::check($oldPassword, $oldHashedPassword);
        if ($status) {

            //change to new password
            $this->userPasswordUpdate();
            $responseMessage["statusCode"] = STATUS_OK;
            $responseMessage["response"]['status'] = TRUE;
            $responseMessage["response"]['message'] = config('mts-config.change_password.success');
        } else {

            $responseMessage["statusCode"] = STATUS_UNAUTHORIZED;
            $responseMessage["response"]['status'] = FALSE;
            $responseMessage["response"]['message'] = config('mts-config.change_password.password_failed');
        }

        return $responseMessage;
    }

    /**
     * To update the user password to new value
     */
    public function userPasswordUpdate()
    {
        $password = Hash::make($this->request->input("new_password"));
        $updateArray = [
            "password" => $password
        ];
        $whereArray = [
            ["user_id", '=', $this->request->input("user_id")]
        ];
        $this->usersModel->setTableName("users");
        $this->usersModel->setInsertUpdateData($updateArray);
        $this->usersModel->setWhere($whereArray);
        $this->usersModel->updateData();
    }

    /**
     * To update the user  to new value
     */
    public function userSaltStringUpdate($token, $userId)
    {

        $updateArray = [
            "salt_string" => $token
        ];
        $whereArray = [
            ["user_id", '=', $userId]
        ];
        $this->usersModel->setTableName("users");
        $this->usersModel->setInsertUpdateData($updateArray);
        $this->usersModel->setWhere($whereArray);
        $this->usersModel->updateData();
    }

    /**
     * To Bring the staff related chat rooms
     * @return string
     * @throws NotFoundHttpException
     */
    public function chatUsers()
    {
        $where = [["roles.role_name", "=", "staff"]];

        $this->usersModel->setWhere($where);
        $userList = $this->usersModel->getUsers();
        if ($userList == null) {
            throw new NotFoundHttpException(config('mts-config.login.failed'), null, 10056);
        }

        $chatList = $chatusersList = [];
        foreach ($userList as $user) {
            $chatList['first_name'] = $user->first_name;
            $chatList['last_name'] = $user->last_name;
            $chatList['chat_room'] = "user-id-" . $user->user_id;
            $chatusersList[] = $chatList;
        }

        $responseMessage["statusCode"] = STATUS_OK;
        $responseMessage["response"]['status'] = TRUE;
        $responseMessage["response"]["chattList"] = $chatusersList;
        return $responseMessage;
    }

    /**
     * To send the User related Dashboard information
     * @return type
     * @throws NotFoundHttpException
     */
    public function dashboard()
    {
        $responseMessage = array();
        $userId = $this->request->input("user_id");
        $where = [["users.user_id", "=", $userId]];
        $this->usersModel->setWhere($where);
        $user = $this->usersModel->getUsers();

        if ($user == null) {
            throw new NotFoundHttpException(config('mts-config.change_password.user_id_failed'), null, 10056);
        }

        //events info
        $eventModel = new EventsModel();
        $whereArray = [
            'user_id' => $userId,
            'event_end_date' => DateHelper::todayDate(),
        ];

        $eventModel->setWhere($whereArray);
        $events = $eventModel->getUserEvents();
        $eventDetails["accepted_count"] = 0;
        $eventDetails["pending_count"] = 0;
        if (count($events) > 0) {
            foreach ($events as $event) {
                if ($event->status_name == "accepted") {
                    $eventDetails["accepted_count"] = $event->statuscount;
                }

                if ($event->status_name == "pending") {
                    $eventDetails["pending_count"] = $event->statuscount;
                }
            }
        }



        //survey info
        $surveysModel = new SurveysModel();
        $surveyWhereArray = ['user_id' => $userId,
            'survey_end_date' => DateHelper::todayDate(),
        ];
        $surveysModel->setWhere($surveyWhereArray);
        $surveys = $surveysModel->getUserSurveyStatus();

        $surveyDetails["accepted_count"] = 0;
        $surveyDetails["pending_count"] = 0;
        if (count($surveys) > 0) {
            foreach ($surveys as $survey) {
                if ($survey->status_name == "accepted") {
                    $surveyDetails["accepted_count"] = $survey->statuscount;
                }

                if ($survey->status_name == "pending") {
                    $surveyDetails["pending_count"] = $survey->statuscount;
                }
            }
        }


        //get the topic count
        $this->usersModel->setTableName('topics');
        $topicWhereArray = [
            ["topic_id", '>', 0],
        ];
        $this->usersModel->setWhere($topicWhereArray);
        $topic = $this->usersModel->getData();

        //get the user commented count
        $this->usersModel->setTableName('comments');
        $commentsWhereArray = [
            ["created_by", '=', $userId],
        ];
        $this->usersModel->setWhere($commentsWhereArray);
        $commentes = $this->usersModel->getData();

        //chat related logic
        $chatusersList = $this->chatUsers();
        $chatusersList = $chatusersList["response"]["chattList"];

        $responseMessage["statusCode"] = STATUS_OK;
        $responseMessage["response"]['status'] = TRUE;
        $responseMessage["response"]["calendar"] = $eventDetails;
        $responseMessage["response"]["surveys"] = $surveyDetails;
        $responseMessage["response"]["message_board"]["posts"] = count($topic);
        $responseMessage["response"]["message_board"]["replies"] = count($commentes);
        $responseMessage["response"]["chattList"] = $chatusersList;

        return $responseMessage;
    }

}
