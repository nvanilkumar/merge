<?php

/**
 * User related business logic will be defined in this class
 *
 * @package		CodeIgniter
 * @author		Qison  Dev Team
 * @copyright	Copyright (c) 2015, MeraEvents.
 * @Version		Version 1.0
 * @Since       Class available since Release Version 1.0 
 * @Created     18-06-2015
 * @Last Modified 18-06-2015
 */
require_once(APPPATH . 'handlers/handler.php');
require_once (APPPATH . 'handlers/verificationtoken_handler.php');
require_once(APPPATH . 'handlers/file_handler.php');
require_once(APPPATH . 'handlers/email_handler.php');
require_once(APPPATH . 'handlers/country_handler.php');
require_once(APPPATH . 'handlers/state_handler.php');
require_once(APPPATH . 'handlers/city_handler.php');
require_once(APPPATH . 'handlers/event_handler.php');
require_once(APPPATH . 'handlers/organizer_handler.php');
require_once(APPPATH . 'handlers/promoter_handler.php');
require_once(APPPATH . 'handlers/collaborator_handler.php');
require_once(APPPATH . 'handlers/userdevicedetail_handler.php');
require_once(APPPATH . 'handlers/messagetemplate_handler.php');

class User_handler extends Handler {

    var $fileHandler;
    var $ci;
    var $verificationtokenHandler;
    var $emailHandler;
    var $countryHandler;
    var $stateHandler;
    var $cityHandler;
    var $organizerHandler;
    var $promoterHandler;
    var $collaboratorHandler;
    var $userDeviceDetailHanlder;
    var $dashboardHandler;
    var $profileHandler;

    public function __construct() {
        parent::__construct();
        $this->ci = parent::$CI;
        $this->ci->load->model('User_model');
        $this->ci->load->library('email');
        $this->verificationtokenHandler = new Verificationtoken_handler();
        $this->fileHandler = new File_handler();
        $this->emailHandler = new Email_handler();
        $this->countryHandler = new Country_handler();
        $this->stateHandler = new State_handler();
        $this->cityHandler = new City_handler();
    }

    public function login($inputArray) {

        $this->ci->form_validation->reset_form_rules();
        $this->ci->form_validation->pass_array($inputArray);
        //check login type(me,facebook,twitter,google)
        $this->ci->form_validation->set_rules('type', 'type', 'required_strict|is_valid_type[login]');
        if ($this->ci->form_validation->run() == FALSE) {
            $response = $this->ci->form_validation->get_errors();
            $output['status'] = FALSE;
            $output['response']['messages'] = $response['message'];
            $output['statusCode'] = STATUS_BAD_REQUEST;
            return $output;
        }
        //get type data
        $type = strtolower($inputArray['type']);
        //based on type check inputs passed(me-->email & password required,others-->accessToken)
        $this->ci->form_validation->pass_array($inputArray);
        if (strcmp($type, 'me') == 0) {
            $this->ci->form_validation->set_rules('email', 'email', 'required_strict');
            $this->ci->form_validation->set_rules('password', 'password', 'required_strict');
        } else {
            $this->ci->form_validation->set_rules('accessToken', 'accessToken', 'required_strict');
        }
        $this->ci->form_validation->set_rules('deviceId', 'Device Id', 'alphanumeric|min_length[1]|max_length[60]');
        $this->ci->form_validation->set_rules('deviceType', 'Device Type', 'specialname|min_length[1]|max_length[50]');
        if ($this->ci->form_validation->run() == FALSE) {
            $response = $this->ci->form_validation->get_errors();
            $output['status'] = FALSE;
            $output['response']['messages'] = $response['message'];
            $output['statusCode'] = STATUS_BAD_REQUEST;
            return $output;
        }
        //set default values
        $email = $password = $name = $phone = $socialId = $picture = '';
        //check the string in md5 or not
        if (isValidMd5($inputArray['password'])) {
            $inputArray['password'] = $inputArray['password'];
        } else {
            $inputArray['password'] = encryptPassword($inputArray['password']);
        }
        //fb type then get user data from access token(graph api)
        if (strcmp($type, 'facebook') == 0) {
            $fbResposne = $this->fbDataFromAccessToken($inputArray['accessToken']);
            if ($fbResposne['status'] && $fbResposne['response']['total'] > 0) {
                $user = $fbResposne['response']['fbData'];

                $name = $user->name;
                $socialId = $user->id;
                $email = !empty($user->email) ? $user->email : str_replace(' ', '_', $name) . '.' . $socialId . '@facebook.com';
                $picture = $user->picture->data->url;
            } else {
                return $fbResposne;
            }
        }
        //google type get user data from access token(curl call to googleapis/oauth)
        else if (strcmp($type, 'google') == 0) { 
            $userResponse = $this->googleDataFromAccessToken($inputArray['accessToken']);
            if ($userResponse['status'] && $userResponse['response']['total'] > 0) {
                $user = $userResponse['response']['googleUserData'];
                $name = $user->name;
                $socialId = $user->id;
                $email = !empty($user->email) ? $user->email : str_replace(' ', '_', $name) . '.' . $socialId . '@gmail.com';
                //$phone = $user->phone;
                $picture = $user->picture;
            } else {
                return $userResponse;
            }
        }
        //twitter type get user data from access token(twitter library-->verify_credentials)
        else if (strcmp($type, 'twitter') == 0) {
            $userResponse = $this->twitterDataFromAccessToken($inputArray['accessToken']);
            if ($userResponse['status'] && $userResponse['response']['total'] > 0) {
                $user = $userResponse['response']['twitterData'];
                $name = $user->name;
                $socialId = $user->id;
                $email = str_replace(' ', '_', $name) . '.' . $socialId . '@twitter.com';
                $picture = $user->profile_image_url;
            } else {
                return $userResponse;
            }
        }
        //ME type login strore email and password
        else {
            $email = $inputArray['email'];
            $password = $inputArray['password'];
        }
        //check user exists by passing email for social logins and email,password for normal login
        if (!empty($email) && !empty($password)) {
            $input['email'] = $email;
            if (!isset($inputArray['guestLogin']) || !$inputArray['guestLogin']) {
                $input['password'] = $password;
            }
        } elseif (!empty($email)) {
            $input['email'] = $email;
        }
        //get user details
        $input['loginType'] = 'userName';
        $userData = $this->getUserData($input);
        $signupResponse = array();
        //data recieved
        if ($userData['status']) {
            //if user present check status and return proper messages and if its social login then check if social id exits,profileimage if not then update socialid,profile image...
            if ($userData['response']['total'] > 0) {

                //active user so if its social login check and update socailid and profileimage if not available
                if ($userData['response']['userData']['status'] == 1 || ($userData['response']['userData']['status'] == 2 && $inputArray['guestLogin']) || ($userData['response']['userData']['status'] == 2 && in_array($type, array('google', 'facebook', 'twitter')))) {
                    $output['status'] = TRUE;
                    $output['response']['userData'] = $userData['response']['userData'];
                    $output['response']['messages'] = array();
                    $output['response']['total'] = ($userData['response']['total']);
                    $output['statusCode'] = STATUS_OK;
                    //check if its social login
                    if (in_array($type, array('google', 'facebook', 'twitter'))) {
                        if ($userData['response']['userData']['status'] == 2) {
                            $inputData['status'] = 1;
                        }
                        $inputData['type'] = $type;
                        if (empty($userData['response']['userData'][$type . 'id'])) {
                            $inputData['socialid'] = $socialId;
                        }
                        if ($userData['response']['userData']['profileimagefileid'] == $this->ci->config->item('default_profile_image_id')) {
                            $inputData['profilepath'] = $picture;
                        }
                        $inputData['userid'] = $userData['response']['userData']['id'];
                        //calling method to update user data
                        if (isset($inputData['socailid']) || isset($inputData['profilepath'])) {
                            $updateSocialIdResponse = $this->updateSocialData($inputData);
                            if (!$updateSocialIdResponse['status']) {
                                return $updateSocialIdResponse;
                            }
                            $userData['response']['userData'][$type . 'id'] = $socialId;
                            $userData['response']['userData']['profileimagefilepath'] = $updateSocialIdResponse['response']['socialData']['profileimagefilepath'];
                            $userData['response']['userData']['profileimagefileid'] = $updateSocialIdResponse['response']['socialData']['profileimagefileid'];
                        } else if (isset($inputData['status'])) {
                            $this->updateSocialData($inputData);
                        }
                    }

                    //Saving User device details
                    if (isset($inputArray['deviceId'])) {
                        $this->userDeviceDetailHanlder = new Userdevicedetail_handler();
                        $inputArray['userId'] = $userData['response']['userData']['id'];
                        $userDeviceData = $this->userDeviceDetailHanlder->manipulateUserDeviceDetails($inputArray);
                        if (isset($userDeviceData['status']) && !$userDeviceData['status']) {
                            return $userDeviceData;
                        }
                    }
                    $userData['response']['userData']['guestLogin'] = ($inputArray['guestLogin'] != "" ? $inputArray['guestLogin'] : 0);
                    //
                    //Set userid and profileimagepath in session
                    $this->setSession($userData['response']['userData']);

                    $redirectionUrl = $this->getuserRedirectionUrl();
                    $output['response']['userData']['redirectUrl'] = $redirectionUrl;
                    return $output;
                }
                //inactive user so contact MeraEvents message
                else if ($userData['response']['userData']['status'] == 0) {
                    $output['status'] = FALSE;
                    $output['response']['messages'][] = ERROR_CONTACT_ME;
                    $output['response']['total'] = 0;
                    $output['statusCode'] = STATUS_CONFLICT;
                    return $output;
                }
                //notverified user so not activated message
                else if ($userData['response']['userData']['status'] == 2) {
                    $resendActivation = $this->resendActivationLink($input);
                    if ($resendActivation['status']) {
                        $output['status'] = FALSE;
                        $output['response']['messages'][] = ERROR_NOT_ACTIVATED_EMAIL . ". " . EMAIL_SUCESS;
                        $output['response']['total'] = 0;
                        $output['statusCode'] = STATUS_INVALID;
                        return $output;
                    }
                }
            }
            //user is not available if its social login create user and then login user...
            elseif (in_array($type, array('google', 'facebook', 'twitter'))) {
                $userArray['email'] = $email;
                $userArray['name'] = $name;
                $userArray['password'] = md5(commonHelpergenerateRandomString());
                $userArray['phonenumber'] = $phone;
                $userArray[$type . 'id'] = $socialId;
                $signupResponse = $this->signup($userArray);
            } else {
                $output['status'] = FALSE;
                $output['response']['messages'][] = ERROR_INVALID_USER;
                $output['response']['total'] = 0;
                $output['statusCode'] = STATUS_INVALID_USER;
                return $output;
            }
            $signup = 0;
            //signup is successful and save profile image(in signup fileupload is available but not image saving from url so calling updateSocialData)
            if (count($signupResponse) > 0 && $signupResponse['status']) {
                $output['status'] = TRUE;
                // For WizRocket Push for signup Through Social Logins
                $signup = $signupResponse['response']['userData']['socialLoginSignup'];
                //  For WizRocket Push for signup Through Social Logins
                $output['response']['messages'] = array();
                $output['response']['total'] = 1;
                $inputArray['type'] = $type;
                $inputArray['profilepath'] = $picture;
                $inputArray['userid'] = $signupResponse['response']['userData']['id'];
                $updateSocialIdResponse = $this->updateSocialData($inputArray);
                if (!$updateSocialIdResponse['status']) {
                    return $updateSocialIdResponse;
                }
                //reset data after updating user
                $signupResponse['response']['userData'][$type . 'id'] = $socialId;
                $signupResponse['response']['userData']['profileimagefilepath'] = $updateSocialIdResponse['response']['socialData']['profileimagefilepath'];
                $signupResponse['response']['userData']['profileimagefileid'] = $updateSocialIdResponse['response']['socialData']['profileimagefileid'];
                $signupResponse['response']['userData']['guestLogin'] = $inputArray['guestLogin'];
                $signupResponse['response']['userData']['name'] = $userArray['name'];
                $signupResponse['response']['userData']['email'] = $userArray['email'];
                $signupResponse['response']['userData']['phone'] = $userArray['phonenumber'];
                
                $this->setSession($signupResponse['response']['userData']);
                $output['statusCode'] = 200;
                $signupResponse['response']['userData']['socialLoginSignup'] = $signup;
                $output['response']['userData'] = $signupResponse['response']['userData'];
                $redirectionUrl = $this->getuserRedirectionUrl();
                $output['response']['userData']['redirectUrl'] = $redirectionUrl; 
                return $output;
            }
            //return error messages while signup is happening if any
            elseif (count($signupResponse) > 0) {
                return $signupResponse;
            }
            //untrakable errors
            else {
                $output['status'] = FALSE;
                $output['response']['messages'][] = ERROR_SOMETHING_WENT_WRONG;
                $output['response']['total'] = 0;
                $output['statusCode'] = STATUS_SERVER_ERROR;
                return $output;
            }
        }
        //user does not exists
        else {
            return $userData;
        }
    }

