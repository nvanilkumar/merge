<?php

/**
 * profle related business logic will be defined in this class
 * @package		CodeIgniter
 * @author		Qison  Dev Team
 * @param		
 * @addTicket		
 * @copyright	Copyright (c) 2015, Meraevents.
 * @Version		Version 1.0
 * @Since       Class available since Release Version 1.0
 * @Created     05-08-2015
 * @Last Modified 05-08-2015
 */
require_once (APPPATH . 'handlers/handler.php');
require_once(APPPATH . 'handlers/user_handler.php');
require_once(APPPATH . 'handlers/file_handler.php');
require_once (APPPATH . 'handlers/eventsignup_handler.php');
require_once (APPPATH . 'handlers/eventsignupticketdetail_handler.php');
require_once (APPPATH . 'handlers/dashboard_handler.php');
require_once(APPPATH . 'handlers/timezone_handler.php');
require_once(APPPATH . 'handlers/event_handler.php');
require_once(APPPATH . 'handlers/ticket_handler.php');
require_once(APPPATH . 'handlers/organizer_handler.php');
require_once(APPPATH . 'handlers/category_handler.php');
require_once(APPPATH . 'handlers/city_handler.php');

class Profile_handler extends Handler {

    var $ci;
    var $userHandler;
    var $fileHandler;
    var $eventSignupHandler;
    var $eventSignupTicketdetailHandler;
    var $dashboradHandler;
    var $timezoneHandler;
    var $eventHandler;
    var $ticketHandler;
    var $organizerHandler;
    var $cityHandler;

    public function __construct() {
        parent::__construct();
        $this->ci = parent::$CI;
        $this->userHandler = new User_handler();
        $this->fileHandler = new File_handler();
        $this->organizerHandler = new Organizer_handler();
    }

    public function ImageInsert($inputArray) {
        $imageFileConfig['fieldName'] = 'picture';
        /* validating the uploaded file is image ot not */
        $filename = $_FILES[$imageFileConfig['fieldName']]['name'];
        $fileExtension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        $extensionArray = explode('|', IMAGE_EXTENTIONS);
        if (!in_array($fileExtension, $extensionArray)) {
            $output['status'] = FALSE;
            $output["response"]["messages"][] = ERROR_INVALID_IMAGE;
            $output['statusCode'] = STATUS_BAD_REQUEST;
            return $output;
        }
        $imagePath = $this->ci->config->item('user_profile_path') . $inputArray['userId'];
        $imageFileConfig['upload_path'] = $this->ci->config->item('file_upload_path') . $imagePath;
        $imageFileConfig['allowed_types'] = IMAGE_EXTENTIONS;
        $imageFileConfig['dbFilePath'] = $imagePath . "/";
        $imageFileConfig['dbFileType'] = FILE_TYPE_USERPROFILE;
        $imageFileConfig['folderId'] = $inputArray['userId'];
        $imageResponse = $this->fileHandler->doUpload($imageFileConfig);
        if ($imageResponse['status'] === FALSE) {
            return $imageResponse;
        }
        $output['response']['imageFileId'] = $imageResponse['response']['fileId'];
        $output['response']['imageFilePath'] = $imageResponse['response']['filePath'];
        $output['status'] = TRUE;
        $output['statusCode'] = STATUS_UPDATED;
        return $output;
    }

    public function getPersonalDetails($inputArray) {
        $inputArray['userIds'] = $inputArray['userId'];
        $userDetails = $this->userHandler->getUserInfo($inputArray);
        if ($userDetails) {
            $output['status'] = TRUE;
            $output['response']['personalDetails'][] = $userDetails;
            $output['statusCode'] = STATUS_OK;
            return $output;
        } else {
            $output['status'] = FALSE;
            $output["response"]["messages"][] = SOMETHING_WENT_WRONG;
            $output['statusCode'] = STATUS_SERVER_ERROR;
            return $output;
        }
    }

