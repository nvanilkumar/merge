<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\SurveyService;
use App\Services\UserService;
use App\Services\GroupService;
use Session;

class SurveyController extends Controller
{

    public function __construct(Request $request, SurveyService $surveyService)
    {
        $this->request = $request;
        $this->surveyService = $surveyService;
        $this->groupService = new GroupService($this->request);
        $this->userService = new UserService($this->request);
    }

    public function createSurveyView()
    {
        $groupList = $this->groupService->getGroupList();

        $this->request->request->add(['role_name' => "student"]);
        $usersList = $this->userService->getUserList();
        return view('surveys.survey', ["title" => "Create Survey",
            "groupList" => $groupList,
            "usersList" => $usersList
        ]);
    }

    public function updateSurveyView($survey_id)
    {
        $this->request->request->add(['survey_id' => $survey_id]);
        $details = $this->surveyService->getSurveys();
        if ($details == NULL) {
            return redirect('/dashboard');
        }
        $survey = (array) $details[0];

        $this->request->request->add(['role_name' => "student"]);
        $usersList = $this->userService->getUserList();

        $selectedUsers = $this->surveyService->getSurveyUsers();
        $selectedUsers = $this->surveyService->formatSurveyUsersList($selectedUsers);


        return view('surveys.surveyEdit', ["title" => "Update Survey",
            "survey" => $survey,
            "selectedUsers" => $selectedUsers,
            "usersList" => $usersList
        ]);
    }

    public function createSurvey()
    {
        $this->surveyService->createSurvey();
        return redirect('surveys/list');
    }

    /**
     * To update the posted survey details
     * @return type
     */
    public function updateSurvey()
    {
        $this->surveyService->updateSurvey();
        return redirect('/surveys/update/' . $this->request->input("survey_id"))
                        ->with('status', 'Survey updated successfully');
        
    }

    /**
     * To Bring the Surveys list
     * @return type
     */
    public function getSurveyList()
    {
        $lists = $this->surveyService->getSurveys();
        return view('surveys.surveyList', ["title" => "All Surveys",
            "surveyList" => $lists]);
    }

    /**
     * To delete the specified survey id
     * @return type
     */
    public function deleteSurvey($survey_id)
    {
        $this->request->request->add(['survey_id' => $survey_id]);
        $this->surveyService->deleteSurvey();
        Session::flash('message', 'Success! Record is deleted successfully.');
        return redirect('/surveys/list');
    }

}
