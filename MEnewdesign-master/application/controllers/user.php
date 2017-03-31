<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
/**
 * Default landing page controller
 *
 * @package		CodeIgniter
 * @author		Qison  Dev Team
 * @copyright	Copyright (c) 2015, MeraEvents.
 * @Version		Version 1.0
 * @Since       Class available since Release Version 1.0 
 * @Created     11-06-2015
 * @Last Modified On 25-06-2015
 * @Last Modified By Sridevi
 */
require_once(APPPATH . 'handlers/banner_handler.php');
require_once(APPPATH . 'handlers/state_handler.php');
require_once(APPPATH . 'handlers/city_handler.php');
require_once(APPPATH . 'handlers/category_handler.php');
require_once(APPPATH . 'handlers/event_handler.php');
require_once(APPPATH . 'handlers/search_handler.php');
require_once(APPPATH . 'handlers/blog_handler.php');
require_once(APPPATH . 'handlers/common_handler.php');
require_once(APPPATH . 'handlers/user_handler.php');

//require(APPPATH . 'libraries/facebook/src/Facebook/autoload.php');
class User extends CI_Controller {

//    var $bannerHandler;
//    var $stateHandler;
//    var $cityHandler;
//    var $categoryHandler;
//    var $subCategoryHandler;
//    var $eventHandler;
//    var $searchHandler;
//    var $blogHandler;
    var $commonHandler;
    var $userHandler;

//    var $user;
    public function __construct() {
        parent::__construct();
//        $this->bannerHandler = new Banner_handler();
//        $this->cityHandler = new City_handler();
//        $this->stateHandler = new State_handler();
//        $this->categoryHandler = new Category_handler();
//        $this->eventHandler = new Event_handler();
//        $this->searchHandler = new Search_handler();
//        $this->blogHandler = new Blog_handler();
        $this->commonHandler = new Common_handler();
        $this->userHandler = new User_handler();
        $this->load->helper('url');
        // Load facebook library and pass associative array which contains appId and secret key
        //$this->load->library('facebook/src/Facebook/Facebook', array('appId' => FB_APP_ID, 'secret' => FB_APP_SECRET));
// Get user's login information
        //$this->user = $this->facebook->getUser();
    }