    public function getUserDetails() {
        $inputArray['ownerId'] = $this->ci->customsession->getUserId();
        $userDetails = $this->userHandler->getUserData($inputArray);
        if ($userDetails['status'] && $userDetails['response']['total'] > 0) {
            unset($userDetails['response']['userData']['facebookid']);
            unset($userDetails['response']['userData']['googleid']);
            unset($userDetails['response']['userData']['twitterid']);
            unset($userDetails['response']['userData']['profileimagefileid']);
            unset($userDetails['response']['userData']['password']);
        }
        return $userDetails;
    }

    public function updatePersonalDetails($userInfo) {

        parent::$CI->form_validation->reset_form_rules();
        parent::$CI->form_validation->pass_array($userInfo);
        parent::$CI->form_validation->set_rules('name', 'name', 'required_strict|name');
        parent::$CI->form_validation->set_rules('username', 'username', 'required_strict||min_length[6]|max_length[50]');
        if (isset($userInfo['countryId']) && ($userInfo['countryId']) > 0) {
            parent::$CI->form_validation->set_rules('countryId', 'countryId', 'required_strict|is_natural_no_zero');
            // parent::$CI->form_validation->set_rules('stateId', 'stateId', 'required_strict|is_natural_no_zero');
            if (isset($userInfo['stateId']) && $userInfo['stateId'] > 0) {
                parent::$CI->form_validation->set_rules('stateId', 'stateId', 'required_strict|is_natural_no_zero');
            }
            if (isset($userInfo['cityId']) && $userInfo['cityId'] > 0) {
                parent::$CI->form_validation->set_rules('cityId', 'cityId', 'required_strict|is_natural_no_zero');
            } elseif (isset($userInfo['cityName'])) {

                parent::$CI->form_validation->set_rules('cityName', 'cityName', 'required_strict');
            }
//        elseif (!isset($userInfo['cityId']) && !isset($userInfo['cityName'])) {
//
//            parent::$CI->form_validation->set_rules('cityId', 'cityId', 'required_strict|is_natural_no_zero');
//        } 
        } else if (isset($userInfo['cityId']) && ($userInfo['cityId']) > 0) {
            parent::$CI->form_validation->set_rules('countryId', 'countryId', 'required_strict|is_natural_no_zero');
            parent::$CI->form_validation->set_rules('stateId', 'stateId', 'required_strict|is_natural_no_zero');
        } else if (isset($userInfo['stateId']) && ($userInfo['stateId']) > 0) {
            parent::$CI->form_validation->set_rules('countryId', 'countryId', 'required_strict|is_natural_no_zero');
            parent::$CI->form_validation->set_rules('cityId', 'cityId', 'required_strict|is_natural_no_zero');
        }
        parent::$CI->form_validation->set_rules('mobile', 'Mobile', 'required_strict|numeric|min_length[10]|max_length[10]');
        parent::$CI->form_validation->set_rules('pincode', 'pincode', 'numeric|min_length[6]|max_length[8]');
        if (parent::$CI->form_validation->run() === FALSE) {
            $errorMessages = $this->ci->form_validation->get_errors();
            $output['status'] = FALSE;
            $output['response']['messages'] = $errorMessages['message'];
            $output['statusCode'] = STATUS_BAD_REQUEST;
            return $output;
        }
        $image = $_FILES['picture']['name'];
        if ($image) {
            $personalLogoId = $this->ImageInsert($userInfo);
            if ($personalLogoId['status'] === FALSE) {
                return $personalLogoId;
            }
            $personalFileId = $personalLogoId['response']['imageFileId'];
            $personalFilePath = $personalLogoId['response']['imageFilePath'];
            $personalInfo['profileimagefileid'] = $personalFileId;
        }
        $userId = $this->ci->customsession->getUserId();
        if (isset($userInfo['username'])) {
            $personalInfo['username'] = $userInfo['username'];
        }
        if (isset($userInfo['name'])) {
            $personalInfo['name'] = $userInfo['name'];
        }
        if (isset($userInfo['address'])) {
            $personalInfo['address'] = $userInfo['address'];
        }
        if (isset($userInfo['countryId'])) {
            $personalInfo['countryid'] = $userInfo['countryId'];
        }
        if (isset($userInfo['stateId'])) {
            $personalInfo['stateid'] = $userInfo['stateId'];
        }
        if (isset($userInfo['cityId'])) {
            $personalInfo['cityid'] = $userInfo['cityId'];
        }
        if (isset($userInfo['phone'])) {
            $personalInfo['phone'] = $userInfo['phone'];
        }
        if (isset($userInfo['mobile'])) {
            $personalInfo['mobile'] = $userInfo['mobile'];
        }
        if (isset($userInfo['pincode'])) {
            $personalInfo['pincode'] = $userInfo['pincode'];
        }
        if (isset($userInfo['companyname'])) {
            $personalInfo['company'] = $userInfo['companyname'];
        }
        //company details related data
        if (isset($userInfo['designation'])) {
            $orgnizerInfo['designation'] = $userInfo['designation'];
        }
        ///  social share related data 
        if (isset($userInfo['facebooklink'])) {
            $orgnizerInfo['facebooklink'] = $userInfo['facebooklink'];
        }
        if (isset($userInfo['twitterlink'])) {
            $orgnizerInfo['twitterlink'] = $userInfo['twitterlink'];
        }
        if (isset($userInfo['googlepluslink'])) {
            $orgnizerInfo['googlepluslink'] = $userInfo['googlepluslink'];
        }
        if (isset($userInfo['linkedinlink'])) {
            $orgnizerInfo['linkedinlink'] = $userInfo['linkedinlink'];
        }
        $where['id'] = $userId;
        $this->ci->load->model('User_model');
        $this->ci->User_model->resetVariable();
        $this->ci->User_model->setInsertUpdateData($personalInfo);
        $this->ci->User_model->setWhere($where);
        $response = $this->ci->User_model->update_data();
        $userInfo['userId'] = $userId;
        $orgnizerInfo['userId'] = $userId;
        $isOrganizer = $this->ci->customsession->getData('isOrganizer');
        if ($isOrganizer == 1) {
            if ($response) {
                $org = $this->organizerHandler->getCompanyDetails($userInfo);
                $orgnizerInfo['id'] = $org['response']['companyDetails']['id'];
                if ($org['status'] == TRUE && $org['response']['total'] > 0) {
                    $shareData = $this->organizerHandler->updateOrgCompanyDetails($orgnizerInfo);
                } else {
                    $shareData = $this->organizerHandler->insertOrgCompanyDetails($orgnizerInfo);
                }
                if ($shareData) {
                    $output['status'] = TRUE;
                    $output["response"]["messages"][] = UPDATE_PERSONAL_DETAILS;
                    $output['statusCode'] = STATUS_UPDATED;
                    return $output;
                } else {
                    $output['status'] = FALSE;
                    $output["response"]["messages"][] = SOMETHING_WENT_WRONG;
                    $output['statusCode'] = STATUS_SERVER_ERROR;
                    return $output;
                }
            }
        } else {
            $output['status'] = TRUE;
            $output["response"]["messages"][] = UPDATE_PERSONAL_DETAILS;
            $output['statusCode'] = STATUS_UPDATED;
            return $output;
        }
        $output['status'] = FALSE;
        $output["response"]["messages"][] = SOMETHING_WENT_WRONG;
        $output['statusCode'] = STATUS_SERVER_ERROR;
        return $output;
    }

