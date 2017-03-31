<?php

/**
 * Seo data related business logic will be defined in this class
 *
 * @package		CodeIgniter
 * @author		Qison  Dev Team
 * @copyright	Copyright (c) 2015, MeraEvents.
 * @Version		Version 1.0
 * @Since       Class available since Release Version 1.0 
 * @Created     8-02-2016
 * @Last Modified 8-02-2016
 */
require_once (APPPATH . 'handlers/handler.php');

class Seodata_handler extends Handler {

    var $ci;

    public function __construct() {
        parent::__construct();
        $this->ci = parent::$CI;
        $this->ci->load->model('Seodata_model');
    }

    public function getSeoData($inputArray) { 
        $this->ci->form_validation->reset_form_rules();
        $this->ci->form_validation->pass_array($inputArray);
        $this->ci->form_validation->set_rules('url', 'Seo Url', 'required_strict');
        if ($this->ci->form_validation->run() === FALSE) {
            $response = $this->ci->form_validation->get_errors('message');
            $output['status'] = FALSE;
            $output['response']['messages'] = $response['message'];
            $output['statusCode'] = STATUS_BAD_REQUEST;
            return $output;
        }
        $this->ci->Seodata_model->resetVariable();
        
        $this->seotitle = "seotitle";
    	$this->seokeywords = "seokeywords";
    	$this->seodescription = "seodescription";
        $this->canonicalurl = "canonicalurl";
    	$this->url = "url";
    	$this->pagedescription = "pagedescription";
        $where=array();
        $select['seotitle'] = $this->ci->Seodata_model->seotitle;
        $select['seokeywords'] = $this->ci->Seodata_model->seokeywords;
        $select['seodescription'] = $this->ci->Seodata_model->seodescription;
        $select['canonicalurl'] = $this->ci->Seodata_model->canonicalurl;
        $select['url'] = $this->ci->Seodata_model->url;
        $select['pagedescription'] = $this->ci->Seodata_model->pagedescription;
        $select['mappingurl'] = $this->ci->Seodata_model->mappingurl;
        $select['pagetype'] = $this->ci->Seodata_model->pagetype;
        $select['mappingtype'] = $this->ci->Seodata_model->mappingtype;
        $select['params'] = $this->ci->Seodata_model->params;
        if(isset($inputArray['url']) && strlen($inputArray['url']) > 0){
            $where[$this->ci->Seodata_model->url] = $inputArray['url']; //for exact search
            $where[$this->ci->Seodata_model->deleted] = 0;
        }
        
        $this->ci->Seodata_model->setSelect($select);
        if(isset($where)){ 
            $this->ci->Seodata_model->setWhere($where);
        }
//        $this->ci->Seodata_model->setRecords(1);
        $seoDataList = $this->ci->Seodata_model->custom_query($inputArray['url']);
            //$seoDataList = $this->ci->Seodata_model->get();
        if((count($seoDataList) == 0) && isset($inputArray['searchRegex']) && $inputArray['searchRegex']){
            
            $params = array();$url = '';
            $params = explode('/', $inputArray['url']);$count = count($params)-1;
            
          $url = '(^'.$params['0'];
            if(count($params) == 1){
                $url .= '/{1}[a-zA-Z\-]+)$';
            }else{
                for ($i = 0; $i < $count; $i++) {
                    $url .= '/[a-zA-Z\-]+';
                }
                $url .= ')$';
            }
            $whre[$this->ci->Seodata_model->url] = $url; 
            $seoDataList = $this->ci->Seodata_model->get_regex_data($whre);
            //echo $this->ci->db->last_query();exit;
        }
        
        if (count($seoDataList) > 0) {
            $output['status'] = true;
            $output['response']['seoData'] = $seoDataList;
            $output['response']['messages'] = '';
            $output['response']['total'] = 1;
            $output['statusCode'] = STATUS_OK;
            return $output;
        }
        $output['status'] = true;
        $output['response']['messages'][] = STATUS_NO_DATA;
        $output['statusCode'] = STATUS_OK;
        $output['response']['total'] = 0;
        return $output;
    }
    
    }
