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

    public function getUserSurveys()
    {
        $select = "select users.*,statuslookup.status_name
                   from surveys 
                   inner join survey_users on surveys.survey_id = survey_users.survey_id 
                   inner join users on users.user_id = survey_users.user_id
                   inner join statuslookup on  statuslookup.status_id = survey_users.user_response_status 
                   where (surveys.survey_id = :survey_id) 
                   order by surveys.survey_end_date desc";
         
        $surveys = DB::select(DB::raw($select), $this->where);

        if (count($surveys) == 0) {
            return NULL;
        }
        return $surveys;
    }

}