    public function getUserTicketList($inputArray) {
        $this->eventHandler = new Event_handler();
        $this->eventSignupHandler = new Eventsignup_handler();
        $this->timezoneHandler = new Timezone_handler();
        $this->eventSignupTicketdetailHandler = new Eventsignup_Ticketdetail_handler();
        $this->dashboradHandler = new Dashboard_handler();
        $this->ticketHandler = new Ticket_handler();
        $this->categoryHandler = new Category_handler();

        $inputArray['userId'] = getUserId();
        $this->ci->form_validation->reset_form_rules();
        $this->ci->form_validation->pass_array($inputArray);
        $this->ci->form_validation->set_rules('userId', 'user Id', 'is_natural_no_zero|required_strict');
        if(isset($inputArray['eventSignupId'])) {
            $this->ci->form_validation->set_rules('eventSignupId', 'event Signup Id', 'is_natural_no_zero');
        }
        if (!empty($inputArray) && $this->ci->form_validation->run() == FALSE) {
            $validationStatus = $this->ci->form_validation->get_errors();
            $output['status'] = FALSE;
            $output['response']['messages'] = $validationStatus['message'];
            $output['statusCode'] = STATUS_BAD_REQUEST;
            return $output;
        }
        $ticketType = isset($inputArray['ticketType']) ? $inputArray['ticketType'] : 'current';
        $eventList = $this->eventSignupHandler->getUserEventSignupDetail($inputArray);
        $eventListData = array();
        $timezoneArray = array();
        $timezoneData = array();
        if ($eventList != FALSE && count($eventList['response']['eventSignupList']) > 0) {
            $eventListData = $eventList['response']['eventSignupList'];
            $eventIdArray = commonHelperGetIdArray($eventList['response']['eventSignupList'], 'eventId');
            $eventIds = array_keys($eventIdArray);
            $eventDetail = $this->dashboradHandler->getUserCurrentTicketEvent(array('eventList' => $eventIds, 'ticketType' => $ticketType));
            $eventDetailIdArray = array();
            if ($eventDetail['status'] != FALSE && count($eventDetail['response']['eventList']) > 0) {
                $eventDetailIdArray = commonHelperGetIdArray($eventDetail['response']['eventList'], 'eventId');
                $timezoneArray = commonHelperGetIdArray($eventDetail['response']['eventList'], 'timezoneid');
                $categoryArray = commonHelperGetIdArray($eventDetail['response']['eventList'], 'categoryid');
                $timezoneIds = array_keys($timezoneArray);
                $timezoneIds = array_unique($timezoneIds);

                $categoryData = array();
                $categoryData = $this->categoryHandler->getCategoryList(array('major' => 1));
                if ($categoryData['status'] && $categoryData['response']['total'] > 0) {
                    $categoryData = commonHelperGetIdArray($categoryData['response']['categoryList']);
                }

                if (count($timezoneIds) > 0) {
                    $timezoneResData = $this->timezoneHandler->timeZoneList(array('idList' => $timezoneIds));
                    if ($timezoneResData['status'] != FALSE && count($timezoneResData['response']['timeZoneList']) > 0) {
                        $timezoneData = $timezoneResData['response']['timeZoneList'];
                    }
                }
                $eventIdsArray = array_keys($eventDetailIdArray);
                $eventsignupIds = array();
                foreach ($eventListData as $key => $event) {

                    $eventId = $event['eventId'];
                    $eventTimeZoneName = $this->eventHandler->getEventTimeZone($eventId);
                    if (in_array($eventId, $eventIdsArray)) {
                        $inputTkt = array();
                        $eventsignupIds = array();
                        $ticketDetails = array();
                        $eventsignupIds['eventsignupids'] = array($event['eventSignupId']);
                        $inputTkt['eventId'] = $eventId;
                        $inputTkt['status'] = 0;
                        $selectTicketResponse = $this->ticketHandler->getTicketName($inputTkt);
                        if ($selectTicketResponse['status'] && $selectTicketResponse['response']['total'] > 0) {
                            $ticketDataIdIndexed = commonHelperGetIdArray($selectTicketResponse['response']['ticketName']);
                        }
                        $eventsignupIds['transactiontype'] = 'all';
                        $eventsignupticketdetails = $this->eventSignupTicketdetailHandler->getListByEventsignupIds($eventsignupIds);
                        $eventsignupticketdetails = $eventsignupticketdetails['response']['eventSignupTicketDetailList'];
                        foreach ($eventsignupticketdetails as $tkey => $tval) {
                            $eventsignuptickets[$tkey]['ticketId'] = $tval['ticketid'];
                        }
                        //  Start of ticket list for the Eventsignup
                        foreach ($eventsignupticketdetails as $ticketvalues) {
                            $ticketDetails[$ticketvalues['ticketid']]['ticketId'] = $ticketvalues['ticketid'];
                            $ticketDetails[$ticketvalues['ticketid']]['name'] = $ticketDataIdIndexed[$ticketvalues['ticketid']]['name'];
                            $ticketDetails[$ticketvalues['ticketid']]['amount'] = round($ticketvalues['totalamount']);
                            $ticketDetails[$ticketvalues['ticketid']]['quantity'] = $ticketvalues['ticketquantity'];
                        }
                        $eventListData[$key]['eventName'] = $eventDetailIdArray[$eventId]['eventName'];
                        $eventListData[$key]['venuename'] = $eventDetailIdArray[$eventId]['venuename'];
                        $eventListData[$key]['signupdate'] = allTimeFormats(convertTime($event['signupdate'], $eventTimeZoneName, true),18);
                        if ($eventDetailIdArray[$eventId]["categoryid"] > 0) {
                            $catDetails = $categoryData[$eventDetailIdArray[$eventId]["categoryid"]];
                            $eventListData[$key]['themeColor'] = $catDetails['themecolor'];
                        } else {
                            $eventListData[$key]['themeColor'] = "";
                        }
                        $eventListData[$key]['ticketDetail'] = $ticketDetails;
                    } else {
                        unset($eventListData[$key]);
                    }
                }
                $output['status'] = TRUE;
                $output['response']['eventList'] = array_values($eventListData);
                $output['response']['messages'] = array();
                $output['response']['total'] = count($eventListData);
                $output['statusCode'] = STATUS_OK;
            } elseif($eventDetail['response']['total'] == 0) {
                $output['status'] = TRUE;
                $output['response']['messages'][] = ERROR_NO_DATA;
                $output['response']['total'] = 0;
                $output['statusCode'] = STATUS_OK;
            }
            // For setting the User redirection URL After Login
            if (isset($inputArray['loginredirectCheck']) && $inputArray['loginredirectCheck'] == true) {
                $count = 0;
                if ($eventDetail['status'] != FALSE && count($eventDetail['response']['eventList']) > 0) {
                    $count = count($eventDetail);
                }
                $output['status'] = TRUE;
                $output['response']['messages'][] = '';
                $output['response']['total'] = $count;
                $output['statusCode'] = STATUS_OK;
                return $output;
            }
        } else {//No records are fetched    $eventList['status'] != FALSE && 
            $output['status'] = TRUE;
            $output['response']['messages'][] = ERROR_NO_DATA;
            $output['response']['total'] = 0;
            $output['statusCode'] = STATUS_OK;
        }
        return $output;
    }

