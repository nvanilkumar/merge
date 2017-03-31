<?php

defined('BASEPATH') or exit('No direct script access allowed');
require_once (APPPATH . 'handlers/handler.php');

class customsession {

    private $ci;
    private $handler;
    private $accessToken;
	
    public function __construct() {
        session_start();
        $this->ci = & get_instance();
        $this->ci->config->load('solrconfig');
        $this->solrUrl = $this->ci->config->item('solrUrl');
        $this->accessToken=0;
    }

    public function setData($key,$value="",$multyArrayKey = '') 
	{
		if(strlen($key) > 1)
		{
			if(strlen($multyArrayKey) > 0)
				$_SESSION[$multyArrayKey][$key] = $value;
			else
				$_SESSION[$key] = $value;
			return true;		
		}
			return FALSE;
	
	}

	public function getData($key,$multyArrayKey="") 
	{
		if(strlen($key) > 0 ) 
		{
				if(strlen($multyArrayKey) > 0 && isset($_SESSION[$multyArrayKey][$key]))
				{
					return $_SESSION[$multyArrayKey][$key];
				}
				else if(isset($_SESSION[$key]))
					return $_SESSION[$key];
				
		}
			return false;
	}
	
	public function unSetData($key) 
	{

		if(strlen($key) > 1 ) 
		{
				unset($_SESSION[$key]);
				return true;
		}
			return false;
	}
	
	public function getUserId() 
	{
            if(isset($_SESSION['userId']) && $_SESSION['userId']>0){  // if userid session is set then return userid
			return $_SESSION['userId'];
            }else if($this->accessToken > 0){
                return $this->accessToken;
            } else
			return FALSE;
	}
	
	public function destroy() 
	{
		//unset($_SESSION['userId']);
		foreach ($_SESSION as $key=>$value)
		{
			if (isset($_SESSION[$key]))
				unset($_SESSION[$key]);
		}
		return true;
	
	}
	
	public function loginCheck($resType = "json") 
	{
		$this->handler = new Handler();
		$userId = $this->getUserId();
		if(!$userId > 0)
		{
			if($resType == "json")
			{
				return $this->handler->createResponse(false,array(ERROR_NOT_AUTHORIZED),STATUS_INVALID_SESSION,"","","");
			}
			else
			{
				redirect(commonHelperGetPageUrl('home'), 'refresh');
			}
		}	
		return true;
	
	}
        
    public function setAccessToken($value){
        $this->accessToken=$value;
    }
    public function getAccessToken(){
        return $this->accessToken;
    }
}
