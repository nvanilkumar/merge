<?php

namespace App\Models;

use DB;

class SurveysModel extends CommonModel
{

    public function getSurveys()
    {
        $surveys = DB::table('surveys')
                ->orderBy('survey_end_date', 'desc')
                ->get();
        return $surveys;
    }

    /**
     * To get the surveys list
     * @return type
     */
    public function getUserSurveys()
    {
        $select = "select surveys.*,statuslookup.status_name
                   from surveys 
                   inner join survey_users on surveys.survey_id = survey_users.survey_id 
                   inner join statuslookup on  statuslookup.status_id = survey_users.user_response_status 
                   where (survey_users.user_id = :user_id and surveys.survey_end_date >= :survey_end_date
                   and statuslookup.status_name =:status_name
                   ) 
                   order by surveys.survey_end_date desc";
         
        $surveys = DB::select(DB::raw($select), $this->where);

        if (count($surveys) == 0) {
            return NULL;
        }
        return $surveys;
    }
    
    /**
     * To get the user related survey list status
     * @return type
     */
    public function getUserSurveyStatus()
    {
        $select = "select count(user_response_status) as statuscount, MAX(status_name) AS status_name 
                   from surveys 
                   inner join survey_users on surveys.survey_id = survey_users.survey_id 
                   inner join statuslookup on  statuslookup.status_id = survey_users.user_response_status 
                   where (survey_users.user_id = :user_id and surveys.survey_end_date >= :survey_end_date ) 
                   group by user_response_status";
         
        $surveys = DB::select(DB::raw($select), $this->where);

        if (count($surveys) == 0) {
            return NULL;
        }
        return $surveys;
    }

}