    // Get UserDataByid for Eventsignup

    public function getEventsignupUserdata($inputArray) {

        $this->ci->form_validation->pass_array($inputArray);
        $this->ci->form_validation->set_rules('userId', 'userId', 'required_strict|is_natural_no_zero');
        if ($this->ci->form_validation->run() == FALSE) {
            $response = $this->ci->form_validation->get_errors();
            $output['status'] = FALSE;
            $output['response']['messages'] = $response['message'];
            $output['response']['total'] = 0;
            $output['statusCode'] = STATUS_BAD_REQUEST;
            return $output;
        }
        $userid = $inputArray['userId'];
        $this->ci->User_model->resetVariable();
        $selectInput['id'] = $this->ci->User_model->id;
        $selectInput['name'] = $this->ci->User_model->name;
        $selectInput['email'] = $this->ci->User_model->email;
        $selectInput['phone'] = $this->ci->User_model->phone;
        $selectInput['mobile'] = $this->ci->User_model->mobile;
        $this->ci->User_model->setSelect($selectInput);
        $where[$this->ci->User_model->deleted] = 0;
        $this->ci->User_model->setOrWhere($whereOr);
        $where[$this->ci->User_model->id] = $userid;

        //if (isset($password) || isset($userid) || isset($status)) {
        $this->ci->User_model->setWhere($where);
        $this->ci->User_model->setRecords(1);
        $userData = $this->ci->User_model->get();
        if (count($userData) == 0) {
            $output['status'] = TRUE;
            $output['response']['messages'][] = ERROR_NO_USER;
            $output['response']['total'] = 0;
            $output['statusCode'] = STATUS_INVALID_USER;
            return $output;
        }
        $output['status'] = TRUE;
        $output['response']['userData'] = $userData[0];
        $output['response']['messages'] = array();
        $output['response']['total'] = 1;
        $output['statusCode'] = STATUS_OK;
        return $output;
    }

    public function getUserData($inputArray) {
        $this->ci->form_validation->reset_form_rules();
        $this->ci->form_validation->pass_array($inputArray);
        $this->ci->form_validation->set_rules('email', 'email', 'valid_email');
        if (isset($inputArray['loginType'])) {
            $this->ci->form_validation->set_rules('email', 'email');
        }
        $this->ci->form_validation->set_rules('ownerId', 'owner Id', 'is_natural_no_zero');
        $this->ci->form_validation->set_rules('status', 'status', 'is_natural_no_zero');
        if ($this->ci->form_validation->run() == FALSE) {
            $response = $this->ci->form_validation->get_errors();
            $output['status'] = FALSE;
            $output['response']['messages'] = $response['message'];
            $output['response']['total'] = 0;
            $output['statusCode'] = STATUS_BAD_REQUEST;
            return $output;
        }
        $userid = '';
        if (isset($inputArray['ownerId'])) {
            $userid = $inputArray['ownerId'];
        }
        $status = '';
        if (isset($inputArray['status'])) {
            $status = $inputArray['status'];
        }
        $password = '';
        if (isset($inputArray['password'])) {
            $password = $inputArray['password'];
        }
        $organizerDataReq = false;
        if (isset($inputArray['organizerDataReq']) && $inputArray['organizerDataReq']) {
            $organizerDataReq = true;
        }
        $profileImageReq = true;
        if (isset($inputArray['profileImageReq']) && !$inputArray['profileImageReq']) {
            $profileImageReq = false;
        }
//$userData = $this->CI->userModel->isValidUser($input);
        $this->ci->User_model->resetVariable();
        $selectInput['id'] = $this->ci->User_model->id;
        $selectInput['name'] = $this->ci->User_model->name;
        $selectInput['email'] = $this->ci->User_model->email;
        $selectInput['password'] = $this->ci->User_model->password;
        $selectInput['phone'] = $this->ci->User_model->phone;
        $selectInput['mobile'] = $this->ci->User_model->mobile;
        $selectInput['company'] = $this->ci->User_model->company;
        $selectInput['facebookid'] = $this->ci->User_model->facebookid;
        $selectInput['googleid'] = $this->ci->User_model->googleid;
        $selectInput['twitterid'] = $this->ci->User_model->twitterid;
        $selectInput['profileimagefileid'] = $this->ci->User_model->profileimagefileid;
        $selectInput['status'] = $this->ci->User_model->status;

        $selectInput['address'] = $this->ci->User_model->address;
        $selectInput['pincode'] = $this->ci->User_model->pincode;
        $selectInput['countryid'] = $this->ci->User_model->countryid;
        $selectInput['stateid'] = $this->ci->User_model->stateid;
        $selectInput['cityid'] = $this->ci->User_model->cityid;
        $selectInput['isattendee'] = $this->ci->User_model->isattendee;
        $selectInput['usertype'] = $this->ci->User_model->usertype;

        $this->ci->User_model->setSelect($selectInput);
        $where[$this->ci->User_model->deleted] = 0;
        if (isset($inputArray['email']) && $inputArray['email'] != '') {
            $whereOr[$this->ci->User_model->email] = $inputArray['email'];
            $whereOr[$this->ci->User_model->username] = $inputArray['email'];
            $this->ci->User_model->setOrWhere($whereOr);
        }
        if (!empty($password)) {
            $where[$this->ci->User_model->password] = $password;
        }
        if (!empty($userid)) {
            $where[$this->ci->User_model->id] = $userid;
        }
        if (!empty($status)) {
            $where[$this->ci->User_model->status] = $status;
        }

        $this->ci->User_model->setWhere($where);
        $this->ci->User_model->setRecords(1);
        $userData = $this->ci->User_model->get();
        if (count($userData) == 0) {
            $output['status'] = TRUE;
            $output['response']['messages'][] = ERROR_NO_USER;
            $output['response']['total'] = 0;
            $output['statusCode'] = STATUS_INVALID_USER;
            return $output;
        }
        if ($profileImageReq) {
            $profileId = $this->ci->config->item('default_profile_image_id');
            if ($userData[0]['profileimagefileid'] > 0) {
                $profileId = $userData[0]['profileimagefileid'];
            }
            $userData[0]['profileimagefileid'] = $profileId;
            $userData[0]['profileimagefilepath'] = '';
            $profilePathResponse = $this->fileHandler->getFileData(array('id', array($profileId)));
            if ($profilePathResponse['status'] && $profilePathResponse['response']['total'] > 0) {
                $userData[0]['profileimagefilepath'] = $this->ci->config->item('images_content_cloud_path') . $profilePathResponse['response']['fileData'][0]['path'];
            }
        }
        if ($userData[0]['countryid'] > 0) {
            $countryInput['countryId'] = $userData[0]['countryid'];
            $countryData = $this->countryHandler->getCountryListById($countryInput);
        }
        if (isset($countryData) && $countryData['status'] && $countryData['response']['total'] > 0) {
            $userData[0]['country'] = $countryData['response']['detail']['name'];
            $userData[0]['countryCode'] = $countryData['response']['detail']['shortName'];
        }
        if ($userData[0]['stateid'] > 0) {
            $stateInput['stateId'] = $userData[0]['stateid'];
            $stateData = $this->stateHandler->getStateListById($stateInput);
        }
        if (isset($stateData) && $stateData['status'] && $stateData['response']['total'] > 0) {
            $userData[0]['state'] = $stateData['response']['stateList'][0]['name'];
        }
        if ($userData[0]['countryid'] > 0 && $userData[0]['cityid'] > 0) {
            $cityInput['countryId'] = $userData[0]['countryid'];
            $cityInput['cityId'] = $userData[0]['cityid'];
            $cityData = $this->cityHandler->getCityDetailById($cityInput);
        }
        if (isset($cityData) && $cityData['status'] && $cityData['response']['total'] > 0) {
            $userData[0]['city'] = $cityData['response']['detail']['name'];
        }
        if ($organizerDataReq && !empty($userid)) {
            $organizerHandler = new Organizer_handler();
            $inputOrg['userId'] = $userid;
            $organizerResponse = $organizerHandler->get($inputOrg);
        }
        if (isset($organizerResponse) && $organizerResponse['status'] && $organizerResponse['response']['total'] > 0) {
            $organizerData = $organizerResponse['response']['organizerDetails'];
        }
        $output['status'] = TRUE;
        $output['response']['userData'] = $userData[0];
        if (isset($organizerData)) {
            $output['response']['organizerData'] = $organizerData;
        }
        $output['response']['messages'] = array();
        $output['response']['total'] = 1;
        $output['statusCode'] = STATUS_OK;
        return $output;
    }

//    private function isValidUser($input) {
//        $select['userid'] = $this->ci->User_model->id;
//        $selectInput['email'] = $this->ci->User_model->email;
//        $selectInput['phone'] = $this->ci->User_model->phone;
//        $selectInput['mobile'] = $this->ci->User_model->mobile;
//        $this->ci->User_model->setSelect($select);
//        $whereOr[$this->ci->User_model->email] = $input['email'];
//        $whereOr[$this->ci->User_model->username] = $input['email'];
//        $this->ci->User_model->setOrWhere($whereOr);
//        $where[$this->ci->User_mode/menewl->status] = 1;
//        if (isset($input['password']) && strcmp($input['type'], 'ME') == 0) {
//            $where[$this->ci->User_model->password] = $input['password'];
//        }
//        $this->ci->User_model->setWhere($where);
//        $userData = $this->ci->User_model->get();
//        if (count($userData) == 0) {
//            $output['status'] = FALSE;
//            $output['response']['messages'] = ERROR_INVALID_USER;
//            $output['statusCode'] = 400;
//            return $output;
//        }
//        return $userData;
//    }
    function fbDataFromAccessToken($accessToken) {
        $userGraphUrl = "https://graph.facebook.com/me?access_token=" . $accessToken . "&fields=name,id,email,picture";
        $contents = file_get_contents($userGraphUrl);
        if (!$contents) {
            $output['status'] = FALSE;
            $output['response']['messages'][] = "Invalid access token";
            $output['response']['total'] = 0;
            $output['statusCode'] = STATUS_INVALID_INPUTS;
            return $output;
        }
        $data = json_decode($contents);
        $output['status'] = TRUE;
        $output['response']['fbData'] = $data;
        $output['response']['messages'] = array();
        $output['response']['total'] = 1;
        $output['statusCode'] = STATUS_OK;
        return $output;
    }

