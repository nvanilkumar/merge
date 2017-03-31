<?php

require_once (APPPATH . 'handlers/handler.php');
require_once(APPPATH . 'handlers/solr_handler.php');

class Tag_handler extends Handler {

    var $ci;

    public function __construct() {
        parent::__construct();
        $this->ci = parent::$CI;
        $this->ci->load->model('Tag_model');
    }

    public function getTags($inputArray) {

        $validationStatus = $this->validateTag($inputArray);
        if ($validationStatus['error'] == true) {
            $output['status'] = FALSE;
            $output['response']['messages'] = $validationStatus['message'];
            $output['statusCode'] = STATUS_BAD_REQUEST;
            return $output;
        }
          //Feacth the solr search results
        $solrHandler = new Solr_handler(); 
        if (isset($inputArray['keyword'])) {
            $inputs['keyword'] = $inputArray['keyword'];
        }
        
        if (!isset($inputArray['limit'])) {
            $inputs['limit'] = 5;
        }else{
            $inputs['limit'] = $inputArray['limit'];
        }
         $solrResults = $solrHandler->getSolrTags($inputs);
         
        if ((isset($solrResults["response"]["error"])) && $solrResults["response"]["error"] == true) {
            return $solrResults;
        }
         $tags=$solrResults['response']['tags'];
          if (count($tags) > 0) {
            $output['status'] = TRUE;
            $output['response']['tags'] = $tags;
            $output['response']['messages'] = array();			
            $output['response']['total'] = count($tags);
			if(isset($inputArray['limit']))
			{
				 $output['response']['limit'] = $inputArray['limit'];
			}
            $output['statusCode'] = STATUS_OK;
            return $output;
          }else {
            $output['status'] = FALSE;
            $output['response']['messages'][] = ERROR_NO_TAGS;
            $output['response']['total'] = 0;
            $output['statusCode'] = STATUS_NO_DATA;
            return $output;
        }          
    }

    //validating the input parameters
    public function validateTag($inputs) {
        $this->ci->form_validation->pass_array($inputs);
		//The tag keyword field includes words and _ , @ , & , - , . , \\ , /		
        $this->ci->form_validation->set_rules('keyword', 'keyword', 'keyWordRule|required_strict');
         $this->ci->form_validation->set_rules('limit', 'Limit', 'is_natural_no_zero');
        if ($this->ci->form_validation->run() === FALSE) {
            $error_messages = $this->ci->form_validation->get_errors('message');
            return $error_messages;
        }
    }

    
    
    
//  Add event Tags

    public function addTag($data) {
        $validationStatus = $this->addTagValidation($data);
        if ($validationStatus['error'] == TRUE) {
            $output['status'] = FALSE;
            $output['response']['messages'] = $validationStatus['message'];
            $output['statusCode'] = STATUS_BAD_REQUEST;
            return $output;
        }

        $tagData=$oldTags=$newTags=$tags=array();
        
        if(!is_array($data['tags']) || empty($data['tags'])){
            $output['status'] = FALSE;
            $output['response']['messages'][] = ERROR_TAGS_VALUE;
            $output['statusCode'] = STATUS_BAD_REQUEST;
            return $output;
        }
        
        // validation of tags data
        foreach ($data['tags'] as $tagValidKey => $tagValidValue) {
            if(!is_numeric($tagValidValue['id']) || $tagValidValue['id']<0 || preg_match('/^[\w_@&\s\-\.\\\/]*$/', $tagValidValue['tag'])){
            $output['status'] = FALSE;
            $output['response']['messages'][] = ERROR_INVALID_TAG_VALUE;
            $output['statusCode'] = STATUS_INVALID;
            return $output;
            }
        }
        foreach ($data['tags'] as $tagKey => $tagValue) {
         // add new tags into tag table which have 0 "zero" ids   
            if($tagValue['id']==0){
                $tagData['name']=$tagValue['tag'];
                $tagData['status']= 1;
                $this->ci->Tag_model->resetVariable();
                // checking tag name is availabale or not
                $selectTagInput['id'] = $this->ci->Tag_model->id;
                $selectTagInput['name'] = $this->ci->Tag_model->name;
                $this->ci->Tag_model->setSelect($selectTagInput);
                $where[$this->ci->Tag_model->name] = $tagValue['tag'];
                $this->ci->Tag_model->setWhere($where); 
                $tagDetails = $this->ci->Tag_model->get();
                if(empty($tagDetails)){
                $this->ci->Tag_model->setInsertUpdateData($tagData);
                $newTags[$tagKey]['id']=$this->ci->Tag_model->insert_data();
                $newTags[$tagKey]['tag']=$tagValue['tag'];
                }else{
                 $newTags[$tagKey]['id']=$tagDetails[0]['id'];  
                 $newTags[$tagKey]['tag']=$tagDetails[0]['name'];  
                }
            }else{
                $oldTags[$tagKey]['id']=$tagValue['id'];
                $oldTags[$tagKey]['tag']=$tagValue['tag'];
            }
        }
        $tags=  array_merge($oldTags,$newTags);
        $output['status'] = TRUE;
        $output['response']['tags']=$tags;
        $output["response"]["messages"] = array(); 
        $output['statusCode'] = STATUS_CREATED;
        return $output;
    }
    
 
    // Add tag  Validations
     public function addTagValidation($inputs) {
        $errorMessages = array();
        $this->ci->form_validation->pass_array($inputs);
        
        $this->ci->form_validation->set_rules('tags','tags','required_strict');
       
         if ($this->ci->form_validation->run() === FALSE) {
            $errorMessages = $this->ci->form_validation->get_errors();
            return $errorMessages;
        }

        $errorMessages['error'] = FALSE;
        return $errorMessages;
    }
    

}

?>