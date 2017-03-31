<?php
if(!defined('BASEPATH')) exit('No direct script access allowed');
if(!function_exists('verifyAccess'))
{
    function verifyAccess() 
    {
        $CI =& get_instance();
        $error=array();
        
        $CI->load->library('resource');
        $status=$CI->resource->verifyAccessToken();
        
        if(!$status){
            $error['status'] = 'false';
            $error['errorDescription'] = ERROR_INVALID_ACCESS;
             
        } else{
            $error['status'] = 'true';
        }
        
        return $error;
        
    }
}