    //getting details from g+ access token
    function googleDataFromAccessToken($accessToken) {
        try {
            $url = "https://www.googleapis.com/oauth2/v1/userinfo";
            $curl = curl_init($url);
            curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
            $curlheader[0] = "Authorization: Bearer " . $accessToken;
            curl_setopt($curl, CURLOPT_HTTPHEADER, $curlheader);
            $json_response = curl_exec($curl);
            if (!$json_response) {
                $output['status'] = FALSE;
                $output['response']['messages'] = curl_error($curl);
                $output['statusCode'] = 507;
                return $output;
            } else {
                $responseObj = json_decode($json_response);
                if (isset($responseObj->error)) {
                    //var_dump($responseObj);
                    $output['status'] = FALSE;
                    $output['response']['messages'] = $responseObj->error->errors[0]->message;
                    $output['response']['total'] = 0;
                    $output['statusCode'] = 507;
                    return $output;
                }
            }
            curl_close($curl);
        } catch (Exception $e) {
            echo $e->getMessage();
        }
        $output['status'] = TRUE;
        $output['response']['messages'] = array();
        $output['response']['total'] = 1;
        $output['response']['googleUserData'] = $responseObj;
        return $output;
    }

    public function twitterDataFromAccessToken($accessToken) {
        require_once(APPPATH . 'libraries/twitteroauth/twitteroauth.php');
        
        /* Create a TwitterOauth object with consumer/user tokens. */
        $connection = new TwitterOAuth($this->ci->config->item('twitter_consumer_key'), $this->ci->config->item('twitter_secret_key'), $accessToken['oauth_token'], $accessToken['oauth_token_secret']);
        /* If method is set change API call made. Test is called by default. */
        $twcontent = $connection->get('account/verify_credentials');
        if (isset($twcontent->errors)) {
            $output['status'] = FALSE;
            $output['response']['messages'] = $twcontent->errors[0]->message;
            $output['response']['total'] = 0;
            $output['statusCode'] = 507;
            return $output;
        }
        $output['status'] = TRUE;
        $output['response']['messages'] = array();
        $output['response']['total'] = 1;
        $output['response']['twitterData'] = $twcontent;
        return $output;
    }

    function setSession($userData) {
        $this->ci->customsession->destroy();
        //  print_r($userData);
        $this->ci->customsession->setData('userId', $userData['id']);
        $this->ci->customsession->setData('userEmail', $userData['email']);
        $this->ci->customsession->setData('userName', $userData['name']);
        $this->ci->customsession->setData('userMobile', $userData['mobile']);
        $this->ci->customsession->setData('userCity', $userData['city']);
        $this->ci->customsession->setData('userState', $userData['state']);
        $this->ci->customsession->setData('userCountry', $userData['country']);
        $this->ci->customsession->setData('profileImagePath', $userData['profileimagefilepath']);
        $this->ci->customsession->setData('isAttendee', $userData['isattendee']);
        $this->ci->customsession->setData('userType', $userData['usertype']);
        $this->ci->customsession->setData('isCollaborator', 1);
        $this->organizerHandler = new Organizer_handler();
        $this->promoterHandler = new Promoter_handler();
        $this->collaboratorHandler = new Collaborator_handler();

        //check to set isOrganizer Accesss
        $orgResponse = $this->organizerHandler->checkOrganizerAccess();
        if ($orgResponse['status'] == TRUE && (!isset($userData['guestLogin']) || $userData['guestLogin'] == 0))
            $this->ci->customsession->setData('isOrganizer', $orgResponse['response']['isOrganizer']);
        else
            $this->ci->customsession->setData('isOrganizer', 0);

        //check to set isPromoter Accesss
        $promoterResponse = $this->promoterHandler->checkPromoterAccess();
        if ($promoterResponse['status'] == TRUE && (!isset($userData['guestLogin']) || $userData['guestLogin'] == 0))
            $this->ci->customsession->setData('isPromoter', $promoterResponse['response']['isPromoter']);
        else
            $this->ci->customsession->setData('isPromoter', 0);

        //check to set isCollaborator Accesss
        $collaboratorResponse = $this->collaboratorHandler->checkCollaboratorAccess();
        if ($collaboratorResponse['status'] == TRUE && (!isset($userData['guestLogin']) || $userData['guestLogin'] == 0))
            $this->ci->customsession->setData('isCollaborator', $collaboratorResponse['response']['isCollaborator']);
        else
            $this->ci->customsession->setData('isCollaborator', 0);

        //check to set isGuestLogin Accesss   
        if (isset($userData['guestLogin']) && $userData['guestLogin'] == 1) {
            $this->ci->customsession->setData('isGuestLogin', 1);
        } else {
            $this->ci->customsession->setData('isGuestLogin', 0);
        }
        //check is global promoter
        $isGlobalPromoter=0;
        $inputCode['userid']=  getUserId();
        $codeResponse=$this->promoterHandler->getGlobalCode($inputCode);
        if($codeResponse['status'] && $codeResponse['response']['total']>0){
            $code=$codeResponse['response']['promoterList'][0]['code'];
            $isGlobalPromoter=1;
            $this->ci->customsession->setData('globalPromoterCode', $code);
        }
        $this->ci->customsession->setData('isGlobalPromoter', $isGlobalPromoter);
    }

