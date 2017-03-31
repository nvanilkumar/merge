<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

require_once(APPPATH . 'handlers/seodata_handler.php');

class NoPage extends CI_Controller {
    
     public function __construct() {
        parent::__construct();
        $this->seoHandler = new Seodata_handler();
     }
     
      public function index() { 
         
          $url = commonHelperGetPageUrl('home');
          
          $seoKeys = $paramArray = array();
          $param1 = $param2 = $param3 = '';
          
          $param1 = $this->uri->segment(1);
          $param2 = $this->uri->segment(2);
          $param3 = $this->uri->segment(3);
          $param4 = $this->uri->segment(4);
          
          if(isset($param1) && $param1 != ''){
              $paramArray[] = $param1;
          }
          if(isset($param2) && $param2 != ''){
              $paramArray[] = $param2;
          }
          if(isset($param3) && $param3 != ''){
              $paramArray[] = $param3;
          }
          if(isset($param4) && $param4 != ''){
              $paramArray[] = $param4;
          }
          //only one param
          $seoArray['url'] = $paramArray['0'];

          if(count($paramArray) > 1){
              
              for ($i = 1; $i < count($paramArray); $i++) {
                    $seoArray['url'] .= '/'.$paramArray[$i];
                }
              $seoArray['searchRegex'] = 1;
          }
        
          $seoKeys = $this->seoHandler->getSeoData($seoArray);//print_r($seoKeys);
          
          if(count($seoKeys['response']['seoData']) > 0){
              
            $seoData = array();
            $mappingtype = $controller = $method = '';
            $seoData = $seoKeys['response']['seoData'][0];
            //get type include/redirect
            $mappingtype = $seoData['mappingtype'];
            //get type of controller - home/microsite
            $controller = $seoData['pagetype'];
            if ($controller != '') {
                $loadControl = 'controllers/' . $controller . '.php';
                require_once(APPPATH . $loadControl);
            }
            //get mapping method
            $method = $seoData['mappingurl'];
            //unserialize the params
            $paramsList = unserialize($seoData['params']);
            
            if($mappingtype == 'redirect' && $method == ''){
                redirect($url);
            }

            if ($mappingtype == 'include') {
                //for microsites
                if ($controller == 'microsite') {
                    //create an object
                    $Obj = new microsite();

                    $paramsInput['name'] = $paramsList['name'];
                    if (isset($paramsList['param'])) {
                        $paramsInput['param'] = strtolower($param2);
                    }

                    if (count($paramsInput) > 1) {
                        $msresponse = $Obj->$method($paramsInput['name'], $paramsInput['param']);
                    } else {
                        $msresponse = $Obj->$method($paramsInput['name']);
                    }
                }
                //for cities or categories or both
                else if ($controller == 'home') {

                    $Obj = new home();

                    $paramsInput['id'] = $paramsList['id'];
                    if (isset($paramsList['lookuptype'])) {
                        $paramsInput['lookuptype'] = $paramsList['lookuptype'];
                    }
                    if (count($paramsInput) == 1) {
                        $homeresponse = $Obj->$method($paramsInput['id']);
                    } else {
                        if (count($paramArray) == 2) {
//                            if (strtolower($param2) == "trade-shows") {
//                                $paramData = "trade shows";
//                            } else {
                                $paramData = strtolower($param2);
                            //}
                        } else {
                            for ($i = 1; $i < count($paramArray); $i++) {
                                $paramData[] = strtolower($paramArray[$i]);
                            }
                        }
                        $homeresponse = $Obj->$method($paramsInput['id'], $paramData, $paramsInput['lookuptype']);
                    }
                } else {
                    //if ($controller == 'other')
                }
            } else if ($mappingtype == 'redirect') {
                //redirect to the specific url
                redirect($url . $method);
            } else {
                redirect($url);
            }
        }
        else
        {
            redirect($url);
        }
    }    
}

?>