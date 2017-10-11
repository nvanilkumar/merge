<?php

namespace App\Services;

use Illuminate\Http\Request;
use App\Models\SurveysModel;
use DateHelper;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class SurveyService
{

    protected $request;
    protected $surveyModel;

    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->surveyModel = new SurveysModel();
    }

    public function getSurveys()
    {
        $responseMessage = array();
        //check the record
        $whereArray = ['user_id' => $this->request->input("user_id"),
            'survey_end_date' => DateHelper::todayDate(),
            'status_name' => 'pending',
        ];
        $this->surveyModel->setWhere($whereArray);
        $surveys = $this->surveyModel->getUserSurveys();

        if ($surveys == null) {
            throw new NotFoundHttpException(config('mts-config.links.zerorecords'), null, 10060);
        }

        $responseMessage["statusCode"] = STATUS_OK;
        $responseMessage["response"]['status'] = true;
        $responseMessage["response"]['surveys'] = $surveys;
        return $responseMessage;
    }

    public function changeSurveyUserStatus()
    {
        $responseMessage = array();
       //check the details first
        $this->surveyModel->setTableName('survey_users');
        $whereArray = [
            ["survey_id", '=', $this->request->input("survey_id")],
            ["user_id", '=', $this->request->input("user_id")],
        ];
        $this->surveyModel->setWhere($whereArray);
        $surveyRecord = $this->surveyModel->getData();

        if ($surveyRecord == null) {
            throw new NotFoundHttpException(config('mts-config.links.zerorecords'), null, 10060);
        }

        //Update the record
        $insertArray = array(
            "survey_id" => $this->request->input("survey_id"),
            "user_id" => $this->request->input("user_id"),
            "user_response_status" => $this->request->input("status_id")
        );

        $whereArray = [
            ["survey_id", '=', $this->request->input("survey_id")],
            ["user_id", '=', $this->request->input("user_id")],
        ];
        
        $this->surveyModel->setInsertUpdateData($insertArray);
        $this->surveyModel->setWhere($whereArray);

        $this->surveyModel->updateData();
        
        $responseMessage["statusCode"] = STATUS_OK;
        $responseMessage["response"]['status'] = true;
        $responseMessage["response"]['message'] = config('mts-config.surveys.user_survey_status');
        return $responseMessage;
    }

}