    //signup user to app 
    public function signup($inputArray) {

        if (count($inputArray) == 0) {
            $output = parent::createResponse(FALSE, ERROR_INVALID_INPUT, STATUS_BAD_REQUEST);
            return $output;
        }

        $this->ci->form_validation->pass_array($inputArray);
        //checking validation using Group Validation (signup)        
        if ($this->ci->form_validation->run('signup') == FALSE) {
            $errorMsg = $this->ci->form_validation->get_errors();
            //creating response output
            $output = parent::createResponse(FALSE, $errorMsg['message'], STATUS_BAD_REQUEST);
            return $output;
        } else {
            //Setting Data for inserting or update
            $data['name'] = $inputArray['name'];
            $data['email'] = $data['username'] = $inputArray['email'];  //By default username and email will be same
            //check the string in md5 or not
            if (isValidMd5($inputArray['password'])) {
                $data['password'] = $inputArray['password'];
            } else {
                $data['password'] = encryptPassword($inputArray['password']);
            }
            $data['mobile'] = trim($inputArray['phonenumber']);
            $pos = strpos($inputArray['phonenumber'], trim($inputArray['countryphoneCode']), 0);
            if ($pos == 1) {
                $data['mobile'] = substr($data['mobile'], strlen($inputArray['countryphoneCode']) + 1);
            }
            $data['countryid'] = isset($inputArray['countryId']) ? $inputArray['countryId'] : "";
            $data['address'] = isset($inputArray['address']) ? $inputArray['address'] : "";
            $data['company'] = isset($inputArray['company']) ? $inputArray['company'] : "";
            $data['pincode'] = isset($inputArray['pincode']) ? $inputArray['pincode'] : "";
            $data['facebookid'] = isset($inputArray['facebookid']) ? $inputArray['facebookid'] : "";
            $data['googleid'] = isset($inputArray['googleid']) ? $inputArray['googleid'] : "";
            $data['twitterid'] = isset($inputArray['twitterid']) ? $inputArray['twitterid'] : "";
            $data['signupdate'] = allTimeFormats('', 11);
            $data['ipaddress'] = commonHelperGetClientIp();
            $socialLogin = 0;
            $socialLoginSignup = 0;
            //  default profile image file path
            $data['profileImageFileId'] = $profileImageFileId = $this->ci->config->item('default_profile_image_id');

            //fetching file path for profileImageFileId
            $fileReponse = $this->fileHandler->getFileData(array('id', array($data['profileImageFileId'])));
            if ($fileReponse['status'] == TRUE) {
                //if profileimage filepath is available set it else blank value
                // default profile image file path
                $profileImageFilePath = isset($fileReponse['response']['fileData'][0]['path']) ? $this->ci->config->item('images_content_cloud_path') . $fileReponse['response']['fileData'][0]['path'] : '';
            }

            $data['status'] = 2; // By default user status is notverified mode
            if (strlen($data['googleid']) > 0 || strlen($data['facebookid']) > 0 || strlen($data['twitterid']) > 0)
                $data['status'] = 1; // Using social signup user status is verified
            $socialLogin = 1;

            if (isset($inputArray['bookingSignup']) && $inputArray['bookingSignup'] == 'Yes') {
                $data['status'] = 1; // If the user logins from booking page
            }




            if (!$this->emailExist($data['email'])) {

                //setting data for inserting
                $this->ci->User_model->setInsertUpdateData($data);
                if ($socialLogin == 1) {
                    $socialLoginSignup = 1;
                }
                //executing insert query
                $response = $this->ci->User_model->insert_data();

                if ($response) {
                    if (isset($inputArray['deviceId'])) {
                        //Saving User device details
                        $this->userDeviceDetailHanlder = new Userdevicedetail_handler();
                        $inputArray['userId'] = $response;
                        $userDeviceData = $this->userDeviceDetailHanlder->manipulateUserDeviceDetails($inputArray);
                        if (isset($userDeviceData['status']) && !$userDeviceData['status']) {
                            return $userDeviceData;
                        }
                    }

                    $emailEnable = $this->ci->config->item('emailEnable');
                    /* Sending the reset password link to reset the guest login details */
                    if (isset($inputArray['bookingSignup']) && $inputArray['bookingSignup'] == 'Yes' && $emailEnable) {
                        $resetInput['email'] = $inputArray['email'];
                        $resetReturn = $this->resetPassword($resetInput);
                        if (!$resetReturn['status']) {
                            $output['status'] = FALSE;
                            $output["response"]['messages'][] = $resetReturn['response']['messages'];
                            $output['statusCode'] = STATUS_MAIL_NOT_SENT;
                            return $output;
                        }
                    }

                    //Sending mail
                    if ($emailEnable) {
                        $inputs['email'] = $inputArray['email'];
                        $inputs['callType'] = 'signup';
                        $sendEmailResponse = $this->resendActivationLink($inputs);
                        if (!$sendEmailResponse['status']) {
                            return $sendEmailResponse;
                        }
                    }

                    //uploading image if present
                    if (isset($_FILES['profileImage']["name"])) {
                        $uploadResponse = $this->uploadUserProfileImage($response);
                        if ($uploadResponse['status'] == TRUE) {
                            $profileImageFileId = $uploadResponse['response']['fileId'];
                            //$profileImageFilePath = $this->ci->config->item('images_content_cloud_path') . $uploadResponse['response']['filePath'];
                            $profileImageFilePath = $this->ci->config->item('images_cloud_path') . $uploadResponse['response']['filePath'];
                            //update profileImageFileId for the registered user
                            $where = array($this->ci->User_model->id => $response);
                            $userData['profileImageFileId'] = $profileImageFileId;
                            $this->ci->User_model->setInsertUpdateData($userData);
                            $this->ci->User_model->setWhere($where);
                            $userReponse = $this->ci->User_model->update_data();
                        }
                    }

                    //CHECK WHAT TO DO IF ERROR OCCURS WHILE FILE UPLOAD OR DURING PROFILE UPDATE
                    //if profile image successfully uploaded

                    $responseData = array('id' => $response, 'profileimagefileid' => $profileImageFileId, 'profileimagefilepath' => $profileImageFilePath, 'socialLoginSignup' => $socialLoginSignup);
                    //creating response output 

                    if (isset($inputArray['bookingSignup']) && $inputArray['bookingSignup'] == 'Yes') {
                        $loginInputArray['email'] = $data['email'];
                        $loginInputArray['password'] = $data['password'];
                        $loginInputArray['type'] = 'me';
                        $loginInputArray['guestLogin'] = 1;
                        $loginResponse = $this->login($loginInputArray);
                        $output['status'] = TRUE;
                        $output["response"]['userData'] = $loginResponse['response']['userData'];
                        $output["response"]['messages'][] = array();
                        $output['statusCode'] = STATUS_OK;
                        return $output;
                    }

                    $output = parent::createResponse(TRUE, SUCCESS_SIGNUP, STATUS_OK, 0, 'userData', $responseData);
                    return $output;
                } else {
                    //creating response output
                    $output = parent::createResponse(FALSE, ERROR_SOMETHING_WENT_WRONG, STATUS_SERVER_ERROR);
                    return $output;
                }
            } else {   //CHECK WHAT RESPONSE TO GIVE FOR THIS
                //creating response output
                $output = parent::createResponse(FALSE, "Email address already registered with us", STATUS_CONFLICT);
                return $output;
            }
        }
    }

    // function to uplaod userprofileimage -- its a common code
    function uploadUserProfileImage($userId) {

        $userFileData['fieldName'] = 'profileImage';
        $userImagePath = $this->ci->config->item('user_profile_path') . "$userId";
        $userFileData['upload_path'] = FCPATH . $userImagePath;
        $userFileData['allowed_types'] = IMAGE_EXTENTIONS;
        $userFileData['dbFilePath'] = $userImagePath . "/";
        $userFileData['dbFileType'] = FILE_TYPE_USERPROFILE; //check this
        $userFileData['folderId'] = $userId; //check this
        $bannerResponse = $this->fileHandler->doUpload($userFileData);
        return $bannerResponse;
    }

    //function to check if email already exists in user table
    function emailExist($email) {


        $selectInput = array();
        $selectInput['id'] = $this->ci->User_model->id;
        $this->ci->User_model->setSelect($selectInput);
        $where[$this->ci->User_model->email] = $email;
        $where[$this->ci->User_model->deleted] = 0;
        $this->ci->User_model->setWhere($where);
        $emailExists = $this->ci->User_model->get();
        if (count($emailExists) > 0)
            return TRUE;
        else
            return FALSE;
    }

    function resendActivationLink($inputArray) {
        $this->ci->form_validation->reset_form_rules();
        $this->ci->form_validation->pass_array($inputArray);
        $this->ci->form_validation->set_rules('email', 'email', 'required_strict|valid_email');
        if ($this->ci->form_validation->run() == FALSE) {
            $response = $this->ci->form_validation->get_errors();
            $output['status'] = FALSE;
            $output['response']['messages'] = $response['message'];
            $output['statusCode'] = STATUS_BAD_REQUEST;
            return $output;
        }
        $email = $inputArray['email'];
        $callType = isset($inputArray['callType']) ? $inputArray['callType'] : '';
        $userIdInfo = $this->getUserDetails($inputArray);
        if ($userIdInfo['response']['total'] > 0) {
            $type = 'useractivation';
            $status = $userIdInfo['response']['userData'][0]['status'];
            $userId = $userIdInfo['response']['userData'][0]['id'];
            $Name = $userIdInfo['response']['userData'][0]['name'];
            if ($status == '2') {
                // tokenType is either numbes or alpha or alpha numeric
                //$tokenType='alnum';
                //$tokenType='alpha';
                $tokenType = 'numeric';
                $token = $this->verificationtokenHandler->create($userId, $type, $tokenType);
                if ($token['status'] == TRUE) {
                    $token = $token['response']['token'];
                    $templateInputs['type'] = TYPE_SIGNUP;

                    $templateInputs['mode'] = 'email';
                    //Sending sign up mail
                    $this->messagetemplateHandler = new Messagetemplate_handler();
                    $templateDetails = $this->messagetemplateHandler->getTemplateDetail($templateInputs);
                    $templateId = $templateDetails['response']['templateDetail']['id'];
                    $from = $templateDetails['response']['templateDetail']['fromemailid'];
                    $templateMessage = $templateDetails['response']['templateDetail']['template'];
                    $to = $email;
                    $subject = SUBJECT_RESEND_ACTIVATION;
                    $data['signupMessage'] = '';
                    $data['resendMessage'] = '';
                    if (strcmp($callType, 'signup') == 0) {
                        $subject = "Your MeraEvents.com Account details";
                        $data['signupMessage'] = "Thank you for signing up!";
                    } else {
                        $data['resendMessage'] = "Click the below link to activate your account";
                    }

                    $this->ci->load->library('parser');
                    $data['link'] = commonHelperGetPageUrl('user-activationLink', $token);
                    $data['userName'] = ucfirst($Name);
                    $data['year'] = allTimeFormats(' ', 17);
                    $data['supportLink'] = commonHelperGetPageUrl('contactUs');
                    $message = $this->ci->parser->parse_string($templateMessage, $data, TRUE);
                    $sentmessageInputs['messageid'] = $templateId;
                    $sentmessageInputs['userId'] = $userId;
                    $email = $this->emailHandler->sendEmail($from, $to, $subject, $message, '', '', '', $sentmessageInputs);
                    if ($email['status'] == FALSE) {
                        $output['status'] = FALSE;
                        $output["response"]['messages'][] = ERROR_EMAIL_NOT_SENT;
                        $output['statusCode'] = STATUS_MAIL_NOT_SENT;
                        return $output;
                    }
                    $output['status'] = TRUE;
                    $output["response"]["token"] = $token;
                    $output["response"]['messages'][] = RESEND_ACTIVATION_MAIL;
                    $output['statusCode'] = STATUS_OK;
                    return $output;
                } else {
                    $output['status'] = FALSE;
                    $output["response"]["messages"][] = ERROR_SOMETHING_WENT_WRONG;
                    $output['statusCode'] = STATUS_SERVER_ERROR;
                    return $output;
                }
            } else if ($status == '1') {
                $output['status'] = TRUE;
                $output["response"]['messages'][] = ACCOUNT_ALREADY_ACTIVATED_ACTIVATION_PAGE;
                $output['statusCode'] = STATUS_ACCOUNT_ALREADY_ACTIVATED;
                return $output;
            } else {
                $output['status'] = FALSE;
                $output["response"]['messages'][] = ERROR_CONTACT_ME;
                $output['statusCode'] = STATUS_CONFLICT;
                return $output;
            }
        } else {
            return $userIdInfo;
        }
    }

