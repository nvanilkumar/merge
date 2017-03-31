<?php

class Handler {

    static $CI;

    function __construct() {

        Handler::$CI = & get_instance();
        Handler::$CI->load->library('form_validation');        
    }

    //function to create response from given inputs 
    function createResponse($status,$message=array(),$statusCode,$total="",$responseName="",$responseDataArray=""){
                $output['status'] = $status;

                if(isset($responseDataArray))
                {
                    if(strlen($responseName)>0)
                    $output['response'][$responseName] = $responseDataArray;  
                }

               if(!is_array($message))
                    $output['response']['messages'][] =$message;  
                    else
                    $output['response']['messages'] =$message; 

                if(is_integer($total)){//To also check for total as 0
                   $output['response']['total'] = $total;                   

                }
                $output['statusCode'] = $statusCode;
                
                return $output;
    }
    

}
