<?php

namespace App\Services;

use Illuminate\Http\Request;
use App\Models\SurveysModel;
use App\Services\GroupService;
use App\Services\NotificationService;
use App\Helpers\DateHelper;
use Validator;

class SurveyService
{

    protected $request;
    protected $surveyModel;

    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->surveyModel = new SurveysModel();
        $this->surveyModel->setTableName("surveys");
    }

    /**
     * To Bring the surveys list
     * @return type
     */
    public function getSurveys()
    {
        $surveyId = $this->request->input("survey_id");
        $whereArray = [];
        if ($surveyId) {
            $whereArray = [
                ["survey_id", '=', $this->request->input("survey_id")]
            ];
        }
        $this->surveyModel->setWhere($whereArray);
        $surveys = $this->surveyModel->getOrderByData("survey_id");
        return $surveys;
    }

    /**
     * To Bring the user specific surveys list
     */
    public function getSurveyUsers()
    {
        $whereArray = [
            'survey_id' => $this->request->input("survey_id"),
        ];

        $this->surveyModel->setWhere($whereArray);
        return $this->surveyModel->getUserSurveys();
       
    }

    /**
     * To create the survey
     */
    public function createSurvey()
    {
        //validations
        if ($this->surveyValidator()->fails()) {
            return redirect()->back()
                            ->withErrors($this->surveyValidator())
                            ->withInput();
        }

        $insertArray = [
            "survey_name" => $this->request->input("survey_name"),
            "survey_description" => $this->request->input("survey_description"),
            "survey_code" => $this->request->input("survey_code"),
            "survey_start_date" => DateHelper::convertToDateTime($this->request->input("survey_start_date")),
            "survey_end_date" => DateHelper::convertToDateTime($this->request->input("survey_end_date")),
            "status" => "active"
        ];

        $this->surveyModel->setInsertDataWithDates($insertArray);
        $surveyId = $this->surveyModel->insertData();

        //Assign users to the survey
        //Bring the groups related users list
        $selectedUsers = ($this->request->input("users_list")) ? $this->request->input("users_list") : [];
        if ($this->request->input("group_list")) {
            $groupService = new GroupService($this->request);
            $groupusersList = $groupService->getMultiGroupUsers();
            
            if (count($groupusersList) > 0) {
                foreach ($groupusersList as $user) 
                {
                    $selectedUsers[] = $user->user_id;
                }
            }

            $selectedUsers = array_unique($selectedUsers);
        }
        $this->insertSurveyUsers($selectedUsers, $surveyId);
        
        //send the notification
        $notificationService = new NotificationService($this->request);
        $this->request->request->add(['message' => $this->prepareSurveyNotificationMessage($surveyId)]);
        $notificationService->createNotification();

        return true;
    }

    /**
     * insert survey validations
     * @return type
     */
    public function surveyValidator()
    {

        return Validator::make($this->request->all(), [
                    'survey_name' => 'required',
                    'survey_description' => 'required',
                    'survey_code' => 'required',
                    'survey_start_date' => 'required',
                    'survey_end_date' => 'required',
        ]);
    }

    /**
     * To Insert the recored related to survey id & user id
     * @param type $selectedUsers
     * @param type $surveyId
     */
    public function insertSurveyUsers($selectedUsers, $surveyId)
    {
        if (count($selectedUsers) > 0) {
            $insertArray = [];
            foreach ($selectedUsers as $userId) {
                $insertArray[] = [
                    "survey_id" => $surveyId,
                    "user_id" => $userId,
                    "created_at" => DateHelper::todayDateTime(),
                    "updated_at" => DateHelper::todayDateTime(),
                ];
            }

            $this->surveyModel->setTableName("survey_users");
            $this->surveyModel->setInsertUpdateData($insertArray);
            $this->surveyModel->bulkInsert();
        }
        return true;
    }

    /**
     * To delete the Survey
     */
    public function deleteSurvey()
    {
        //validations
        if ($this->deletedSurveyValidator()->fails()) {
            return redirect()->back()
                            ->withErrors($this->deletedSurveyValidator())
                            ->withInput();
        }
        $survey_id=$this->request->input("survey_id");
        //delete logic
        $whereArray = [
            "survey_id" => $survey_id,
        ];
        $this->surveyModel->setWhere($whereArray);
        $this->surveyModel->deleteData();

        //delete the assigned survey users
        $this->deleteSurveyUsers($survey_id);

        return TRUE;
    }

    /**
     * delete survey related validations
     * @return type
     */
    public function deletedSurveyValidator()
    {
        return Validator::make($this->request->all(), [
                    'survey_id' => 'required|integer'
        ]);
    }

    /**
     * To delete the Specific survey related Users
     * @param type $survey_id
     */
    public function deleteSurveyUsers($survey_id)
    {
        $whereArray = [
            "survey_id" => $survey_id,
        ];
        $this->surveyModel->setTableName("survey_users");
        $this->surveyModel->setWhere($whereArray);
        $this->surveyModel->deleteData();
    }

    /**
     * To Update the survey details
     */
    public function updateSurvey()
    {
        //validations
        if ($this->surveyValidator()->fails()) {
            return redirect()->back()
                            ->withErrors($this->surveyValidator())
                            ->withInput();
        }

        //update logic
        $surveyId=$this->request->input("survey_id");
        $updateArray = [
            "survey_name" => $this->request->input("survey_name"),
            "survey_description" => $this->request->input("survey_description"),
            "survey_code" => $this->request->input("survey_code"),
            "survey_start_date" => DateHelper::convertToDateTime($this->request->input("survey_start_date")),
            "survey_end_date" => DateHelper::convertToDateTime($this->request->input("survey_end_date")),
        ];

        $whereArray = [
            ["survey_id", '=',$surveyId ]
        ];

        $this->surveyModel->setUpdateDataWithDates($updateArray);
        $this->surveyModel->setWhere($whereArray);
        $this->surveyModel->updateData();
        
        //update users logic
        $selectedUsers = ($this->request->input("users_list")) ? $this->request->input("users_list") : [];
        $selectedUsers = array_unique($selectedUsers);

        $this->insertSurveyUsers($selectedUsers, $surveyId);
        
        //send the notification
        $notificationService = new NotificationService($this->request);
        $this->request->request->add(['message' => $this->prepareSurveyNotificationMessage($surveyId)]);
        $notificationService->createNotification();
        
        return true;
    }
 

    /**
     * To Formated the selected user list related to particular event
     * @param type $list
     * @return string
     */
    public function formatSurveyUsersList($list)
    {
       $formattedList=[];
       if(count($list) > 0){
           foreach($list as $user){
               $formattedList["user_ids"][]=$user->user_id;
               $status=$user->status_name;
               $userName=$user->first_name." ".$user->last_name;
               if($status == "accepted"){
                   $formattedList["accepted"][]=$userName;
               }else if($status == "pending"){
                   $formattedList["pending"][]=$userName;
               }else{
                   $formattedList["rejected"][]=$userName;
               }
               
               
           }
       }
       return $formattedList;
        
    }
    
    /**
     * To Prepare the survey related notification message text
     * @return string
     */
    public function prepareSurveyNotificationMessage($surveyId)
    {

        $message=["alert"=>"Received New Survey",
                   "type" =>"survey",
                   "id"=>$surveyId,
                   "survey_code"=>$this->request->input("survey_code"),
                   'badge' => 1
            ];

        return $message;
    }        

   
}