    function activationLink($inputArray) {
        $this->ci->form_validation->pass_array($inputArray);
        $this->ci->form_validation->set_rules('token', 'Token', 'required_strict');
        if ($this->ci->form_validation->run() == FALSE) {
            $response = $this->ci->form_validation->get_errors();
            $output['status'] = FALSE;
            $output['response']['messages'] = $response['message'];
            $output['statusCode'] = STATUS_BAD_REQUEST;
            return $output;
        } else {
            $token = $inputArray['token'];
            $tokenDetails = $this->verificationtokenHandler->details($token);
            if ($tokenDetails['status'] == TRUE) {
                $tokenId = $tokenDetails['response']['details']['id'];
                $token = $tokenDetails['response']['details']['token'];
                $expdate = $tokenDetails['response']['details']['expirationdate'];
                $userId = $tokenDetails['response']['details']['userid'];
                $used = $tokenDetails['response']['details']['used'];
                $currentdate = allTimeFormats('', 11);
                if ($tokenDetails['response']['total'] == 0) {
                    $output['status'] = FALSE;
                    $output["response"]["messages"][] = ACCOUNT_ALREADY_ACTIVATED;
                    $output['statusCode'] = STATUS_ACCOUNT_ALREADY_ACTIVATED;
                    return $output;
                }
                if ($currentdate < $expdate) {
                    $updateToken = $this->verificationtokenHandler->update($tokenId);
                    if ($updateToken['status'] == TRUE) {
                        $updateStatus = $this->updateStatus($userId);
                        $output['status'] = TRUE;
                        $output["response"]["messages"][] = ACCOUNT_ACTIVATED;
                        $output['statusCode'] = STATUS_OK;
                        return $output;
                    } else {
                        $output['status'] = FALSE;
                        $output["response"]["messages"][] = ERROR_SOMETHING_WENT_WRONG;
                        $output["response"]["total"] = 0;
                        $output['statusCode'] = STATUS_SERVER_ERROR;
                        return $output;
                    }
                } else {
                    $output['status'] = FALSE;
                    $output["response"]["messages"][] = ERROR_LINK_EXPIRED;
                    $output['statusCode'] = STATUS_PRECONDITION_FAILED;
                    return $output;
                }
            } else {
                $output['status'] = FALSE;
                $output["response"]["messages"][] = ERROR_SOMETHING_WENT_WRONG;
                $output['statusCode'] = STATUS_SERVER_ERROR;
                return $output;
            }
        }
    }

    function updateStatus($userId) {
        $updateStatus['status'] = '1';
        $where = array($this->ci->User_model->id => $userId);
        $this->ci->User_model->setInsertUpdateData($updateStatus);
        $this->ci->User_model->setWhere($where);
        $response = $this->ci->User_model->update_data();
        if ($response) {
            $output['status'] = TRUE;
            $output["response"]["messages"][] = USER_STATUS_UPDATE;
            $output['statusCode'] = STATUS_OK;
            return $output;
        } else {
            $output['status'] = FALSE;
            $output["response"]["messages"][] = ERROR_SOMETHING_WENT_WRONG;
            $output["response"]["total"] = 0;
            $output['statusCode'] = STATUS_SERVER_ERROR;
            return $output;
        }
    }

    function updateattendeeStatus($inputArray) {
        $this->ci->User_model->resetVariable();
        $updateStatus['isattendee'] = $inputArray['isAttendee'];
        $where = array($this->ci->User_model->id => $inputArray['id']);
        $this->ci->User_model->setInsertUpdateData($updateStatus);
        $this->ci->User_model->setWhere($where);
        $response = $this->ci->User_model->update_data();
        if ($response) {
            $output['status'] = TRUE;
            $output["response"]["messages"][] = USER_STATUS_UPDATE;
            $output['statusCode'] = STATUS_OK;
            return $output;
        } else {
            $output['status'] = FALSE;
            $output["response"]["messages"][] = ERROR_SOMETHING_WENT_WRONG;
            $output["response"]["total"] = 0;
            $output['statusCode'] = STATUS_SERVER_ERROR;
            return $output;
        }
    }

    public function resetPassword($inputArray) {
        //Getting user data to verify whether user's email existed and activated
        $email = $inputArray['email'];
        $this->ci->form_validation->pass_array($inputArray);
        $this->ci->form_validation->set_rules('email', 'email', 'required_strict|valid_email');
        if ($this->ci->form_validation->run() == FALSE) {
            $response = $this->ci->form_validation->get_errors();
            $output['status'] = FALSE;
            $output['response']['messages'] = $response['message'];
            $output['statusCode'] = STATUS_BAD_REQUEST;
            return $output;
        }
        $userData = $this->getUserData($inputArray);
        if ($userData['response']['total'] == 0) {
            $output['status'] = FALSE;
            $output['response']['messages'][] = ERROR_NOT_REGESTRED;
            $output['statusCode'] = STATUS_PRECONDITION_FAILED;
            return $output;
        } else {
//              $output['status'] = FALSE;
//            $output['response']['messages'][] = ERROR_NOT_ACTIVATED_EMAIL;
//            $output['statusCode'] = STATUS_PRECONDITION_FAILED;
//            return $output;

            $userName = $userData['response']['userData']['name'];
            $userId = $userData['response']['userData']['id'];
            $userEmail = $userData['response']['userData']['email'];

            //Sending mail to the specified email 
            $type = 'password';
            $tokenType = 'alnum';
            $token = $this->verificationtokenHandler->create($userId, $type, $tokenType);
            if ($token['status'] == TRUE) {
                $token = $token['response']['token'];
                $templateInputs['type'] = TYPE_FORGET_PASSWORD;                
                $templateInputs['mode'] = 'email';
                //Sending reset password mail
                $this->messagetemplateHandler = new Messagetemplate_handler();
                $templateDetails = $this->messagetemplateHandler->getTemplateDetail($templateInputs);
                $templateId = $templateDetails['response']['templateDetail']['id'];
                $from = $templateDetails['response']['templateDetail']['fromemailid'];
                $templateMessage = $templateDetails['response']['templateDetail']['template'];
                $to = $email;
                $subject = SUBJECT_FORGET_PASSWORD;
                ;
                $data['userName'] = ucfirst($userName);
                $data['link'] = commonHelperGetPageUrl('user-changePassword', $token);
                $data['staticPath'] = $this->ci->config->item('images_static_path');
                $data['year'] = allTimeFormats(' ', 17);
                $data['supportLink'] = commonHelperGetPageUrl('contactUs');
                $this->ci->load->library('parser');
                $message = $this->ci->parser->parse_string($templateMessage, $data, TRUE);
                $sentmessageInputs['messageid'] = $templateId;
                $sentmessageInputs['userId'] = $userId;
                $email = $this->emailHandler->sendEmail($from, $to, $subject, $message, '', '', '', $sentmessageInputs);

                if ($email) {
                    if ($userData['response']['userData']['status'] == 0 || $userData['response']['userData']['status'] == 2) {
                        $output['status'] = TRUE;
                        $output['response']['messages'][] = NOT_ACTIVATE_PASSWORD_MAIL;
                        $output['statusCode'] = STATUS_OK;
                        return $output;
                    } else {
                        $output['status'] = TRUE;
                        $output['response']['messages'][] = SUCCESS_MAIL_SENT;
                        $output['statusCode'] = STATUS_OK;
                        return $output;
                    }
                } else {
                    $output['status'] = FALSE;
                    $output['response']['messages'][] = ERROR_EMAIL_NOT_SENT;
                    $output['statusCode'] = STATUS_MAIL_NOT_SENT;
                    return $output;
                }
            } else {
                $output['status'] = FALSE;
                $output["response"]["messages"][] = ERROR_SOMETHING_WENT_WRONG;
                $output['statusCode'] = STATUS_BAD_REQUEST;
                return $output;
            }
        }
    }

    function changePassword($inputArray) {
        $validationStatus = $this->validateFields($inputArray);
        if ($validationStatus['status'] == FALSE) {
            return $validationStatus;
        }
        $token = $inputArray['verificationString'];
        $tokenArray['code'] = $token;
        $tokenDetails = $this->verificationtokenHandler->details($tokenArray['code']);
        if ($tokenDetails['response']['total'] == 0) {
            return $tokenDetails;
        }
        $activationCodeStatus = $this->validateActivationCode($tokenArray);
        if ($activationCodeStatus['status'] == TRUE) {
            $userId = $tokenDetails['response']['details']['userid'];
            $tokenId = $tokenDetails['response']['details']['id'];
            $passwordUpdation = $this->updatePassword($inputArray, $userId, $tokenId);
            return $passwordUpdation;
        } else {
            return $activationCodeStatus;
        }
    }

    function validateActivationCode($tokenArray) {
        // print_r($tokenArray);exit;
        $token = $tokenArray['code'];
        $tokenDetails = $this->verificationtokenHandler->details($token);
        if ($tokenDetails['response']['total'] == 0) {
            $output['response']['messages'][] = ERROR_LINK_EXPIRED;
            $output['statusCode'] = STATUS_PRECONDITION_FAILED;
            $output['status'] = FALSE;
            return $output;
        }
        $tokenId = $tokenDetails['response']['details']['id'];
        $expdate = strtotime($tokenDetails['response']['details']['expirationdate']);
        $used = $tokenDetails['response']['details']['used'];
        $currentdate = strtotime(allTimeFormats('', 11));
        if ($currentdate < $expdate && $used == 0) {
            $output['status'] = TRUE;
            $output['statusCode'] = STATUS_OK;
            return $output;
        } else {
            $output['response']['messages'][] = ERROR_LINK_EXPIRED;
            $output['statusCode'] = STATUS_PRECONDITION_FAILED;
            $output['status'] = FALSE;
            return $output;
        }
    }

