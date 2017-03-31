<?php

require_once (APPPATH . 'handlers/handler.php');

class Verificationtoken_handler extends Handler {

    var $ci;

    public function __construct() {
        parent::__construct();
        $this->ci = parent::$CI;
        $this->ci->load->model('Verificationtoken_model');
    }

    function create($userId, $type, $tokenType,$token='',$expirayDate='') {
        if($token!=''){
        $rand=$token;
        }else{
        $rand = random_string($tokenType, 8);
        
        }

        $expirayDate = allTimeFormats('+24 hours',11);
        if(!empty($expirayDate)){
            $expirayDate =  allTimeFormats('+168 hours',11);
        }
        $createToken = array();
        $this->ci->Verificationtoken_model->resetVariable();	
        $createToken[$this->ci->Verificationtoken_model->token] = $rand;
        $createToken[$this->ci->Verificationtoken_model->type] = $type;
        $createToken[$this->ci->Verificationtoken_model->expirationdate] = $expirayDate;
        $createToken[$this->ci->Verificationtoken_model->userid] = $userId;
        $this->ci->Verificationtoken_model->setInsertUpdateData($createToken);
        $token = $this->ci->Verificationtoken_model->insert_data();
        if ($token) {
            $output['status'] = TRUE;
            $output["response"]['token'] = $rand;
            $output['statusCode'] = STATUS_OK;
            $output["response"]['messages'][] = '';
            return $output;
        } else {
            $output['status'] = FALSE;
            $output["response"]['messages'][] = ERROR_SOMETHING_WENT_WRONG;
            $output['statusCode'] = STATUS_SERVER_ERROR;
            return $output;
        }
    }

    function details($token) {
        $this->ci->Verificationtoken_model->resetVariable();
        $selectInput['id'] = $this->ci->Verificationtoken_model->id;
        $selectInput['token'] = $this->ci->Verificationtoken_model->token;
        $selectInput['expirationdate'] = $this->ci->Verificationtoken_model->expirationdate;
        $selectInput['userid'] = $this->ci->Verificationtoken_model->userid;
        $selectInput['used'] = $this->ci->Verificationtoken_model->used;
        $this->ci->Verificationtoken_model->setSelect($selectInput);
        $where[$this->ci->Verificationtoken_model->token] = str_replace(' ', '', $token);
        $where[$this->ci->Verificationtoken_model->used] = 0;
        $where[$this->ci->Verificationtoken_model->deleted] = 0;
        $this->ci->Verificationtoken_model->setWhere($where);
        $tokenDetails = $this->ci->Verificationtoken_model->get();
        if ($tokenDetails) {
            $output['status'] = TRUE;
            $output['response']['details'] = $tokenDetails[0];
            $output['response']['total']=1;			
            $output['statusCode'] = STATUS_OK;
            $output["response"]['messages'][] = array();
            return $output;
        } else {
            $output['status'] = TRUE;
            $output['response']['messages'][] = ERROR_INVALID_VERIFICATION_STRING;//Token might been used/not availble in db
	    $output['response']['total']=0;
            $output['statusCode'] = STATUS_OK;
            return $output;
        }
    }

    function update($tokenId) {
        $updateToken['used'] = '1';
        $this->ci->Verificationtoken_model->resetVariable();
        $where = array($this->ci->Verificationtoken_model->id => $tokenId);
        $this->ci->Verificationtoken_model->setInsertUpdateData($updateToken);
        $this->ci->Verificationtoken_model->setWhere($where);
        $response = $this->ci->Verificationtoken_model->update_data();
        if ($response) {
            $output['status'] = TRUE;
            $output["response"]["messages"][] = USER_TOKEN_UPDATE;
            $output['statusCode'] = STATUS_UPDATED;
            return $output;
        } else {
            $output['status'] = FALSE;
            $output["response"]["messages"][] = ERROR_SOMETHING_WENT_WRONG;
            $output['statusCode'] = STATUS_SERVER_ERROR;
            return $output;
        }
    }

}

?>