    /**
     * To change the profile user password
     * @param type $inputArray['password']
     * @param type $inputArray['confirmPassword']
     */
    public function profileChangePassword($inputArray) {
        $userId = getUserId();
        $tokenId = '';
        $output = $this->userHandler->updatePassword($inputArray, $userId, $tokenId);

        return $output;
    }

    /*
     * Function to upload the profile image
     *
     * @access	public
     * @param
     *      	Uploaded image data as input
     * @return	gives the response with updated profile image path
     */

    public function updateProfileImage() {

        $userInfo['userId'] = $this->ci->customsession->getData('userId');
        $userInfo['image'] = $_FILES['picture']['name'];
        $personalLogoId = $this->ImageInsert($userInfo);

        if ($personalLogoId['status'] === FALSE) {
            return $personalLogoId;
        }
        $personalFileId = $personalLogoId['response']['imageFileId'];
        $personalFilePath = $personalLogoId['response']['imageFilePath'];
        $personalInfo['profileimagefileid'] = $personalFileId;

        $where['id'] = $userInfo['userId'];
        $this->ci->load->model('User_model');
        $this->ci->User_model->resetVariable();
        $this->ci->User_model->setInsertUpdateData($personalInfo);
        $this->ci->User_model->setWhere($where);
        $response = $this->ci->User_model->update_data();

        $profileimagefilepath = '';
        $profilePathResponse = $this->fileHandler->getFileData(array('id', array($personalFileId)));
        if ($profilePathResponse['status'] && $profilePathResponse['response']['total'] > 0) {
            $profileimagefilepath = $this->ci->config->item('images_content_cloud_path') . $profilePathResponse['response']['fileData'][0]['path'];
        }

        if ($response) {
            $output['status'] = TRUE;
            $output["response"]["profileImagePath"] = $profileimagefilepath;
            $output["response"]["messages"][] = UPDATE_PERSONAL_DETAILS;
            $output['statusCode'] = STATUS_UPDATED;
            return $output;
        }
        $output['status'] = FALSE;
        $output["response"]["messages"][] = SOMETHING_WENT_WRONG;
        $output['statusCode'] = STATUS_SERVER_ERROR;
        return $output;
    }
    public function updateUserProfile($userInfo) {
        $this->cityHandler = new City_handler();
        $userInfo['picture'] = $_FILES['picture']['name'];
        $cityInfo = array();
        $cityInfo['countryId'] = $userInfo['countryId'];
        $cityInfo['stateId'] = $userInfo['stateId'];
        if ($userInfo['cityName'] != '') {
            $cityInfo['city'] = $userInfo['cityName'];
            $checkCity = $this->cityHandler->cityInsert($cityInfo);
            if ($checkCity['status'] == FALSE) {
                $output['status'] = FALSE;
                $output['response']['total'] = 0;
                $output['response']['messages'] = $checkCity['response']['messages'];
                $output['statusCode'] = $checkCity['statusCode'];
                return $output;
            }
            $userInfo['cityId'] = $checkCity['response']['cityId'];
        }
        $updateData = $this->updatePersonalDetails($userInfo);
        return $updateData;
    }
    