    function validateFields($inputArray) {
        $password1 = $inputArray['password'];
        $password2 = $inputArray['confirmPassword'];

        $this->ci->form_validation->pass_array($inputArray);
        $this->ci->form_validation->set_rules('password', 'password', 'password|required_strict');
        $this->ci->form_validation->set_rules('confirmPassword', 'confirmPassword', 'password|required_strict');

        if (isset($inputArray['verificationString'])) {
            $verificationString = $inputArray['verificationString'];
            $this->ci->form_validation->set_rules('verificationString', 'verificationString', 'required_strict');
        }

        if ($this->ci->form_validation->run() == FALSE) {
            $response = $this->ci->form_validation->get_errors();
            $output['status'] = FALSE;
            $output['response']['messages'] = $response['message'];
            if ($password1 != $password2) {
                $output['response']['messages'][] = ERROR_PASSWORD_NOT_MATCH;
            }
            $output['statusCode'] = STATUS_BAD_REQUEST;
            return $output;
        }
        if ($password1 != $password2) {
            $output['status'] = FALSE;
            $output['response']['messages'][] = ERROR_PASSWORD_NOT_MATCH;
            $output['statusCode'] = STATUS_BAD_REQUEST;
            return $output;
        }
        $output['status'] = TRUE;
        return $output;
    }

    function updatePassword($inputArray, $userId, $tokenId) {
        $validationStatus = $this->validateFields($inputArray);
        if ($validationStatus['status'] == FALSE) {
            return $validationStatus;
        }
        $this->ci->User_model->resetVariable();
        $password = $inputArray['password'];
        $this->ci->User_model->setTableName('user');
        $where[$this->ci->User_model->id] = $userId;
        $this->ci->User_model->setWhere($where);
        // $updateArray = array('password' => encryptPassword($password));
        $updateArray[$this->ci->User_model->password] = encryptPassword($password);
        $input['userIdList'] = $userId;
        $checkActivation = $this->getUserDetails($input);
        if ($checkActivation && ($checkActivation['response']['userData']['status'] == 0 || $checkActivation['response']['userData']['status'] == 2)) {
            $updateArray[$this->ci->User_model->status] = 1;
        }
        $this->ci->User_model->setInsertUpdateData($updateArray);
        $updateUser = $this->ci->User_model->update_data();
        if ($updateUser) {
            if (!empty($tokenId)) {
                //Changing used=1 in verifcationtoken table
                $updateToken = $this->verificationtokenHandler->update($tokenId);
                if ($updateToken['status'] == FALSE) {
                    $output['status'] = FALSE;
                    $output['response']['messages'][] = ERROR_SOMETHING_WENT_WRONG;
                    $output['statusCode'] = STATUS_BAD_REQUEST;
                    return $output;
                }
            }
            $output['status'] = TRUE;
            $output['response']['messages'][] = SUCCESS_UPDATED_PASSWORD;
            $output['statusCode'] = STATUS_UPDATED;
            return $output;
        } else {
            $output['status'] = FALSE;
            $output['response']['messages'][] = ERROR_SOMETHING_WENT_WRONG;
            $output['statusCode'] = STATUS_BAD_REQUEST;
            return $output;
        }
    }

    //to update facebookid or twitterid or googleid or profileimagefileid
    function updateSocialData($inputArray) {
        $this->ci->form_validation->reset_form_rules();
        $this->ci->form_validation->pass_array($inputArray);
        $this->ci->form_validation->set_rules('type', 'type', 'required_strict|is_valid_type[login]');
        $this->ci->form_validation->set_rules('socialid', 'socialid', 'is_natural_no_zero');
        $this->ci->form_validation->set_rules('userid', 'userid', 'required_strict|is_natural_no_zero');
        if ($this->ci->form_validation->run() == FALSE) {
            $response = $this->ci->form_validation->get_errors();
            $output['status'] = FALSE;
            $output['response']['messages'] = $response['message'];
            $output['statusCode'] = STATUS_BAD_REQUEST;
            return $output;
        }
        $this->ci->User_model->resetVariable();
        $type = $inputArray['type'];
        $socialId = isset($inputArray['socialid']) ? $inputArray['socialid'] : '';
        $userId = $inputArray['userid'];
        $profilePath = isset($inputArray['profilepath']) ? $inputArray['profilepath'] : '';
        if (empty($profilePath) && empty($socialId) && !isset($inputArray['status'])) {
            $output['status'] = FALSE;
            $output['response']['messages'][] = ERROR_INVALID_INPUT;
            $output['response']['total'] = 0;
            $output['statusCode'] = STATUS_BAD_REQUEST;
            return $output;
        }
        $insertFileId = 0;
        $socialData = array();
        $profileResponse = array();
        if (!empty($profilePath)) {
            $data['type'] = 'userprofile';
            $data['source'] = $profilePath;
            $data['userid'] = $userId;
            $profileResponse = $this->fileHandler->save($data);
        }
        if (count($profileResponse) > 0 && $profileResponse['status']) {
            $insertFileId = $profileResponse['response']['fileId'];
            $socialData['profileimagefilepath'] = $this->ci->config->item('images_content_cloud_path') . $profileResponse['response']['filePath'];
            $socialData['profileimagefileid'] = $profileResponse['response']['fileId'];
        }
        $columnType = $type . 'id';
        if (!empty($socialId)) {
            $updateData[$this->ci->User_model->$columnType] = $socialId;
            $socialData[$columnType] = $socialId;
        }
        if ($insertFileId > 0) {
            $updateData[$this->ci->User_model->profileimagefileid] = $insertFileId;
        }
        if (isset($inputArray['status'])) {
            $updateData[$this->ci->User_model->status] = $inputArray['status'];
        }
        if (count($updateData) > 0) {
            $this->ci->User_model->setInsertUpdateData($updateData);
            $where[$this->ci->User_model->id] = $userId;
            $this->ci->User_model->setWhere($where);
            $updateStatus = $this->ci->User_model->update_data();
            if ($updateStatus) {
                $output['status'] = TRUE;
                $output['response']['socialData'] = $socialData;
                $output['response']['messages'] = array();
                $output['statusCode'] = STATUS_OK;
                return $output;
            }
            $output['status'] = FALSE;
            $output['response']['messages'][] = $this->ci->db->_error_messages();
            $output['statusCode'] = STATUS_BAD_REQUEST;
            return $output;
        } else {
            $output['status'] = FALSE;
            $output['response']['messages'][] = ERROR_SOMETHING_WENT_WRONG;
            $output['response']['total'] = 0;
            $output['statusCode'] = STATUS_SERVER_ERROR;
            return $output;
        }
    }

    //logout
    public function logout() {
        $status = false;

        $returnStatus = $this->ci->customsession->destroy();
        if ($returnStatus) {
            $status = true;
        } else {
            $output['status'] = FALSE;
            $output['response']['messages'][] = ERROR_NO_SESSION;
            $output['statusCode'] = 507;
            return $output;
        }
        $output['status'] = TRUE;
        $output['response']['loggedout'] = $status;
        $output['response']['messages'] = array();
        $output['statusCode'] = STATUS_OK;
        return $output;
    }

    public function getUserInfo($inputArray) {
        $this->ci->form_validation->pass_array($inputArray);
        $this->ci->form_validation->set_rules('userIds', 'UserId', 'is_natural_no_zero');
        if ($this->ci->form_validation->run() == FALSE) {
            $response = $this->ci->form_validation->get_errors();
            $output['status'] = FALSE;
            $output['response']['messages'] = $response['message'];
            $output['response']['total'] = 0;
            $output['statusCode'] = STATUS_BAD_REQUEST;
            return $output;
        }
        $userid = '';
        if (isset($inputArray['userIds'])) {
            $userid = $inputArray['userIds'];
        }
        $this->ci->User_model->resetVariable();
        $selectInput['id'] = $this->ci->User_model->id;
        $selectInput['username'] = $this->ci->User_model->username;
        $selectInput['name'] = $this->ci->User_model->name;
        $selectInput['email'] = $this->ci->User_model->email;
        $selectInput['phone'] = $this->ci->User_model->phone;
        $selectInput['mobile'] = $this->ci->User_model->mobile;
        $selectInput['pincode'] = $this->ci->User_model->pincode;
        $selectInput['facebookid'] = $this->ci->User_model->facebookid;
        $selectInput['googleid'] = $this->ci->User_model->googleid;
        $selectInput['twitterid'] = $this->ci->User_model->twitterid;
        $selectInput['profileimagefileid'] = $this->ci->User_model->profileimagefileid;
        $selectInput['status'] = $this->ci->User_model->status;
        $selectInput['address'] = $this->ci->User_model->address;
        $selectInput['countryid'] = $this->ci->User_model->countryid;
        $selectInput['stateid'] = $this->ci->User_model->stateid;
        $selectInput['cityid'] = $this->ci->User_model->cityid;
        $selectInput['usertype'] = $this->ci->User_model->usertype;
        $selectInput['isattendee'] = $this->ci->User_model->isattendee;
        $selectInput['company'] = $this->ci->User_model->company;
        $this->ci->User_model->setSelect($selectInput);
        $where[$this->ci->User_model->deleted] = 0;
        if (!empty($userid)) {
            $where[$this->ci->User_model->id] = $userid;
        }
        $this->ci->User_model->setWhere($where);
        $this->ci->User_model->setRecords(1);
        $userData = $this->ci->User_model->get();
        if (count($userData) == 0) {
            $output['status'] = TRUE;
            $output['response']['messages'][] = ERROR_NO_USER;
            $output['response']['total'] = 0;
            $output['statusCode'] = STATUS_INVALID_USER;
            return $output;
        }
        $profileId = $this->ci->config->item('default_profile_image_id');
        if ($userData[0]['profileimagefileid'] > 0) {
            $profileId = $userData[0]['profileimagefileid'];
        }
        $userData[0]['mobile'] = ($userData[0]['mobile'] != '') ? $userData[0]['mobile'] : $userData[0]['phone'];
        $userData[0]['profileimagefileid'] = $profileId;
        $userData[0]['profileimagefilepath'] = '';
        if ($profileId > 0) {
            $profilePathResponse = $this->fileHandler->getFileData(array('id', array($profileId)));
            if ($profilePathResponse['status'] && $profilePathResponse['response']['total'] > 0) {
                $userData[0]['profileimagefilepath'] = $this->ci->config->item('images_content_cloud_path') . $profilePathResponse['response']['fileData'][0]['path'];
            }
        }

        $output['status'] = TRUE;
        $output['response']['userData'] = $userData[0];
        $output['response']['messages'] = array();
        $output['response']['total'] = 1;
        $output['statusCode'] = STATUS_OK;
        return $output;
    }