    public function login() {
    	 $uid = $this->customsession->getUserId();
    	 $fbcode = $this->input->get('code');
    	 if($uid>0 && strlen($fbcode)>0 ){
    		echo "<script>window.opener.location.reload();
						window.close() ;</script>";
    	 }
    	$this->load->library('facebook');
        $data = array();
        $this->customsession->unSetData('userType');
        $inputArray = $this->input->get();
        $headerValues = $this->commonHandler->headerValues($inputArray);
        $data['countryList'] = array();
        $data = $headerValues;
        $data['categoryList'] = array();
        $data['email'] = $inputArray['email'];
        $data['moduleName'] = 'loginModule';
        $data['content'] = 'login_view';
        $data['pageName'] = 'login';
        $data['pageTitle'] = 'Login | Book tickets online for music concerts, live shows and professional events. Be informed about upcoming events in your city';
        //adding data to footer
        $footerValues = $this->commonHandler->footerValues();
        $data['categoryList'] = $footerValues['categoryList'];
        $data['cityList'] = $footerValues['cityList'];
        if (count($data['categoryList']) > 0) {
            $categoryListTemp = commonHelperGetIdArray($data['categoryList']);
            $data['defaultCategory'] = ($data['defaultCategoryId'] > 0 ) ? $categoryListTemp[$data['defaultCategoryId']]['name'] : LABEL_ALL_CATEGORIES;
        }
        
        $data['jsArray'] = array(
            $this->config->item('js_public_path') . 'jquery.validate',
            $this->config->item('js_angular_path') . 'cookies/angular-cookies',
            $this->config->item('js_angular_path') . 'md5/angular-md5',
            $this->config->item('js_angular_path') . 'user/loginModule',
            $this->config->item('js_angular_path').'user/controllers/loginControllers',
           // $this->config->item('js_angular_path') . 'user/controllers/loginControllers',
            $this->config->item('js_angular_path') . 'user/factories/loginFactory',
//                                 $this->config->item('js_angular_path') . 'user/filters/homeFilters.js', 
//                                 $this->config->item('js_angular_path') . 'user/directives/homeDirectives.js', 
            $this->config->item('js_angular_path') . 'common/commonModule',
        //    $this->config->item('js_angular_path') . 'common/services/cookieService',
          //  $this->config->item('js_angular_path') . 'common/controllers/countryController',
           // $this->config->item('js_public_path') . 'login',
            $this->config->item('js_public_path').'login',
            $this->config->item('js_angular_path') . 'user/controllers/resetPasswordControllers',
            $this->config->item('js_angular_path') . 'user/factories/resetPasswordFactory'
            );
        $user = $this->facebook->getUser();
    //
        if ($user) {
        //	print_r($user);exit;
        	try {
        		$data['user_profile'] = $this->facebook->api('/me');
        		$inputArray['accessToken'] = $this->facebook->getAccessToken();
        		$inputArray['type'] ='facebook'; 
        		$userresponse =$this->userHandler->login($inputArray);
        		$userdata['userresponse']=$userresponse;
        		//echo "<pre>";print_r($userdata);exit;
        		if($userresponse['status'] && $userresponse['response']['total']>0 ){
        			$redUrl = $this->input->get('redirect_url');
        		    $redirectUrl = (strlen($redUrl)>0)?site_url().$redUrl:$userresponse['response']['userData']['redirectUrl'];
        			if ($userresponse ['response'] ['userData'] ['socialLoginSignup'] == 1) {
        				echo '<script>
                                                var phoneCode = decodeURIComponent(getCookie("phoneCode"))||"+91";
	     					var username= ' . $userresponse['response']['userData']['name'] . ';
	     					var useremail= ' . $userresponse['response']['userData']['email'] . ';
	     					var usermobile= ' . $userresponse ['response']['userData']['mobile'] . ';
                                                var fbId= ' . isset($userresponse ['response']['userData']['facebookid'])? $userresponse ['response']['userData']['facebookid']:" " . ';
						  var wizrocket = {event:[], profile:[], account:[]};
					    wizrocket.account.push({"id": '.$this->config->item('wizrocket_id').'});
					    (function () {
					        var wzrk = document.createElement("script");
					        wzrk.type ="text/javascript";
					        wzrk.async = true;
					        wzrk.src = ("https:" == document.location.protocol ? "https://d2r1yp2w7bby2u.cloudfront.net" : "http://static.clevertap.com") + "/js/a.js";
					        var s = document.getElementsByTagName(""script")[0];
					        s.parentNode.insertBefore(wzrk, s);
					    })();
	     					wizrocket.event.push("User Signedup",{});
	     	      	 wizrocket.profile.push(
	     	      		        {
	     	      		        "Site": {
	     	      		        "Name": username,
	     	      		        "Email": useremail,
	     	      		        "Phone": phoneCode+usermobile,
                                        "FBID":fbId 
	     	      		        }});
	     				</script>';
        			}
        			echo "<script>
        					window.close();
        					var winurl = '".$redirectUrl."';
        							setTimeout(function(){
        								parent.window.opener.document.location.href = winurl;
        							});
				        	</script>";
        			}
        	} catch (FacebookApiException $e) {
        		$user = null;
        	}
        }
      	$redUrl= $this->input->get('redirect_url');
        if(strlen($redUrl)>0){
       $data['fbloginUrl'] =  $this->facebook->getLoginUrl(array(
        		'redirect_uri' => site_url().'login?redirect_url='.$redUrl,
        		'scope' => array("email")));
        }else{
        	$data['fbloginUrl'] =  $this->facebook->getLoginUrl(array(
        			'redirect_uri' => commonHelperGetPageUrl('user-login'),
        			'scope' => array("email")));
        }
        $data['jsTopArray']=array($this->config->item('js_public_path') . 'fbandgoogle');
        $this->load->view('templates/user_template', $data);
//        if ($this->user) {
//            $data['user_profile'] = $this->facebook->api('/me/');
//
//// Get logout url of facebook
//            $data['logout_url'] = $this->facebook->getLogoutUrl(array('next' => base_url() . 'index.php/oauth_login/logout'));
//
//// Send data to profile page
//            $this->load->view('profile', $data);
//        } else {
//
//// Store users facebook login url
//            $data['login_url'] = $this->facebook->getLoginUrl();
//            $this->load->view('login', $data);
//        }
    }

    public function logout() {
        $response = $this->userHandler->logout();
        //if ($response['status']) {
        header('Location:' . site_url());
        exit;
        //}
    }


    public function signup() {
    	$uid = $this->customsession->getUserId();
    	$fbcode = $this->input->get('code');
    	if($uid>0 && strlen($fbcode)>0 ){
    		echo "<script>window.opener.location.reload();
						window.close() ;</script>";
    	}
    	$this->load->library('facebook');
        $data = array();
        $inputArray = $this->input->get();
        $headerValues = $this->commonHandler->headerValues($inputArray);
        $data['countryList'] = array();
        $data = $headerValues;
        $data['categoryList'] = array();

        $data['moduleName'] = 'signupModule';
        $data['content'] = 'signup_view';
        $data['pageName'] = 'Signup';
        $data['pageTitle'] = 'Signup | Book tickets online for music concerts, live shows and professional events. Be informed about upcoming events in your city.';

        //adding data to footer
        $footerValues = $this->commonHandler->footerValues();
        $data['categoryList'] = $footerValues['categoryList'];
        $data['cityList'] = $footerValues['cityList'];
        if (count($data['categoryList']) > 0) {
            $categoryListTemp = commonHelperGetIdArray($data['categoryList']);
            $data['defaultCategory'] = ($data['defaultCategoryId'] > 0 ) ? $categoryListTemp[$data['defaultCategoryId']]['name'] : LABEL_ALL_CATEGORIES;
        }

        $data['cssArray'] = array($this->config->item('css_public_path') . 'angular/angular');
        $data['jsArray'] = array(
            $this->config->item('js_angular_path') . 'user/signupModule',
            $this->config->item('js_angular_path').'user/controllers/signupControllers',
           // $this->config->item('js_angular_path') . 'user/controllers/signupControllers',
            $this->config->item('js_angular_path') . 'common/httpCallModule',
            $this->config->item('js_angular_path') . 'common/services/httpCallService',
            $this->config->item('js_angular_path') . 'common/commonModule',
         //   $this->config->item('js_angular_path') . 'common/services/cookieService',
          //  $this->config->item('js_angular_path') . 'common/controllers/countryController',
            $this->config->item('js_angular_path') . 'md5/angular-md5',
            $this->config->item('js_public_path') . 'login');
         $data['jsTopArray']=array($this->config->item('js_public_path') . 'fbandgoogle');
         $user = $this->facebook->getUser();
        if ($user) {
        	try {
        		$data['user_profile'] = $this->facebook->api('/me');
        		$inputArray['accessToken'] = $this->facebook->getAccessToken();
        		$inputArray['type'] ='facebook'; 
        		$userresponse =$this->userHandler->login($inputArray);
        		$userdata['userresponse']=$userresponse;
        		if($userresponse['status'] && $userresponse['response']['total']>0 ){
        			$redUrl = $this->input->get('redirect_url');
        		    $redirectUrl = (strlen($redUrl)>0)?site_url().$redUrl:$userresponse['response']['userData']['redirectUrl'];
        			if ($userresponse ['response'] ['userData'] ['socialLoginSignup'] == 1) {
        				echo '<script>
                                             var phoneCode = decodeURIComponent(getCookie("phoneCode"))||"+91";
	     					var username= ' . $userresponse['response']['userData']['name'] . ';
	     					var useremail= ' . $userresponse['response']['userData']['email'] . ';
	     					var usermobile= ' . $userresponse ['response']['userData']['mobile'] . ';
                                                    var fbId= ' . isset($userresponse ['response']['userData']['facebookid'])? $userresponse ['response']['userData']['facebookid']:" " . ';
						  var wizrocket = {event:[], profile:[], account:[]};
					    wizrocket.account.push({"id": '.$this->config->item('wizrocket_id').'});
					    (function () {
					        var wzrk = document.createElement("script");
					        wzrk.type ="text/javascript";
					        wzrk.async = true;
					        wzrk.src = ("https:" == document.location.protocol ? "https://d2r1yp2w7bby2u.cloudfront.net" : "http://static.clevertap.com") + "/js/a.js";
					        var s = document.getElementsByTagName(""script")[0];
					        s.parentNode.insertBefore(wzrk, s);
					    })();
	     					wizrocket.event.push("User Signedup",{});
	     	      	 wizrocket.profile.push(
	     	      		        {
	     	      		        "Site": {
	     	      		        "Name": username,
	     	      		        "Email": useremail,
	     	      		        "Phone": phoneCode+usermobile,
                                        "FBID":fbId 
	     	      		        }});
	     				</script>';
        			}
        			echo "<script>
        					window.close();
        					var winurl = '".$redirectUrl."';
        							setTimeout(function(){
        								parent.window.opener.document.location.href = winurl;
        							});
				        	</script>";
        			}
        	} catch (FacebookApiException $e) {
        		$user = null;
        	}
        }
      	$redUrl= $this->input->get('redirect_url');
        if(strlen($redUrl)>0){
       $data['fbloginUrl'] =  $this->facebook->getLoginUrl(array(
        		'redirect_uri' => site_url().'login?redirect_url='.$redUrl,
        		'scope' => array("email")));
        }else{
        	$data['fbloginUrl'] =  $this->facebook->getLoginUrl(array(
        			'redirect_uri' => commonHelperGetPageUrl('user-login'),
        			'scope' => array("email")));
        }
        $this->load->view('templates/user_template', $data);
    }

    function activationLink($token) { //after activation email link clik 
        $inputArray['token'] = $token;
        $sucess = $this->userHandler->activationLink($inputArray);
        $message = $sucess['response']['messages']['0'];
        $this->customsession->setData('message', $message);
        redirect(commonHelperGetPageUrl('user-login'));
    }

    public function resendActivationLink() {
        $data = array();
        $inputArray = $this->input->get();
        $headerValues = $this->commonHandler->headerValues($inputArray);
        $data['countryList'] = array();
        $data = $headerValues;
        $data['categoryList'] = array();
        $footerValues = $this->commonHandler->footerValues();
        $data['categoryList'] = $footerValues['categoryList'];
        $data['email'] = $inputArray['email'];
        $data['moduleName'] = 'resendlinkModule';
        $data['content'] = 'login_activation_view';
        $data['pageName'] = 'resend activationlink';
        $data['pageTitle'] = 'resend activationlink';

        $data['jsArray'] = array(
            $this->config->item('js_angular_path') . 'user/resendlinkModule',
            $this->config->item('js_angular_path') . 'user/controllers/resendlinkControllers',
            $this->config->item('js_angular_path') . 'user/factories/resendlinkFactory',
            $this->config->item('js_angular_path') . 'common/commonModule',
       //     $this->config->item('js_angular_path') . 'common/services/cookieService',
           // $this->config->item('js_angular_path') . 'common/controllers/countryController',
            $this->config->item('js_public_path') . 'login',
            $this->config->item('js_public_path') . 'fbandgoogle');
        $this->load->view('templates/user_template', $data);
    }

    public function changePassword($code) {
        $inputArray['code'] =$code;
        $activationCodeStatus = $this->userHandler->validateActivationCode($inputArray);
        $errorMessage = $activationCodeStatus['response']['messages'];
        $data = $this->commonHandler->headerValues($inputArray);

        $data['categoryList'] = array();
        $footerValues = $this->commonHandler->footerValues();
        $data['categoryList'] = $footerValues['categoryList'];
        $data['content'] = 'changePassword_view';
        $data['moduleName'] = 'changePasswordModule';
        $data['pageName'] = 'login';
        $data['pageTitle'] = 'Login';
        $data['jsArray'] = array($this->config->item('js_public_path') . 'main',
            $this->config->item('js_angular_path') . 'common/commonModule',
          //  $this->config->item('js_angular_path') . 'common/services/cookieService',
            $this->config->item('js_angular_path') . 'common/controllers/countryController',
            $this->config->item('js_public_path') . 'changePassword',
            $this->config->item('js_angular_path') . 'user/changePasswordModule',
            $this->config->item('js_angular_path') . 'user/controllers/changePasswordControllers',
            $this->config->item('js_angular_path') . 'user/factories/changePasswordFactory');
//
//        $data['cssArray'] = array($this->config->item('css_public_path') . 'dashboard.css',
//            $this->config->item('css_public_path') . 'me-font.css',
//            $this->config->item('css_public_path') . 'common.css');

        if (!$activationCodeStatus['status'] == TRUE) {
            $data['verificationErrorMessage'] = $errorMessage;
        }
        $this->load->view('templates/user_template', $data);
    }
    
    public function fblogin(){
    	$this->load->library('facebook'); // Automatically picks appId and secret from config
    	$user = $this->facebook->getUser();
    	if ($user) {
    		try {
    			$data['user_profile'] = $this->facebook->api('/me');
    			print_r($data['user_profile']);
    		} catch (FacebookApiException $e) {
    			$user = null;
    		}
    	}else {
    		// Solves first time login issue. (Issue: #10)
    		//$this->facebook->destroySession();
    	}
    	
    	if ($user) {
    	
    		$data['logout_url'] = site_url('welcome/logout'); // Logs off application
    		// OR
    		// Logs off FB!
    		// $data['logout_url'] = $this->facebook->getLogoutUrl();
    	
    	} else {
    		$data['login_url'] = $this->facebook->getLoginUrl(array(
    				'redirect_uri' => site_url('welcome/login'),
    				'scope' => array("email") // permissions here
    		));
    	}
    	$this->load->view('fblogin',$data);
    }

}