    public function getUserTicketListCount($inputArray) {
        $this->eventSignupHandler = new Eventsignup_handler();
        $this->dashboradHandler = new Dashboard_handler();
        $inputArray['userId'] = getUserId();
        $this->ci->form_validation->reset_form_rules();
        $this->ci->form_validation->pass_array($inputArray);
        $this->ci->form_validation->set_rules('userId', 'user Id', 'is_natural_no_zero|required_strict');
        if (!empty($inputArray) && $this->ci->form_validation->run() == FALSE) {
            $validationStatus = $this->ci->form_validation->get_errors();
            $output['status'] = FALSE;
            $output['response']['messages'] = $validationStatus['message'];
            $output['statusCode'] = STATUS_BAD_REQUEST;
            return $output;
        }
        if(isset($inputArray['eventSignupId'])) {
            $this->ci->form_validation->set_rules('eventSignupId', 'event Signup Id', 'is_natural_no_zero');
        }
        $ticketType = isset($inputArray['ticketType']) ? $inputArray['ticketType'] : 'current';
        $eventList = $this->eventSignupHandler->getUserEventSignupDetailData($inputArray);
        if ($eventList != FALSE && count($eventList['response']['eventSignupList']) > 0) {
            $eventListData = $eventList['response']['eventSignupList'];
            $eventIdArray = commonHelperGetIdArray($eventList['response']['eventSignupList'], 'eventId');
            $eventIds = array_keys($eventIdArray);
            $eventDetail = $this->dashboradHandler->getUserCurrentTicketEventCount(array('eventList' => $eventIds, 'ticketType' => $ticketType));
            if ($eventDetail != FALSE && $eventDetail['response']['total'] > 0) {
                $output['status'] = TRUE;
                $output['response']['messages'] = array();
                $output['response']['total'] = $eventDetail['response']['total'];
                $output['statusCode'] = STATUS_OK;
           }else {//No records are fetched    $eventList['status'] != FALSE && 
                $output['status'] = TRUE;
                $output['response']['messages'][] = ERROR_NO_DATA;
                $output['response']['total'] = 0;
                $output['statusCode'] = STATUS_OK;
            }
            return $output;
        }
    }
        }