    public function userEditEventCheck($request) {
        $eventHanlder = new Event_handler();
        $eventDetails = $eventHanlder->getEventDetails($request);
        if (count($eventDetails['response']['details']) > 0) {
            return true;
        }
        return false;
    }

    public function add($inputArray) {
        $this->ci->form_validation->pass_array($inputArray);
        //Checking validation using Group Validation (signup)
        if ($this->ci->form_validation->run('adduser') === FALSE) {
            $errorMsg = $this->ci->form_validation->get_errors();
            $output = parent::createResponse(FALSE, $errorMsg['message'], STATUS_BAD_REQUEST);
            return $output;
        }
        $name = $inputArray['name'];
        $email = $inputArray['email'];
        if (isset($inputArray['mobile'])) {
            $mobile = $inputArray['mobile'];
        }
        $this->ci->User_model->resetVariable();
        $password = encryptPassword($inputArray['password']);
        $createUser[$this->ci->User_model->name] = $name;
        $createUser[$this->ci->User_model->password] = $password;
        $createUser[$this->ci->User_model->username] = $email;
        $createUser[$this->ci->User_model->email] = $email;
        if (isset($inputArray['mobile'])) {
            $createUser[$this->ci->User_model->mobile] = $mobile;
        }
        $createUser[$this->ci->User_model->status] = 1;
        $this->ci->User_model->setInsertUpdateData($createUser);
        $userId = $this->ci->User_model->insert_data();
        if ($userId) {
            $output = parent::createResponse(TRUE, SUCCESS_ADDED_USER, STATUS_OK, 1, 'userId', $userId);
            return $output;
        }
        $output = parent::createResponse(FALSE, SOMETHING_WRONG, STATUS_SERVER_ERROR);
        return $output;
    }

    //Simply finding user data in the user table if user id list is passed(no linking with profile paths and clouds)
    public function getUserDetails($inputArray) {
        $this->ci->form_validation->reset_form_rules();
        $this->ci->form_validation->pass_array($inputArray);
        $this->ci->form_validation->set_rules('userIdList', 'user Ids', 'is_array');
        $this->ci->form_validation->set_rules('email', 'Email', 'valid_email');
        if ($this->ci->form_validation->run() == FALSE) {
            $response = $this->ci->form_validation->get_errors();
            $output['status'] = FALSE;
            $output['response']['messages'] = $response['message'];
            $output['response']['total'] = 0;
            $output['statusCode'] = STATUS_BAD_REQUEST;
            return $output;
        }
        $this->ci->User_model->resetVariable();
        $selectInput['id'] = $this->ci->User_model->id;
        $selectInput['status'] = $this->ci->User_model->status;
        $selectInput['name'] = $this->ci->User_model->name;
        $selectInput['email'] = $this->ci->User_model->email;
        $selectInput['phone'] = $this->ci->User_model->phone;
        $selectInput['company'] = $this->ci->User_model->company;
        $selectInput['mobile'] = $this->ci->User_model->mobile;
        $selectInput['username'] = $this->ci->User_model->username;
        $selectInput['usertype'] = $this->ci->User_model->usertype;
        $selectInput['isattendee'] = $this->ci->User_model->isattendee;

        $this->ci->User_model->setSelect($selectInput);
        $where[$this->ci->User_model->deleted] = 0;
        if (isset($inputArray['userIdList'])) {
            $where_in[$this->ci->User_model->id] = $inputArray['userIdList'];
            $this->ci->User_model->setWhereIns($where_in);
        }
        if (isset($inputArray['email'])) {
            $where[$this->ci->User_model->email] = $inputArray['email'];
        }
        $this->ci->User_model->setWhere($where);
        $userData = $this->ci->User_model->get();
        if (count($userData) == 0) {
            $output['status'] = TRUE;
            $output['response']['messages'][] = ERROR_NO_USER;
            $output['response']['total'] = 0;
            $output['statusCode'] = STATUS_OK;
            return $output;
        }
        $output['status'] = TRUE;
        $output['response']['userData'] = $userData;
        $output['response']['messages'] = array();
        $output['response']['total'] = count($userData);
        $output['statusCode'] = STATUS_OK;
        return $output;
    }

    public function signupEmailCheck($inputArray) {
        $this->ci->form_validation->pass_array($inputArray);
        $this->ci->form_validation->set_rules('email', 'Email', 'required|valid_email');

        if ($this->ci->form_validation->run() == FALSE) {
            $response = $this->ci->form_validation->get_errors();
            $output['status'] = FALSE;
            $output['response']['messages'] = $response['message'];
            $output['response']['total'] = 0;
            $output['statusCode'] = STATUS_BAD_REQUEST;
            return $output;
        }

        if ($this->emailExist($inputArray['email'])) {
            $output['status'] = TRUE;
            $output['response']['emailStatus'] = TRUE;
            $output['response']['messages'] = array();
            $output['response']['total'] = 1;
            $output['statusCode'] = STATUS_OK;
            return $output;
        }
        $output['status'] = TRUE;
        $output['response']['emailStatus'] = FALSE;
        $output['response']['messages'] = array();

        $output['statusCode'] = STATUS_OK;
        return $output;
    }

    public function adminSession($inputArray) {

        $userArray = array();
        $adminId = "";
        $adminId = $userArray['userIds'] = $this->ci->customsession->getData("uid");
        
        $adminUserDetails = $this->getAdminUserInfo($userArray);

        $this->ci->form_validation->pass_array($inputArray);
        $this->ci->form_validation->set_rules('organizerId', 'organizer Id', 'is_natural_no_zero|required_strict');
        if ($this->ci->form_validation->run() == FALSE) {
            $response = $this->ci->form_validation->get_errors();
            $output['status'] = FALSE;
            $output['response']['messages'] = $response['message'];
            $output['response']['total'] = 0;
            $output['statusCode'] = STATUS_BAD_REQUEST;
            return $output;
        }

        //admin session not exist
        if (count($adminUserDetails) == 0) {
            $output['status'] = TRUE;
            $output['response']['messages'][] = ERROR_NO_USER;
            $output['response']['total'] = 0;
            $output['statusCode'] = STATUS_OK;
            return $output;
        }

        $userArray['userIds'] = $inputArray['organizerId'];
        $orgUserDetails = $this->getUserInfo($userArray);
        if ($orgUserDetails['response']['total'] === 0) {
            $output['status'] = TRUE;
            $output['response']['messages'][] = ERROR_NO_USER;
            $output['response']['total'] = 0;
            $output['statusCode'] = STATUS_OK;
            return $output;
        }
        $orgUserDetails['response']['userData']['id'] = $inputArray['organizerId'];
        $orgUserDetails['response']['userData']['usertype'] = $inputArray['userType'];


        $this->setSession($orgUserDetails['response']['userData']);
        $this->ci->customsession->setData("adminId", $adminId);
        $output['status'] = TRUE;
        $output['response']['messages'][] = USER_SESSION_UPDATE;
        $output['response']['total'] = 0;
        $output['statusCode'] = STATUS_UPDATED;
        return $output;
    }

    /*
     * Function to login the user as Guest
     *
     * @access	public
     * @param
     *      	`userEmail` - Mandatory
     * @return	starts the session with the user input
     */

    function userGuestLogin($inputArray) {

        $logedUserId = $this->ci->customsession->getData("userId");
        if ($logedUserId > 0) {
            $output['status'] = FALSE;
            $output['response']['messages'][] = ERROR_ALREADY_LOGGED;
            $output['statusCode'] = 408;
            return $output;
        }

        $this->ci->form_validation->reset_form_rules();
        $this->ci->form_validation->pass_array($inputArray);
        $this->ci->form_validation->set_rules('userEmail', 'email Id', 'required_strict|email');
        if ($this->ci->form_validation->run() == FALSE) {
            $response = $this->ci->form_validation->get_errors();
            $output['status'] = FALSE;
            $output['response']['messages'] = $response['message'];
            $output['response']['total'] = 0;
            $output['statusCode'] = STATUS_BAD_REQUEST;
            return $output;
        }

        $userInput['email'] = $inputArray['userEmail'];
        $userData = $this->getUserData($userInput);

        if ($userData['status'] && $userData['response']['total'] > 0) {
            $userDataArray = $userData['response']['userData'];

            $loginInputArray['email'] = $userDataArray['email'];
            $loginInputArray['password'] = $userDataArray['password'];
            $loginInputArray['type'] = 'me';
            $loginReturn = $this->login($loginInputArray);

            return $loginReturn;
        }
        return $userData;
    }

    // Get the User Redirection Url when loggedin

    public function getuserRedirectionUrl() {
        $inputArray['loginredirectCheck'] = true;
        $organizerStatus = $this->ci->customsession->getData('isOrganizer');
        $promoterStatus = $this->ci->customsession->getData('isPromoter');
        $AttendeeStatus = $this->ci->customsession->getData('isAttendee');
        $collaboratorStatus = $this->ci->customsession->getData('isCollaborator');
        $redirectUrl = 'profile';
        if ($organizerStatus || $collaboratorStatus) {
            $redirectUrl = substr(getDashboardUrl(), strlen(site_url()));
        } else if ($promoterStatus) {
            $redirectUrl = substr(getPromoterViewUrl(), strlen(site_url()));
        } else if ($AttendeeStatus) {
            $redirectUrl = substr(getAttendeeUrl(), strlen(site_url()));
        }
        return $redirectUrl;
    }

