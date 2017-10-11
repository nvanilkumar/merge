<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\Validator;
use App\Services\SurveyService;

class SurveyController extends Controller
{

    protected $_validator;
    protected $request;
    protected $surveyService;

    public function __construct(Validator $validator, Request $request, SurveyService $surveyService)
    {
        $this->_validator = $validator;
        $this->request = $request;
        $this->surveyService = $surveyService;
    }
    
    /**
     * @SWG\Get(
     *     path="surveys/list",
     *     summary="Get the survey list",
     *     description="To get the user specific active survey list",
     *     produces= {"application/json"},
     *     tags={"Surveys"},
      @SWG\Parameter(
     *         name="user_id",
     *         in="query",
     *         description="login user id",
     *         required=true,
     *         type="integer",
     *         @SWG\Items(type="string"),
     *         collectionFormat="multi"
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         description="survey list",
     *     ),
     *     @SWG\Response(
     *         response="404",
     *         description="No data available",
     *     )
     *      
     *      
     * )
     */
    public function index()
    {
        $this->_validator->validate("eventListValidations");
        $details = $this->surveyService->getSurveys();
        return $this->success($details);
    }
    
    /**
     * @SWG\Post(
     *     path="surveys/status",
     *     summary="To Change the user related survey status",
     *     description="To Change the user related survey status",
     *     produces= {"application/json"},
     *     tags={"Surveys"},
     *  @SWG\Parameter(
     *         name="user_id",
     *         in="formData",
     *         description="login user id",
     *         required=true,
     *         type="integer",
     *         @SWG\Items(type="string"),
     *         collectionFormat="multi"
     *     ),
     *  @SWG\Parameter(
     *         name="status",
     *         in="formData",
     *         description="event related user status",
     *         required=true,
     *         type="string",
     *         @SWG\Items(type="string"),
     *         collectionFormat="multi"
     *     ),
     *  @SWG\Parameter(
     *         name="survey_id",
     *         in="formData",
     *         description="Selected Survey Id",
     *         required=true,
     *         type="integer",
     *         @SWG\Items(type="string"),
     *         collectionFormat="multi"
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         description="updated status",
     *     ),
     *     @SWG\Response(
     *         response="404",
     *         description="Invalid user id or survey id details",
     *     )
     *      
     *      
     * )
     */
    public function surveyUserStatus()
    {
        $this->_validator->validate("surveyUserStatusValidations");
        $details = $this->surveyService->changeSurveyUserStatus();
        return $this->success($details);
    }
}