    function userNameExist($username) {
        $userId = $this->ci->customsession->getUserId();
        $selectInput = array();
        $selectInput['id'] = $this->ci->User_model->id;
        $this->ci->User_model->setSelect($selectInput);
        $where[$this->ci->User_model->username] = $username;
        $where[$this->ci->User_model->deleted] = 0;
        $this->ci->User_model->setWhere($where);
        $whereNotIn[$this->ci->User_model->id] = $userId;
        $this->ci->User_model->setWhereNotIn($whereNotIn);
        $emailExists = $this->ci->User_model->get();
        if (count($emailExists) > 0)
            return TRUE;
        else
            return FALSE;
    }

    public function userNameCheck($inputArray) {
        $this->ci->form_validation->pass_array($inputArray);
        $this->ci->form_validation->set_rules('username', 'username', 'required');

        if ($this->ci->form_validation->run() == FALSE) {
            $response = $this->ci->form_validation->get_errors();
            $output['status'] = FALSE;
            $output['response']['messages'] = $response['message'];
            $output['response']['total'] = 0;
            $output['statusCode'] = STATUS_BAD_REQUEST;
            return $output;
        }

        if ($this->userNameExist($inputArray['username'])) {
            $output['status'] = TRUE;
            $output['response']['userNameStatus'] = TRUE;
            $output['response']['messages'] = USERNAME_EXIST;
            $output['response']['total'] = 1;
            $output['statusCode'] = STATUS_OK;
            return $output;
        }
        $output['status'] = TRUE;
        $output['response']['userNameStatus'] = FALSE;
        $output['response']['messages'] = USERNAME_AVAILABLE;
        $output['response']['total'] = 0;
        $output['statusCode'] = STATUS_OK;
        return $output;
    }

    function getProfileDropdown() {

        $userId = $this->ci->customsession->getUserId();
        $isLogin = ($userId > 0) ? 1 : 0;
        $guestLogin = $this->ci->customsession->getData('isGuestLogin');
        $isGuestLogin = ($userId > 0 && $guestLogin > 0) ? 1 : 0;

        $isAttendee = $this->ci->customsession->getData('isAttendee');
        $isPromoter = $this->ci->customsession->getData('isPromoter');
        $isOrganizer = $this->ci->customsession->getData('isOrganizer');
        $isCollaborator = $this->ci->customsession->getData('isCollaborator');
        $isDashboardAccess = 0;
        if ($isOrganizer == 1 || $isCollaborator == 1) {
            $isDashboardAccess = 1;
        }

        $dropDownHtml = '';
        if ($userId > 0) {
            $dropDownHtml .= commonHtmlElement('myevent', $isDashboardAccess, $isGuestLogin);
            $dropDownHtml .= commonHtmlElement('attendeeview', $isAttendee, $isGuestLogin);
            $dropDownHtml .= commonHtmlElement('promoterview', $isPromoter, $isGuestLogin);
            $dropDownHtml .= commonHtmlElement('myprofile', $isLogin, $isGuestLogin);
            $dropDownHtml .= commonHtmlElement('create-event', $isLogin, $isGuestLogin);
            $dropDownHtml .= "<hr>" . commonHtmlElement('logout', $isLogin, $isGuestLogin);
        } else {
            $dropDownHtml .= commonHtmlElement('logout', $isLogin, $isGuestLogin);
        }
        return $dropDownHtml;
    }
     public function getAdminUserInfo($inputArray) {
        $this->ci->form_validation->pass_array($inputArray);
        $this->ci->form_validation->set_rules('userIds', 'UserId', 'is_natural_no_zero');
        if ($this->ci->form_validation->run() == FALSE) {
            $response = $this->ci->form_validation->get_errors();
            $output['status'] = FALSE;
            $output['response']['messages'] = $response['message'];
            $output['response']['total'] = 0;
            $output['statusCode'] = STATUS_BAD_REQUEST;
            return $output;
        }
        $userid = 0;
        if (isset($inputArray['userIds'])) {
           $userid = $inputArray['userIds'];
        }
        $this->ci->User_model->resetVariable();
        $selectInput['id'] = $this->ci->User_model->id;
        $selectInput['username'] = $this->ci->User_model->username;
        $selectInput['name'] = $this->ci->User_model->name;
        $selectInput['email'] = $this->ci->User_model->email;
        $selectInput['phone'] = $this->ci->User_model->phone;
        
        $selectInput['status'] = $this->ci->User_model->status;
        
        $selectInput['usertype'] = $this->ci->User_model->usertype;
        
        $this->ci->User_model->setSelect($selectInput);
        $where[$this->ci->User_model->deleted] = 0;
       
        $where[$this->ci->User_model->id] = $userid;
       
        $where_in[$this->ci->User_model->usertype] = $this->ci->config->item('userRoles');
        $this->ci->User_model->setWhere($where);
        $this->ci->User_model->setWhereIns($where_in);
        $this->ci->User_model->setRecords(1);
        $userData = $this->ci->User_model->get();
        
        if (count($userData) == 0) {
            $output['status'] = TRUE;
            $output['response']['messages'][] = ERROR_NO_USER;
            $output['response']['total'] = 0;
            $output['statusCode'] = STATUS_INVALID_USER;
            return $output;
        }
        $output['status'] = TRUE;
        $output['response']['userData'] = $userData[0];
        $output['response']['messages'] = array();
        $output['response']['total'] = 1;
        $output['statusCode'] = STATUS_OK;
        return $output;
    }
    // For TrueSemantic
    public function getUserSpecificInfo($inputArray) {
        $countryHandler = new Country_handler();
        $stateHandler = new State_handler();
        $cityHandler = new City_handler();        
        
        $this->ci->form_validation->pass_array($inputArray);
        $this->ci->form_validation->set_rules('userIds', 'UserId', 'is_natural_no_zero');
        if ($this->ci->form_validation->run() == FALSE) {
            $response = $this->ci->form_validation->get_errors();
            $output['status'] = FALSE;
            $output['response']['messages'] = $response['message'];
            $output['response']['total'] = 0;
            $output['statusCode'] = STATUS_BAD_REQUEST;
            return $output;
        }
        $userid = '';
        if (isset($inputArray['userIds'])) {
            $userid = $inputArray['userIds'];
        }
        $this->ci->User_model->resetVariable();
        $selectInput['id'] = $this->ci->User_model->id;
        $selectInput['name'] = $this->ci->User_model->name;
        $selectInput['email'] = $this->ci->User_model->email;
        $selectInput['phone'] = $this->ci->User_model->phone;
        $selectInput['mobile'] = $this->ci->User_model->mobile;
        $selectInput['countryid'] = $this->ci->User_model->countryid;
        $selectInput['stateid'] = $this->ci->User_model->stateid;
        $selectInput['cityid'] = $this->ci->User_model->cityid;
        
        $this->ci->User_model->setSelect($selectInput);
        
        $where[$this->ci->User_model->deleted] = 0;
        $where[$this->ci->User_model->status] = 1;
        if (!empty($userid)) {
            $where[$this->ci->User_model->id] = $userid;
        }
        $this->ci->User_model->setWhere($where);
        $this->ci->User_model->setRecords(1);
        
        $userData = $this->ci->User_model->get();
        
        if (count($userData) == 0) {
            $output['status'] = TRUE;
            $output['response']['messages'][] = ERROR_NO_USER;
            $output['response']['total'] = 0;
            $output['statusCode'] = STATUS_INVALID_USER;
            return $output;
        }
        //print_r($userData);
        $userData[0]['mobile'] = ($userData[0]['mobile'] != '') ? $userData[0]['mobile'] : $userData[0]['phone'];
        
        //Getting the Country data
        if ($userData[0]['countryid'] > 0) {
            $countryData = $countryHandler->getCountryListById(array('countryId' => $userData[0]['countryid']));
            if (count($countryData) > 0 && $countryData['status'] && $countryData['response']['total'] > 0) {
                $userData[0]['countryName'] = $countryData['response']['detail']['name'];
            }
        }

        //Getting the State data
        if ($userData[0]['stateid'] > 0) {
            $stateData = $stateHandler->getStateListById(array('stateId' => $userData[0]['stateid'], 'nostatus' => true));
            if (count($stateData) > 0 && $stateData['status'] && $stateData['response']['total'] > 0) {
                $stateList = $stateData['response']['stateList'][0];
                $userData[0]['stateName'] = $stateList['name'];
            }
        }

        //Getting the City data
        if ($userData[0]['cityid'] > 0) {
            $request['cityId'] = $userData[0]['cityid'];
            $request['countryId'] = $userData[0]['countryid'];
            $cityData = $cityHandler->getCityDetailById($request);
            if (count($cityData) > 0 && $cityData['status'] && $cityData['response']['total'] > 0) {
                $cityObject = $cityData['response']['detail'];
                $userData[0]['cityName'] = $cityObject['name'];
            }
        }
        unset($userData[0]['countryid']);
        unset($userData[0]['stateid']);
        unset($userData[0]['cityid']);
            
        $output['status'] = TRUE;
        $output['response']['userData'] = $userData[0];
        $output['response']['messages'] = array();
        $output['response']['total'] = 1;
        $output['statusCode'] = STATUS_OK;
        return $output;
    }
	
	//function to get user email by userid
	public function getUserEmailIdByUserId($userId) {       
        
        
        $userid = $userId;
        $this->ci->User_model->resetVariable();
        $selectInput['email'] = $this->ci->User_model->email;
        
        $this->ci->User_model->setSelect($selectInput);
        
        //$where[$this->ci->User_model->deleted] = 0;
        //$where[$this->ci->User_model->status] = 1;
        if (!empty($userid)) {
            $where[$this->ci->User_model->id] = $userid;
        }
        $this->ci->User_model->setWhere($where);
        $this->ci->User_model->setRecords(1);
        
        $userData = $this->ci->User_model->get();
        
        if (count($userData) == 0) {
            $output['status'] = TRUE;
            $output['response']['messages'][] = ERROR_NO_USER;
            $output['response']['total'] = 0;
            $output['statusCode'] = STATUS_INVALID_USER;
            return $output;
        }
        
            
        $output['status'] = TRUE;
        $output['response']['userData'] = $userData[0];
        $output['response']['messages'] = array();
        $output['response']['total'] = 1;
        $output['statusCode'] = STATUS_OK;
        return $output;
    }
}
