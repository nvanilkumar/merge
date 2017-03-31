<?php

/**
 * Event related business logic will be defined in this class
 *
 * @package		CodeIgniter
 * @author		Qison  Dev Team
 * @copyright	Copyright (c) 2015, Meraevents.
 * @Version		Version 1.0
 * @Since       Class available since Release Version 1.0 
 * @Created     11-06-2015
 * @Last Modified 11-06-2015
 */
require_once(APPPATH . 'handlers/handler.php');
require_once(APPPATH . 'handlers/solr_handler.php');
require_once(APPPATH . 'handlers/ticket_handler.php');
require_once(APPPATH . 'handlers/search_handler.php');
require_once(APPPATH . 'handlers/category_handler.php');
require_once(APPPATH . 'handlers/country_handler.php');
require_once(APPPATH . 'handlers/state_handler.php');
require_once(APPPATH . 'handlers/city_handler.php');
require_once(APPPATH . 'handlers/file_handler.php');
require_once(APPPATH . 'handlers/subcategory_handler.php');
require_once(APPPATH . 'handlers/user_handler.php');
require_once(APPPATH . 'handlers/timezone_handler.php');
require_once(APPPATH . 'handlers/tag_handler.php');
require_once(APPPATH . 'handlers/bookmark_handler.php');
require_once(APPPATH . 'handlers/configure_handler.php');
require_once(APPPATH . 'handlers/tickettax_handler.php');
require_once(APPPATH . 'handlers/eventpaymentgateway_handler.php');
require_once(APPPATH . 'handlers/paymentgateway_handler.php');
require_once(APPPATH . 'handlers/ticketdiscount_handler.php');
require_once(APPPATH . 'handlers/eventsignup_handler.php');
require_once(APPPATH . 'handlers/eventextracharge_handler.php');
require_once(APPPATH . 'handlers/email_handler.php');
require_once(APPPATH . 'handlers/discount_handler.php');
require_once(APPPATH . 'handlers/sessionlock_handler.php');
require_once(APPPATH . 'handlers/alert_handler.php');
require_once(APPPATH . 'handlers/promoter_handler.php');
require_once (APPPATH . 'handlers/offlinepromoterdiscounts_handler.php');
require_once(APPPATH . 'handlers/messagetemplate_handler.php');
require_once(APPPATH . 'handlers/organizer_handler.php');
require_once(APPPATH . 'handlers/gallery_handler.php');
require_once (APPPATH . 'handlers/seating_handler.php');

class Event_handler extends Handler {

    var $categoryHandler, $ticketHandler, $timezoneHandler, $countryHandler,
            $stateHandler, $cityHandler, $fileHandler, $subcategoryHandler, $bookmarkHandler, $emailHandler, $galleryHandler;
    var $ci;

    public function __construct() {
        parent::__construct();
        $this->ci = parent::$CI;
        $this->ci->load->model('Event_model');
    }

    //To Get the evetnts list from solr search
    public function getEventList($inputs) {
        //print_r($inputs);//exit;
        $categoryHandler = new Category_handler();
        $cityHandler = new City_handler();
        $timezoneHandler = new Timezone_handler();
        $bookmarkHandler = new Bookmark_handler();
        $output = array();
        $nextPageStatus = false;
        if (isset($inputs['page']) && $inputs['page'] <= 0) {
            $inputs['page'] = 1;
        }
        if (isset($inputs['eventIdsArray']) && count($inputs['eventIdsArray']) > 0) {
            
        } else {
            $validationStatus = $this->eventValidation($inputs);
            if ($validationStatus['error'] == TRUE) {
                $output['status'] = FALSE;
                $output['response']['messages'] = $validationStatus['message'];
                $output['statusCode'] = STATUS_BAD_REQUEST;
                return $output;
            }
        }

        if ($inputs['day'] == 7 && $inputs['dateValue'] == '') {
            $output['status'] = FALSE;
            $output['response']['messages'][] = ERROR_NO_CUSTOME_DATE;
            $output['statusCode'] = 400;
            return $output;
        }
        //Feacth the solr search results
        $solrHandler = new Solr_handler();

        $solrInputArray = array();

        if (!isset($inputs['status'])) {
            $inputs['status'] = 1;
        }
        $solrInputArray = $this->solrArray($inputs);
        if (!isset($solrInputArray['day'])) {
            $solrInputArray['day'] = 6;
        }
        if (isset($solrInputArray['dateValue'])) {
            $solrInputArray['dateValue'] = urldecode($solrInputArray['dateValue']);
            $dateValidation = dateValidation($solrInputArray['dateValue'], '/');
            if (!$dateValidation) {
                $output['status'] = FALSE;
                $output["response"]["messages"][] = ERROR_DATE_VALUE_FORMAT;
                $output['statusCode'] = STATUS_BAD_REQUEST;
                return $output;
            }
            $dateValue = allTimeFormats($solrInputArray['dateValue'], 9);
            if (strtotime($dateValue) < strtotime(allTimeFormats('', 9))) {
                $output['status'] = FALSE;
                $output["response"]["messages"][] = ERROR_DATE_GREATER_THAN_NOW;
                $output['statusCode'] = STATUS_BAD_REQUEST;
                return $output;
            }
        }
        if (isset($inputs['registrationType']) && $inputs['registrationType'] != '') {
            $solrInputArray['registrationType'] = $inputs['registrationType'];
        }
        if (isset($inputs['eventIdsArray']) && count($inputs['eventIdsArray']) > 0) {
            $solrInputArray['eventIdsArray'] = $inputs['eventIdsArray'];
        }
        if (isset($inputs['eventMode'])) {
            $solrInputArray['eventMode'] = $inputs['eventMode'];
        }

        if (!isset($inputs['private'])) {
            $solrInputArray['private'] = 0;
        }
        if (!isset($inputs['status'])) {
            $solrInputArray['status'] = 1;
        }
        if (isset($inputs['ticketSoldout'])) {
            $solrInputArray['ticketSoldout'] = $inputs['ticketSoldout'];
        }
        $solrResults = $solrHandler->getSolrEvents($solrInputArray);
        //The 2nd parameter convert json_decode to array
        $solrResults = json_decode($solrResults, true);

        //solr level validations
        if ((isset($solrResults["response"]["error"])) && $solrResults["response"]["error"] == true) {
            return $solrResults;
        }

        $eventList = array();

        $solrEventList = $solrResults["response"]["events"];
        if (count($solrEventList) > 0) {
            $categoryIdList = array();
            $timezoneIdList = array();
            $cityIdList = array();
            foreach ($solrEventList as $rKey => $rValue) {
                $categoryIdList[] = $rValue["categoryId"];
                $timezoneIdList[] = $rValue["timezoneId"];
                $countryIdList[] = $rValue["countryId"];
                $stateIdList[] = $rValue["stateId"];
                $cityIdList[] = $rValue["cityId"];
            }
            $categoryIdList = array_unique($categoryIdList);
            $timezoneIdList = array_unique($timezoneIdList);
            $countryIdList = array_unique($countryIdList);
            $stateIdList = array_unique($stateIdList);
            $cityIdList = array_unique($cityIdList);
            
            $timezoneData = array();
            $timezoneData = $timezoneHandler->timeZoneList(array('idList' => $timezoneIdList));
            if ($timezoneData['status'] && $timezoneData['response']['total'] > 0) {
                $timezoneData = commonHelperGetIdArray($timezoneData['response']['timeZoneList']);
            }
            $categoryData = array();
            $categoryData = $categoryHandler->getCategoryList(array('major' => 1));
            if ($categoryData['status'] && $categoryData['response']['total'] > 0) {
                $categoryData = commonHelperGetIdArray($categoryData['response']['categoryList']);
            }
            $cityListData = array();

            //Getting the City data
            if (count($cityIdList) > 0) {
                $cityListData = $cityHandler->getCityNames($cityIdList);
            }
            if(count($countryIdList) > 0) {

                $this->countryHandler = new Country_handler();
                $countryInput['major'] = 1;
                $countryList = $this->countryHandler->getCountryList($countryInput);
                $countryListData = commonHelperGetIdArray($countryList['response']['countryList']);
            }

            if ($cityListData['status'] == TRUE && count($cityListData['response']['cityName']) > 0) {
                $cityObject = $cityListData['response']['cityName'];
                $cityListData = commonHelperGetIdArray($cityListData['response']['cityName']);
            }

            $bookmarkEvents = array();
            $userId = $this->ci->customsession->getUserId();
            if ($userId != '') {
                $bookmarkInputs['userId'] = $userId;
                $bookmarkInputs['returnEventIds'] = true;
                $bookmarkEvents = $bookmarkHandler->getUserBookmarks($bookmarkInputs);
                $bookmarkEventsArray = array();
                if ($bookmarkEvents['status'] && $bookmarkEvents['response']['total'] > 0) {
                    $bookmarkEventsArray = $bookmarkEvents['response']['bookmarkedEvents'];
                }
            }

            foreach ($solrEventList as $recordKey => $recordValue) {
                $eventList[$recordKey]['id'] = $recordValue["id"];
                $eventList[$recordKey]['title'] = $recordValue["title"];
                
                
                if (isset($recordValue['thumbImage']) && $recordValue['thumbImage'] != '') {
                    $eventList[$recordKey]['thumbImage'] = $this->ci->config->item('images_content_cloud_path') . $recordValue["thumbImage"];
                } else {
                    $eventList[$recordKey]['thumbImage'] = '';
                }
                if (isset($recordValue['bannerImage']) && $recordValue['bannerImage'] != '') {
                    $eventList[$recordKey]['bannerImage'] = $this->ci->config->item('images_content_cloud_path') . $recordValue["bannerImage"];
                } else {
                    $eventList[$recordKey]['bannerImage'] = '';
                }
                
                
                $eventList[$recordKey]['timeZone'] = "";
                $timezoneId = $recordValue['timezoneId'];
                if ($timezoneId > 0) {
                    $eventList[$recordKey]['timeZone'] = $timezoneData[$timezoneId]['zone'];
                }
                $eventList[$recordKey]['startDate'] = allTimeFormats(convertTime($recordValue["startDateTime"],$timezoneData[$timezoneId]['name'],true), 11);                
                //$eventList[$recordKey]['startDate'] = allTimeFormats($recordValue["startDateTime"], 11);
                $eventList[$recordKey]['endDate'] = allTimeFormats(convertTime($recordValue["endDateTime"],$timezoneData[$timezoneId]['name'],true), 11);
                $eventList[$recordKey]['venueName'] = $recordValue["venueName"];
                $eventList[$recordKey]['eventUrl'] = commonHelperEventDetailUrl($recordValue["url"]);
                if(isset($recordValue["externalurl"]) && !empty($recordValue["externalurl"])){
                    $eventList[$recordKey]['eventExternalUrl'] = $recordValue["externalurl"];
                }
                if ($recordValue["categoryId"] > 0) {
                    $catDetails = $categoryData[$recordValue["categoryId"]];

                    $eventList[$recordKey]['categoryName'] = $catDetails['name'];
                    $eventList[$recordKey]['categoryIcon'] = ''; //$catDetails['iconimagefileid'];
                    $eventList[$recordKey]['themeColor'] = $catDetails['themecolor'];
                } else {
                    $eventList[$recordKey]['categoryName'] = "";
                    $eventList[$recordKey]['categoryIcon'] = "";
                    $eventList[$recordKey]['themeColor'] = "";
                }

                if ($eventList[$recordKey]['thumbImage'] == '') {
                    $eventList[$recordKey]['thumbImage'] = $catDetails['categorydefaultthumbnailid'];
                }
                if ($eventList[$recordKey]['bannerImage'] == '') {
                    $eventList[$recordKey]['bannerImage'] = $catDetails['categorydefaultbannerid'];
                }
                $eventList[$recordKey]['defaultBannerImage'] = $catDetails['categorydefaultbannerid'];
                $eventList[$recordKey]['defaultThumbImage'] = $catDetails['categorydefaultthumbnailid'];
                $eventList[$recordKey]['registrationType'] = eventTypeById($recordValue['registrationType'],$recordValue['eventMode']);
                
                
//                $timezoneId = $recordValue['timezoneId'];
//                if ($timezoneId > 0) {
//                    $eventList[$recordKey]['timeZone'] = $timezoneData[$timezoneId]['zone'];
//                }
                //Getting the City data
                /* if ($recordValue['cityId'] > 0) {
                  $request['cityId'] = $recordValue['cityId'];
                  $request['countryId'] = $recordValue['countryId'];
                  $cityData = $this->cityHandler->getCityDetailById($request);
                  }
                  if (count($cityData) > 0 && $cityData['status'] && $cityData['response']['total'] > 0) {
                  $cityObject = $cityData['response']['detail'];
                  $eventList[$recordKey]['cityName'] = $cityObject['name'];
                  } */
                //Getting the City data
                if ($recordValue['cityId'] > 0) {
                    if (isset($cityListData[$recordValue['cityId']]['name']))
                        $eventList[$recordKey]['cityName'] = $cityListData[$recordValue['cityId']]['name'];
                    else
                        $eventList[$recordKey]['cityName'] = "";
                }
                if ($recordValue['countryId'] > 0) {
                    if (isset($countryListData[$recordValue['countryId']]['name']))
                        $eventList[$recordKey]['countryName'] = $countryListData[$recordValue['countryId']]['name'];
                    else
                        $eventList[$recordKey]['countryName'] = "";
                }
                
                $eventList[$recordKey]['bookMarked'] = 0;
                if ($userId != '') {
                    if (in_array($recordValue["id"], $bookmarkEventsArray)) {
                        $eventList[$recordKey]['bookMarked'] = 1;
                    }
                }
                
                $latitude = $longitude = 0;
                if (isset($recordValue['latitude'])) {
                    $latitude = $recordValue['latitude'];
            }
                if (isset($recordValue['longitude'])) {
                    $longitude = $recordValue['longitude'];
                }
                $eventList[$recordKey]['latitude'] = $latitude;
                $eventList[$recordKey]['longitude'] = $longitude;

                $seatingEnabled = false;
                if (in_array($recordValue["id"], $seatingEventsIdArr)) {
                    $seatingEnabled = true;
                }
                $eventList[$recordKey]['seatingLayout'] = $seatingEnabled;
                $eventList[$recordKey]['booknowButtonValue'] = $recordValue['booknowbuttonvalue'];
                $eventList[$recordKey]['limitSingleTicketType'] = $recordValue['limitsingletickettype'];
                $eventList[$recordKey]['isMobileApiVisible'] = ($recordValue['isMobileApiVisible'] == 1) ? 1 : 0;
                $eventList[$recordKey]['isStandardApiVisible'] = ($recordValue['isStandardApiVisible'] == 1) ? 1 : 0;
                $eventList[$recordKey]['popularity'] = ($recordValue['popularity'] > 0) ? $recordValue['popularity'] : 0;
            }
            if (!isset($inputs['page'])) {
                $inputs['page'] = 1;
            }
            if (!isset($solrInputArray['limit'])) {
                $solrInputArray['limit'] = 12;
            }

            if ((($solrResults["response"]["total"] / $solrInputArray['limit'])) > $inputs['page']) {
                $nextPageStatus = true;
            }

            $output['status'] = TRUE;
            $output['response']['eventList'] = $eventList;
            $output['response']['page'] = $inputs['page'];
            $output['response']['limit'] = isset($inputs['limit']) ? $inputs['limit'] : $solrInputArray['limit'];
            $output['response']['nextPage'] = $nextPageStatus;
            $output['response']['total'] = $solrResults["response"]["total"];
            $output['statusCode'] = STATUS_OK;
            return $output;
        } else {
            $output['status'] = TRUE;
            $output['response']['messages'][] = ERROR_NO_DATA;
            $output['response']['total'] = 0;
            $output['statusCode'] = STATUS_OK;
            return $output;
        }
    }
    
    //To get the category details from category handler
    public function getcategoryDetails($categoryId) {
        $categoryHandler = new Category_handler();
        $catArray['categoryId'] = $categoryId;
        $outputResponse = array("name" => "", "icon" => "", "themecolor" => "");
        $catDetails = $categoryHandler->getCategoryDetails($catArray);
        if ($catDetails['status']) {
            $outputResponse["name"] = $catDetails["response"]['detail']["name"];
            $outputResponse["icon"] = ''; //$catDetails["response"]['detail']["iconimagefileid"];
            $outputResponse["themecolor"] = $catDetails["response"]['detail']["themecolor"];
            $outputResponse["categorydefaultbannerid"] = $catDetails["response"]['detail']["categorydefaultbannerid"];
            $outputResponse["categorydefaultthumbanilid"] = $catDetails["response"]['detail']["categorydefaultthumbnailid"];
        }
        return $outputResponse;
    }

    //Prepareing solr array
    public function solrArray($inputs) {
        $solrArray = array();

        $recordsPerPage = (isset($inputs['limit'])) ? $inputs['limit'] : 12;
        $staringRecordIndex = (isset($inputs['page'])) ? ($inputs['page'] - 1) * $recordsPerPage : 0;


        if (isset($inputs['countryId'])) {
            $solrArray['countryId'] = $inputs['countryId'];
        }
        if (isset($inputs['stateId'])) {
            $solrArray['stateId'] = $inputs['stateId'];
        }
        if (isset($inputs['cityId'])) {
            $solrArray['cityId'] = $inputs['cityId'];
        }
        if (isset($inputs['categoryId'])) {
            $solrArray['categoryId'] = $inputs['categoryId'];
        }
        if (isset($inputs['subcategoryId'])) {
            $solrArray['subcategoryId'] = $inputs['subcategoryId'];
        }
        if (isset($inputs['day'])) {
            $solrArray['day'] = $inputs['day'];
        }
        if (isset($inputs['dateValue'])) {
            $solrArray['dateValue'] = $inputs['dateValue'];
        }
        if (isset($inputs['page'])) {
            $solrArray['start'] = $staringRecordIndex;
        }
        if (isset($inputs['limit'])) {
            $solrArray['limit'] = $recordsPerPage;
        }
        if (isset($inputs['type']) && ($inputs['type'] >= 1 && $inputs['type'] <= 3)) {
            $solrArray['registrationType'] = $inputs['type'];
        }
        if (isset($inputs['type']) && $inputs['type'] == 4) {
            $solrArray['eventMode'] = 1;
        }
        if (isset($inputs['eventMode'])) {
            $solrArray['eventMode'] = $inputs['eventMode'];
        }
        if (isset($inputs['keyWord'])) {
            $solrArray['keyWord'] = $inputs['keyWord'];
        }
        if (isset($inputs['private'])) {
            $solrArray['private'] = $inputs['private'];
        }
        if (isset($inputs['status'])) {
            $solrArray['status'] = $inputs['status'];
        }
        if (isset($inputs['ticketSoldout'])) {
            $solrArray['ticketSoldout'] = $inputs['ticketSoldout'];
        }
        if (!isset($inputs['ticketSoldout'])) {
            $solrArray['ticketSoldout'] = 0;
        }
        if (isset($inputs['timeStamp'])) {
            $solrArray['timeStamp'] = $inputs['timeStamp'];
        }
        return $solrArray;
    }

    //event list validation
    public function eventValidation($inputs) {
        $errorMessages = array();
        $this->ci->form_validation->reset_form_rules();
        $this->ci->form_validation->pass_array($inputs);
        $this->ci->form_validation->set_rules('countryId', 'Country Id', 'is_natural_no_zero|required_strict');
        $this->ci->form_validation->set_rules('stateid', 'State Id', 'is_natural_no_zero');
        $this->ci->form_validation->set_rules('cityId', 'City Id', 'is_natural_no_zero');
        $this->ci->form_validation->set_rules('categoryId', 'Category Id', 'is_natural_no_zero');
        $this->ci->form_validation->set_rules('subcategoryId', 'Subcategory Id', 'is_natural_no_zero');
        $this->ci->form_validation->set_rules('day', 'Day', 'numeric|greater_than[0]|less_than[8]');
        $this->ci->form_validation->set_rules('page', 'Page', 'is_natural_no_zero');
        $this->ci->form_validation->set_rules('limit', 'limit', 'is_natural_no_zero');
        $this->ci->form_validation->set_rules('type', 'type', 'eventType');
        //$this->ci->form_validation->set_rules('dateValue', 'dateValue', 'specialDate');

        if ($this->ci->form_validation->run() === FALSE) {
            $errorMessages = $this->ci->form_validation->get_errors();
            return $errorMessages;
        }

        $errorMessages['error'] = FALSE;
        return $errorMessages;
    }
    //To Bring the specific event related information
    public function getEventDetails($request) {
        $fileHandler = new File_handler();
        $countryHandler = new Country_handler();
        $stateHandler = new State_handler();
        $cityHandler = new City_handler();
        $timezoneHandler = new Timezone_handler();
        $bookmarkHandler = new Bookmark_handler();
        $this->ci->load->model('Eventdetail_model');
        $output = array();

        $validationStatus = $this->eventDetailValidation($request);
        if ($validationStatus['error'] == TRUE) {
            $output['status'] = FALSE;
            $output['response']['messages'] = $validationStatus['message'];
            $output['statusCode'] = STATUS_BAD_REQUEST;
            return $output;
        }
        $this->ci->Event_model->resetVariable();
        $selectInput['id'] = $this->ci->Event_model->id;
        $selectInput['ownerId'] = $this->ci->Event_model->ownerid;
        $selectInput['startDate'] = $this->ci->Event_model->startdatetime;
        $selectInput['endDate'] = $this->ci->Event_model->enddatetime;
        $selectInput['title'] = $this->ci->Event_model->title;
        $selectInput['description'] = $this->ci->Event_model->description;
        $selectInput['countryId'] = $this->ci->Event_model->countryid;
        $selectInput['stateId'] = $this->ci->Event_model->stateid;
        $selectInput['cityId'] = $this->ci->Event_model->cityid;
        $selectInput['localityId'] = $this->ci->Event_model->localityid;
        $selectInput['venuename'] = $this->ci->Event_model->venue;
        $selectInput['url'] = $this->ci->Event_model->url;
        $selectInput['thumbnailfileid'] = $this->ci->Event_model->logo;
        $selectInput['bannerfileid'] = $this->ci->Event_model->banner;
        $selectInput['categoryId'] = $this->ci->Event_model->categoryid;
        $selectInput['subcategoryId'] = $this->ci->Event_model->subcategoryid;
        $selectInput['pincode'] = $this->ci->Event_model->pincode;
        $selectInput['registrationType'] = $this->ci->Event_model->registrationtype;
        $selectInput['eventMode'] = $this->ci->Event_model->eventmode;
        $selectInput['timeZoneId'] = $this->ci->Event_model->timezoneid;
        $selectInput['venueName'] = $this->ci->Event_model->venuename;
        $selectInput['venueaddress1'] = $this->ci->Event_model->venueaddress1;
        $selectInput['venueaddress2'] = $this->ci->Event_model->venueaddress2;
        $selectInput['private'] = $this->ci->Event_model->private;
        $selectInput['status'] = $this->ci->Event_model->status;
        $selectInput['latitude'] = $this->ci->Event_model->latitude;
        $selectInput['longitude'] = $this->ci->Event_model->longitude;
        $selectInput['acceptmeeffortcommission'] = $this->ci->Event_model->acceptmeeffortcommission;
        $this->ci->Event_model->setSelect($selectInput);

        $where[$this->ci->Event_model->id] = $request['eventId'];

        if (isset($request['userId']) && $request['userId'] > 0) {   // if user id is set then add condition
            $where[$this->ci->Event_model->ownerid] = $request['userId'];
        }

        $where[$this->ci->Event_model->deleted] = 0;
        $this->ci->Event_model->setWhere($where);
        $eventDetailsResponse = $this->ci->Event_model->get();
        if (count($eventDetailsResponse) == 0) {
            $output['status'] = TRUE;
            $output['response']['messages'][] = ERROR_NO_DATA;
            $output['response']['total'] = 0;
            $output['statusCode'] = STATUS_OK;
            return $output;
        } else {
            $eventDetails = $eventDetailsResponse[0];
            $fileIdList = array();
            if ($eventDetails['thumbnailfileid'] > 0) {
                $fileIdList[] = $eventDetails['thumbnailfileid'];
            }
            if ($eventDetails['bannerfileid'] > 0) {
                $fileIdList[] = $eventDetails['bannerfileid'];
            }
            // Getting the Banner and Thumbnail images from File Handler
            $eventDetails['bannerPath'] = "";
            $eventDetails['thumbnailPath'] = "";
			$eventDetails['description'] = stripslashes($eventDetails['description']);
            if (count($fileIdList) > 0) {

                $fileHandler = new File_handler();
                $eventFileidsData = array('id', $fileIdList);
                $fileData = $fileHandler->getFileData($eventFileidsData);
                if ($fileData['status'] && $fileData['response']['total'] > 0) {
                    //converting to indexed based array
                    $fileData = commonHelperGetIdArray($fileData['response']['fileData']);
                    //setting images paths
                    if (isset($fileData[$eventDetails['bannerfileid']]['path'])) {
                        $eventDetails['bannerPath'] = $this->ci->config->item('images_content_cloud_path') . $fileData[$eventDetails['bannerfileid']]['path'];
                    }
                    if (isset($fileData[$eventDetails['thumbnailfileid']]['path'])) {
                        $eventDetails['thumbnailPath'] = $this->ci->config->item('images_content_cloud_path') . $fileData[$eventDetails['thumbnailfileid']]['path'];
                    }
                }
            }

            // Getting the event details from event_details table
            $eventDetails['eventDetails'] = array();
            $selectDetailsInput['contactdetails'] = $this->ci->Eventdetail_model->eventdetail_contactdetails;
            $selectDetailsInput['bookButtonValue'] = $this->ci->Eventdetail_model->booknowbuttonvalue;
            $selectDetailsInput['facebookLink'] = $this->ci->Eventdetail_model->eventdetail_facebooklink;
            $selectDetailsInput['googleLink'] = $this->ci->Eventdetail_model->eventdetail_googlelink;
            $selectDetailsInput['twitterLink'] = $this->ci->Eventdetail_model->eventdetail_twitterlink;
            $selectDetailsInput['tnctype'] = $this->ci->Eventdetail_model->eventdetail_tnctype;
            $selectDetailsInput['meraeventstnc'] = $this->ci->Eventdetail_model->eventdetail_meraeventstnc;
            $selectDetailsInput['organizertnc'] = $this->ci->Eventdetail_model->eventdetail_organizertnc;
            $selectDetailsInput['contactWebsiteUrl'] = $this->ci->Eventdetail_model->eventdetail_contactwebsiteurl;
            $selectDetailsInput['limitSingleTicketType'] = $this->ci->Eventdetail_model->eventdetail_limitsingletickettype;
            $selectDetailsInput['salespersonid'] = $this->ci->Eventdetail_model->salespersonid;
            $selectDetailsInput['contactdisplay'] = $this->ci->Eventdetail_model->contactdisplay;
            $selectDetailsInput['customvalidationfunction'] = $this->ci->Eventdetail_model->customvalidationfunction;
            $selectDetailsInput['customvalidationflag'] = $this->ci->Eventdetail_model->customvalidationflag;
            $this->ci->Eventdetail_model->setSelect($selectDetailsInput);

            $whereDetails[$this->ci->Eventdetail_model->eventdetail_id] = $request['eventId'];
            $this->ci->Eventdetail_model->setWhere($whereDetails);
            $eventDetailsFrmTable = $this->ci->Eventdetail_model->get();

            if (count($eventDetailsFrmTable) > 0) {
                $eventDetails['eventDetails'] = $eventDetailsFrmTable[0];
            }
            $eventDetails['categoryName'] = "";
            $eventDetails['categoryThemeColor'] = "";
            //Getting the Category data
            if (!empty($eventDetails["categoryId"])) {
                $catDetails = $this->getcategoryDetails($eventDetails["categoryId"]);
                $eventDetails['categoryName'] = $catDetails['name'];
                $eventDetails['categoryThemeColor'] = $catDetails['themecolor'];
            }
            if (isset($request['editEvent']) && $request['editEvent'] == true) {
                if ($eventDetails['thumbnailPath'] == '') {
                    $eventDetails['thumbnailPath'] = '';
                }
                if ($eventDetails['bannerPath'] == '') {
                    $eventDetails['bannerPath'] = '';
                }
            } else {
                $copyEvent = 0;
                if (isset($request['copyEvent'])) {
                    $copyEvent = $request['copyEvent'];
                }
                if ($eventDetails['thumbnailPath'] == '' && $copyEvent != 1) {
                    $eventDetails['thumbnailPath'] = $catDetails['categorydefaultthumbanilid'];
                }
                if ($eventDetails['bannerPath'] == '' && $copyEvent != 1) {
                    $eventDetails['bannerPath'] = $catDetails['categorydefaultbannerid'];
                }
            }
            $eventDetails['defaultthumbnailPath'] = $catDetails['categorydefaultthumbanilid'];
            //Getting the Subcategory details
            if (!empty($eventDetails["subcategoryId"])) {
                $this->subcategoryHandler = new Subcategory_handler();
                $subcatDetails = $this->subcategoryHandler->getSubcategoryDetails($eventDetails);
                if ($subcatDetails['status'] == TRUE && $subcatDetails['response']['total'] > 0) {

                    $eventDetails['subCategoryName'] = $subcatDetails['response']['subCategoryList'][0]['name'];
                }
            }
            //get sales person contact details when event contact details not present 
            //support contact details should shown when sales person details not available

            $countryData = $stateData = $cityData = array();
            //Getting the Country data
            if ($eventDetails['countryId'] > 0) {
                $countryData = $countryHandler->getCountryListById(array('countryId' => $eventDetails['countryId']));
            }
            if (count($countryData) > 0 && $countryData['status'] && $countryData['response']['total'] > 0) {
                $locationDetails['countryId'] = $eventDetails['countryId'];
                $locationDetails['countryName'] = $countryData['response']['detail']['name'];
            }

            //Getting the State data
            if ($eventDetails['stateId'] > 0) {
                $stateData = $stateHandler->getStateListById(array('stateId' => $eventDetails['stateId'], 'nostatus' => true));
            }
            if (count($stateData) > 0 && $stateData['status'] && $stateData['response']['total'] > 0) {
                $stateList = $stateData['response']['stateList'][0];
                $locationDetails['stateId'] = $eventDetails['stateId'];
                $locationDetails['stateName'] = $stateList['name'];
            }

            //Getting the City data
            if ($eventDetails['cityId'] > 0) {
                $request['cityId'] = $eventDetails['cityId'];
                $request['countryId'] = $eventDetails['countryId'];
                $cityData = $cityHandler->getCityDetailById($request);
            }
            if (count($cityData) > 0 && $cityData['status'] && $cityData['response']['total'] > 0) {
                $cityObject = $cityData['response']['detail'];
                $locationDetails['cityId'] = $eventDetails['cityId'];
                $locationDetails['cityName'] = $cityObject['name'];
            }


            //Preparing the venue details 
            $locationDetails['venueName'] = $eventDetails['venuename'];
            $locationDetails['address1'] = $eventDetails['venueaddress1'];
            $locationDetails['address2'] = $eventDetails['venueaddress2'];
            $locationDetails['pincode'] = $eventDetails['pincode'];

            $eventDetails['eventUrl'] = commonHelperEventDetailUrl($eventDetails["url"]);

            //To Featching the tags information
            $eventTagData['eventId'] = $request['eventId'];
            $eventTagsListResponse = $this->getEventTagIds($eventTagData);
            $eventTagsList = array();
            $eventDetails['tags'] = "";
            if ($eventTagsListResponse['status'] && $eventTagsListResponse['response']['total'] > 0) {
                $eventTagsList = $eventTagsListResponse['response']['eventTagsList'];
                $tagsListArray = $this->getEventTagNames($eventTagsList);
                if ($tagsListArray['status'] && $tagsListArray['response']['total'] > 0) {
                    $eventDetails['tags'] = implode(',', array_column($tagsListArray['response']['tags'], 'name'));
                }
            }



            $timezoneDetails = $timezoneHandler->details(array('timezoneId' => $eventDetails['timeZoneId']));
            if ($timezoneDetails['status'] && count($timezoneDetails) > 0) {
                $locationDetails['timeZone'] = $timezoneDetails['response']['detail'][$eventDetails['timeZoneId']]['zone'];
                $locationDetails['timeZoneName'] = $timezoneDetails['response']['detail'][$eventDetails['timeZoneId']]['name'];
            }

            $eventDetails['bookMarked'] = 0;
            $bookmarkEvents = array();
            $userId = $this->ci->customsession->getUserId();
            if ($userId != '') {
                $bookmarkInputs['userId'] = $userId;
                $bookmarkInputs['returnEventIds'] = true;
                $bookmarkInputs['eventId'] = $request['eventId'];
                $bookmarkEvents = $bookmarkHandler->getUserBookmarks($bookmarkInputs);
                if ($bookmarkEvents['status'] && $bookmarkEvents['response']['total'] > 0) {
                    $bookmarkEventsArray = $bookmarkEvents['response']['bookmarkedEvents'];
                    if (in_array($eventDetails['id'], $bookmarkEventsArray)) {
                        $eventDetails['bookMarked'] = 1;
                    }
                }
            }


            //unseting the un wanted field names
            unset($eventDetails['stateId']);
            unset($eventDetails['cityId']);
            unset($eventDetails['venuename']);
            unset($eventDetails['venueaddress1']);
            unset($eventDetails['venueaddress2']);
            unset($eventDetails['countryId']);


            $output['status'] = TRUE;
            $output['response']['messages'] = array();
            $output['response']['total'] = 1;
            $output['response']['details'] = $eventDetails;
            $output['response']['details']['location'] = $locationDetails;
            $output['statusCode'] = STATUS_OK;
            return $output;
        }
    }

    public function getEventLocationDetails($request) {
        $fileHandler = new File_handler();
        $countryHandler = new Country_handler();
        $stateHandler = new State_handler();
        $cityHandler = new City_handler();
        $timezoneHandler = new Timezone_handler();
        $bookmarkHandler = new Bookmark_handler();
        $this->ci->load->model('Eventdetail_model');
        $output = array();

        $validationStatus = $this->eventDetailValidation($request);
        if ($validationStatus['error'] == TRUE) {
            $output['status'] = FALSE;
            $output['response']['messages'] = $validationStatus['message'];
            $output['statusCode'] = STATUS_BAD_REQUEST;
            return $output;
        }
        $this->ci->Event_model->resetVariable();
        $selectInput['id'] = $this->ci->Event_model->id;
        $selectInput['title'] = $this->ci->Event_model->title;
        $selectInput['startDate'] = $this->ci->Event_model->startdatetime;
        $selectInput['endDate'] = $this->ci->Event_model->enddatetime;
        $selectInput['description'] = $this->ci->Event_model->description;
        $selectInput['categoryId'] = $this->ci->Event_model->categoryid;
        $selectInput['countryId'] = $this->ci->Event_model->countryid;
        $selectInput['stateId'] = $this->ci->Event_model->stateid;
        $selectInput['cityId'] = $this->ci->Event_model->cityid;
        $selectInput['localityId'] = $this->ci->Event_model->localityid;
        $selectInput['venuename'] = $this->ci->Event_model->venue;
        $selectInput['url'] = $this->ci->Event_model->url;
        $selectInput['thumbnailfileid'] = $this->ci->Event_model->logo;
        $selectInput['pincode'] = $this->ci->Event_model->pincode;
        $selectInput['venueName'] = $this->ci->Event_model->venuename;
        $selectInput['venueaddress1'] = $this->ci->Event_model->venueaddress1;
        $selectInput['venueaddress2'] = $this->ci->Event_model->venueaddress2;
        $selectInput['timeZoneId'] = $this->ci->Event_model->timezoneid;

        $this->ci->Event_model->setSelect($selectInput);

        $where[$this->ci->Event_model->id] = $request['eventId'];

        if (isset($request['userId']) && $request['userId'] > 0) {   // if user id is set then add condition
            $where[$this->ci->Event_model->ownerid] = $request['userId'];
        }

        $where[$this->ci->Event_model->deleted] = 0;
        $this->ci->Event_model->setWhere($where);
        $eventDetailsResponse = $this->ci->Event_model->get();
        if (count($eventDetailsResponse) == 0) {
            $output['status'] = TRUE;
            $output['response']['messages'][] = ERROR_NO_DATA;
            $output['response']['total'] = 0;
            $output['statusCode'] = STATUS_OK;
            return $output;
        } else {
            $eventDetails = $eventDetailsResponse[0];
            $fileIdList = array();
            if ($eventDetails['thumbnailfileid'] > 0) {
                $fileIdList[] = $eventDetails['thumbnailfileid'];
            }

            // Getting the Banner and Thumbnail images from File Handler
            $eventDetails['thumbnailPath'] = "";

            if (count($fileIdList) > 0) {

                $fileHandler = new File_handler();
                $eventFileidsData = array('id', $fileIdList);
                $fileData = $fileHandler->getFileData($eventFileidsData);
                if ($fileData['status'] && $fileData['response']['total'] > 0) {
                    //converting to indexed based array
                    $fileData = commonHelperGetIdArray($fileData['response']['fileData']);
                    //setting images paths
                    $eventDetails['thumbnailPath'] = $this->ci->config->item('images_content_cloud_path') . $fileData[$eventDetails['thumbnailfileid']]['path'];
                }
            }

            // print_r($eventDetails);exit;
            //Getting the Category data
            if (!empty($eventDetails["categoryId"])) {
                $noImages = false;
                $catDetails = $this->getcategoryDetails($eventDetails["categoryId"], $noImages);
                $eventDetails['categoryName'] = $catDetails['name'];
                $eventDetails['categoryThemeColor'] = $catDetails['themecolor'];
            }

            if ($eventDetails['thumbnailPath'] == '') {
                $eventDetails['thumbnailPath'] = $catDetails['categorydefaultthumbanilid'];
            }
            $eventDetails['defaultthumbnailPath'] = $catDetails['categorydefaultthumbanilid'];

            $countryData = $stateData = $cityData = array();
            //Getting the Country data
            if ($eventDetails['countryId'] > 0) {
                $countryData = $countryHandler->getCountryListById(array('countryId' => $eventDetails['countryId']));
            }
            if (count($countryData) > 0 && $countryData['status'] && $countryData['response']['total'] > 0) {
                $locationDetails['countryId'] = $eventDetails['countryId'];
                $locationDetails['countryName'] = $countryData['response']['detail']['name'];
            }

            //Getting the State data
            if ($eventDetails['stateId'] > 0) {
                $stateData = $stateHandler->getStateListById(array('stateId' => $eventDetails['stateId']));
            }
            if (count($stateData) > 0 && $stateData['status'] && $stateData['response']['total'] > 0) {
                $stateList = $stateData['response']['stateList'][0];
                $locationDetails['stateId'] = $eventDetails['stateId'];
                $locationDetails['stateName'] = $stateList['name'];
            }

            //Getting the City data
            if ($eventDetails['cityId'] > 0) {
                $request['cityId'] = $eventDetails['cityId'];
                $request['countryId'] = $eventDetails['countryId'];
                $cityData = $cityHandler->getCityDetailById($request);
            }
            if (count($cityData) > 0 && $cityData['status'] && $cityData['response']['total'] > 0) {
                $cityObject = $cityData['response']['detail'];
                $locationDetails['cityId'] = $eventDetails['cityId'];
                $locationDetails['cityName'] = $cityObject['name'];
            }


            //Preparing the venue details 
            $locationDetails['venueName'] = $eventDetails['venuename'];
            $locationDetails['address1'] = $eventDetails['venueaddress1'];
            $locationDetails['address2'] = $eventDetails['venueaddress2'];

            $eventDetails['eventUrl'] = commonHelperEventDetailUrl($eventDetails["url"]);

            $timezoneDetails = $timezoneHandler->details(array('timezoneId' => $eventDetails['timeZoneId']));
            if ($timezoneDetails['status'] && count($timezoneDetails) > 0) {
                $locationDetails['timeZone'] = $timezoneDetails['response']['detail'][$eventDetails['timeZoneId']]['zone'];
                $locationDetails['timeZoneName'] = $timezoneDetails['response']['detail'][$eventDetails['timeZoneId']]['name'];
            }

            $eventStartDate = convertTime($eventDetails['startDate'], $locationDetails['timeZoneName'], TRUE);
            $eventEndDate = convertTime($eventDetails['endDate'], $locationDetails['timeZoneName'], TRUE);

            $eventDetails['startDate'] = $eventStartDate;
            $eventDetails['endDate'] = $eventEndDate;

            //unseting the un wanted field names
            unset($eventDetails['stateId']);
            unset($eventDetails['cityId']);
            unset($eventDetails['venuename']);
            unset($eventDetails['venueaddress1']);
            unset($eventDetails['venueaddress2']);
            unset($eventDetails['countryId']);


            $output['status'] = TRUE;
            $output['response']['messages'] = array();
            $output['response']['total'] = 1;
            $output['response']['details'] = $eventDetails;
            $output['response']['details']['location'] = $locationDetails;
            $output['statusCode'] = STATUS_OK;
            return $output;
        }
    }

    public function getEventPageDetails($request) {
        $fileHandler = new File_handler();
        $countryHandler = new Country_handler();
        $stateHandler = new State_handler();
        $cityHandler = new City_handler();
        $timezoneHandler = new Timezone_handler();
        $bookmarkHandler = new Bookmark_handler();
        $this->ci->load->model('Eventdetail_model');
        $output = array();

        $validationStatus = $this->eventDetailValidation($request);
        if ($validationStatus['error'] == TRUE) {
            $output['status'] = FALSE;
            $output['response']['messages'] = $validationStatus['message'];
            $output['statusCode'] = STATUS_BAD_REQUEST;
            return $output;
        }
        $this->ci->Event_model->resetVariable();
        $selectInput['id'] = $this->ci->Event_model->id;
        $selectInput['ownerId'] = $this->ci->Event_model->ownerid;
        $selectInput['startDate'] = $this->ci->Event_model->startdatetime;
        $selectInput['endDate'] = $this->ci->Event_model->enddatetime;
        $selectInput['title'] = $this->ci->Event_model->title;
        $selectInput['description'] = $this->ci->Event_model->description;
        $selectInput['countryId'] = $this->ci->Event_model->countryid;
        $selectInput['stateId'] = $this->ci->Event_model->stateid;
        $selectInput['cityId'] = $this->ci->Event_model->cityid;
        $selectInput['localityId'] = $this->ci->Event_model->localityid;
        $selectInput['venuename'] = $this->ci->Event_model->venue;
        $selectInput['url'] = $this->ci->Event_model->url;
        $selectInput['thumbnailfileid'] = $this->ci->Event_model->logo;
        $selectInput['bannerfileid'] = $this->ci->Event_model->banner;
        $selectInput['categoryId'] = $this->ci->Event_model->categoryid;
        $selectInput['subcategoryId'] = $this->ci->Event_model->subcategoryid;
        $selectInput['pincode'] = $this->ci->Event_model->pincode;
        $selectInput['registrationType'] = $this->ci->Event_model->registrationtype;
        $selectInput['eventMode'] = $this->ci->Event_model->eventmode;
        $selectInput['timeZoneId'] = $this->ci->Event_model->timezoneid;
        $selectInput['venueName'] = $this->ci->Event_model->venuename;
        $selectInput['venueaddress1'] = $this->ci->Event_model->venueaddress1;
        $selectInput['venueaddress2'] = $this->ci->Event_model->venueaddress2;
        $selectInput['private'] = $this->ci->Event_model->private;
        $selectInput['status'] = $this->ci->Event_model->status;


        $this->ci->Event_model->setSelect($selectInput);

        $where[$this->ci->Event_model->id] = $request['eventId'];

        if (isset($request['userId']) && $request['userId'] > 0) {   // if user id is set then add condition
            $where[$this->ci->Event_model->ownerid] = $request['userId'];
        }

        $where[$this->ci->Event_model->deleted] = 0;
        $this->ci->Event_model->setWhere($where);

        $eventDetailsResponse = $this->ci->Event_model->get();

        if (count($eventDetailsResponse) == 0) {
            $output['status'] = TRUE;
            $output['response']['messages'][] = ERROR_NO_DATA;
            $output['response']['total'] = 0;
            $output['statusCode'] = STATUS_OK;
            return $output;
        } else {
            $eventDetails = $eventDetailsResponse[0];

            if (isset($eventDetails['thumbnailfileid']) && $eventDetails['thumbnailfileid'] > 0) {
                $fileIdList[] = $eventDetails['thumbnailfileid'];
            }
            if (isset($eventDetails['bannerfileid']) && $eventDetails['bannerfileid'] > 0) {
                $fileIdList[] = $eventDetails['bannerfileid'];
            }

            if (count($fileIdList) > 0) {
                // Getting the Banner and Thumbnail images from File Handler
                $eventFileidsData = array('id', $fileIdList);
                $fileData = $fileHandler->getFileData($eventFileidsData);
                if ($fileData['status'] && $fileData['response']['total'] > 0) {
                    //converting to indexed based array
                    $fileData = commonHelperGetIdArray($fileData['response']['fileData']);
                    //setting images paths
                    $eventDetails['bannerPath'] = $this->ci->config->item('images_content_cloud_path') . $fileData[$eventDetails['bannerfileid']]['path'];
                    $eventDetails['thumbnailPath'] = $this->ci->config->item('images_content_cloud_path') . $fileData[$eventDetails['thumbnailfileid']]['path'];
                    // Default banner path
                    //$eventDetails['defaultBannerImage'] = $this->ci->config->item('images_static_path') . DEFAULT_EVENT_BANNER_IMAGE;
                } else {
                    $eventDetails['bannerPath'] = "";
                    $eventDetails['thumbnailPath'] = "";
                }
            } else {
                $eventDetails['bannerPath'] = "";
                $eventDetails['thumbnailPath'] = "";
            }

            // Getting the event details from event_details table
            $eventDetails['eventDetails'] = array();
            $selectDetailsInput['contactdetails'] = $this->ci->Eventdetail_model->eventdetail_contactdetails;
            $selectDetailsInput['bookButtonValue'] = $this->ci->Eventdetail_model->booknowbuttonvalue;
            $selectDetailsInput['facebookLink'] = $this->ci->Eventdetail_model->eventdetail_facebooklink;
            $selectDetailsInput['googleLink'] = $this->ci->Eventdetail_model->eventdetail_googlelink;
            $selectDetailsInput['twitterLink'] = $this->ci->Eventdetail_model->eventdetail_twitterlink;
            $selectDetailsInput['tnctype'] = $this->ci->Eventdetail_model->eventdetail_tnctype;
            $selectDetailsInput['meraeventstnc'] = $this->ci->Eventdetail_model->eventdetail_meraeventstnc;
            $selectDetailsInput['organizertnc'] = $this->ci->Eventdetail_model->eventdetail_organizertnc;
            $selectDetailsInput['contactWebsiteUrl'] = $this->ci->Eventdetail_model->eventdetail_contactwebsiteurl;
            $selectDetailsInput['limitSingleTicketType'] = $this->ci->Eventdetail_model->eventdetail_limitsingletickettype;
            $selectDetailsInput['salespersonid'] = $this->ci->Eventdetail_model->salespersonid;
            $selectDetailsInput['contactdisplay'] = $this->ci->Eventdetail_model->contactdisplay;
            $selectDetailsInput['seotitle'] = $this->ci->Eventdetail_model->eventdetail_seotitle;
            $selectDetailsInput['seokeywords'] = $this->ci->Eventdetail_model->eventdetail_seokeywords;
            $selectDetailsInput['seodescription'] = $this->ci->Eventdetail_model->eventdetail_seodescription;
            $selectDetailsInput['conanicalurl'] = $this->ci->Eventdetail_model->eventdetail_conanicalurl;

            $selectDetailsInput['googleanalyticsscripts'] = $this->ci->Eventdetail_model->googleanalyticsscripts;
            $selectDetailsInput['confirmationpagescripts'] = $this->ci->Eventdetail_model->confirmationpagescripts;
$selectDetailsInput['viewcount'] = $this->ci->Eventdetail_model->viewcount;
			$selectDetailsInput['promotionaltext'] = $this->ci->Eventdetail_model->promotionaltext;

            $this->ci->Eventdetail_model->setSelect($selectDetailsInput);

            $whereDetails[$this->ci->Eventdetail_model->eventdetail_id] = $request['eventId'];
            $this->ci->Eventdetail_model->setWhere($whereDetails);
            $eventDetailsFrmTable = $this->ci->Eventdetail_model->get();

            if (count($eventDetailsFrmTable) > 0) {
                $eventDetails['eventDetails'] = $eventDetailsFrmTable[0];
            }
            $eventDetails['categoryName'] = "";
            $eventDetails['categoryThemeColor'] = "";
            //Getting the Category data
            if (!empty($eventDetails["categoryId"])) {

                $catDetails = $this->getcategoryDetails($eventDetails["categoryId"]);
                $eventDetails['categoryName'] = $catDetails['name'];
                $eventDetails['categoryThemeColor'] = $catDetails['themecolor'];
                $eventDetails['defaultBannerImage'] = $catDetails['categorydefaultbannerid'];
            }
            if ($eventDetails['bannerPath'] == '') {
                $eventDetails['bannerPath'] = $catDetails['categorydefaultbannerid'];
            }
            //Getting the Subcategory details
            if (!empty($eventDetails["subcategoryId"])) {
                $this->subcategoryHandler = new Subcategory_handler();
                $subcatDetails = $this->subcategoryHandler->getSubcategoryDetails($eventDetails);
                if ($subcatDetails['status'] == TRUE && $subcatDetails['response']['total'] > 0) {

                    $eventDetails['subCategoryName'] = $subcatDetails['response']['subCategoryList'][0]['name'];
                }
            }
            //get sales person contact details when event contact details not present 
            //support contact details should shown when sales person details not available

            $countryData = $stateData = $cityData = array();
            //Getting the Country data
            if ($eventDetails['countryId'] > 0) {
                $countryData = $countryHandler->getCountryListById(array('countryId' => $eventDetails['countryId']));
            }
            if (count($countryData) > 0 && $countryData['status'] && $countryData['response']['total'] > 0) {
                $locationDetails['countryId'] = $eventDetails['countryId'];
                $locationDetails['countryName'] = $countryData['response']['detail']['name'];
            }

            //Getting the State data
            if ($eventDetails['stateId'] > 0) {
                $stateData = $stateHandler->getStateListById(array('stateId' => $eventDetails['stateId']));
            }
            if (count($stateData) > 0 && $stateData['status'] && $stateData['response']['total'] > 0) {
                $stateList = $stateData['response']['stateList'][0];
                $locationDetails['stateId'] = $eventDetails['stateId'];
                $locationDetails['stateName'] = $stateList['name'];
            }

            //Getting the City data
            if ($eventDetails['cityId'] > 0) {
                $request['cityId'] = $eventDetails['cityId'];
                $request['countryId'] = $eventDetails['countryId'];
                $cityData = $cityHandler->getCityDetailById($request);
            }
            if (count($cityData) > 0 && $cityData['status'] && $cityData['response']['total'] > 0) {
                $cityObject = $cityData['response']['detail'];
                $locationDetails['cityId'] = $eventDetails['cityId'];
                $locationDetails['cityName'] = $cityObject['name'];
            }


            //Preparing the venue details 
            $locationDetails['venueName'] = $eventDetails['venuename'];
            $locationDetails['address1'] = $eventDetails['venueaddress1'];
            $locationDetails['address2'] = $eventDetails['venueaddress2'];

            $eventDetails['eventUrl'] = commonHelperEventDetailUrl($eventDetails["url"]);




            $timezoneDetails = $timezoneHandler->details(array('timezoneId' => $eventDetails['timeZoneId']));
            if ($timezoneDetails['status'] && count($timezoneDetails) > 0) {
                $locationDetails['timeZone'] = $timezoneDetails['response']['detail'][$eventDetails['timeZoneId']]['zone'];
                $locationDetails['timeZoneName'] = $timezoneDetails['response']['detail'][$eventDetails['timeZoneId']]['name'];
            }

            $eventDetails['bookMarked'] = 0;
            $bookmarkEvents = array();
            $userId = $this->ci->customsession->getUserId();
            if ($userId != '') {
                $bookmarkInputs['userId'] = $userId;
                $bookmarkInputs['returnEventIds'] = true;
                if (isset($request['eventId']) && $request['eventId'] != '') {
                    $bookmarkInputs['eventId'] = $request['eventId'];
                }
                $bookmarkEvents = $bookmarkHandler->getUserBookmarks($bookmarkInputs);
                if ($bookmarkEvents['status'] && $bookmarkEvents['response']['total'] > 0) {
                    $bookmarkEventsArray = $bookmarkEvents['response']['bookmarkedEvents'];
                    if (in_array($eventDetails['id'], $bookmarkEventsArray)) {
                        $eventDetails['bookMarked'] = 1;
                    }
                }
            }


            $eventStartDate = convertTime($eventDetails['startDate'], $locationDetails['timeZoneName'], TRUE);
            $eventEndDate = convertTime($eventDetails['endDate'], $locationDetails['timeZoneName'], TRUE);
            $eventDetails['startDate'] = $eventStartDate;
            $eventDetails['endDate'] = $eventEndDate;

            //Event related seo data
            //SEO Title
            if (!empty(strip_tags(stripslashes($eventDetails['eventDetails']['seotitle'])))) {
                $eventDetails['eventDetails']['seotitle'] = strip_tags(stripslashes($eventDetails['eventDetails']['seotitle']));
            } else {
                $eventDetails['eventDetails']['seotitle'] = strip_tags(stripslashes($eventDetails['title']));
            }
            //SEO description
            if (!empty(stripslashes($eventDetails['eventDetails']['seodescription']))) {
                $eventDetails['eventDetails']['seodescription'] = substr(stripslashes(stripslashes($eventDetails['eventDetails']['seodescription'])), 0, 150);
            } else {
                $eventDetails['eventDetails']['seodescription'] = str_replace('EVENTNAME', $eventDetails['title'], SEO_DEFAULT_DESCRIPTION);
            }
            //SEO keywords
            if (!empty(strip_tags(stripslashes($eventDetails['eventDetails']['seokeywords'])))) {
                $eventDetails['eventDetails']['seokeywords'] = strip_tags(stripslashes($eventDetails['eventDetails']['seokeywords']));
            } else {
                $eventDetails['eventDetails']['seokeywords'] = SEO_DEFAULT_KEYWORDS;
            }
            //SEO url
            if (!empty($eventDetails['eventDetails']['conanicalurl'])) {
                $eventDetails['eventDetails']['conanicalurl'] = $redirectUrl = $eventDetails['eventDetails']['conanicalurl'];
            } else {
                $eventDetails['eventDetails']['conanicalurl'] = "";
            }


            //unseting the un wanted field names
            unset($eventDetails['stateId']);
            unset($eventDetails['cityId']);
            unset($eventDetails['venuename']);
            unset($eventDetails['venueaddress1']);
            unset($eventDetails['venueaddress2']);
            unset($eventDetails['countryId']);


            $output['status'] = TRUE;
            $output['response']['messages'] = array();
            $output['response']['total'] = 1;
            $output['response']['details'] = $eventDetails;
            $output['response']['details']['location'] = $locationDetails;
            $output['statusCode'] = STATUS_OK;

            //print_r($output);

            return $output;
        }
    }

    public function getEventTicketDetails($inputs) {
        $ticketHandler = new Ticket_handler();
        $validationStatus = $this->eventDetailValidation($inputs);
        if ($validationStatus['error'] == TRUE) {
            $output['status'] = FALSE;
            $output['response']['messages'] = $validationStatus['message'];
            $output['statusCode'] = STATUS_BAD_REQUEST;
            return $output;
        }

        $inputArray = array();
        $inputArray['eventId'] = $inputs['eventId'];
        if (isset($inputs['ticketId']) && $inputs['ticketId'] > 0) {
            $inputArray['ticketId'] = $inputs['ticketId'];
        }
        if (isset($inputs['allTickets']) && $inputs['allTickets'] != '') {
            $inputArray['allTickets'] = $inputs['allTickets'];
        }
        if (isset($inputs['statuslabels']) && $inputs['statuslabels'] == 1) {
            $inputArray['statuslabels'] = $inputs['statuslabels'];
        }
//        $event['eventId']=$inputs['eventId'];
//        $event=$this->getEventTimeZoneName($event);
//        $inputArray['timeZoneName'] = $event['response']['details']['location']['timeZoneName'];
        $response = $ticketHandler->getEventTicketList($inputArray);

        return $response;
    }

    public function getActualEventTicketDetails($inputs) {
        $ticketHandler = new Ticket_handler();
        $validationStatus = $this->eventDetailValidation($inputs);
        if ($validationStatus['error'] == TRUE) {
            $output['status'] = FALSE;
            $output['response']['messages'] = $validationStatus['message'];
            $output['statusCode'] = STATUS_BAD_REQUEST;
            return $output;
        }

        $inputArray = array();
        $inputArray['eventId'] = $inputs['eventId'];
        if (isset($inputs['ticketId']) && $inputs['ticketId'] > 0) {
            $inputArray['ticketId'] = $inputs['ticketId'];
        }
        if (isset($inputs['allTickets']) && $inputs['allTickets'] != '') {
            $inputArray['allTickets'] = $inputs['allTickets'];
        }
        $inputArray['eventTimeZoneName'] = $inputs['eventTimeZoneName'];
        $response = $ticketHandler->getActualEventTicketList($inputArray);

        return $response;
    }

    //event list validation
    public function eventDetailValidation($inputs) {
        $errorMessages = array();
        $this->ci->form_validation->reset_form_rules();
        $this->ci->form_validation->pass_array($inputs);
        $this->ci->form_validation->set_rules('eventId', 'event Id', 'required_strict|is_natural_no_zero');
        $this->ci->form_validation->set_rules('ticketId', 'ticket Id', 'is_natural_no_zero');


        if ($this->ci->form_validation->run() === FALSE) {
            $errorMessages = $this->ci->form_validation->get_errors();
            return $errorMessages;
        }

        $errorMessages['error'] = FALSE;
        return $errorMessages;
    }

    /*
     * Function to get the Events list based on City
     *
     * @access	public
     * @param	$inputArray contains
     * 				countryId - integer
     * 				StateId - optional - If StatId > 0, then it returns Event count of that `State Id` also
     * 				CityId - optional - If not given it will consider all cities
     * @return	array
     */

    public function getEventListCityWise($inputArray) {
        $this->ci->form_validation->pass_array($inputArray);
        $this->ci->form_validation->set_rules('countryId', 'countryId', 'required_strict|is_natural_no_zero');
        //$this->ci->form_validation->set_rules('stateId', 'stateid', 'required_strict');
        //$this->ci->form_validation->set_rules('cityId', 'cityId', 'required_strict');

        if ($this->ci->form_validation->run() === FALSE) {
            $errorMessages = $this->ci->form_validation->get_errors();
            $output['status'] = FALSE;
            $output['response']['messages'] = $errorMessages['message'];
            $output['statusCode'] = STATUS_BAD_REQUEST;
            return $output;
        }
        //Feacth the solr search results
        $solrHandler = new Solr_handler();
        $solrInputArray = $this->solrArray($inputArray);
        $solrResults = $solrHandler->getSolrEvents($solrInputArray);
        $solrResults = json_decode($solrResults, true);

        //solr level validations
        if ((isset($solrResults["response"]["error"])) && $solrResults["response"]["error"] == true) {
            $output['status'] = FALSE;
            $output['response']['messages'] = $solrResults["response"]['message'];
            $output['statusCode'] = STATUS_BAD_REQUEST;
            return $output;
        }

        $output['response']['stateEventCount'] = 0;
        if (isset($inputArray['stateId']) && $inputArray['stateId'] > 0) {
            $this->searchHandler = new Search_handler();
            $requests = array('countryId' => $inputArray['countryId'], 'facetType' => 'state', 'facetValues' => array($inputArray['stateId']));
            $stateCountJson = $this->searchHandler->citesEventCount($requests);
            $stateCountResults = json_decode($stateCountJson, true);
            if ($stateCountResults['response']['status']) {
                $output['response']['stateEventCount'] = $stateCountResults['response']['result']['facetCounts']['stateId'][0][1];
            }
        }

        $eventList = array();
        $solrEventList = $solrResults["response"]["events"];
        if (count($solrEventList) > 0) {

            foreach ($solrEventList as $recordKey => $recordValue) {
                $eventList[$recordKey]['id'] = $recordValue["id"];
                $eventList[$recordKey]['title'] = $recordValue["title"];
                $eventList[$recordKey]['thumbImage'] = $recordValue["thumbImage"];
                $eventList[$recordKey]['startDate'] = $recordValue["startDateTime"];
                $eventList[$recordKey]['venue'] = $recordValue["venueName"];
                $eventList[$recordKey]['eventUrl'] = $recordValue["url"];

                // Getting the Event category details
                $catDetails = $this->getcategoryDetails($recordValue["categoryId"]);

                $eventList[$recordKey]['categoryName'] = $catDetails['name'];
                $eventList[$recordKey]['categoryIcon'] = $catDetails['icon'];
                $eventList[$recordKey]['timeZone'] = $recordValue["timezoneId"];
            }
            $output['status'] = TRUE;
            $output['response']['total'] = count($eventList);
            $output['response']['eventList'] = $eventList;
            $output['statusCode'] = STATUS_OK;
            return $output;
        } else {
            $output = $solrResults;
            $output['status'] = TRUE;
            $output["response"]["messages"][] = ERROR_NO_DATA;
            $output['response']['total'] = 0;
            $output['statusCode'] = STATUS_OK;
            return $output;
        }
    }

    //To feacth the event owener information details
    public function getEventContactInfo($inputArray) {
        $this->userHandler = new User_handler();
        $ownerData = $this->userHandler->getUserData($inputArray);
        return $ownerData;
    }

    /*
     * Function to get the calculation data based on ticket selection
     *
     * @access	public
     * @param	$inputArray contains
     * 			Tickets Array with Ticket Id and Quantity
     * 			Event Id
     * @return	array
     */

   public function getEventTicketCalculation($inputArray) {
        $ticketHandler = new Ticket_handler();
        $output = array();
        $this->ci->form_validation->reset_form_rules();
        $this->ci->form_validation->pass_array($inputArray);
        $this->ci->form_validation->set_rules('eventId', 'eventId', 'required_strict|is_natural_no_zero');
        //$this->ci->form_validation->set_rules('ticketArray', 'ticketArray', 'is_array');
        //$this->ci->form_validation->set_rules('donateTicketArray', 'donateTicketArray', 'is_array');
        if (isset($inputArray['donateTicketArray']) && count($inputArray['donateTicketArray']) > 0) {
            $this->ci->form_validation->set_rules('ticketArray', 'ticketArray', 'is_array');
        } else {
            $this->ci->form_validation->set_rules('ticketArray', 'ticketArray', 'required_strict|is_array');
        }

        if ($this->ci->form_validation->run() == FALSE) {
            $errorMsg = $this->ci->form_validation->get_errors();
            //creating response output
            $output = parent::createResponse(FALSE, $errorMsg['message'], STATUS_BAD_REQUEST);
            return $output;
        }
        $eventId = $inputArray['eventId'];
        $this->TicketTaxHandler = new Tickettax_handler();
        $this->TicketDiscountHandler = new Ticketdiscount_handler();
        $validStatus = true;
        $request['eventId'] = $inputArray['eventId'];
        $ticketArray = $inputArray['ticketArray'];

        $donateTicketArray = array();
        if (isset($inputArray['donateTicketArray'])) {
            $donateTicketArray = $inputArray['donateTicketArray'];
        }

        $discountCode = "";
        if (isset($inputArray['discountCode']) && strlen($inputArray['discountCode']) > 0) {
            $discountCode = $inputArray['discountCode'];
        }

        $ticketIds = array();
        foreach ($ticketArray as $key => $value) {
            if ($value > 0) {
                $ticketIds[] = $key;
            }
        }

        //offline promoter related changes
        if (isset($inputArray['promoterId']) && $inputArray['promoterId'] > 0 && isset($inputArray['discountCode'])) {
            $proDiscountHandler = new Discount_handler();
            $checkArray['eventId'] = $inputArray['eventId'];
            $checkArray['discountCode'] = $inputArray['discountCode'];
            $discountData = $proDiscountHandler->getDiscountData($checkArray);

            if ($discountData['response']['total'] > 0) {
                $checkArray['discountId'] = $discountData['response']['discountList'][0]['id'];

                $promoterdiscountHandler = new Offlinepromoterdiscounts_handler();
//                print_r($ticketArray);
                $checkArray['ticketId'] = $ticketIds[0];
                $checkArray['promoterId'] = $inputArray['promoterId'];
                $promoterDiscountStatus = $promoterdiscountHandler->getPrometerEvetTicketDiscounts($checkArray);

                if ($promoterDiscountStatus['response']['total'] == 0) {
                    $output['status'] = false;
                    $output['statusCode'] = STATUS_INVALID_DISCOUNT_CODE;
                    $output['response']['messages'][] = 'Invalid discount code';
                    //return $output;
                    $validStatus = false;
                    $discountCode = '';
                }
            }
        }


        $referralCode = "";
        if (isset($inputArray['referralCode']) && strlen($inputArray['referralCode']) > 0) {
            $referralCode = $inputArray['referralCode'];
        }

        $promoterCode = "";
        if (isset($inputArray['ucode']) && strlen($inputArray['ucode']) > 0) {
            $promoterCode = $inputArray['ucode'];
        }


        //merge donation tickets
        foreach ($donateTicketArray as $key => $value) {
            if ($value > 0) {
                $ticketIds[] = $key;
            }
        }


        //print_r($ticketIds);
        $guestBooking = isset($inputArray['guestListbooking']) ? ($inputArray['guestListbooking']) : false;
        $request['ticketIds'] = $ticketIds;
//        // geting time zone start 
//        $event['eventId']=$inputArray['eventId'];
//        $event=$this->getEventTimeZoneName($event);
//        $request['timeZoneName'] = $event['response']['details']['location']['timeZoneName'];
//        // time zone end
        // get ticket details
        $request['bookingType'] = $guestBooking;
        $ticketDataResponse = $ticketHandler->getEventTicketList($request);
        if ($ticketDataResponse['status'] && $ticketDataResponse['response']['total'] > 0) {
            $ticketDetails = $ticketDataResponse['response']['ticketList'];
            $indexedTicketArray = commonHelperGetIdArray($ticketDetails);
            //fetch tax details
            $input['ticketIds'] = $ticketIds;
            $taxDetails = array();
            if (count($ticketIds) > 0) {
                $taxDetails = $this->TicketTaxHandler->getTicketTaxByTicketId($input);
            }
        } else {
            //return $ticketDataResponse;
        }
        foreach ($ticketIds as $value) {
            if (!empty($indexedTicketArray[$value]['currencyCode'])) {
                $currencies[$indexedTicketArray[$value]['currencyId']] = $indexedTicketArray[$value]['currencyCode'];
                $currencyId = $indexedTicketArray[$value]['currencyId'];
                $currencyCode = $indexedTicketArray[$value]['currencyCode'];
            }
            $ticketTypes[] = $indexedTicketArray[$value]['type'];
        }

        foreach ($ticketDetails as $ticket) {
            $ticketSoldQty = $ticket['totalSoldTickets'];
            $availableTktQty = $ticket['quantity'];

            $ticketNewSoldQty = $ticketSoldQty + $ticketArray[$ticket['id']];
            // If the selected quantity with already sold tickets exceeded total quantity
            if ($ticketNewSoldQty > $availableTktQty && !$guestBooking) {
                $output['status'] = false;
                $output['statusCode'] = 400;
                $output['response']['messages'][] = $ticket['name'] . ERROR_TICKET_EXCEEDED;
                return $output;
            }
        }

        $requestEventDetails['eventId'] = $inputArray['eventId'];
        $eventDataArr = $this->getTicketOptions($requestEventDetails);

        $limitSingleTicketType = 0;
        $taxType = "ontaxedprice";
        $discountAfterTax = true;
        $nonormalwhenbulk=false;
        if ($eventDataArr['status'] && ($eventDataArr['response']['total']) > 0) {
            $limitSingleTicketType = $eventDataArr['response']['ticketingOptions'][1]['limitsingletickettype'];
            $taxType = $eventDataArr['response']['ticketingOptions'][0]['calculationmode'];
            $discountAfterTax = $eventDataArr['response']['ticketingOptions'][1]['discountaftertax'];
            $nonormalwhenbulk=$eventDataArr['response']['ticketingOptions'][0]['nonormalwhenbulk'];
        } else {
            $output['status'] = false;
            $output['statusCode'] = 400;
            $output['response']['messages'][] = ERROR_NO_EVENTS;
            return $output;
        }

        // Collecting the remaining tickets otherthan selected currency
        $getDiffCurrencyTkts['eventId'] = $inputArray['eventId'];
        //$getDiffCurrencyTkts['ticketCurrencyId'] = $currencyId;
        $getDiffCurrencyTkts['ticketId'] = 0;
        $getDiffCurrencyTkts['ticketIds'] = array();
        $getDiffCurrencyTkts['timeZoneName'] = $request['timeZoneName'];
        $getDiffCurrencyTkts['disableSessionLockTickets'] = true;
        $tTktDataResponse = $ticketHandler->getEventTicketList($getDiffCurrencyTkts);
        $otherTicketArray = array();
        if ($tTktDataResponse['status']) {

            $allTickets = $tTktDataResponse['response']['ticketList'];
            foreach ($allTickets as $tempTicket) {
                if ($limitSingleTicketType != 1) {

                    if ($tempTicket['currencyId'] != $currencyId && $tempTicket['type'] != 'free') {
                        if (count($ticketTypes) == 1 && $ticketTypes[0] == 'free') {
                            
                        } else {
                            $otherTicketArray[] = $tempTicket['id'];
                        }
                    }
                } elseif ($limitSingleTicketType == 1) {

                    if (!in_array($tempTicket['id'], $ticketIds)) {
                        $otherTicketArray[] = $tempTicket['id'];
                    }
                }
            }
        }

        //print_r($currencies);
        $currencyData = array_unique(array_keys($currencies));
        //print_r($currencyData);
        if (count($currencyData) > 1) {
            $output['status'] = false;
            $output['statusCode'] = 400;
            $output['response']['messages'][] = ERROR_MULTIPLE_CURRENCY;
            return $output;
        }
        $transCurrencyId = $currencyData[0];
        $transCurrencyCode = $currencies[$transCurrencyId];
        $ticketTaxDetails = array();
        if ($taxDetails['status']) {
            if ($taxDetails['response']['total'] > 0) {
                $ticketTaxDetails = $taxDetails['response']['ticketTaxList'];
            }
        } else {
            return $taxDetails;
        }
        if (!empty($discountCode)) {
            $discountHandler = new Discount_handler();
            $inputDis['eventId'] = $eventId;
            $inputDis['discountType'] = 'normal';
            $inputDis['discountCode'] = $discountCode;
            $isValidDiscountcode = $discountHandler->getDisountId($inputDis);
        }

        if (isset($isValidDiscountcode) && $isValidDiscountcode['status'] && $isValidDiscountcode['response']['total'] == 0) {
            $output['status'] = false;
            $output['statusCode'] = STATUS_INVALID_DISCOUNT_CODE;
            $output['response']['messages'][] = 'Invalid discount code';
            $validStatus = false;
            $discountCode = '';
        } elseif ($isValidDiscountcode['response']['total'] > 0) {
            $discountData = $isValidDiscountcode['response']['discountCode'][0];
            $discountId = $discountData['id'];

            $ticketDiscountInput[0] = $discountId;
            $discountTicketResponse = $this->TicketDiscountHandler->getTicketDiscountData($ticketDiscountInput);
            $discountTicketArray = $discountTicketResponse['response']['ticketDiscountList'];

            $selectedTktQty = 0;
            if (count($discountTicketArray) > 0) {
                foreach ($discountTicketArray as $discountTicket) {
                    $selectedTktQty += $ticketArray[$discountTicket['ticketid']];
                }
            }
            $usageLimit = $discountData['usagelimit'];
            $alreadyUsed = $discountData['totalused'];

            if ($usageLimit < $alreadyUsed) {   //($alreadyUsed + $selectedTktQty)
                $output['status'] = false;
                $output['statusCode'] = STATUS_DISCOUNT_USAGE_EXCEEDED;
                $output['response']['messages'][] = ERROR_DISCOUNT_USAGE_EXCEEDED . ($alreadyUsed - $usageLimit);
                $validStatus = false;
                $discountCode = '';
            }
        }
        //fetch discount details
        $input['eventId'] = $inputArray['eventId'];
        if (!empty($discountCode)) {
            $input['discountCode'] = $discountCode;
        }
        $discountDetails = $this->TicketDiscountHandler->getDiscountByTicketId($input);
        $ticketDiscountDetails = array();
        if (isset($discountDetails) && $discountDetails['status']) {
            if ($discountDetails['response']['total'] > 0) {
                $ticketDiscountDetails = $discountDetails['response']['ticketDiscountList'];
            }
        } elseif (isset($discountDetails)) {
            //return $discountDetails;
        }
        if (!empty($referralCode)) {
            $eventsignupHandler = new Eventsignup_handler();
            $inputViral['type'] = 'referral';
            $inputViral['code'] = $referralCode;
            $isValidReffCode = $eventsignupHandler->isValidCode($inputViral);
        }
        $viralTickets = array();
        if (isset($isValidReffCode) && $isValidReffCode['status'] && $isValidReffCode['response']['codeData']['valid']) {
            //if ($isValidReffCode['response']['total'] > 0) {
            $inputViral['ticketIdArr'] = $ticketIds;
            $viralTickets = $ticketHandler->getViralTickets($inputViral);
            //}
        } elseif (isset($isValidReffCode)) {
            $output['status'] = false;
            $output['statusCode'] = STATUS_INVALID_REFERRAL_CODE;
            $output['response']['messages'][] = $isValidReffCode['response']['messages'][0];
            return $output;
        }
        if (isset($viralTickets) && $viralTickets['status']) {
            if ($viralTickets['response']['total'] > 0) {
                $indexedViralTickets = commonHelperGetIdArray($viralTickets['response']['viralData'], 'ticketid');
            }
        } elseif (isset($viralTickets)) {
            //return $viralTickets;
        }



        $discountRemaining = array();
        $totalPaidQuantity = $totalDonationQty = 0;  // getting number of paid ticket quantity (free is excluded)
        $calculationDetails = array();
        if (!$discountAfterTax) {
            foreach ($ticketDetails as $value) {
                if ($value['type'] != 'donation') { // ignore below calculation for donation tickets
                    $selectedQuantity = $ticketArray[$value['id']];
                    $price = $value['price'];

                    $calculationDetails[$value['id']]['ticketId'] = $value['id'];
                    $calculationDetails[$value['id']]['ticketName'] = $value['name'];
                    $calculationDetails[$value['id']]['ticketType'] = $value['type'];

                    //if selected quantity is more than maximum order
                    //from admin panel spot booking we r not considering the max quantity
                    if ($selectedQuantity > $value['maxOrderQuantity'] && empty($inputArray['spotBooking']))
                        $selectedQuantity = $value['maxOrderQuantity'];

                    $calculationDetails[$value['id']]['ticketPrice'] = $price;
                    $calculationDetails[$value['id']]['selectedQuantity'] = $selectedQuantity;

                    if ($price > 0) // ignoring free ticket count
                        $totalPaidQuantity+=$selectedQuantity;

                    $totalamount = $selectedQuantity * $price;
                    $calculationDetails[$value['id']]['totalAmount'] = $totalamount;
                    $calculationDetails[$value['id']]['currencyCode'] = $value['currencyCode'];

                    if (isset($ticketTaxDetails[$value['id']]['tax'])) {
                        $taxid = "";

                        /*
                         *  tax : 1 is service tax
                         *          2 is Entertainment Tax                         * 
                         */
                        $amountplustax = $totalamount;
                        $valueArray = array();
                        $taxes = $ticketTaxDetails[$value['id']]['tax'];
                        krsort($taxes);
                        foreach ($taxes as $taxvalue) {  /// LOGIC SHOULD BE CHANGED IF MORE TAXES ARE ADDED
                            // $taxlabel=  str_replace(" ","_",strtolower($taxvalue['label']))."_amount";
                            $taxAmount = $amountplustax * ($taxvalue['value'] / 100);
                            if ($taxType == 'ontaxedprice') {
                                $amountplustax+=$taxAmount;
                            }
                            $taxes[$taxvalue['id']]['taxAmount'] = round($taxAmount, 2);
                            //$tax[$taxvalue['id']]['$taxlabel']=$taxAmount;

                            $calculationDetails[$value['id']]['taxes'][$taxvalue['id']] = $taxes[$taxvalue['id']];
                        }
                    }
                    $calculationDetails[$value['id']]['referralDiscount'] = 0;
                    if (isset($indexedViralTickets[$value['id']]) && $indexedViralTickets[$value['id']]['status'] == 1) {
                        $currentViralTicket = $indexedViralTickets[$value['id']];
                        $calculationDetails[$value['id']]['referralDiscount'] = round($selectedQuantity * (((($currentViralTicket['receivercommission'] > 100 ? (100 * $value['price']) : ($currentViralTicket['receivercommission']) * $value['price'])) / 100)), 2);
                        if ($currentViralTicket['type'] == 'flat') {
                            $calculationDetails[$value['id']]['referralDiscount'] = round($selectedQuantity * (($currentViralTicket['receivercommission'] > $value['price']) ? $value['price'] : $currentViralTicket['receivercommission']), 2);
                        }
                        $calculationDetails[$value['id']]['referralDiscountId'] = $currentViralTicket['id'];
                    } 
                    //Adding Discount to main tickets array
                    if (isset($ticketDiscountDetails[$value['id']]['discount'])) {
                        $discounts = $ticketDiscountDetails[$value['id']]['discount'];
                        foreach ($discounts as $discountkey => $discountvalue) {  /// LOGIC SHOULD BE CHANGED IF MORE TAXES ARE ADDED
                            //if its bulk discount 
                            if ($discountvalue['type'] == 'bulk') {
                                if(isset($calculationDetails[$value['id']]['referralDiscount']) && $calculationDetails[$value['id']]['referralDiscount']>0){
                                    $referralAmt=round($calculationDetails[$value['id']]['referralDiscount']/$selectedQuantity);
                                    if($price-$referralAmt<=0){
                                        $price=0;
                                    }
                                }
                                //checking if selected quantity is present in bulk discount rules
                                if (($selectedQuantity >= $discountvalue['minticketstobuy'] && $selectedQuantity <= $discountvalue['maxticketstobuy'])) {
                                    $discountval = ($discountvalue['value'] > $price ? $price : $discountvalue['value']);
                                    if ($discountvalue['calculationmode'] == 'percentage') {
                                        $discountval = (($discountvalue['value'] > 100) ? 100 : $discountvalue['value']) * ($price / 100);
                                    }
                                    $calculationDetails[$value['id']]['singleBulkDiscount'][$discountkey] = $discountval;
                                    $calculationDetails[$value['id']]['bulkDiscountId'] = $discountkey;
                                } else if (($selectedQuantity > $discountvalue['maxticketstobuy']) && ($selectedQuantity >= $discountvalue['minticketstobuy'])) {
                                    $discountval = ($discountvalue['value'] > $price ? $price : $discountvalue['value']);
                                    if ($discountvalue['calculationmode'] == 'percentage') {
                                        $discountval = (($discountvalue['value'] > 100) ? 100 : $discountvalue['value']) * ($price / 100);
                                    }
                                    $calculationDetails[$value['id']]['singleBulkDiscountOdd'][$discountkey] = round($discountval, 2);
                                    $calculationDetails[$value['id']]['bulkDiscountId'] = $discountkey;
                                }
                            }
                        }
                    }
                    foreach ($calculationDetails as $key1 => $value1) {
                        $maxDiscount = 0;
                        if (isset($value1['singleBulkDiscount']) || isset($value1['singleBulkDiscountOdd']))  {
                            $selectedQuantity = $value1['selectedQuantity'];
                            if (isset($value1['singleBulkDiscount'])) {
                                    $maxDiscount = max($value1['singleBulkDiscount']); // get max value
                                    $bulkDiscountKey = array_search($maxDiscount, $value1['singleBulkDiscount']); // get related key to max value
                                    unset($calculationDetails[$key1]['singleBulkDiscount']);
                            } else if (isset($value1['singleBulkDiscountOdd'])) {
                                    $maxDiscount = max($value1['singleBulkDiscountOdd']); // get max value
                                    $bulkDiscountKey = array_search($maxDiscount, $value1['singleBulkDiscountOdd']); // get related key to max value
                                    unset($calculationDetails[$key1]['singleBulkDiscountOdd']);
                            }


                            //for limiting usage to remaining discount quantity
                            $discountQtyToBeUsed = $selectedQuantity;
                            $calculationDetails[$key1]['maxDiscount'] = $maxDiscount;
                            $calculationDetails[$key1]['bulkDiscount'] = $discountQtyToBeUsed * $maxDiscount;
                            if ($calculationDetails[$key1]['bulkDiscountId'] > 0) {
                                    $calculationDetails[$key1]['bulkDiscountId'] = $calculationDetails[$key1]['bulkDiscountId'];
                            }
//                        foreach ($value1['taxes'] as $taxKey => $taxValue) {
//                            if (!isset($totalTaxes[$taxKey])) {
//                                $totalTaxes[$taxKey] = $taxValue;
//                            } else {
//                                $totalTaxes[$taxKey]['taxAmount']+=$taxValue['taxAmount'];
//                            }
//                        }
                        }
                    }
					//print_r($calculationDetails);exit;
                    if (isset($ticketDiscountDetails[$value['id']]['discount'])) {
                        $discounts = $ticketDiscountDetails[$value['id']]['discount'];
                        foreach ($discounts as $discountkey => $discountvalue) {  /// LOGIC SHOULD BE CHANGED IF MORE TAXES ARE ADDED
                            $referralAmt=$bulkAmt=0;
                            $priceAfterBulkReffDisc=$price;
                            if(isset($calculationDetails[$value['id']]['referralDiscount']) && $calculationDetails[$value['id']]['referralDiscount']>0){
                                $referralAmt=round($calculationDetails[$value['id']]['referralDiscount']/$selectedQuantity);
                                if($price-$referralAmt<=0){
                                    $priceAfterBulkReffDisc=0;
                                }
                            }
                            if(isset($calculationDetails[$value['id']]['maxDiscount']) && $calculationDetails[$value['id']]['maxDiscount']>0){
                                $bulkAmt=round($calculationDetails[$value['id']]['maxDiscount']);
                                if($price-$bulkAmt<=0){
                                    $priceAfterBulkReffDisc=0;
                                }
                            }
                            if($price-$referralAmt-$bulkAmt<=0){
                                $priceAfterBulkReffDisc=0;
                            }
                            //if its normal discount with promocode
                            if ($discountvalue['type'] == 'normal' && (!$nonormalwhenbulk || ($nonormalwhenbulk && $calculationDetails[$value['id']]['maxDiscount']==0))) {
                                if (!isset($discountRemaining[$discountkey]) && isset($discountvalue['remainingDiscountQuantity'])) {
                                    $discountRemaining[$discountkey] = $discountvalue['remainingDiscountQuantity'];
                                }
                                //when more than ticket price is saved
                                $discountval = $discountvalue['value'];
                                if ($discountvalue['value'] > $priceAfterBulkReffDisc) {
                                    $discountval = $priceAfterBulkReffDisc;
                                }
                                if ($discountvalue['calculationmode'] == 'percentage' && $discountvalue['value'] > 100) {
                                    $discountval = 100 * ($priceAfterBulkReffDisc / 100);
                                } elseif ($discountvalue['calculationmode'] == 'percentage') {
                                    $discountval = $discountvalue['value'] * ($priceAfterBulkReffDisc / 100);
                                }
                                //for limiting usage to remaining discount quantity
                                $discountQtyToBeUsed = $selectedQuantity;
                                if ($discountRemaining[$discountkey] < $selectedQuantity) {
                                    $discountQtyToBeUsed = $discountRemaining[$discountkey];
                                }
                                $ticketNormalDiscount = $discountval * $discountQtyToBeUsed;
                                //decrementing used quantity
                                $discountRemaining[$discountkey]-=$discountQtyToBeUsed;
                                $calculationDetails[$value['id']]['normalDiscount'] = round($ticketNormalDiscount, 2);
                                $calculationDetails[$value['id']]['normalDiscountId'] = $discountkey;
                            }

                        }
                    } 
	//print_r($calculationDetails);
                }// end of donation ticket check
                else {
                    $calculationDetails[$value['id']]['ticketId'] = $value['id'];
                    $calculationDetails[$value['id']]['ticketName'] = $value['name'];
                    $calculationDetails[$value['id']]['ticketType'] = $value['type'];
                    $calculationDetails[$value['id']]['selectedQuantity'] = 1;  // for donation ticketquantity is always 1
                    //$totalPaidQuantity+=1;
                    $totalDonationQty+=1;
                    $calculationDetails[$value['id']]['totalAmount'] = $donateTicketArray[$value['id']]; //$donateTicketArray[$value['id']];
                    $calculationDetails[$value['id']]['currencyCode'] = $value['currencyCode'];
                }
            }   
        } else {
            //for bulk discount
            foreach ($ticketDetails as $value) {
                if ($value['type'] != 'donation') { // ignore below calculation for donation tickets
                    $selectedQuantity = $ticketArray[$value['id']];
                    $price = $value['price'];

                    $calculationDetails[$value['id']]['ticketId'] = $value['id'];
                    $calculationDetails[$value['id']]['ticketName'] = $value['name'];
                    $calculationDetails[$value['id']]['ticketType'] = $value['type'];

                    //if selected quantity is more than maximum order
                    //from admin panel spot booking we r not considering the max quantity
                    if ($selectedQuantity > $value['maxOrderQuantity'] && empty($inputArray['spotBooking']))
                        $selectedQuantity = $value['maxOrderQuantity'];

                    $calculationDetails[$value['id']]['ticketPrice'] = $price;
                    $calculationDetails[$value['id']]['selectedQuantity'] = $selectedQuantity;

                    if ($price > 0) // ignoring free ticket count
                        $totalPaidQuantity+=$selectedQuantity;

                    $totalamount = $selectedQuantity * $price;
                    $calculationDetails[$value['id']]['totalAmount'] = $totalamount;
                    $calculationDetails[$value['id']]['currencyCode'] = $value['currencyCode'];

                    //for viral ticket
                    foreach ($ticketDetails as $value1) {
                        if ($value1['type'] != 'donation') { // ignore below calculation for donation tickets
                            $selectedQuantity1 = $ticketArray[$value1['id']];
                            if (isset($indexedViralTickets[$value1['id']]) && $indexedViralTickets[$value1['id']]['status'] == 1) {
                                $currentViralTicket = $indexedViralTickets[$value1['id']];
                                //$price = $value1['price'] - $calculationDetails[$value1['ticketid']]['maxDiscount'] - $calculationDetails[$value1['ticketid']]['discountval'];
                                if ($currentViralTicket['type'] == 'flat') {
                                    $calculationDetails[$value1['id']]['referralDiscount'] = round($selectedQuantity1 * (($currentViralTicket['receivercommission'] > $value1['price']) ? $value1['price'] : $currentViralTicket['receivercommission']), 2);
                                } else {
                                    $calculationDetails[$value1['id']]['referralDiscount'] = round($selectedQuantity1 * ((($currentViralTicket['receivercommission'] > 100 ? (100 * $value1['price']) : ($currentViralTicket['receivercommission'] * $value1['price'])) / 100)), 2);
                                }
                                $calculationDetails[$value1['id']]['referralDiscountId'] = $currentViralTicket['id'];
                            } else {
                                $calculationDetails[$value1['id']]['referralDiscount'] = 0;
                            }
                        }// end of donation ticket check
                    }
                    // var_dump($ticketDiscountDetails);
                    //Adding Discount to main tickets array
                    //var_dump(isset($ticketDiscountDetails[$value['id']]['discount']));
                    if (isset($ticketDiscountDetails[$value['id']]['discount'])) {

                        $discounts = $ticketDiscountDetails[$value['id']]['discount'];
                        foreach ($discounts as $discountkey => $discountvalue) {  /// LOGIC SHOULD BE CHANGED IF MORE TAXES ARE ADDED
                            //if its bulk discount 
                            if ($discountvalue['type'] == 'bulk') {
                                $priceAfterReffDisc = $price-(isset($calculationDetails[$value['id']]['referralDiscount'])?round($calculationDetails[$value['id']]['referralDiscount']/$selectedQuantity):0);
                                //checking if selected quantity is present in bulk discount rules
                                if (($selectedQuantity >= $discountvalue['minticketstobuy'] && $selectedQuantity <= $discountvalue['maxticketstobuy'])) {
                                    if ($discountvalue['calculationmode'] == 'percentage') {
                                        $discountval = (($discountvalue['value'] > 100) ? 100 : $discountvalue['value']) * ($priceAfterReffDisc / 100);
                                    } else {
                                        $discountval = ($discountvalue['value'] > $priceAfterReffDisc ? $priceAfterReffDisc : $discountvalue['value']);
                                    }
                                    $calculationDetails[$value['id']]['singleBulkDiscount'][$discountkey] = $discountval;
                                    $calculationDetails[$value['id']]['bulkDiscountId'] = $discountkey;
                                    } else if (($selectedQuantity > $discountvalue['maxticketstobuy']) && ($selectedQuantity >= $discountvalue['minticketstobuy'])) {
                                    if ($discountvalue['calculationmode'] == 'percentage') {
                                        $maxDiscount = (($discountvalue['value'] > 100) ? 100 : $discountvalue['value']) * ($priceAfterReffDisc / 100);
                                    } else {
                                        $maxDiscount = ($discountvalue['value'] > $priceAfterReffDisc ? $priceAfterReffDisc : $discountvalue['value']);
                                    }
//                                $calculationDetails[$value['id']]['singleBulkDiscountOdd'][$discountvalue['maxticketstobuy']]=$maxDiscount;
                                    $calculationDetails[$value['id']]['singleBulkDiscountOdd'][$discountkey] = round($maxDiscount, 2);
                                    $calculationDetails[$value['id']]['bulkDiscountId'] = $discountkey;
                                }
                            }
//                           // $taxlabel=  str_replace(" ","_",strtolower($taxvalue['label']))."_amount";
//                            $taxAmount=$amountplustax*($taxvalue['value']/100);
//                            $amountplustax+=$taxAmount;
//                            $taxes[$taxvalue['id']]['taxAmount']=$taxAmount;
//                            //$tax[$taxvalue['id']]['$taxlabel']=$taxAmount;
//                            
//                                $calculationDetails[$value['id']]['taxes'][$taxvalue['id']]=$taxes[$taxvalue['id']];
                          
                        }
                        //print_r($calculationDetails);exit;
                    }
                }// end of donation ticket check
                else {
                    $calculationDetails[$value['id']]['ticketId'] = $value['id'];
                    $calculationDetails[$value['id']]['ticketName'] = $value['name'];
                    $calculationDetails[$value['id']]['ticketType'] = $value['type'];
                    $calculationDetails[$value['id']]['selectedQuantity'] = 1;  // for donation ticketquantity is always 1
                    //$totalPaidQuantity+=1;
                    $totalDonationQty+=1;
                    $calculationDetails[$value['id']]['totalAmount'] = $donateTicketArray[$value['id']]; //$donateTicketArray[$value['id']];
                    $calculationDetails[$value['id']]['currencyCode'] = $value['currencyCode'];
                }
            }

            //to finalize bulk discount
            foreach ($calculationDetails as $key1 => $value1) {
                $maxDiscount = 0;
                if (isset($value1['singleBulkDiscount']) || isset($value1['singleBulkDiscountOdd'])) {
                    $selectedQuantity = $value1['selectedQuantity'];
                    if (isset($value1['singleBulkDiscount'])) {
                        $maxDiscount = max($value1['singleBulkDiscount']); // get max value
                        $bulkDiscountKey = array_search($maxDiscount, $value1['singleBulkDiscount']); // get related key to max value
                        unset($calculationDetails[$key1]['singleBulkDiscount']);
                    } else if (isset($value1['singleBulkDiscountOdd'])) {
                        $maxDiscount = max($value1['singleBulkDiscountOdd']); // get max value
                        $bulkDiscountKey = array_search($maxDiscount, $value1['singleBulkDiscountOdd']); // get related key to max value
//                            $maxKey=max(array_keys($value1['singleBulkDiscountOdd']));
//                            $maxDiscount=$value1['singleBulkDiscountOdd'][$maxKey];
//                            $calculationDetails[$key1]['bulkDiscount']=$selectedQuantity*$maxDiscount;
                        unset($calculationDetails[$key1]['singleBulkDiscountOdd']);
                    }
                    //for limiting usage to remaining discount quantity
                    $discountQtyToBeUsed = $selectedQuantity;
//            if ($discountRemainingBulk[$bulkDiscountKey] < $selectedQuantity) {
//                $discountQtyToBeUsed = $discountRemainingBulk[$bulkDiscountKey];
//            }

                    $calculationDetails[$key1]['maxDiscount'] = $maxDiscount;
                    $calculationDetails[$key1]['bulkDiscount'] = $discountQtyToBeUsed * $maxDiscount;
                    if ($calculationDetails[$key1]['bulkDiscountId'] > 0) {
                        $calculationDetails[$key1]['bulkDiscountId'] = $calculationDetails[$key1]['bulkDiscountId'];
                    }
                }
                //decrementing used quantity
                //$discountRemainingBulk[$bulkDiscountKey]-=$discountQtyToBeUsed;
            }


            //for normal discount
            foreach ($ticketDetails as $value) {
                if ($value['type'] != 'donation') { // ignore below calculation for donation tickets
                    if (isset($ticketDiscountDetails[$value['id']]['discount'])) {
                        $selectedQuantity = $ticketArray[$value['id']];
                        $price = $value['price'];
                        $totalamount = $selectedQuantity * $price;
                        $discounts = $ticketDiscountDetails[$value['id']]['discount'];
                      //  echo 'price-'.$price;
                       // var_dump($calculationDetails[$value['id']]['maxDiscount']);
                      //  var_dump($calculationDetails[$value['id']]['referralDiscount']);
                        $priceAfterBulkDiscount = $price - (isset($calculationDetails[$value['id']]['maxDiscount']) ? $calculationDetails[$value['id']]['maxDiscount'] : 0)-(isset($calculationDetails[$value['id']]['referralDiscount'])?round($calculationDetails[$value['id']]['referralDiscount']/$selectedQuantity):0);
                        //$priceAfterReferralDiscount = $price - (isset($calculationDetails[$value['id']]['referralDiscount']) ? $calculationDetails[$value['id']]['referralDiscount'] : 0);
//                        if ($totalamount == $calculationDetails[$value['id']]['referralDiscount']) {
//                            $priceAfterBulkDiscount = 0;
//                        }
                       // echo $priceAfterBulkDiscount;
                        foreach ($discounts as $discountkey => $discountvalue) {  /// LOGIC SHOULD BE CHANGED IF MORE TAXES ARE ADDED
                            //if its normal discount with promocode
                            if ($discountvalue['type'] == 'normal' && (!$nonormalwhenbulk || ($nonormalwhenbulk && $calculationDetails[$value['id']]['maxDiscount']==0))) {
                                if (!isset($discountRemaining[$discountkey]) && isset($discountvalue['remainingDiscountQuantity'])) {
                                    $discountRemaining[$discountkey] = $discountvalue['remainingDiscountQuantity'];
                                }
                                $discountval = ($discountvalue['value'] > $priceAfterBulkDiscount ? $priceAfterBulkDiscount : $discountvalue['value']);
                                if ($discountvalue['calculationmode'] == 'percentage') {
                                    $discountval = ($discountvalue['value'] > 100 ? 100 : $discountvalue['value']) * ($priceAfterBulkDiscount / 100);
                                }


                                //for limiting usage to remaining discount quantity
                                $discountQtyToBeUsed = $selectedQuantity;
                                // print_r($discountRemaining);
                                // print_r($discountvalue);
                                if ($discountRemaining[$discountkey] < $selectedQuantity) {
                                    $discountQtyToBeUsed = $discountRemaining[$discountkey];
                                }

                                $ticketNormalDiscount = $discountval * $discountQtyToBeUsed;

                                //decrementing used quantity
                                $discountRemaining[$discountkey]-=$discountQtyToBeUsed;


                                $calculationDetails[$value['id']]['normalDiscount'] = round($ticketNormalDiscount, 2);
                                $calculationDetails[$value['id']]['normalDiscountId'] = $discountkey;
                                $calculationDetails[$value['id']]['discountval'] = $discountval;
                            }
                        }
                    }
                }// end of donation ticket check
            }

            //setting only the correct bulk discount value 


            foreach ($ticketDetails as $value) {
                //echo isset($ticketTaxDetails[$value['id']]['tax']);exit;
                if ($value['type'] != 'donation') {
                    if (isset($ticketTaxDetails[$value['id']]['tax'])) {
                        $taxid = "";

                        /*
                         *  tax : 1 is service tax
                         *          2 is Entertainment Tax                         * 
                         */
                        $amountAfterDiscount = round($calculationDetails[$value['id']]['totalAmount'] - (isset($calculationDetails[$value['id']]['bulkDiscount']) ? $calculationDetails[$value['id']]['bulkDiscount'] : 0) - (isset($calculationDetails[$value['id']]['normalDiscount']) ? $calculationDetails[$value['id']]['normalDiscount'] : 0) - (isset($calculationDetails[$value['id']]['referralDiscount']) ? $calculationDetails[$value['id']]['referralDiscount'] : 0));
                        if ($amountAfterDiscount >= 0) {
                            //$valueArray = array();
                            $taxes = $ticketTaxDetails[$value['id']]['tax'];
                            krsort($taxes);
                            foreach ($taxes as $taxvalue) {  /// LOGIC SHOULD BE CHANGED IF MORE TAXES ARE ADDED
                                // $taxlabel=  str_replace(" ","_",strtolower($taxvalue['label']))."_amount";
                                $taxAmount = $amountAfterDiscount * ($taxvalue['value'] / 100);
                                if ($taxType == 'ontaxedprice') {
                                    $amountAfterDiscount+=$taxAmount;
                                }
                                $taxes[$taxvalue['id']]['taxAmount'] = round($taxAmount, 2);
                                //$tax[$taxvalue['id']]['$taxlabel']=$taxAmount;

                                $calculationDetails[$value['id']]['taxes'][$taxvalue['id']] = $taxes[$taxvalue['id']];
                            }
                        }
                    }
                }
            }
        }
        $totalTaxes = array();
        foreach ($calculationDetails as $key => $value) {
            if ($value['type'] != 'donation') {
                foreach ($value['taxes'] as $taxKey => $taxValue) {
                    if (!isset($totalTaxes[$taxKey])) {
                        $totalTaxes[$taxKey] = $taxValue;
                    } else {
                        $totalTaxes[$taxKey]['taxAmount']+=$taxValue['taxAmount'];
                    }
                }
            }
        }
        //print_r($totalTaxes);
        //exit;
        $totalTicketAmount = 0;
        $totalTicketQty = 0;
        $totalDiscount = 0;
        //$finalAmount = 0;
        $totalReferralDiscount = 0;
        //$currencyCode = '';
        $extraDiscount=0;
        foreach ($calculationDetails as $key => $value) {
            $ticketDiscount=0;
            $totalTicketAmount+=$value['totalAmount'];
            $totalTicketQty+=$value['selectedQuantity'];
            if(isset($value['normalDiscount'])){
                $ticketDiscount+=$value['normalDiscount'];
            }
            if(isset($value['bulkDiscount'])){
                $ticketDiscount+=$value['bulkDiscount'];
            }
            if(isset($value['referralDiscount'])){
                $ticketDiscount+=$value['referralDiscount'];
            }
            if($value['totalAmount']<$ticketDiscount){
                $extraDiscount+=($ticketDiscount-$value['totalAmount']);
            }
            if (isset($value['normalDiscount']))
                $totalDiscount+=$value['normalDiscount'];

            if (isset($value['bulkDiscount']))
                $totalBulkDiscount+=$value['bulkDiscount'];


//            if (strlen($currencyCode) == 0)  /// Pass the parameter
//                $currencyCode = $value['currencyCode'];

            $totalReferralDiscount+=$value['referralDiscount'];
        }
        
        // Getting Total Tax
        $totalTax = 0;
        $count = 0;
        $finalTax = array();
        foreach ($totalTaxes as $value) {
            $finalTax[$count] = $value;
            $totalTax+=$value['taxAmount'];
            $count++;
        }

        $finalArray = array();
        $eventExtraChargeHandler = new Eventextracharge_handler();
        $inputExtracharge = array('eventid' => $inputArray['eventId']);
        $responseExtraCharge = $eventExtraChargeHandler->getExtrachargeByEventId($inputExtracharge);
        
        if ($responseExtraCharge['status'] && $responseExtraCharge['response']['total'] > 0) {
            $extraChargeCurrent = $responseExtraCharge['response']['eventExtrachargeList'];
            foreach($extraChargeCurrent as $extraChargeArr) {
                $eventExtraCharges['label'] = $extraChargeArr['label'];
                $eventExtraCharges['type'] = $extraChargeArr['type'];
                $eventExtraCharges['value'] = $extraChargeArr['value'];
                $eventExtraCharges['id'] = $extraChargeArr['id'];
                $eventExtraCharges['currencyId'] = $extraChargeArr['currencyid'];
                $eventExtraCharges['currencyCode'] = $extraChargeArr['currencycode'];
                $finalEventExtraCharges[] = $eventExtraCharges;
        }
        }

        uasort($calculationDetails, function($a, $b) {
            return $a['ticketType'] - $b['ticketType'];
        });
        $finalArray['ticketsData'] = $calculationDetails;
        //$finalArray['taxesData'] = $totalTaxes;
        $finalArray['totalTicketAmount'] = $totalTicketAmount;
        $finalArray['totalTicketQuantity'] = $totalTicketQty;
        $finalArray['totalCodeDiscount'] = $totalDiscount;
        $finalArray['totalBulkDiscount'] = $totalBulkDiscount;
        $finalArray['totalTaxDetails'] = $finalTax;
        $finalArray['totalTaxAmount'] = $totalTax;
        $finalArray['totalReferralDiscount'] = $totalReferralDiscount;

        $amountexcludingextra = $totalTicketAmount + $totalTax - $totalDiscount - $totalBulkDiscount - $totalReferralDiscount+$extraDiscount;
        $finalArray['extraCharge'] = array();
        //setting extracharge details
        $totalExtraCharge = 0;
        if (is_array($finalEventExtraCharges)) {
            foreach($finalEventExtraCharges as $eventExtraCharges) {
                
                $prepareExtraChargeArr['currencyId'] = $eventExtraCharges['currencyId'];
                $prepareExtraChargeArr['currencyCode'] = $eventExtraCharges['currencyCode'];
                
                if ($eventExtraCharges['type'] == 1) {
                    $extracharge = $amountexcludingextra * ($eventExtraCharges['value'] / 100);
                    
                    $prepareExtraChargeArr['currencyId'] = $currencyId;
                    $prepareExtraChargeArr['currencyCode'] = $currencyCode;
                } else {
                    $extracharge = ($totalPaidQuantity + $totalDonationQty) * $eventExtraCharges['value'];
                }
                $totalExtraCharge += $extracharge;

                $prepareExtraChargeArr['label'] = $eventExtraCharges['label'];
                $prepareExtraChargeArr['totalAmount'] = round($extracharge, 2);
                $prepareExtraChargeArr['id'] = $eventExtraCharges['id'];
                
                $finalArray['extraCharge'][] = $prepareExtraChargeArr;
        }
        }
        $roundofvalue = round(($amountexcludingextra + $totalExtraCharge) - ($amountexcludingextra + $totalExtraCharge), 2);
        $finalArray['roundofvalue'] = $roundofvalue;
        $totalPurchaseAmount = round($amountexcludingextra + $totalExtraCharge);
        $finalArray['totalPurchaseAmount'] = ($totalPurchaseAmount > 0) ? $totalPurchaseAmount : 0;
        $finalArray['currencyCode'] = $transCurrencyCode;
        $finalArray['discountCode'] = $discountCode;
        $finalArray['referralCode'] = $referralCode;
        $finalArray['promoterCode'] = $promoterCode;
        $finalArray['otherCurrencyTickets'] = $otherTicketArray;

        if (!$validStatus) {
            $output['status'] = FALSE;
            $output['response']['calculationDetails'] = $finalArray;
            $output['response']['messages'][0] = $output['response']['messages'][0];
            $output['statusCode'] = $output['statusCode'];
            return $output;
        }
        $output['status'] = TRUE;
        $output['response']['calculationDetails'] = $finalArray;
        $output['response']['messages'] = array();
        $output['statusCode'] = STATUS_OK;
        return $output;
    }

    /*
     * Function to format the calculation data for mobile from above function `getEventTicketCalculation()`
     *
     * @access	public
     * @param	$inputArray contains
     * 			Tickets Array with Ticket Id and Quantity
     * 			Event Id
     * @return	array
     */

    function getEventTicketCalculation_mobile($inputArray) {

        $response = $this->getEventTicketCalculation($inputArray);
        $ticketArray = $mesageArr = array();
        $ticketDataResponse = $response['response']['calculationDetails']['ticketsData'];
        foreach ($ticketDataResponse as $ticketId => $ticketData) {
            if (is_array($ticketData['taxes']) && count($ticketData['taxes']) > 0) {
                $taxArray = array();
                foreach ($ticketData['taxes'] as $taxes) {
                    $taxArray[] = $taxes;
                }
                unset($ticketData['taxes']);
                $ticketData['taxes'] = $taxArray;
            }
            $ticketArray[] = $ticketData;
        }
        $tempResp = $response['response']['calculationDetails'];
        unset($tempResp['ticketsData']);

        $finalResponse['status'] = $response['status'];
        $finalResponse['statusCode'] = $response['statusCode'];
        $finalResponse['response']['messages'] = $response['response']['messages'];
        $finalResponse['response']['calculationDetails'] = $tempResp;
        $finalResponse['response']['calculationDetails']['ticketsData'] = $ticketArray;

        return $finalResponse;
    }

    /*
     * Function to create Event
     *
     * @access	public
     * @param	$data contains
     *          for stateInsert()  if stateId is not available
     *     @param:  state 
     *          
     *          for cityInsert()   if cityId is not available
     *     @param:  city
     *  
     *          for addEvent()
     *     @param:  ownerId,countryId,stateId,cityId,modifiedby,createdby,categoryId,subcategoryId,
     *              timezoneId,title,description,url,venueName,venueaddress1,venueaddress2,
     *              startDate(mm/dd/yyyy),startTime(H:i:s),endDate(mm/dd/yyyy),endTime(H:i:s),private,thumbnail,banner,localityId,
     *              latitude,longitude,pincode,status,ticketSoldout,popularity,registrationType
     * 
     * 		for addEventDetail()
     *     @param:  eventId,booknowButtonValue,contactDisplay,contactDetails,extraReportingEmails,facebookLink,
     *              googleLink,twitterLink,salesPersonId,tnc,useOriginalImage,contactWebsiteUrl,passRequired,
     *              password,limitSingleTicketType,discountAfterTax,seoTitle,seoKeywords,seoDescription,conanicalUrl,
     *              googleAnalytcisScripts,createdby,modifiedby	
     *          for addTag() if new tag add id as 0
     *     @param:  tags
     *              Eg: array(array("id"=>1,"tag"=>"camp6"),array("id"=>0,"tag"=>"Camp7"))
     * 
     *          for tickets 
     *     @param:  tickets
     * 
     *              tickets array contains:
     *              startDate,startTime,endDate,endTime,name,type,description,eventId,price,
     *              quantity,minOrderQuantity,maxOrderQuantity,order,currencyId,
     *              soldOut,label,value   
     * @return	array
     */

    public function createEvent($data) {
        $countryHandler = new Country_handler();
        $stateHandler = new State_handler();
        $cityHandler = new City_handler();
        $timezoneHandler = new Timezone_handler();
        $ticketHandler = new Ticket_handler();
        // Block event titles
        $blockedTitleResponse = $this->blockEventTitles($data);
        if ($blockedTitleResponse['status'] === FALSE) {
            return $blockedTitleResponse;
        }
        // Transaction based event creation if one of them failed it will 
        // rollback if all success quries will commit
        $this->ci->Event_model->startTransaction();

        $data['eventMode'] = 0;
        /*
         * registrationType=1 =>free
         * registrationType=2 =>paid
         * registrationType=3 =>no registration
         *  
         */
        /*   if ($data['registrationType'] == 1 || $data['registrationType'] == 2 || $data['registrationType'] == 3) {
          $data['registrationType'] = $data['registrationType'];
          } */
        // for webinar country, state and city will be null
        if ($data['iswebinar'] == 1) {
            $data['eventMode'] = 1;
        }

        // add country if countryId is not exist
        if (isset($data['country'])) {
            $countryData = array();
            $countryData['country'] = $data['country'];
            $countryResponse = $countryHandler->countryInsert($countryData);
            if ($countryResponse['status'] === FALSE) {
                return $countryResponse;
            }
            $data['countryId'] = $countryResponse['response']['countryId'];
        }


        // add state if stateId is not exist
        if (isset($data['state'])) {
            $stateData = array();
            $stateData['countryId'] = $data['countryId'];
            $stateData['state'] = $data['state'];
            $stateResponse = $stateHandler->stateInsert($stateData);
            if ($stateResponse['status'] === FALSE) {
                return $stateResponse;
            }
            $data['stateId'] = $stateResponse['response']['stateId'];
        }

        // add city if cityId is not exist
        if (isset($data['city'])) {
            $cityData = array();
            $cityData['countryId'] = $data['countryId'];
            $cityData['stateId'] = $data['stateId'];
            $cityData['city'] = $data['city'];
            $cityResponse = $cityHandler->cityInsert($cityData);
            if ($cityResponse['status'] === FALSE) {
                return $cityResponse;
            }
            $data['cityId'] = $cityResponse['response']['cityId'];
        }
        //Bring the sub category details
        $this->subcategoryHandler = new Subcategory_handler();
        $subcategoryData = array();
        $subcategoryData['categoryId'] = $data['categoryId'];
        $subcategoryData['subcategoryName'] = $data['subcategoryId'];
        $subcategoryResponse = $this->subcategoryHandler->subcategoryInsert($subcategoryData);
        if (!$subcategoryResponse['status']) {
            return $subcategoryResponse;
        }

        $data['subcategoryId'] = $subcategoryResponse['response']['subcategoryId'];

        $data['startDate'] = urldecode($data['startDate']);
        $data['endDate'] = urldecode($data['endDate']);
        $data['startTime'] = urldecode($data['startTime']);
        $data['endTime'] = urldecode($data['endTime']);
        $startDateValidation = dateValidation($data['startDate'], '/');
        $endDateValidation = dateValidation($data['endDate'], '/');
        if (!$startDateValidation || !$endDateValidation) {
            $output['status'] = FALSE;
            $output["response"]["messages"][] = ERROR_DATE_VALUE_FORMAT;
            $output['statusCode'] = STATUS_BAD_REQUEST;
            return $output;
        }
        // changing date format mm/dd/yyyy to yyyy-mm-dd    
        $startDate = dateFormate($data['startDate'], '/');
        $endDate = dateFormate($data['endDate'], '/');


        $timeZoneData['timezoneId'] = $data['timezoneId'];
        $timeZoneData['status'] = 1;
        $timeZoneDetails = $timezoneHandler->details($timeZoneData);
        $timeZoneName = "";
        if ($timeZoneDetails['status']) {
            $timeZoneName = $timeZoneDetails['response']['detail'][1]['name'];
        } else {
            return $timeZoneDetails;
        }
        $eventStartDate = convertTime($startDate . ' ' . $data['startTime'], $timeZoneName);
        $eventEndDate = convertTime($endDate . ' ' . $data['endTime'], $timeZoneName);
        $data['utcStartDate'] = $eventStartDate;
        $data['utcEndDate'] = $eventEndDate;
        //print_r(date("Y-m-d H:i:s").' '.$eventStartDate);
        if (strtotime(date("Y-m-d H:i:s")) > strtotime($eventStartDate)) {
            $output['status'] = FALSE;
            $output["response"]["messages"][] = ERROR_EVENT_START_DATE_GREATER_THAN_NOW;
            $output['statusCode'] = STATUS_BAD_REQUEST;
            return $output;
        }

        if (strtotime($eventStartDate) > strtotime($eventEndDate)) {
            $output['status'] = FALSE;
            $output["response"]["messages"][] = ERROR_EVENT_START_DATE_GREATER;
            $output['statusCode'] = STATUS_BAD_REQUEST;
            return $output;
        }
        $data['startDateTime'] = $eventStartDate;
        $data['endDateTime'] = $eventEndDate;
        $data['registrationtype'] = $this->getEventRegistrationType($data['tickets']);

        $data['latitude'] = $data['latitude'];
        $data['longitude'] = $data['longitude'];

        /* add event in event table
         * it returns event id
         */
        $addEventResponse = $this->addEvent($data);
        // addevent respose is error it will return in arrya otherwise it's return event inserted id

        if ($addEventResponse['status'] === FALSE) {
            return $addEventResponse;
        }
        $data['eventId'] = $addEventResponse['response']['eventid'];

        //This are the html dom file object Names
        $data['thumbnail'] = "thumbImage";
        $data['banner'] = "bannerImage";
        $data['bannerSource'] = isset($data['bannerSource']) ? $data['bannerSource'] : '';
        $data['thumbSource'] = isset($data['thumbSource']) ? $data['thumbSource'] : '';
        if(isset($data['removeBanner']) && $data['removeBanner'] == 1){
            $_FILES[$data['banner']]["name"] ='';
        }
       else if (!empty($data['bannerSource']) || isset($_FILES[$data['banner']]["name"])) { 
            $bannerIds = $this->eventBannerUpload($data);
        }
         if(isset($data['removeThumb']) && $data['removeThumb'] == 1){
            $_FILES[$data['thumbnail']]["name"] ='';
        }
      else  if (!empty($data['thumbSource']) || isset($_FILES[$data['thumbnail']]["name"])) {
            $logoIds = $this->eventLogoUpload($data);
        }
        if (isset($bannerIds) && $bannerIds['status'] === FALSE) {
            return $bannerIds;
        } elseif (isset($bannerIds)) {
            $data['bannerFileId'] = $bannerIds ['response']['bannerFileId'];
            $data['bannerFilePath'] = $bannerIds ['response']['bannerFilePath'];
        }
        if (isset($logoIds) && $logoIds['status'] === FALSE) {
            return $logoIds;
        } elseif (isset($logoIds)) {
            $data['thumbnailFileId'] = $logoIds ['response']['thumbnailFileId'];
            $data['thumbnailFilePath'] = $logoIds ['response']['thumbnailFilePath'];
        }

        //update event 
        $updateEventResonse = $this->updateEvent($data);
        if ($updateEventResonse['status'] === FALSE) {
            return $updateEventResonse;
        }

        // add evetdetails in eventdetail table
        $addEventDetailResponse = $this->addEventDetail($data);
        if ($addEventDetailResponse['status'] === FALSE) {
            return $addEventDetailResponse;
        }

        /* add tags in tag table
         * it returns tags with id and name
         */

        $tagHandler = new Tag_handler();
        if (isset($data['tags']) && !empty($data['tags'])) {
            $addTagResponse = $tagHandler->addTag($data);
            if ($addTagResponse['status'] === FALSE) {
                return $addTagResponse;
            }


            $eventTagData = array();
            $eventTagData['tags'] = $addTagResponse['response']['tags'];
            $eventTagData['eventId'] = $data['eventId'];
            $addEventTagResponse = $this->addEventTag($eventTagData);
            if ($addEventTagResponse['status'] === FALSE) {
                return $addEventTagResponse;
            }
        }
 // checking tickets count
    $totalTicketsLength= count($data['tickets']);
    if($totalTicketsLength  < 1){ 
       $output['status'] = FALSE;
       $output["response"]["messages"][] = ERROR_TICKET_REQUIRED;
       $output['statusCode'] = STATUS_BAD_REQUEST;
       return $output; 
    }
        // add tickets 
        // for free registration no need tickets
        if ($data['registrationType'] != 3) {
            foreach ($data['tickets'] as $ticketKey => $ticketValue) {
                $ticketData = array();
                $ticketData['startDate'] = $ticketValue['startDate'];
                $ticketData['startTime'] = $ticketValue['startTime'];
                $ticketData['endDate'] = $ticketValue['endDate'];
                $ticketData['endTime'] = $ticketValue['endTime'];
                $ticketData['name'] = $ticketValue['name'];
                $ticketData['type'] = getTicketType($ticketValue['type']);
                $ticketData['description'] = $ticketValue['description'];
                $ticketData['eventId'] = $data['eventId'];
                $ticketData['price'] = $ticketValue['price'];
                $ticketData['quantity'] = $ticketValue['quantity'];
                $ticketData['minOrderQuantity'] = $ticketValue['minOrderQuantity'];
                $ticketData['maxOrderQuantity'] = $ticketValue['maxOrderQuantity'];
                $ticketData['order'] = $ticketValue['order'];
                $ticketData['currencyId'] = $ticketValue['currencyId'];
                $ticketData['soldOut'] = $ticketValue['soldOut'];
                $ticketData['displayStatus'] = $ticketValue['displayStatus'];
                $ticketData['userId'] = $data['ownerId'];
                $ticketData['taxArray'] = $ticketValue['taxArray'];
                $ticketData['timeZoneName'] = $timeZoneName;

                $ticketResponse = $ticketHandler->add($ticketData);
                if ($ticketResponse['status'] === FALSE) {
                    return $ticketResponse;
                }
            }
        }

        //Insert the default custom fields 
        $configureHandler = new Configure_handler();
        $customFieldStatus = $configureHandler->insertEventDefaultCustomFiedls($data);
        if (!$customFieldStatus['status']) {
            return $customFieldStatus;
        }
        //To insert the event setting table data
        $eventSettingStatus = $this->insertEventSettingDetails($data);
        if (!$eventSettingStatus['status']) {
            return $eventSettingStatus;
        }

        $eventPaymentGateway = new EventpaymentGateway_handler();
        $paymentGaetewayStatus = $eventPaymentGateway->insertEventDefaultPaymentEventList($data);
        if (!$paymentGaetewayStatus['status']) {
            return $paymentGaetewayStatus;
        }

        //set default alerts
        $alertsResponse = $this->setAlertsForOrganizer();
        if (!$alertsResponse['status']) {
            return $alertsResponse;
        }

        //Change the organizer status
        $organizerHandler = new Organizer_handler();
        $organizerHandler->changeOrganizerStatus();

        if ($this->ci->Event_model->transactionStatusCheck() === FALSE) {
            $this->ci->Event_model->rollBackLastTransaction();
            $output['status'] = FALSE;
            $output["response"]["messages"][] = ERROR_SOMETHING_WENT_WRONG;
            $output['statusCode'] = 200;
            return $output;
        } else {
            $solrHandler = new Solr_handler();
            // add event in slor
            $solrData = array();
            $solrData = $this->solrAddEventInputData($data);
            $solrData['limitsingletickettype'] = 0;
            $addEventInSolr = $solrHandler->solrAddEvent($solrData);
            //print_r($addEventInSolr);exit;
            if ($addEventInSolr['status'] === FALSE) {
                return $addEventInSolr;
            }
            // add tags in solr
            $solrTagData = array();
            if (isset($data['tags']) && !empty($data['tags'])) {
                foreach ($addTagResponse['response']['tags'] as $solrTagKey => $solrTagvalue) {
                    $solrTagData['id'] = $solrTagvalue['id'];
                    $solrTagData['name'] = $solrTagvalue['tag'];
                    $addTagInSolr = $solrHandler->solrAddTag($solrTagData);
                    if ($addTagInSolr['status'] === FALSE) {
                        // delete solr event while got error in adding tags   
                        $solrDeletedData = array();
                        $solrDeletedData['id'] = $data['eventId'];
                        $deleteInSolr = $solrHandler->solrDeleteEvent($solrDeletedData);
                        if ($deleteInSolr['status']) {
                            return $addTagInSolr; //because we need to throw why error came for adding tags after deleting tag
                        } else {
                            return $deleteInSolr;
                        }
                    }
                }
            }

            $this->ci->Event_model->commitLastTransaction();

            // Insert extra charges if the organiser checks the checkboxes for fees
            $organiser_fee = $data['organiser_fee'];
            if($organiser_fee != '') {
                
                $eventId = $data['eventId'];
                $feeArray = $this->ci->config->item('organizer_fees');
                require_once (APPPATH . 'handlers/eventextracharge_handler.php');
                $eventextrachargeHandler = new Eventextracharge_handler();
                
                $addedExtraArr = array();
                if($organiser_fee == 'both') {
                    $addedExtraArr = array('servicecharge','gatewaycharge');
                } else {
                    $addedExtraArr = array($organiser_fee);
                }
                $serviceTax = $this->ci->config->item('service_tax');
                foreach($feeArray as $feeKey => $feeValue) {
                    $insertArr = array();
                    $insertArr['eventId'] = $eventId;
                    if(in_array($feeKey,$addedExtraArr)) {
                        $insertArr['label'] = $feeValue['label'];
                        $insertArr['value'] = $feeValue['value']+round(($feeValue['value']*$serviceTax/100),2);
                        $eventextrachargeHandler->extraChargeInsert($insertArr);   
                    }
                }    
            }
           
            
            $output['status'] = TRUE;
            $output["response"]["url"] = $data['url'];
            $output["response"]["id"] = $data['eventId'];
            $output["response"]["messages"][] = SOLR_EVENT_CREATE;
            $output['statusCode'] = STATUS_CREATED;
            return $output;
        }
    }

//Inseart event data
    public function addEvent($data) {
        //check url is existed or not in solr
        $solrRequestArray['eventUrl'] = $data['url'];
        $urlCheck = $this->checkUrlExists($solrRequestArray);
        if ($urlCheck['status'] === FALSE) {
            return $urlCheck;
        }
        $validationStatus = $this->addEventValidation($data);
        if ($validationStatus['error'] == TRUE) {
            $output['status'] = FALSE;
            $output['response']['messages'] = $validationStatus['message'];
            $output['statusCode'] = STATUS_BAD_REQUEST;
            return $output;
        }

        $eventData['registrationtype'] = $data['registrationtype'];
        $eventData['eventmode'] = isset($data['eventMode']) ? $data['eventMode'] : 0;
        $eventData['title'] = $data['title'];
        $eventData['acceptmeeffortcommission'] = $data['acceptmeeffortcommission'];
        $eventData['description'] = $data['description'];
        $eventData['categoryid'] = $data['categoryId'];
        $eventData['subcategoryid'] = $data['subcategoryId'];
        $eventData['url'] = $data['url'];
        $eventData['venuename'] = isset($data['venueName']) ? $data['venueName'] : '';
        $eventData['venueaddress1'] = isset($data['venueaddress1']) ? $data['venueaddress1'] : '';
        $eventData['venueaddress2'] = isset($data['venueaddress2']) ? $data['venueaddress2'] : '';
        $eventData['countryid'] = $data['countryId'];
        $eventData['stateid'] = $data['stateId'];
        $eventData['cityid'] = $data['cityId'];
        $eventData['startdatetime'] = $data['startDateTime'];
        $eventData['enddatetime'] = $data['endDateTime'];
        $eventData['timezoneid'] = $data['timezoneId'];
        $eventData['private'] = $data['private'];
        $eventData['thumbnailfileid'] = isset($data['thumbnailFileId']) ? $data['thumbnailFileId'] : null;
        $eventData['bannerfileid'] = isset($data['bannerFileId']) ? $data['bannerFileId'] : null;
        $eventData['ownerid'] = $data['ownerId'];
        $eventData['localityid'] = isset($data['localityId']) ? $data['localityId'] : '';



        $eventData['latitude'] = ($data['latitude'] != '') ? $data['latitude'] : 0;
        $eventData['longitude'] = ($data['longitude'] != '') ? $data['longitude'] : 0;
        $eventData['pincode'] = isset($data['pincode']) ? $data['pincode'] : '';
        $eventData['status'] = isset($data['status']) ? $data['status'] : 0;
        $eventData['ticketsoldout'] = isset($data['ticketSoldout']) ? $data['ticketSoldout'] : 0;
        $eventData['popularity'] = $data['popularity'];
        $eventData['ipaddress'] = $this->getClientIp();

        $this->ci->Event_model->setInsertUpdateData($eventData);

        $eventId = $this->ci->Event_model->insert_data();
        $output['status'] = TRUE;
        $output['response']['eventid'] = $eventId;
        $output["response"]["messages"] = array();
        $output['statusCode'] = STATUS_CREATED;
        return $output;
    }

// Get client ipaddress    
    public function getClientIp() {
        $ipaddress = '';
        if (getenv('HTTP_CLIENT_IP'))
            $ipaddress = getenv('HTTP_CLIENT_IP');
        else if (getenv('HTTP_X_FORWARDED_FOR'))
            $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
        else if (getenv('HTTP_X_FORWARDED'))
            $ipaddress = getenv('HTTP_X_FORWARDED');
        else if (getenv('HTTP_FORWARDED_FOR'))
            $ipaddress = getenv('HTTP_FORWARDED_FOR');
        else if (getenv('HTTP_FORWARDED'))
            $ipaddress = getenv('HTTP_FORWARDED');
        else if (getenv('REMOTE_ADDR'))
            $ipaddress = getenv('REMOTE_ADDR');
        else
            $ipaddress = 'UNKNOWN';
        return $ipaddress;
    }

// Inseart Event Details data
    public function addEventDetail($data) {

        $validationStatus = $this->addEventDetailValidation($data);
        if ($validationStatus['error'] == TRUE) {
            $output['status'] = FALSE;
            $output['response']['messages'] = $validationStatus['message'];
            $output['statusCode'] = STATUS_BAD_REQUEST;
            return $output;
        }
        $eventData['eventid'] = $data['eventId'];
        $eventData['contactdisplay'] = isset($data['contactDisplay']) ? $data['contactDisplay'] : 0;
        $eventData['contactdetails'] = $data['contactDetails'];
        $eventData['extrareportingemails'] = $data['extraReportingEmails'];
        $eventData['facebooklink'] = $data['facebookLink'];
        $eventData['googlelink'] = $data['googleLink'];
        $eventData['twitterlink'] = $data['twitterLink'];
        $eventData['salespersonid'] = $data['salesPersonId'];

        $eventData['contactwebsiteurl'] = $data['contactWebsiteUrl'];
        $eventData['passrequired'] = $data['passRequired'];
        $eventData['password'] = $data['password'];
        $eventData['limitsingletickettype'] = isset($data['limitSingleTicketType']) ? $data['limitSingleTicketType'] : 0;
        //$eventData['discountaftertax'] = isset($data['discountAfterTax']) ? $data['discountAfterTax'] : 0;
        $eventData['booknowbuttonvalue'] = $data['booknowButtonValue'];
        $eventData['seotitle'] = $data['seoTitle'];
        $eventData['seokeywords'] = $data['seoKeywords'];
        $eventData['seodescription'] = $data['seoDescription'];
        $eventData['conanicalurl'] = $data['conanicalUrl'];
        $eventData['googleanalyticsscripts'] = $data['googleAnalytcisScripts'];

        $this->ci->load->model('Eventdetail_model');
        $this->ci->Eventdetail_model->resetVariable();
        $this->ci->Eventdetail_model->setInsertUpdateData($eventData);

        $response = $this->ci->Eventdetail_model->insert_data();
        $output['status'] = TRUE;
        $output['response']['eventid'] = $response;
        $output["response"]["messages"] = array();
        $output['statusCode'] = STATUS_CREATED;
        return $output;
    }

// Add event Validations
    public function addEventValidation($inputs) {
        $errorMessages = array();
        $this->ci->form_validation->pass_array($inputs);
        if (isset($inputs['eventMode']) && $inputs['eventMode'] != 1) {
            $this->ci->form_validation->set_rules('countryId', 'Country Id', 'required_strict|is_natural_no_zero');
            $this->ci->form_validation->set_rules('stateId', 'State Id', 'required_strict|is_natural_no_zero');
            $this->ci->form_validation->set_rules('cityId', 'City Id', 'required_strict|is_natural_no_zero');
            $this->ci->form_validation->set_rules('venueName', 'venueName', 'required_strict');
        }
        $this->ci->form_validation->set_rules('categoryId', 'Category Id', 'required_strict|is_natural_no_zero');
        $this->ci->form_validation->set_rules('subcategoryId', 'Subcategory Name', 'required_strict|is_natural_no_zero');
        //$this->ci->form_validation->set_rules('registrationType', 'registrationType', 'required_strict');
        $this->ci->form_validation->set_rules('ownerId', 'ownerId', 'required_strict|is_natural_no_zero');
        $this->ci->form_validation->set_rules('timezoneId', 'timezoneId', 'required_strict|is_natural_no_zero');
        $this->ci->form_validation->set_rules('title', 'title', 'required_strict');
        $this->ci->form_validation->set_rules('description', 'description', 'required_strict');
        $this->ci->form_validation->set_rules('url', 'url', 'required_strict|url');
        $this->ci->form_validation->set_rules('startDate', 'startDate', 'required_strict|date');
        $this->ci->form_validation->set_rules('startTime', 'startTime', 'required_strict');
        $this->ci->form_validation->set_rules('endDate', 'endDate', 'required_strict|date');
        $this->ci->form_validation->set_rules('endTime', 'endTime', 'required_strict');
        $this->ci->form_validation->set_rules('private', 'private', 'required_strict|enable');
        $this->ci->form_validation->set_rules('localityId', 'localityId', 'numeric');
        $this->ci->form_validation->set_rules('latitude', 'latitude', 'numeric');
        $this->ci->form_validation->set_rules('longitude', 'longitude', 'numeric');
//        $this->ci->form_validation->set_rules('pincode', 'pincode', 'is_natural_no_zero');
        $this->ci->form_validation->set_rules('status', 'status', 'required_strict|enable');
        $this->ci->form_validation->set_rules('ticketSoldout', 'ticketSoldout', 'is_natural');
        $this->ci->form_validation->set_rules('popularity', 'popularity', 'is_natural');
//        $this->ci->form_validation->set_rules('thumbnailFileId', 'thumbnailFileId', 'required_strict|is_natural_no_zero');
//        $this->ci->form_validation->set_rules('bannerFileId', 'bannerFileId', 'required_strict|is_natural_no_zero');


        if ($this->ci->form_validation->run() === FALSE) {
            $errorMessages = $this->ci->form_validation->get_errors();
            return $errorMessages;
        }

        $errorMessages['error'] = FALSE;
        return $errorMessages;
    }

    // Add event Detail Validations
    public function addEventDetailValidation($inputs) {
        $errorMessages = array();
        $this->ci->form_validation->pass_array($inputs);

        $this->ci->form_validation->set_rules('eventId', 'eventId', 'required_strict|is_natural_no_zero');
        $this->ci->form_validation->set_rules('booknowButtonValue', 'booknowButtonValue', 'required_strict');
        $this->ci->form_validation->set_rules('contactDisplay', 'contactDisplay', 'enable');
        $this->ci->form_validation->set_rules('salesPersonId', 'salesPersonId', 'is_natural');
        $this->ci->form_validation->set_rules('useOriginalImage', 'useOriginalImage', 'enable');
        $this->ci->form_validation->set_rules('passRequired', 'passRequired', 'enable');
        $this->ci->form_validation->set_rules('limitSingleTicketType', 'limitSingleTicketType', 'enable');
        // $this->ci->form_validation->set_rules('discountAfterTax', 'discountAfterTax', 'is_natural');

        if ($this->ci->form_validation->run() === FALSE) {
            $errorMessages = $this->ci->form_validation->get_errors();
            $errorMessages['error'] = TRUE;
            return $errorMessages;
        }

        $errorMessages['error'] = FALSE;
        return $errorMessages;
    }

    //  Add event Tags in eventtags table

    public function addEventTag($data) {

        $validationStatus = $this->addEventTagValidation($data);
        if ($validationStatus['error'] == TRUE) {
            $output['status'] = FALSE;
            $output['response']['messages'] = $validationStatus['message'];
            $output['statusCode'] = STATUS_BAD_REQUEST;
            return $output;
        }
        if (!is_array($data['tags']) || empty($data['tags'])) {
            $output['status'] = FALSE;
            $output['response']['messages'][] = ERROR_TAGS_VALUE;
            $output['statusCode'] = STATUS_BAD_REQUEST;
            return $output;
        }
        $tagEventData = array();
        foreach ($data['tags'] as $tagsKey => $tagsValue) {
            if (!isset($tagsValue['id']) || $tagsValue['id'] == '') {
                $output['status'] = FALSE;
                $output['response']['messages'][] = ERROR_TAGS_VALUE;
                $output['statusCode'] = STATUS_BAD_REQUEST;
                return $output;
            }
            $tagEventData[$tagsKey]['eventid'] = $data['eventId'];
            $tagEventData[$tagsKey]['tagid'] = $tagsValue['id'];
        }

        $this->ci->load->model('Eventtag_model');
        $this->ci->Eventtag_model->resetVariable();
        $this->ci->Eventtag_model->setInsertUpdateData($tagEventData);

        $response = $this->ci->Eventtag_model->insertMultiple_data();
        if (count($data['tags']) == $response) {
            $output['status'] = TRUE;
            $output['response']['affectedRows'] = $response;
            $output["response"]["messages"] = array();
            $output['statusCode'] = STATUS_CREATED;
            return $output;
        } else {
            $output['status'] = FALSE;
            $output["response"]["messages"][] = ERROR_SOMETHING_WENT_WRONG;
            $output['statusCode'] = 400;
            return $output;
        }
    }

    // Add event tag  Validations
    public function addEventTagValidation($inputs) {
        $errorMessages = array();
        $this->ci->form_validation->pass_array($inputs);

        $this->ci->form_validation->set_rules('eventId', 'eventId', 'required_strict|is_natural_no_zero');

        if ($this->ci->form_validation->run() === FALSE) {
            $errorMessages = $this->ci->form_validation->get_errors();
            return $errorMessages;
        }

        $errorMessages['error'] = FALSE;
        return $errorMessages;
    }

    // solr addevent array formation
    public function solrAddEventInputData($inputs) {

        if (isset($inputs['countryId'])) {
            $solrArray['countryId'] = $inputs['countryId'];
        }
        if (isset($inputs['stateId'])) {
            $solrArray['stateId'] = $inputs['stateId'];
        }
        if (isset($inputs['cityId'])) {
            $solrArray['cityId'] = $inputs['cityId'];
        }
        if (isset($inputs['categoryId'])) {
            $solrArray['categoryId'] = $inputs['categoryId'];
        }
        if (isset($inputs['subcategoryId'])) {
            $solrArray['subcategoryId'] = $inputs['subcategoryId'];
        }
        if (isset($inputs['venueName'])) {
            $solrArray['venueName'] = $inputs['venueName'];
        }
        if (isset($inputs['timezoneId'])) {
            $solrArray['timezoneId'] = $inputs['timezoneId'];
        }
        if (isset($inputs['status'])) {
            $solrArray['status'] = $inputs['status'];
        }
        if (isset($inputs['registrationtype'])) {
            $solrArray['registrationType'] = $inputs['registrationtype'];
        }
        if (isset($inputs['registrationType'])) {
            $solrArray['registrationType'] = $inputs['registrationType'];
        }
        if (isset($inputs['utcStartDate'])) {
            $solrArray['startDateTime'] = $inputs['utcStartDate'];
        }
        if (isset($inputs['utcEndDate'])) {
            $solrArray['endDateTime'] = $inputs['utcEndDate'];
        }
        if (isset($inputs['seoTitle'])) {
            $solrArray['seoTitle'] = $inputs['seoTitle'];
        }
        if (isset($inputs['url'])) {
            $solrArray['url'] = $inputs['url'];
        }
        if (isset($inputs['private'])) {
            $solrArray['private'] = $inputs['private'];
        }
        if (isset($inputs['eventId'])) {
            $solrArray['id'] = $inputs['eventId'];
        }
        if (isset($inputs['seoKeywords'])) {
            $solrArray['seoKeywords'] = $inputs['seoKeywords'];
        }
        if (isset($inputs['title'])) {
            $solrArray['title'] = $inputs['title'];
        }
        if (isset($inputs['thumbnailFilePath'])) {
            $solrArray['thumbImage'] = $inputs['thumbnailFilePath'];
        }
        if (isset($inputs['bannerFilePath'])) {
            $solrArray['bannerImage'] = $inputs['bannerFilePath'];
        }
        if (isset($inputs['eventMode'])) {
            $solrArray['eventMode'] = $inputs['eventMode'];
        }
        if (isset($inputs['ticketSoldout'])) {
            $solrArray['ticketSoldout'] = $inputs['ticketSoldout'];
        }
        if (!isset($solrArray['ticketSoldout'])) {
            $solrArray['ticketSoldout'] = 0;
        }
        if (isset($inputs['seoDescription'])) {
            $solrArray['seoDescription'] = $inputs['seoDescription'];
        }

        if (isset($inputs['popularity'])) {
            $solrArray['popularity'] = $inputs['popularity'];
        }
        if (isset($inputs['latitude'])) {
            $solrArray['latitude'] = $inputs['latitude'];
        }
        if (isset($inputs['longitude'])) {
            $solrArray['longitude'] = $inputs['longitude'];
        }

        if (isset($inputs['tags'])) {

            foreach ($inputs['tags'] as $tagKey => $tagValue) {
                $tagData[] = $tagValue['tag'];
            }
            $solrArray['eventTags'] = implode(',', $tagData);
        }

        if (isset($inputs['booknowButtonValue'])) {
            $solrArray['booknowbuttonvalue'] = $inputs['booknowButtonValue'];
        }
        return $solrArray;
    }

    /**
     * 
     * @param type $inputArray
     *  $inputArray[eventId]
     *   
     * $inputArray[banner]//Html dom element name
     * 
     */
    public function eventBannerUpload($inputArray) {
        $fileHandler = new File_handler();
        $galleryHandler = new Gallery_handler();
        //checking banner selected from theme or new image
        if (isset($inputArray['bannerSource']) && !empty($inputArray['bannerSource'])) {
            $input['source'] = $inputArray['bannerSource'];
            $input['type'] = 'bannerImage';
            $input['eventId'] = $inputArray['eventId'];
            $bannerResponse = $fileHandler->save($input);
            if ($bannerResponse['status'] === FALSE) {
                return $bannerResponse;
            } elseif ($bannerResponse['status'] === TRUE) {
                $output['response']['bannerFileId'] = $bannerResponse['response']['fileId'];
                $output['response']['bannerFilePath'] = $bannerResponse['response']['filePath'];
            }
            $output['status'] = TRUE;
            $output['statusCode'] = STATUS_CREATED;
            return $output;
        } else if (($inputArray["submitValue"] == "golive") ||
                ($inputArray["submitValue"] != "golive" && isset($_FILES[$inputArray['banner']]["name"]))) {//go live button click
            //Banner Image Upload 
            $bannerFileConfig['fieldName'] = $inputArray['banner'];
            $bannerPath = $this->ci->config->item('event_banner_path') . $inputArray['eventId'];
            $bannerFileConfig['upload_path'] = $this->ci->config->item('file_upload_path') . $bannerPath;
            $bannerFileConfig['allowed_types'] = IMAGE_EXTENTIONS;
            $bannerFileConfig['dbFilePath'] = $bannerPath . "/";
            $bannerFileConfig['dbFileType'] = FILE_TYPE_BANNER;
            $bannerFileConfig['folderId'] = $inputArray['eventId'];
            $bannerFileConfig['fileName'] = $_FILES[$inputArray['banner']]["name"];
            $bannerFileConfig['sourcePath'] = $_FILES[$inputArray['banner']]["tmp_name"];
            $bannerFileConfig['imageResize'] = FALSE;
            $imageResponse = $fileHandler->doUpload($bannerFileConfig);
        }

        if ($imageResponse['status'] === FALSE) {
            return $imageResponse;
        } elseif ($imageResponse['status'] === TRUE) {
            $output['response']['imageFileId'] = $imageResponse['response']['fileId'];
            $output['response']['imageFilePath'] = $imageResponse['response']['filePath'];

            //Getting the thumbnail image with the 1170x370px 
            $fileExtention = getExtension($_FILES[$inputArray['banner']]["name"]);
            $srcImageResponse = $galleryHandler->createImage($_FILES[$inputArray['banner']]["tmp_name"], $fileExtention);
            if ($srcImageResponse['status'] == FALSE) {
                return $srcImageResponse;
            }
            $srcImage = $srcImageResponse['response']['image'];
            //Finding start and end points where to start craping
            $srcWidth = imagesx($srcImage);
            $srcHeight = imagesy($srcImage);
            $bannerWidth = 1170;
            $bannerHeight = 370;
            //Create new image with the 1170 and 370 dimensions
            $destImage = imagecreatetruecolor($bannerWidth, $bannerHeight);
            //Resizing the image into the new image acc to given dimensions
            imagecopyresampled($destImage, $srcImage, 0, 0, 0, 0, $bannerWidth, $bannerHeight, $srcWidth, $srcHeight);
            $bannerName = preg_replace('/(\.gif|\.jpeg|\.jpg|\.png)/', '_banner$1', $_FILES[$inputArray['banner']]['name']);
            $sourcePath = $bannerFileConfig['sourcePath'] = $this->ci->config->item('file_upload_temp_path') . $bannerName;
            $createImageResponse = $galleryHandler->createImageType($destImage, $sourcePath, $fileExtention);

            if (!$createImageResponse['status']) {
                return $createImageResponse;
            }
            $bannerFileConfig['fileName'] = $bannerName;
            $bannerFileConfig['dbFileType'] = FILE_TYPE_BANNER;
            $bannerFileConfig['imageResize'] = TRUE;
            $bannerResponse = $fileHandler->doUpload($bannerFileConfig);
            unlink($sourcePath); //Deleting the thumbnail file generated in the temp folder
            if ($bannerResponse['status'] === FALSE) {
                return $bannerResponse;
            } elseif ($bannerResponse['status'] === TRUE) {
                $output['response']['bannerFileId'] = $bannerResponse['response']['fileId'];
                $output['response']['bannerFilePath'] = $bannerResponse['response']['filePath'];
            }
            $output['status'] = TRUE;
            $output['statusCode'] = STATUS_CREATED;
        }
        return $output;
    }

    /**
     * 
     * @param type $inputArray
     *  $inputArray[eventId]
     *  $inputArray[thumbnail]//Html dom element name
     *   
     */
    public function eventLogoUpload($inputArray) {
        $fileHandler = new File_handler();
        $galleryHandler = new Gallery_handler();
        //checking thumbnail selected from theme or new image
        if (isset($inputArray['thumbSource']) && !empty($inputArray['thumbSource'])) {
            $input['source'] = $inputArray['thumbSource'];
            $input['type'] = 'thumbImage';
            $input['eventId'] = $inputArray['eventId'];
            $thumbnailResponse = $fileHandler->save($input);
            if ($thumbnailResponse['status'] === FALSE) {
                return $thumbnailResponse;
            } elseif ($thumbnailResponse['status'] === TRUE) {
                $output['response']['thumbnailFileId'] = $thumbnailResponse['response']['fileId'];
                $output['response']['thumbnailFilePath'] = $thumbnailResponse['response']['filePath'];
            }

            $output['status'] = TRUE;
            $output['statusCode'] = STATUS_CREATED;
            return $output;
        } else if (($inputArray["submitValue"] == "golive") ||
                ($inputArray["submitValue"] != "golive" && isset($_FILES[$inputArray['thumbnail']]["name"]))) {//go live button click
            //Thumbnail image upload
            $thumbnailFileConfig['fieldName'] = $inputArray['thumbnail'];
            $thumbnailPath = $this->ci->config->item('event_thumbnail_path') . $inputArray['eventId'];
            $thumbnailFileConfig['upload_path'] = $this->ci->config->item('file_upload_path') . $thumbnailPath;
            $thumbnailFileConfig['allowed_types'] = IMAGE_EXTENTIONS;
            $thumbnailFileConfig['dbFilePath'] = $thumbnailPath . "/";
            $thumbnailFileConfig['dbFileType'] = FILE_TYPE_THUMBNAIL;
            $thumbnailFileConfig['folderId'] = $inputArray['eventId'];
            $thumbnailFileConfig['fileName'] = $_FILES[$inputArray['thumbnail']]["name"];
            $thumbnailFileConfig['sourcePath'] = $_FILES[$inputArray['thumbnail']]["tmp_name"];
            $thumbnailFileConfig['imageResize'] = FALSE;
            $imageResponse = $fileHandler->doUpload($thumbnailFileConfig);
        }

        if ($imageResponse['status'] === FALSE) {
            return $imageResponse;
        } elseif ($imageResponse['status'] === TRUE) {
            $output['response']['imageFileId'] = $imageResponse['response']['fileId'];
            $output['response']['imageFilePath'] = $imageResponse['response']['filePath'];

            //Getting the thumbnail image with the 350x200px 
            $fileExtention = getExtension($_FILES[$inputArray['thumbnail']]["name"]);
            $srcImageResponse = $galleryHandler->createImage($_FILES[$inputArray['thumbnail']]["tmp_name"], $fileExtention);
            if ($srcImageResponse['status'] == FALSE) {
                return $srcImageResponse;
            }
            $srcImage = $srcImageResponse['response']['image'];
            //Finding start and end points where to start craping
            $srcWidth = imagesx($srcImage);
            $srcHeight = imagesy($srcImage);
            $bannerWidth = 350;
            $bannerHeight = 200;
            //Create new image with the 350 and 200 dimensions
            $destImage = imagecreatetruecolor($bannerWidth, $bannerHeight);
            //Resizing the image into the new image acc to given dimensions
            imagecopyresampled($destImage, $srcImage, 0, 0, 0, 0, $bannerWidth, $bannerHeight, $srcWidth, $srcHeight);
            $thumbnailName = preg_replace('/(\.gif|\.jpeg|\.jpg|\.png)/', '_thumb$1', $_FILES[$inputArray['thumbnail']]["name"]);
            $sourcePath = $thumbnailFileConfig['sourcePath'] = $this->ci->config->item('file_upload_temp_path') . $thumbnailName;
            $createImageResponse = $galleryHandler->createImageType($destImage, $sourcePath, $fileExtention);

            if (!$createImageResponse['status']) {
                return $createImageResponse;
            }
            $thumbnailFileConfig['fileName'] = $thumbnailName;
            $thumbnailFileConfig['dbFileType'] = FILE_TYPE_THUMBNAIL;
            $thumbnailFileConfig['imageResize'] = TRUE;
            $thumbnailResponse = $fileHandler->doUpload($thumbnailFileConfig);
            unlink($sourcePath); //Deleting the thumbnail file generated in the temp folder
            if ($thumbnailResponse['status'] === FALSE) {
                return $thumbnailResponse;
            } elseif ($thumbnailResponse['status'] === TRUE) {
                $output['response']['thumbnailFileId'] = $thumbnailResponse['response']['fileId'];
                $output['response']['thumbnailFilePath'] = $thumbnailResponse['response']['filePath'];
            }
            $output['status'] = TRUE;
            $output['statusCode'] = STATUS_CREATED;
            return $output;
        }
    }

    public function updateEvent($data) {
        $validationStatus = $this->updateEventTagValidation($data);
        if ($validationStatus['error'] == TRUE) {
            $output['status'] = FALSE;
            $output['response']['messages'] = $validationStatus['message'];
            $output['statusCode'] = STATUS_BAD_REQUEST;
            return $output;
        }
        $eventData['thumbnailfileid'] = $data['thumbnailFileId'];
        $eventData['bannerfileid'] = $data['bannerFileId'];
        $this->ci->Event_model->resetVariable();
        $where = array($this->ci->Event_model->id => $data['eventId']);
        $this->ci->Event_model->setInsertUpdateData($eventData);
        $this->ci->Event_model->setWhere($where);
        $response = $this->ci->Event_model->update_data();
        if ($response) {
            $output['status'] = TRUE;
            $output["response"]["messages"] = array();
            $output['statusCode'] = STATUS_UPDATED;
            return $output;
        }
        $output['status'] = FALSE;
        $output["response"]["messages"][] = SOMETHING_WENT_WRONG;
        $output['statusCode'] = 400;
        return $output;
    }

    // update event tag  Validations
    public function updateEventTagValidation($inputs) {
        $errorMessages = array();
        $this->ci->form_validation->pass_array($inputs);

        $this->ci->form_validation->set_rules('eventId', 'eventId', 'required_strict|is_natural_no_zero');
        if ($inputs["submitValue"] == "golive") {
            $this->ci->form_validation->set_rules('thumbnailFileId', 'thumbnailFileId', 'is_natural_no_zero');
            $this->ci->form_validation->set_rules('bannerFileId', 'bannerFileId', 'is_natural_no_zero');
        }


        if ($this->ci->form_validation->run() === FALSE) {
            $errorMessages = $this->ci->form_validation->get_errors();
            return $errorMessages;
        }

        $errorMessages['error'] = FALSE;
        return $errorMessages;
    }

    /**
     * It checks the event url exist are not 
     * @param type $input
     * $input['eventId']//excludes passed event id
     * @return int
     */
    public function checkUrlExists($input) {
        $solrHandler = new Solr_handler();
        $urlCheck = $solrHandler->getEventByUrl($input);

        //At the time of update event we sending event id we are excluding that eventid
        if (isset($urlCheck['response']['eventId']) && $urlCheck['response']['eventId'] != '' && ($input['eventId'] != $urlCheck['response']['eventId'])) {
            $output['status'] = FALSE;
            $output["response"]["messages"][] = ERROR_EXISTED_EVENT_URL;
            $output['statusCode'] = 400;
        } else {
            $output['status'] = TRUE;
            $output["response"]["messages"][] = SUCCESS_EVENT_URL_AVAILABLE;
            $output['statusCode'] = STATUS_OK;
        }
        return $output;
    }

    // Create Event Input data formation for form submit
    public function createEventInputDataFormat($data) {
        //print_r($data);exit;
        $this->ci->form_validation->reset_form_rules();
        $this->ci->form_validation->pass_array($data);
        $this->ci->form_validation->set_rules('title', 'title', 'required_strict|min_length[5]|titlePattern|notOnlySpecialChars|max_length[255]');
        $this->ci->form_validation->set_rules('description', 'description', 'required_strict|min_length[50]');
        
        $this->ci->form_validation->set_rules('categoryId', 'categoryId', 'required_strict|is_natural_no_zero');
        $this->ci->form_validation->set_rules('subcategoryId', 'subcategoryId', 'required_strict');
        $this->ci->form_validation->set_rules('url', 'url', 'required_strict|urlPattern');
          $this->ci->form_validation->set_rules('country', 'country', 'required_strict|countryPattern');
            $this->ci->form_validation->set_rules('state', 'state', 'required_strict|statePattern');
                $this->ci->form_validation->set_rules('city', 'city', 'required_strict|cityPattern');
        $this->ci->form_validation->set_rules('venueName', 'venueName', 'required_strict|venuePattern|max_length[255]');
        $this->ci->form_validation->set_rules('venueaddress1', 'venueaddress1', 'venuePattern|max_length[150]');
        $this->ci->form_validation->set_rules('venueaddress2', 'venueaddress2', 'max_length[150]');
        $this->ci->form_validation->set_rules('startDate', 'startDate', 'required_strict|date');
        $this->ci->form_validation->set_rules('startTime', 'startTime', 'required_strict|timeFormat');
        $this->ci->form_validation->set_rules('endDate', 'endDate', 'required_strict|date');
        $this->ci->form_validation->set_rules('endTime', 'endTime', 'required_strict|timeFormat');
        
        $this->ci->form_validation->set_rules('timezoneId', 'timezoneId', 'required_strict|is_natural_no_zero');
        $this->ci->form_validation->set_rules('booknowButtonValue', 'booknowButtonValue', 'required_strict|booknowValues');
        $this->ci->form_validation->set_rules('private', 'private', 'enable');
        //$this->ci->form_validation->set_rules('latitude', 'latitude', 'required_strict|is_numeric');
        //$this->ci->form_validation->set_rules('longitude', 'longitude', 'required_strict|is_numeric');
      //  $this->ci->form_validation->set_rules('localityId', 'localityId', 'required_strict');
        
        $this->ci->form_validation->set_rules('pincode', 'pincode', 'is_natural_no_zero|max_length[6]');
       // $this->ci->form_validation->set_rules('popularity', 'popularity', 'required_strict');
        //$this->ci->form_validation->set_rules('ownerId', 'ownerId', 'required_strict');
        
        $this->ci->form_validation->set_rules('bannerSource', 'bannerSource', 'imagePattern');
        $this->ci->form_validation->set_rules('thumbSource', 'thumbSource', 'imagePattern');
        $this->ci->form_validation->set_rules('iswebinar', 'iswebinar', 'enable');
        $this->ci->form_validation->set_rules('acceptmeeffortcommission', 'acceptmeeffortcommission', 'enable');
                   // $this->ci->form_validation->set_rules('country', 'country', 'required_strict');
                    //    $this->ci->form_validation->set_rules('country', 'country', 'required_strict');
        
        $this->ci->form_validation->set_rules('submitValue', 'submitValue', 'required_strict|submitValues');
        if ($this->ci->form_validation->run() == FALSE) {
            $response = $this->ci->form_validation->get_errors('message');
            $output['status'] = FALSE;
            $output['response']['messages'] = $response['message'];
            $output['statusCode'] = STATUS_BAD_REQUEST;
            //print_r( $output['response']['messages']);exit;
            return $output;
        }
        if ($data['submitValue'] == 'save' || $data['submitValue'] == 'preview')
            $param['status'] = '0';

        if ($data['submitValue'] == 'golive')
            $param['status'] = 1;

        $param['submitValue'] = $data['submitValue'];
        // $param['registrationType'] = $data['registrationType'];
//        if(isset($data['tickets'])) {
//            
//        } else {
//            $data['tickets'] = array();
//        }
        //$param['registrationType'] = $this->getEventRegistrationType($data['tickets']);
        $param['title'] = removeScriptTag($data['title']);
        $param['acceptmeeffortcommission'] = $data['acceptmeeffortcommission'];
        $param['description'] = $data['description'];
        $param['categoryId'] = $data['categoryId'];
        $param['subcategoryId'] = $data['subcategoryId'];
        $param['url'] = cleanUrl($data['url']);
        $param['venueName'] = $data['venueName'];
        $param['venueaddress1'] = $data['venueaddress1'];
        $param['venueaddress2'] = $data['venueaddress2'];
        $param['startDate'] = urldecode($data['startDate']);
        $param['startTime'] = allTimeFormats(urldecode($data['startTime']), 12);
        $param['endDate'] = urldecode($data['endDate']);
        $param['endTime'] = allTimeFormats(urldecode($data['endTime']), 12);
        $param['timezoneId'] = $data['timezoneId'];
        $param['booknowButtonValue'] = isset($data['booknowButtonValue']) ? $data['booknowButtonValue'] : 'book now';
        $param['private'] = isset($data['private']) ? $data['private'] : "0";
        $param['latitude'] = isset($data['latitude']) ? $data['latitude'] : "0";
        $param['longitude'] = isset($data['longitude']) ? $data['longitude'] : "0";
        $param['localityId'] = isset($data['localityId']) ? $data['localityId'] : "0";
        $param['pincode'] = isset($data['pincode']) ? $data['pincode'] : "0";
        $param['popularity'] = isset($data['popularity']) ? $data['popularity'] : "0";
        $param['ownerId'] = $data['ownerId'];
        $param['bannerSource'] = isset($data['bannerSource']) ? $data['bannerSource'] : '';
        $param['thumbSource'] = isset($data['thumbSource']) ? $data['thumbSource'] : '';
        $param['iswebinar'] = isset($data['iswebinar']) ? $data['iswebinar'] : "0";
        $param['removeBanner'] = isset($data['removebannerSource']) ? $data['removebannerSource'] : '';
        $param['removeThumb'] = isset($data['removethumbSource']) ? $data['removethumbSource'] : ''; 
        
        $param['organiser_fee'] = isset($data['organiser_fee']) ? $data['organiser_fee'] : '';
        
        foreach ($data['ticketName'] as $key => $value) {
            if(empty(trim($value))){
                $output['status'] = FALSE;
                $output['response']['ticketmessages'][$key]['ticketName'] = ERROR_EMPTY_TICKET_NAME;
                $output['statusCode'] = STATUS_BAD_REQUEST;
                //return $output;
            }elseif(strlen($value)<2){
                $output['status'] = FALSE;
                $output['response']['ticketmessages'][$key]['ticketName'] = ERROR_TICKET_NAME_MIN_LENGTH;
                $output['statusCode'] = STATUS_BAD_REQUEST;
                //return $output;
            }elseif(strlen($value)>75){
                $output['status'] = FALSE;
                $output['response']['ticketmessages'][$key]['ticketName'] = ERROR_TICKET_NAME_MAX_LENGTH;
                $output['statusCode'] = STATUS_BAD_REQUEST;
                //return $output;
            }elseif(!preg_match('/^[0-9a-zA-Z \$\%\#\&\_\-\*\@\+\,\(\)]+$/', $value)){
                $output['status'] = FALSE;
                $output['response']['ticketmessages'][$key]['ticketName'] = ERROR_TICKET_NAME_PATTERN;
                $output['statusCode'] = STATUS_BAD_REQUEST;
                //return $output;
            }
            if(!isset($data['order'][$key]) || empty($data['order'][$key])){
                $output['status'] = FALSE;
                $output['response']['ticketmessages'][$key]['order'] = ERROR_TICKET_ORDER_REQUIRED;
                $output['statusCode'] = STATUS_BAD_REQUEST;
                //return $output;
            }elseif(!preg_match('/^[0-9]+$/', $data['order'][$key]) && (int)$data['order'][$key]<=0){
                $output['status'] = FALSE;
                $output['response']['ticketmessages'][$key]['order'] = ERROR_TICKET_ORDER_INVALID;
                $output['statusCode'] = STATUS_BAD_REQUEST;
                //return $output;
            }
            if(strlen($data['ticketDescription'][$key])>300){
                $output['status'] = FALSE;
                $output['response']['ticketmessages'][$key]['ticketDescription'] = ERROR_TICKET_DESCRIPTION_MAX_LENGTH;
                $output['statusCode'] = STATUS_BAD_REQUEST;
                //return $output;
            }
            if(!isset($data['ticketType'][$key]) || empty($data['ticketType'][$key])){
                $output['status'] = FALSE;
                $output['response']['ticketmessages'][$key]['ticketType'] = ERROR_TICKET_TYPE_REQUIRED;
                $output['statusCode'] = STATUS_BAD_REQUEST;
            }elseif(!in_array($data['ticketType'][$key],array(1,2,3,4))){
                $output['status'] = FALSE;
                $output['response']['ticketmessages'][$key]['ticketType'] = ERROR_TICKET_TYPE_INVALID;
                $output['statusCode'] = STATUS_BAD_REQUEST;
            }
            if(($data['ticketType'][$key]=='2' || $data['ticketType'][$key]=='4')){
                if($data['price'][$key]==''){
                    $output['status'] = FALSE;
                    $output['response']['ticketmessages'][$key]['price'] = ERROR_TICKET_PRICE_EMPTY;
                    $output['statusCode'] = STATUS_BAD_REQUEST;
                    //return $output;
                }elseif(!preg_match('/^[0-9]+$/',$data['price'][$key]) || (int)$data['price'][$key]<=0){
                    $output['status'] = FALSE;
                    $output['response']['ticketmessages'][$key]['price'] = ERROR_TICKET_PRICE_NON_NUMERIC;
                    $output['statusCode'] = STATUS_BAD_REQUEST;
                    //return $output;
                }
            }
            if($data['ticketType'][$key]!='3'){
                if(!preg_match('/^[0-9]+$/',$data['quantity'][$key]) || (int)$data['quantity'][$key]<=0){
                    $output['status'] = FALSE;
                    $output['response']['ticketmessages'][$key]['quantity'] = ERROR_TICKET_QUANTITY_NON_NUMERIC;
                    $output['statusCode'] = STATUS_BAD_REQUEST;
                    //return $output;
                }
                if(!preg_match('/^[0-9]+$/',$data['minquantity'][$key]) || (int)$data['minquantity'][$key]<=0){
                    $output['status'] = FALSE;
                    $output['response']['ticketmessages'][$key]['minquantity'] = ERROR_TICKET_MIN_QTY_NON_NUMERIC;
                    $output['statusCode'] = STATUS_BAD_REQUEST;
                    //return $output;
                }elseif(!isset($output['response']['ticketmessages'][$key]['quantity']) && $data['minquantity'][$key]>$data['quantity'][$key]){
                    $output['status'] = FALSE;
                    $output['response']['ticketmessages'][$key]['minquantity'] = ERROR_TICKET_MIN_QTY_MORE_THAN_QTY;
                    $output['statusCode'] = STATUS_BAD_REQUEST;
                    //return $output;
                }
                if(!preg_match('/^[0-9]+$/',$data['maxquantity'][$key]) || (int)$data['maxquantity'][$key]<=0){
                    $output['status'] = FALSE;
                    $output['response']['ticketmessages'][$key]['maxquantity'] = ERROR_TICKET_MAX_QTY_NON_NUMERIC;
                    $output['statusCode'] = STATUS_BAD_REQUEST;
                    //return $output;
                }elseif(!isset($output['response']['ticketmessages'][$key]['quantity']) && $data['maxquantity'][$key]>$data['quantity'][$key]){
                    $output['status'] = FALSE;
                    $output['response']['ticketmessages'][$key]['maxquantity'] = ERROR_TICKET_MAX_QTY_MORE_THAN_QTY;
                    $output['statusCode'] = STATUS_BAD_REQUEST;
                    //return $output;
                }elseif(!isset($output['response']['ticketmessages'][$key]['minquantity']) && $data['maxquantity'][$key]<$data['minquantity'][$key]){
                    $output['status'] = FALSE;
                    $output['response']['ticketmessages'][$key]['maxquantity'] = ERROR_TICKET_MAX_QTY_LESS_THAN_MIN_QTY;
                    $output['statusCode'] = STATUS_BAD_REQUEST;
                    //return $output;
                }
            }
            if(!isset($data['ticketstartDate'][$key])){
                $output['status'] = FALSE;
                $output['response']['ticketmessages'][$key]['ticketstartDate'] = ERROR_TICKET_START_DATE_REQUIRED;
                $output['statusCode'] = STATUS_BAD_REQUEST;
                //return $output;
            }elseif(!preg_match("/^(0[1-9]|1[0-2])\/(0[1-9]|[1-2][0-9]|3[0-1])\/[0-9]{4}+$/", $data['ticketstartDate'][$key])){
                $output['status'] = FALSE;
                $output['response']['ticketmessages'][$key]['ticketstartDate'] = ERROR_DATE_VALUE_FORMAT;
                $output['statusCode'] = STATUS_BAD_REQUEST;
                //return $output;
            }
            if(!isset($data['ticketstartTime'][$key])){
                $output['status'] = FALSE;
                $output['response']['ticketmessages'][$key]['ticketstartTime'] = ERROR_TICKET_START_TIME_REQUIRED;
                $output['statusCode'] = STATUS_BAD_REQUEST;
                //return $output;
            }elseif(!(preg_match("/(0?\d|1[0-2]):(0\d|[0-5]\d) (AM|PM)/i", $data['ticketstartTime'][$key]))){
                $output['status'] = FALSE;
                $output['response']['ticketmessages'][$key]['ticketstartTime'] = ERROR_TIME_VALUE_FORMAT;
                $output['statusCode'] = STATUS_BAD_REQUEST;
                //return $output;
            }
            if(!isset($data['ticketendDate'][$key])){
                $output['status'] = FALSE;
                $output['response']['ticketmessages'][$key]['ticketendDate'] = ERROR_TICKET_END_DATE_REQUIRED;
                $output['statusCode'] = STATUS_BAD_REQUEST;
                //return $output;
            }elseif(!preg_match("/^(0[1-9]|1[0-2])\/(0[1-9]|[1-2][0-9]|3[0-1])\/[0-9]{4}+$/", $data['ticketendDate'][$key])){
                $output['status'] = FALSE;
                $output['response']['ticketmessages'][$key]['ticketendDate'] = ERROR_DATE_VALUE_FORMAT;
                $output['statusCode'] = STATUS_BAD_REQUEST;
                //return $output;
            }
            if(!isset($data['ticketendTime'][$key])){
                $output['status'] = FALSE;
                $output['response']['ticketmessages'][$key]['ticketendTime'] = ERROR_TICKET_END_TIME_REQUIRED;
                $output['statusCode'] = STATUS_BAD_REQUEST;
                //return $output;
            }elseif(!preg_match("/(0?\d|1[0-2]):(0\d|[0-5]\d) (AM|PM)/i", $data['ticketendTime'][$key])){
                $output['status'] = FALSE;
                $output['response']['ticketmessages'][$key]['ticketendTime'] = ERROR_TIME_VALUE_FORMAT;
                $output['statusCode'] = STATUS_BAD_REQUEST;
                //return $output;
            }
            if(!isset($output['status']) || !$output['status']){
            $startDate = isset($data['ticketstartDate'][$key]) ? urldecode($data['ticketstartDate'][$key]) : '';
            $startTime = isset($data['ticketstartTime'][$key]) ? allTimeFormats(urldecode($data['ticketstartTime'][$key]), 12) : '';
            $endDate = isset($data['ticketendDate'][$key]) ? urldecode($data['ticketendDate'][$key]) : '';
            $endTime = isset($data['ticketendTime'][$key]) ? allTimeFormats(urldecode($data['ticketendTime'][$key]), 12) : '';
            $param['tickets'][$key]['name'] = $value;
            $param['tickets'][$key]['type'] = $data['ticketType'][$key];
            $param['tickets'][$key]['startDate'] = $startDate;
            $param['tickets'][$key]['startTime'] = $startTime;
            $param['tickets'][$key]['endDate'] = $endDate;
            $param['tickets'][$key]['endTime'] = $endTime;
            $param['tickets'][$key]['description'] = $data['ticketDescription'][$key];
            $param['tickets'][$key]['price'] = $data['price'][$key];
            $param['tickets'][$key]['quantity'] = $data['quantity'][$key];
            $param['tickets'][$key]['minOrderQuantity'] = $data['minquantity'][$key];
            $param['tickets'][$key]['maxOrderQuantity'] = $data['maxquantity'][$key];
            $param['tickets'][$key]['order'] = $data['order'][$key];
            if ($data['ticketType'][$key] == 1) {
                $param['tickets'][$key]['currencyId'] = 3; // Free Currency Type
            } else {
                $param['tickets'][$key]['currencyId'] = isset($data['currencyType'][$key]) ? $data['currencyType'][$key] : '';
            }
            $param['tickets'][$key]['soldOut'] = isset($data['soldOut'][$key]) ? $data['soldOut'][$key] : 0;
            $param['tickets'][$key]['displayStatus'] = isset($data['nottodisplay'][$key]) ? $data['nottodisplay'][$key] : 1;

            //$param['tickets'][$key]['taxArray'] = $this->processInputTaxArray($data, $key);
            $param['tickets'][$key]['taxArray'] = isset($data['taxArray'][$key]) ? $data['taxArray'][$key] : array();
        }
        }
        if(isset($output['status']) && !$output['status']){
         //print_r($output);exit;
                return $output;
        }
        if (isset($data['country']))
            $param['country'] = $data['country'];
        if (isset($data['state']))
            $param['state'] = $data['state'];
        if (isset($data['city']))
            $param['city'] = $data['city'];
        if (isset($data['countryId']))
            $param['countryId'] = $data['countryId'];
        if (isset($data['stateId']))
            $param['stateId'] = $data['stateId'];
        if (isset($data['cityId']))
            $param['cityId'] = $data['cityId'];

        $tags = array();

        /* currently we are getting tags like comma suppurated so we have added id as 0
          in future if you pass id and tag array can directly pass like $param['tags]=$data['tags];

         * Eg: $data['tags']=array(array("id"=>1,"tag"=>"Zumbass Lake Worth"),array("id"=>2,"tag"=>"Youth Camp"),
          array("id"=>0,"tag"=>"Camp7"),array("id"=>0,"tag"=>"Camp8"),array("id"=>"0","tag"=>"Camp9"));
         * 
          $param['tags']=$data['tags];
         */
        if (isset($data['tags']) && $data['tags'] != '') {
            $tags = explode(",", $data['tags']);
            foreach ($tags as $tagKey => $tagValue) {
                $param['tags'][$tagKey]['id'] = 0;
                $param['tags'][$tagKey]['tag'] = $tagValue;
            }
        }
        $output['status'] = TRUE;
        $output['response']['formattedData'] = $param;
        $output['statusCode'] = STATUS_OK;
        $output['response']['total'] = 1;
        $output['response']['messages'] = array();
        return $output;
    }

    public function galleryList($inputArray) {
        $validationStatus = $this->galleryValidation($inputArray);
        if ($validationStatus['error'] == TRUE) {
            $output['status'] = FALSE;
            $output['response']['messages'] = $validationStatus['message'];
            $output['statusCode'] = STATUS_BAD_REQUEST;
            return $output;
        }
        $eventExists = $this->eventExists($inputArray);
        if ($eventExists['status'] == TRUE) {
            $list = array(
                array(
                    "id" => "1",
                    "thumbnailId" => "10",
                    "thumbnailPath" => $this->ci->config->item('images_content_path') . "gallery/event_detail_img2.jpg",
                    "imageId" => "1",
                    "imagePath" => $this->ci->config->item('images_content_path') . "gallery/event_detail_img21.jpg",
                    "order" => "1"
                ),
                array("id" => "2",
                    "thumbnailId" => "10",
                    "thumbnailPath" => $this->ci->config->item('images_content_path') . "gallery/event_detail_img3.jpg",
                    "imageId" => "2",
                    "imagePath" => $this->ci->config->item('images_content_path') . "gallery/event_detail_img31.jpg",
                    "order" => "1"),
                array("id" => "3",
                    "thumbnailId" => "12",
                    "thumbnailPath" => $this->ci->config->item('images_content_path') . "gallery/event_detail_img4.jpg",
                    "imageId" => "3",
                    "imagePath" => $this->ci->config->item('images_content_path') . "gallery/event_detail_img41.jpg",
                    "order" => "1"),
                array("id" => "4",
                    "thumbnailId" => "13",
                    "thumbnailPath" => $this->ci->config->item('images_content_path') . "gallery/event_detail_img5.jpg",
                    "imageId" => "4",
                    "imagePath" => $this->ci->config->item('images_content_path') . "gallery/event_detail_img51.jpg",
                    "order" => "1"));

            $output['status'] = TRUE;
            $output['response']['galleryList'] = $list;
            $output['statusCode'] = STATUS_OK;
            $output['response']['total'] = 4;
            $output['response']['messages'] = array();
            return $output;
        } else {
            return $eventExists;
        }
    }

    public function galleryValidation($inputArray) {

        $errorMessages = array();
        $this->ci->form_validation->reset_form_rules();
        $this->ci->form_validation->pass_array($inputArray);
        $this->ci->form_validation->set_rules('eventId', 'EventId', 'is_natural_no_zero|required_strict');
        if ($this->ci->form_validation->run() === FALSE) {
            $errorMessages = $this->ci->form_validation->get_errors();
            return $errorMessages;
        }
        $errorMessages['error'] = FALSE;
        return $errorMessages;
    }

    public function eventExists($inputArray) {
        parent::$CI->form_validation->reset_form_rules();
        $this->ci->form_validation->pass_array($inputArray);
        $this->ci->form_validation->set_rules('eventId', 'eventId', 'required_strict|is_natural_no_zero');
        if ($this->ci->form_validation->run() == FALSE) {
            $response = $this->ci->form_validation->get_errors('message');
            $output['status'] = FALSE;
            $output['response']['messages'] = $response['message'];
            $output['statusCode'] = STATUS_BAD_REQUEST;
            return $output;
        }
        $this->ci->Event_model->resetVariable();
        $select['id'] = 'COUNT( ' . $this->ci->Event_model->id . ' )';
        $where[$this->ci->Event_model->id] = $inputArray['eventId'];
        $where[$this->ci->Event_model->deleted] = 0;
        //$where[$this->ci->Event_model->status] = 1;
        $this->ci->Event_model->setRecords(1);
        $this->ci->Event_model->setSelect($select);
        $this->ci->Event_model->setWhere($where);
        $eventExistResponse = $this->ci->Event_model->get();
        if ($eventExistResponse[0]['id'] > 0) {
            $output = parent::createResponse(TRUE, SUCCESS_EVENT_EXISTED, STATUS_OK);
            return $output;
        } else {
            $output = parent::createResponse(FALSE, ERROR_NO_EVENT, STATUS_BAD_REQUEST);
            return $output;
        }
    }

    /**
     * Event update process start from this function
     * @param type $data
     */
    public function eventUpdate($data) {
        $countryHandler = new Country_handler();
        $stateHandler = new State_handler();
        $cityHandler = new City_handler();
        $timezoneHandler = new Timezone_handler();
        $userType = $this->ci->customsession->getData('userType');
        // Block event titles
        $blockedTitleResponse = $this->blockEventTitles($data);
        if ($blockedTitleResponse['status'] === FALSE) {
            return $blockedTitleResponse;
        }
        // Transaction based event creation if one of them failed it will 
        // rollback if all success quries will commit
        $this->ci->Event_model->resetVariable();
        $this->ci->Event_model->startTransaction();

        $selectValues[$this->ci->Event_model->registrationtype] = $this->ci->Event_model->registrationtype;
        $this->ci->Event_model->setSelect($selectValues);
        $whereES[$this->ci->Event_model->id] = $data['eventId'];
        $this->ci->Event_model->setWhere($whereES);
        $oldEventDetails = $this->ci->Event_model->get();

        $oldregistrationType = isset($oldEventDetails[0]['registrationtype']) ? $oldEventDetails[0]['registrationtype'] : '';
        unset($data['oldregistrationType']);
        $data['eventMode'] = 0; //(IsWebinar)
        /*
         * registrationType=1 =>free
         * registrationType=2 =>paid
         * registrationType=3 =>no registration
         *  
         */

        $data['registrationType'] = $this->getEventRegistrationType($data['tickets']);
        // for webinar country, state and city will be null
        if ($data['iswebinar'] == 1) {
            $data['eventMode'] = 1;
        }

        // add country if countryId is not exist
        if (isset($data['country'])) {
            $countryData = array();
            $countryData['country'] = $data['country'];
            $countryResponse = $countryHandler->countryInsert($countryData);
            if ($countryResponse['status'] === FALSE) {
                return $countryResponse;
            }
            $data['countryId'] = $countryResponse['response']['countryId'];
        }


        // add state if stateId is not exist
        if (isset($data['state'])) {
            $stateData = array();
            $stateData['countryId'] = $data['countryId'];
            $stateData['state'] = $data['state'];
            $stateResponse = $stateHandler->stateInsert($stateData);
            if ($stateResponse['status'] === FALSE) {
                return $stateResponse;
            }
            $data['stateId'] = $stateResponse['response']['stateId'];
        }

        // add city if cityId is not exist
        if (isset($data['city'])) {
            $cityData = array();
            $cityData['countryId'] = $data['countryId'];
            $cityData['stateId'] = $data['stateId'];
            $cityData['city'] = $data['city'];
            $cityResponse = $cityHandler->cityInsert($cityData);
            if ($cityResponse['status'] === FALSE) {
                return $cityResponse;
            }
            $data['cityId'] = $cityResponse['response']['cityId'];
        }
        //Bring the sub category details
        $this->subcategoryHandler = new Subcategory_handler();
        $subcategoryData = array();
        $subcategoryData['categoryId'] = $data['categoryId'];
        $subcategoryData['subcategoryName'] = $data['subcategoryId'];
        $subcategoryResponse = $this->subcategoryHandler->subcategoryInsert($subcategoryData);
        if (!$subcategoryResponse['status']) {
            return $subcategoryResponse;
        }
        $data['subcategoryId'] = $subcategoryResponse['response']['subcategoryId'];

        $data['startDate'] = urldecode($data['startDate']);
        $data['endDate'] = urldecode($data['endDate']);
        $data['startTime'] = urldecode($data['startTime']);
        $data['endTime'] = urldecode($data['endTime']);
        $startDateValidation = dateValidation($data['startDate'], '/');
        $endDateValidation = dateValidation($data['endDate'], '/');
        if (!$startDateValidation || !$endDateValidation) {
            $output['status'] = FALSE;
            $output["response"]["messages"][] = ERROR_DATE_VALUE_FORMAT;
            $output['statusCode'] = STATUS_BAD_REQUEST;
            return $output;
        }

        // changing date format mm/dd/yyyy to yyyy-mm-dd    
        $startDate = dateFormate($data['startDate'], '/');
        $endDate = dateFormate($data['endDate'], '/');

        $timeZoneData['timezoneId'] = $data['timezoneId'];
        $timeZoneData['status'] = 1;
        $timeZoneDetails = $timezoneHandler->details($timeZoneData);
        $timeZoneName = "";
        if ($timeZoneDetails['status']) {
            $data['timeZoneName'] = $timeZoneDetails['response']['detail'][1]['name'];
        }
        $eventStartDate = convertTime($startDate . ' ' . $data['startTime'], $data['timeZoneName']);
        $eventEndDate = convertTime($endDate . ' ' . $data['endTime'], $data['timeZoneName']);
        $data['utcStartDate'] = $eventStartDate;
        $data['utcEndDate'] = $eventEndDate;
        if ($data["submitValue"] == "golive") {
            if (strtotime(allTimeFormats('', 11)) > strtotime($eventStartDate) && !in_array($userType, $this->ci->config->item('editEventAccess'))) {
                $output['status'] = FALSE;
                $output["response"]["messages"][] = ERROR_EVENT_START_DATE_GREATER_THAN_NOW;
                $output['statusCode'] = STATUS_BAD_REQUEST;
                return $output;
            }
        }
        if ((strtotime(allTimeFormats('', 11)) > strtotime($eventStartDate)) && $data['newStartDate'] && !in_array($userType, $this->ci->config->item('editEventAccess'))) {
            $output['status'] = FALSE;
            $output["response"]["messages"][] = ERROR_START_DATE_GREATER_THAN_NOW;
            $output['statusCode'] = STATUS_BAD_REQUEST;
            return $output;
        }

        if ((strtotime($eventStartDate) >= strtotime($eventEndDate)) && $data['newStartDate']) {
            $output['status'] = FALSE;
            $output["response"]["messages"][] = ERROR_START_DATE_GREATER;
            $output['statusCode'] = STATUS_BAD_REQUEST;
            return $output;
        }
        $data['startDateTime'] = $eventStartDate;
        $data['endDateTime'] = $eventEndDate;

        $data['thumbnail'] = "thumbImage";
        $data['banner'] = "bannerImage";
        $data['bannerSource'] = isset($data['bannerSource']) ? $data['bannerSource'] : '';
        $data['thumbSource'] = isset($data['thumbSource']) ? $data['thumbSource'] : '';
        //Either theme image or file has uploaded

        if (strlen($data['bannerSource']) > 0 || isset($_FILES[$data['banner']]["name"])) {
            $bannerIds = $this->eventBannerUpload($data);
            if (!$bannerIds['status']) {
                return $bannerIds;
            }
            $data['bannerFileId'] = $bannerIds ['response']['bannerFileId'];
            $data['bannerFilePath'] = $bannerIds ['response']['bannerFilePath'];
        } else {
            $data['bannerFileId'] = $data['oldBannerId'];
        }

        if (strlen($data['thumbSource']) > 0 || isset($_FILES[$data['thumbnail']]["name"])) {
            $logoIds = $this->eventLogoUpload($data);
            if ($logoIds['status'] === FALSE) {
                return $logoIds;
            }
            $data['thumbnailFileId'] = $logoIds ['response']['thumbnailFileId'];
            $data['thumbnailFilePath'] = $logoIds ['response']['thumbnailFilePath'];
        } else {
            $data['thumbnailFileId'] = $data['oldThumbId'];
        }
        // remove banner in file table
        if(isset($data['removeBanner']) && $data['removeBanner'] == 1 && $data['oldBannerId'] > 0){
        $removeBanner= array();
        $removeBanner['id']=$data['oldBannerId'];
        $removeBanner['deleted'] =1;
        $removeFile=$this->removeEventFileData($removeBanner);
        $data['bannerFileId'] = 0;
        $data['bannerFilePath']='';
        }

        // remove thumb in file table
        if(isset($data['removeThumb']) && $data['removeThumb'] == 1 && $data['oldThumbId'] > 0){
        $removeThumb= array();
        $removeThumb['id']=$data['oldThumbId'];
        $removeThumb['deleted'] =1;
        $removeFile=$this->removeEventFileData($removeThumb);
        $data['thumbnailFileId'] = 0;
        $data['thumbnailFilePath']='';
        }

        $updateEventResponse = $this->eventTableUpate($data);
            
        if (!$updateEventResponse['status']) {
            return $updateEventResponse;
        }
//        print_r($updateEventResponse);
        // add evetdetails in eventdetail table
        $updateEventDetailResponse = $this->eventDetailsUpdate($data);

        if (!$updateEventDetailResponse['status']) {
            return $updateEventDetailResponse;
        }

        /* add tags in tag table
         * it returns tags with id and name
         */
        $tagHandler = new Tag_handler();
        $addTagResponse = array();
        if (isset($data['tags']) && !empty($data['tags'])) {
            $addTagResponse = $tagHandler->addTag($data);
            if (!$addTagResponse['status']) {
                return $addTagResponse;
            }
        }
        $eventTagData = array();
        $eventTagData['newTags'] = isset($addTagResponse['response']['tags']) ? $addTagResponse['response']['tags'] : array();
        $eventTagData['eventId'] = $data['eventId'];

        //get old eventtags records
        $oldResponse = $this->getEventTagIds($eventTagData);
        $eventTagData['oldTags'] = $oldResponse['response']['eventTagsList'];
        $eventTagResponse = $this->processEventTags($eventTagData);
        //Change the deleted status for not existed tags
        if (count($eventTagResponse['removeTags']) > 0) {
            $eventTagData['tags'] = $eventTagResponse['removeTags'];
            $removeTagsResponse = $this->removeEventTags($eventTagData);
            if (!$removeTagsResponse['status']) {
                return $removeTagsResponse;
            }
        }
        //insert the new ids
        if (count($eventTagResponse['insertTags']) > 0) {
            $eventTagData['tags'] = $eventTagResponse['insertTags'];
            $addEventTagResponse = $this->addEventTag($eventTagData);
            if ($addEventTagResponse['status'] === FALSE) {
                return $addEventTagResponse;
            }
        }

        //Free Registration no tickets
        if ($data['registrationType'] != 3) {
            $ticketStatus = $this->processEventTicketUpdateArray($data);

            if (!$ticketStatus['status']) {
                return $ticketStatus;
            }
        }
        //Check the transaction status
        if ($this->ci->Event_model->transactionStatusCheck() === FALSE) {
            $this->ci->Event_model->rollBackLastTransaction();
            $output['status'] = FALSE;
            $output["response"]["messages"][] = ERROR_SOMETHING_WENT_WRONG;
            $output['statusCode'] = STATUS_SERVER_ERROR;
            return $output;
        } else {

            $solrHandler = new Solr_handler();

            // add event in slor
            $solrData = array();
            $solrData = $this->solrAddEventInputData($data);
            $addEventInSolr = $solrHandler->solrUpdateEvent($solrData);
            if (!$addEventInSolr['status']) {
                return $addEventInSolr;
            }
            // add tags in solr
            $solrTagData = array();
            if (isset($data['tags']) && !empty($data['tags'])) {
                foreach ($addTagResponse['response']['tags'] as $solrTagKey => $solrTagvalue) {
                    $solrTagData['id'] = $solrTagvalue['id'];
                    $solrTagData['name'] = $solrTagvalue['tag'];
                    $addTagInSolr = $solrHandler->solrAddTag($solrTagData);
                    if ($addTagInSolr['status'] === FALSE) {
                        return $addTagInSolr;
                    }
                }
            }
            $this->ci->Event_model->commitLastTransaction();
            
            // Insert extra charges if the organiser checks the checkboxes for fees
            
            $organiser_fee = $data['organiser_fee'];
            $eventId = $data['eventId'];

            $feeArray = $this->ci->config->item('organizer_fees');
            require_once (APPPATH . 'handlers/eventextracharge_handler.php');
            $eventextrachargeHandler = new Eventextracharge_handler();
            $removeArr['eventId'] = $eventId;
            $eventextrachargeHandler->extraChargeRemove($removeArr);

            if($organiser_fee != '') {
                
                $addedExtraArr = array();
                if($organiser_fee == 'both') {
                    $addedExtraArr = array('servicecharge','gatewaycharge');
                } else {
                    $addedExtraArr = array($organiser_fee);
                }
                $serviceTax = $this->ci->config->item('service_tax');
                foreach($feeArray as $feeKey => $feeValue) {
                    $insertArr = array();
                    $insertArr['eventId'] = $eventId;
                    if(in_array($feeKey,$addedExtraArr)) {
                        $insertArr['label'] = $feeValue['label'];
                        $insertArr['value'] = $feeValue['value']+round(($feeValue['value']*$serviceTax/100),2);
                        $eventextrachargeHandler->extraChargeInsert($insertArr);   
                    }
                }    
            }
            
            $output['status'] = TRUE;
            $output["response"]["url"] = $data['url'];
            $output["response"]["id"] = $data['eventId'];
            $output["response"]["total"] = 1;
            $output["response"]["messages"][] = SOLR_EVENT_UPDATE;
            $output['statusCode'] = STATUS_UPDATED;
            return $output;
        }
    }

    /**
     * To Formate the input data ,
     * If fields are not passed assigning the previous values from db
     * @param type $data
     */
    public function updateEventInputDataFormat($data) {
        //print_r($data);exit;
        $this->ci->form_validation->reset_form_rules();
        $this->ci->form_validation->pass_array($data);
        $this->ci->form_validation->set_rules('eventId', 'eventId', 'required_strict|is_natural_no_zero');
        $this->ci->form_validation->set_rules('title', 'title', 'required_strict|min_length[5]|titlePattern|notOnlySpecialChars|max_length[255]');
        $this->ci->form_validation->set_rules('description', 'description', 'required_strict|min_length[50]');
        
        $this->ci->form_validation->set_rules('categoryId', 'categoryId', 'required_strict|is_natural_no_zero');
        $this->ci->form_validation->set_rules('subcategoryId', 'subcategoryId', 'required_strict|subcategoryNamePattern');
        $this->ci->form_validation->set_rules('url', 'url', 'required_strict|urlPattern');
          $this->ci->form_validation->set_rules('country', 'country', 'required_strict|countryPattern');
            $this->ci->form_validation->set_rules('state', 'state', 'required_strict|statePattern');
                $this->ci->form_validation->set_rules('city', 'city', 'required_strict|cityPattern');
        $this->ci->form_validation->set_rules('venueName', 'venueName', 'required_strict|venuePattern|max_length[255]');
        $this->ci->form_validation->set_rules('venueaddress1', 'venueaddress1', 'venuePattern|max_length[150]');
        $this->ci->form_validation->set_rules('venueaddress2', 'venueaddress2', 'max_length[150]');
        $this->ci->form_validation->set_rules('startDate', 'startDate', 'date');
        $this->ci->form_validation->set_rules('startTime', 'startTime', 'timeFormat');
        $this->ci->form_validation->set_rules('endDate', 'endDate', 'date');
        $this->ci->form_validation->set_rules('endTime', 'endTime', 'timeFormat');
        
        $this->ci->form_validation->set_rules('timezoneId', 'timezoneId', 'required_strict|is_natural_no_zero');
        $this->ci->form_validation->set_rules('booknowButtonValue', 'booknowButtonValue', 'required_strict|booknowValues');
        $this->ci->form_validation->set_rules('private', 'private', 'enable');
       // $this->ci->form_validation->set_rules('latitude', 'latitude', 'required_strict');
       // $this->ci->form_validation->set_rules('longitude', 'longitude', 'required_strict');
      //  $this->ci->form_validation->set_rules('localityId', 'localityId', 'required_strict');
        
        $this->ci->form_validation->set_rules('pincode', 'pincode', 'is_natural_no_zero|max_length[6]');
       // $this->ci->form_validation->set_rules('popularity', 'popularity', 'required_strict');
        //$this->ci->form_validation->set_rules('ownerId', 'ownerId', 'required_strict');
        
       // $this->ci->form_validation->set_rules('bannerSource', 'bannerSource', 'required_strict');
       // $this->ci->form_validation->set_rules('thumbSource', 'thumbSource', 'required_strict');
        $this->ci->form_validation->set_rules('iswebinar', 'iswebinar', 'enable');
        $this->ci->form_validation->set_rules('acceptmeeffortcommission', 'acceptmeeffortcommission', 'enable');
                   // $this->ci->form_validation->set_rules('country', 'country', 'required_strict');
                    //    $this->ci->form_validation->set_rules('country', 'country', 'required_strict');
        
        $this->ci->form_validation->set_rules('submitValue', 'submitValue', 'required_strict|submitValues');
        if ($this->ci->form_validation->run() == FALSE) {
            $response = $this->ci->form_validation->get_errors('message');
            $output['status'] = FALSE;
            $output['response']['messages'] = $response['message'];
            $output['statusCode'] = STATUS_BAD_REQUEST;
            return $output;
        }
        $eventArray = array();
        $output = array();
        $eventArray['eventId'] = $data['eventId'];
        $eventDetailsResponse = $this->getEventDetails($eventArray);
        if (!$eventDetailsResponse['status']) {
            $output['status'] = FALSE;
            $output['response']['message'][] = ERROR_INVALID_EVENTID;
            $output['statusCode'] = STATUS_BAD_REQUEST;
            return $output;
        }
        $eventDetails = $eventDetailsResponse['response']['details'];

        if ($data['submitValue'] == 'golive')
            $param['status'] = "1";

        $param['eventId'] = $data['eventId'];
        $param['acceptmeeffortcommission'] = $data['acceptmeeffortcommission'];
        $param['submitValue'] = ($data['submitValue']) ? $data['submitValue'] : 0;
        $param['registrationType'] = isset($data['registrationType']) ? $data['registrationType'] : $eventDetails['registrationType'];
        $param['title'] = isset($data['title']) ? removeScriptTag($data['title']) : $eventDetails['title'];
        $param['description'] = isset($data['description']) ? $data['description'] : $eventDetails['description'];
        $param['categoryId'] = isset($data['categoryId']) ? $data['categoryId'] : $eventDetails['categoryId'];
        $param['subcategoryId'] = isset($data['subcategoryId']) ? $data['subcategoryId'] : $eventDetails['subcategoryId'];
        $param['url'] = isset($data['url']) ? cleanUrl($data['url']) : $eventDetails['url'];
        $param['venueName'] = isset($data['venueName']) ? $data['venueName'] : $eventDetails['venueName'];
        $param['venueaddress1'] = isset($data['venueaddress1']) ? $data['venueaddress1'] : $eventDetails['location']['address1'];
        $param['venueaddress2'] = isset($data['venueaddress2']) ? $data['venueaddress2'] : $eventDetails['location']['address2'];
        $param['status'] = (isset($param['status'])) ? $param['status'] : $eventDetails ['status'];
        if (isset($data['startDate'])) {
            $param['startDate'] = urldecode($data['startDate']);
            $param['newStartDate'] = TRUE;
        } else {
            $param['startDate'] = extractDate($eventDetails['startDate']);
        }
        $param['startTime'] = isset($data['startTime']) ? allTimeFormats(urldecode($data['startTime']), 12) : extractTime($eventDetails['startDate']);
        if ($data['endDate']) {
            $param['endDate'] = urldecode($data['endDate']);
            $param['newEndDate'] = TRUE;
        } else {
            $param['endDate'] = extractDate($eventDetails['endDate']);
            $param['newEndDate'] = FALSE;
        }
        $param['endTime'] = isset($data['endTime']) ? allTimeFormats(urldecode($data['endTime']), 12) : extractTime($eventDetails['endDate']);
        $param['timezoneId'] = isset($data['timezoneId']) ? $data['timezoneId'] : $eventDetails['timeZoneId'];

        $param['private'] = isset($data['private']) ? $data['private'] : $eventDetails['eventMode'];
        $param['pincode'] = isset($data['pincode']) ? $data['pincode'] : $eventDetails['pincode'];
        $param['latitude'] = isset($data['latitude']) ? $data['latitude'] : $eventDetails['latitude'];
        $param['longitude'] = isset($data['longitude']) ? $data['longitude'] : $eventDetails['longitude'];
        $param['ownerId'] = isset($data['ownerId']) ? $data['ownerId'] : $eventDetails['ownerId'];
        $param['bannerSource'] = isset($data['bannerSource']) ? $data['bannerSource'] : '';
        $param['thumbSource'] = isset($data['thumbSource']) ? $data['thumbSource'] : '';
        $param['oldBannerId'] = $eventDetails['bannerfileid'];
        $param['oldThumbId'] = $eventDetails['thumbnailfileid'];
        $param['iswebinar'] = isset($data['iswebinar']) ? $data['iswebinar'] : $eventDetails['eventmode'];
        $param['removeBanner'] = isset($data['removebannerSource']) ? $data['removebannerSource'] : '';
        $param['removeThumb'] = isset($data['removethumbSource']) ? $data['removethumbSource'] : ''; 
        $param['booknowButtonValue'] = isset($data['booknowButtonValue']) ? $data['booknowButtonValue'] : $eventDetails['eventDetails']['bookButtonValue'];
        $param['country'] = (isset($data['country'])) ? $data['country'] : $eventDetails['location']['countryName'];
        $param['state'] = (isset($data['state'])) ? $data['state'] : $eventDetails['location']['stateName'];
        $param['city'] = (isset($data['city'])) ? $data['city'] : $eventDetails['location']['cityName'];
        
        $param['organiser_fee'] = isset($data['organiser_fee']) ? $data['organiser_fee'] : '';
        
        $tags = array();
        if (isset($data['tags']) && $data['tags'] != '') {
            $tags = explode(",", $data['tags']);
            foreach ($tags as $tagKey => $tagValue) {
                $param['tags'][$tagKey]['id'] = 0;
                $param['tags'][$tagKey]['tag'] = $tagValue;
            }
        }

        $ticketDetailsData = $this->updateEventTicketDataFormat($data);
        if(!$ticketDetailsData['status']){
            return $ticketDetailsData;
    }
        $param['tickets'] = $ticketDetailsData['response']['formattedData']['tickets'];
        $output['status'] = TRUE;
        $output['response']['formattedData'] = $param;
        $output['statusCode'] = STATUS_OK;
        $output['response']['total'] = 1;
        $output['response']['messages'] = array();
        return $output;
    }

    /**
     * To Process the input tickets information data
     */
    public function updateEventTicketDataFormat($data) {
        //print_r($data);exit;
        $eventArray = array();
        $output = array();
        $eventArray['eventId'] = $data['eventId'];
        $ticketDetails = $this->getEventTicketDetails($eventArray);

        if (!$ticketDetails['status']) {
            $output['status'] = FALSE;
            $output['response']['message'][] = ERROR_INVALID_EVENTID;
            $output['statusCode'] = STATUS_BAD_REQUEST;
            return $output;
        }
        $ticketDetails = $ticketDetails['response']['ticketList'];
        foreach ($data['ticketName'] as $key => $value) {
            $ticketCount=$data['indexedTicketArr'][$key];
            if(empty(trim($value))){
                $output['status'] = FALSE;
                $output['response']['ticketmessages'][$ticketCount]['ticketName'] = ERROR_EMPTY_TICKET_NAME;
                $output['statusCode'] = STATUS_BAD_REQUEST;
                //return $output;
            }elseif(strlen($value)<2){
                $output['status'] = FALSE;
                $output['response']['ticketmessages'][$ticketCount]['ticketName'] = ERROR_TICKET_NAME_MIN_LENGTH;
                $output['statusCode'] = STATUS_BAD_REQUEST;
                //return $output;
            }elseif(strlen($value)>75){
                $output['status'] = FALSE;
                $output['response']['ticketmessages'][$ticketCount]['ticketName'] = ERROR_TICKET_NAME_MAX_LENGTH;
                $output['statusCode'] = STATUS_BAD_REQUEST;
                //return $output;
            }elseif(!preg_match('/^[0-9a-zA-Z \$\%\#\&\_\-\*\@\+\,\(\)]+$/', $value)){
                $output['status'] = FALSE;
                $output['response']['ticketmessages'][$ticketCount]['ticketName'] = ERROR_TICKET_NAME_PATTERN;
                $output['statusCode'] = STATUS_BAD_REQUEST;
                //return $output;
            }
            if(!isset($data['order'][$key]) || empty($data['order'][$key])){
                $output['status'] = FALSE;
                $output['response']['ticketmessages'][$ticketCount]['order'] = ERROR_TICKET_ORDER_REQUIRED;
                $output['statusCode'] = STATUS_BAD_REQUEST;
                //return $output;
            }elseif(!preg_match('/^[0-9]+$/', $data['order'][$key]) && (int)$data['order'][$key]<=0){
                $output['status'] = FALSE;
                $output['response']['ticketmessages'][$ticketCount]['order'] = ERROR_TICKET_ORDER_INVALID;
                $output['statusCode'] = STATUS_BAD_REQUEST;
                //return $output;
            }
            if(strlen($data['ticketDescription'][$key])>300){
                $output['status'] = FALSE;
                $output['response']['ticketmessages'][$ticketCount]['ticketDescription'] = ERROR_TICKET_DESCRIPTION_MAX_LENGTH;
                $output['statusCode'] = STATUS_BAD_REQUEST;
                //return $output;
            }
            if(!isset($data['ticketType'][$key]) || empty($data['ticketType'][$key])){
                $output['status'] = FALSE;
                $output['response']['ticketmessages'][$ticketCount]['ticketType'] = ERROR_TICKET_TYPE_REQUIRED;
                $output['statusCode'] = STATUS_BAD_REQUEST;
            }elseif(!in_array($data['ticketType'][$key],array(1,2,3,4))){
                $output['status'] = FALSE;
                $output['response']['ticketmessages'][$ticketCount]['ticketType'] = ERROR_TICKET_TYPE_INVALID;
                $output['statusCode'] = STATUS_BAD_REQUEST;
            }
            if(($data['ticketType'][$key]=='2' || $data['ticketType'][$key]=='4')){
                if($data['price'][$key]==''){
                    $output['status'] = FALSE;
                    $output['response']['ticketmessages'][$ticketCount]['price'] = ERROR_TICKET_DESCRIPTION_MAX_LENGTH;
                    $output['statusCode'] = STATUS_BAD_REQUEST;
                    //return $output;
                }elseif(!preg_match('/^[0-9]+$/',$data['price'][$key]) || (int)$data['price'][$key]<=0){
                    $output['status'] = FALSE;
                    $output['response']['ticketmessages'][$ticketCount]['price'] = ERROR_TICKET_PRICE_NON_NUMERIC;
                    $output['statusCode'] = STATUS_BAD_REQUEST;
                    //return $output;
                }
            }
            if($data['ticketType'][$key]!='3'){
                if(!preg_match('/^[0-9]+$/',$data['quantity'][$key]) || (int)$data['quantity'][$key]<=0){
                    $output['status'] = FALSE;
                    $output['response']['ticketmessages'][$ticketCount]['quantity'] = ERROR_TICKET_QUANTITY_NON_NUMERIC;
                    $output['statusCode'] = STATUS_BAD_REQUEST;
                    //return $output;
                }
                if(!preg_match('/^[0-9]+$/',$data['minquantity'][$key]) || (int)$data['minquantity'][$key]<=0){
                    $output['status'] = FALSE;
                    $output['response']['ticketmessages'][$ticketCount]['minquantity'] = ERROR_TICKET_MIN_QTY_NON_NUMERIC;
                    $output['statusCode'] = STATUS_BAD_REQUEST;
                    //return $output;
                }elseif(!isset($output['response']['ticketmessages'][$ticketCount]['quantity']) && $data['minquantity'][$key]>$data['quantity'][$key]){
                    $output['status'] = FALSE;
                    $output['response']['ticketmessages'][$ticketCount]['minquantity'] = ERROR_TICKET_MIN_QTY_MORE_THAN_QTY;
                    $output['statusCode'] = STATUS_BAD_REQUEST;
                    //return $output;
                }
                if(!preg_match('/^[0-9]+$/',$data['maxquantity'][$key]) || (int)$data['maxquantity'][$key]<=0){
                    $output['status'] = FALSE;
                    $output['response']['ticketmessages'][$ticketCount]['maxquantity'] = ERROR_TICKET_MAX_QTY_NON_NUMERIC;
                    $output['statusCode'] = STATUS_BAD_REQUEST;
                    //return $output;
                }elseif(!isset($output['response']['ticketmessages'][$ticketCount]['quantity']) && $data['maxquantity'][$key]>$data['quantity'][$key]){
                    $output['status'] = FALSE;
                    $output['response']['ticketmessages'][$ticketCount]['maxquantity'] = ERROR_TICKET_MAX_QTY_MORE_THAN_QTY;
                    $output['statusCode'] = STATUS_BAD_REQUEST;
                    //return $output;
                }elseif(!isset($output['response']['ticketmessages'][$ticketCount]['minquantity']) && $data['maxquantity'][$key]<$data['minquantity'][$key]){
                    $output['status'] = FALSE;
                    $output['response']['ticketmessages'][$ticketCount]['maxquantity'] = ERROR_TICKET_MAX_QTY_LESS_THAN_MIN_QTY;
                    $output['statusCode'] = STATUS_BAD_REQUEST;
                    //return $output;
                }
            }
            if(!isset($data['ticketstartDate'][$key])){
                $output['status'] = FALSE;
                $output['response']['ticketmessages'][$ticketCount]['ticketstartDate'] = ERROR_TICKET_START_DATE_REQUIRED;
                $output['statusCode'] = STATUS_BAD_REQUEST;
                //return $output;
            }elseif(!preg_match("/^(0[1-9]|1[0-2])\/(0[1-9]|[1-2][0-9]|3[0-1])\/[0-9]{4}+$/", $data['ticketstartDate'][$key])){
                $output['status'] = FALSE;
                $output['response']['ticketmessages'][$ticketCount]['ticketstartDate'] = ERROR_DATE_VALUE_FORMAT;
                $output['statusCode'] = STATUS_BAD_REQUEST;
                //return $output;
            }
            if(!isset($data['ticketstartTime'][$key])){
                $output['status'] = FALSE;
                $output['response']['ticketmessages'][$ticketCount]['ticketstartTime'] = ERROR_TICKET_START_TIME_REQUIRED;
                $output['statusCode'] = STATUS_BAD_REQUEST;
                //return $output;
            }elseif(!(preg_match("/(0?\d|1[0-2]):(0\d|[0-5]\d) (AM|PM)/i", $data['ticketstartTime'][$key]))){
                $output['status'] = FALSE;
                $output['response']['ticketmessages'][$ticketCount]['ticketstartTime'] = ERROR_TIME_VALUE_FORMAT;
                $output['statusCode'] = STATUS_BAD_REQUEST;
                //return $output;
            }
            if(!isset($data['ticketendDate'][$key])){
                $output['status'] = FALSE;
                $output['response']['ticketmessages'][$ticketCount]['ticketendDate'] = ERROR_TICKET_END_DATE_REQUIRED;
                $output['statusCode'] = STATUS_BAD_REQUEST;
                //return $output;
            }elseif(!preg_match("/^(0[1-9]|1[0-2])\/(0[1-9]|[1-2][0-9]|3[0-1])\/[0-9]{4}+$/", $data['ticketendDate'][$key])){
                $output['status'] = FALSE;
                $output['response']['ticketmessages'][$ticketCount]['ticketendDate'] = ERROR_DATE_VALUE_FORMAT;
                $output['statusCode'] = STATUS_BAD_REQUEST;
                //return $output;
            }
            if(!isset($data['ticketendTime'][$key])){
                $output['status'] = FALSE;
                $output['response']['ticketmessages'][$ticketCount]['ticketendTime'] = ERROR_TICKET_END_TIME_REQUIRED;
                $output['statusCode'] = STATUS_BAD_REQUEST;
                //return $output;
            }elseif(!preg_match("/(0?\d|1[0-2]):(0\d|[0-5]\d) (AM|PM)/i", $data['ticketendTime'][$key])){
                $output['status'] = FALSE;
                $output['response']['ticketmessages'][$ticketCount]['ticketendTime'] = ERROR_TIME_VALUE_FORMAT;
                $output['statusCode'] = STATUS_BAD_REQUEST;
                //return $output;
            }
            if(!isset($output['status']) || !$output['status']){
            //New ticket is added
            if (empty($data['ticketId'][$key]) || $data['ticketId'][$key] == 0) {
                $param['tickets'][$key]['ticketStatus'] = "NewTicket";
                $startDate = isset($data['ticketstartDate'][$key]) ? urldecode($data['ticketstartDate'][$key]) : '';
                $startTime = isset($data['ticketstartTime'][$key]) ? allTimeFormats(urldecode($data['ticketstartTime'][$key]), 12) : '';
                $endDate = isset($data['ticketendDate'][$key]) ? urldecode($data['ticketendDate'][$key]) : '';
                $endTime = isset($data['ticketendTime'][$key]) ? allTimeFormats(urldecode($data['ticketendTime'][$key]), 12) : '';
                $param['tickets'][$key]['name'] = $value;
                $param['tickets'][$key]['type'] = $data['ticketType'][$key];
                $param['tickets'][$key]['startDate'] = $startDate;
                $param['tickets'][$key]['startTime'] = $startTime;
                $param['tickets'][$key]['endDate'] = $endDate;
                $param['tickets'][$key]['endTime'] = $endTime;
                $param['tickets'][$key]['description'] = $data['ticketDescription'][$key];
                if ($param['tickets'][$key]['type'] != 2 && $param['tickets'][$key]['type'] != 4) {
                    $param['tickets'][$key]['price'] = 0;
                } else {
                    $param['tickets'][$key]['price'] = $data['price'][$key];
                }
                $param['tickets'][$key]['quantity'] = $data['quantity'][$key];
                $param['tickets'][$key]['minOrderQuantity'] = $data['minquantity'][$key];
                $param['tickets'][$key]['maxOrderQuantity'] = $data['maxquantity'][$key];
                $param['tickets'][$key]['order'] = $data['order'][$key];
                if ($data['ticketType'][$key] == 1) {
                    $param['tickets'][$key]['currencyId'] = 3; // Free Currency Type
                } else {
                    $param['tickets'][$key]['currencyId'] = isset($data['currencyType'][$key]) ? $data['currencyType'][$key] : '';
                }
                $param['tickets'][$key]['soldOut'] = isset($data['soldOut'][$key]) ? $data['soldOut'][$key] : 0;
                $param['tickets'][$key]['displayStatus'] = isset($data['nottodisplay'][$key]) ? $data['nottodisplay'][$key] : 1;
            } else {//Updating the old ticket details
                $oldTicketDetails = arrayComparison($ticketDetails, $data['ticketId'][$key]);
                $param['tickets'][$key]['ticketStatus'] = "oldTicket";
                $param['tickets'][$key]['ticketId'] = $data['ticketId'][$key];
                if ($data['ticketstartDate'][$key]) {

                    $param['tickets'][$key]['startDate'] = urldecode($data['ticketstartDate'][$key]);
                    $param['tickets'][$key]['newStartDate'] = TRUE;
                } else {
                    $param['tickets'][$key]['startDate'] = extractDate($oldTicketDetails['startDate']);
                }

                $param['tickets'][$key]['startTime'] = isset($data['ticketstartTime'][$key]) ? allTimeFormats(urldecode($data['ticketstartTime'][$key]), 12) : extractTime($oldTicketDetails['startDate']);
                if ($data['ticketendDate'][$key]) {

                    $param['tickets'][$key]['endDate'] = urldecode($data['ticketendDate'][$key]);
                    $param['tickets'][$key]['newendDate'] = TRUE;
                } else {
                    $param['tickets'][$key]['endDate'] = extractDate($oldTicketDetails['endDate']);
                }

                $param['tickets'][$key]['endTime'] = isset($data['ticketendTime'][$key]) ? allTimeFormats(urldecode($data['ticketendTime'][$key]), 12) : extractTime($oldTicketDetails['endDate']);
                $param['tickets'][$key]['name'] = ($value) ? $value : $oldTicketDetails['name'];
                $param['tickets'][$key]['type'] = ($data['ticketType'][$key]) ? $data['ticketType'][$key] : $oldTicketDetails['type'];
                $param['tickets'][$key]['description'] = isset($data['ticketDescription'][$key]) ? $data['ticketDescription'][$key] : $oldTicketDetails['description'];
                if ($param['tickets'][$key]['type'] != 2 && $param['tickets'][$key]['type'] != 4) {
                    $param['tickets'][$key]['price'] = 0;
                } else {
                    $param['tickets'][$key]['price'] = ($data['price'][$key]) ? $data['price'][$key] : $oldTicketDetails['price'];
                }
                $param['tickets'][$key]['quantity'] = ($data['quantity'][$key]) ? $data['quantity'][$key] : $oldTicketDetails['quantity'];
                $param['tickets'][$key]['minOrderQuantity'] = ($data['minquantity'][$key]) ? $data['minquantity'][$key] : $oldTicketDetails['minOrderQuantity'];
                $param['tickets'][$key]['maxOrderQuantity'] = ($data['maxquantity'][$key]) ? $data['maxquantity'][$key] : $oldTicketDetails['maxOrderQuantity'];
                $param['tickets'][$key]['order'] = isset($data['order'][$key]) ? $data['order'][$key] : $oldTicketDetails['order'];
                $param['tickets'][$key]['currencyId'] = isset($data['currencyType'][$key]) ? $data['currencyType'][$key] : $oldTicketDetails['currencyId'];
                $param['tickets'][$key]['soldOut'] = isset($data['soldOut'][$key]) ? $data['soldOut'][$key] : 0;
                $param['tickets'][$key]['totalSoldTickets'] = $oldTicketDetails['totalSoldTickets'];
                $param['tickets'][$key]['displayStatus'] = isset($data['nottodisplay'][$key]) ? $data['nottodisplay'][$key] : 1;
            }

            //$param['tickets'][$key]['taxArray'] = $this->processInputTaxArray($data, $key);
            //avoid taxes for free,donation ticket
            if ($param['tickets'][$key]['type'] == 2) {
                $param['tickets'][$key]['taxArray'] = isset($data['taxArray'][$key]) ? $data['taxArray'][$key] : array();
            } else {
                $param['tickets'][$key]['taxArray'] = array();
            }
        }
            $ticketCount++;
        }
        if(isset($output['status']) && !$output['status']){
         //print_r($output);exit;
                return $output;
        }
        $output['status'] = TRUE;
        $output['response']['formattedData'] = $param;
        $output['statusCode'] = STATUS_OK;
        $output['response']['total'] = 1;
        $output['response']['messages'] = array();
//        print_r($param);
//        exit;
        return $output;
    }

    /**
     * Edit event related fields
     */
    public function eventTableUpate($data) {
        $solrRequestArray['eventUrl'] = $data['url'];
        $solrRequestArray['eventId'] = $data['eventId'];
        $urlCheck = $this->checkUrlExists($solrRequestArray);
        if (!$urlCheck['status']) {
            return $urlCheck;
        }
        $validationStatus = $this->addEventValidation($data);
        if ($validationStatus['error'] == TRUE) {
            $output['status'] = FALSE;
            $output['response']['messages'][] = $validationStatus['message'];
            $output['statusCode'] = STATUS_BAD_REQUEST;
            return $output;
        }

        $eventData['registrationtype'] = $data['registrationType'];
        $eventData['eventmode'] = isset($data['eventMode']) ? $data['eventMode'] : 0;
        $eventData['title'] = $data['title'];
        $eventData['description'] = $data['description'];
        $eventData['categoryid'] = $data['categoryId'];
        $eventData['subcategoryid'] = $data['subcategoryId'];
        $eventData['url'] = $data['url'];
        $eventData['venuename'] = isset($data['venueName']) ? $data['venueName'] : '';
        $eventData['venueaddress1'] = isset($data['venueaddress1']) ? $data['venueaddress1'] : '';
        $eventData['venueaddress2'] = isset($data['venueaddress2']) ? $data['venueaddress2'] : '';
        $eventData['countryid'] = $data['countryId'];
        $eventData['stateid'] = $data['stateId'];
        $eventData['cityid'] = $data['cityId'];
        $eventData['startdatetime'] = $data['startDateTime'];
        $eventData['enddatetime'] = $data['endDateTime'];
        $eventData['timezoneid'] = $data['timezoneId'];
        $eventData['private'] = $data['private'];
        $eventData['thumbnailfileid'] = $data['thumbnailFileId'];
        $eventData['bannerfileid'] = $data['bannerFileId'];
        // $eventData['ownerid'] = $data['ownerId'];
        $eventData['localityid'] = isset($data['localityId']) ? $data['localityId'] : '';
        $eventData['pincode'] = isset($data['pincode']) ? $data['pincode'] : '';
        $eventData['status'] = isset($data['status']) ? $data['status'] : 0;
        $eventData['ticketsoldout'] = $this->ticketSoldout($data);
        //$eventData['ticketsoldout'] =$this->ticketSoldout($data);
        $eventData['popularity'] = $data['popularity'];

        $eventData['latitude'] = $data['latitude'];
        $eventData['longitude'] = $data['longitude'];
        $eventData['acceptmeeffortcommission'] = $data['acceptmeeffortcommission'];
        $eventData['ipaddress'] = $this->getClientIp();
        $this->ci->Event_model->resetVariable();
        $this->ci->Event_model->setInsertUpdateData($eventData);

        $where[$this->ci->Event_model->id] = $data['eventId'];
        $where[$this->ci->Event_model->deleted] = 0;
        $this->ci->Event_model->setWhere($where);

        $response = $this->ci->Event_model->update_data();
        if ($response) {
            $output['status'] = TRUE;
            $output['response']['eventId'] = $data['eventId'];
            $output["response"]["messages"] = array();
            $output['statusCode'] = STATUS_UPDATED;
            return $output;
        }
        $output['status'] = FALSE;
        $output["response"]["messages"][] = SOMETHING_WENT_WRONG;
        $output['statusCode'] = STATUS_SERVER_ERROR;
        return $output;
    }

    /**
     * Event details table update
     */
    public function eventDetailsUpdate($data) {
        $validationStatus = $this->addEventDetailValidation($data);
        if ($validationStatus['error']) {
            $output['status'] = FALSE;
            $output['response']['messages'][] = $validationStatus['message'];
            $output['statusCode'] = STATUS_BAD_REQUEST;
            return $output;
        }
        $eventData['booknowbuttonvalue'] = $data['booknowButtonValue'];

        $this->ci->load->model('Eventdetail_model');
        $this->ci->Eventdetail_model->resetVariable();
        $this->ci->Eventdetail_model->setInsertUpdateData($eventData);

        $where[$this->ci->Eventdetail_model->eventdetail_id] = $data['eventId'];
        $this->ci->Eventdetail_model->setWhere($where);

        $response = $this->ci->Eventdetail_model->update_data();
        if ($response) {
            $output['status'] = TRUE;
            $output['response']['eventId'] = $data['eventId'];
            $output["response"]["messages"] = array();
            $output['statusCode'] = STATUS_UPDATED;
            return $output;
        }
        $output['status'] = FALSE;
        $output["response"]["messages"][] = SOMETHING_WENT_WRONG;
        $output['statusCode'] = STATUS_SERVER_ERROR;
        return $output;
    }

    /**
     * To Bring the event related tag ids
     * @param type $data
     */
    public function getEventTagIds($data) {
        $output = array();
        $this->ci->load->model('Eventtag_model');
        $this->ci->form_validation->pass_array($data);
        $this->ci->form_validation->set_rules('eventId', 'Event Id', 'is_natural_no_zero|required_strict');
        if ($this->ci->form_validation->run() === FALSE) {
            $errorMessages = $output['status'] = FALSE;
            $output['response']['message'] = $this->ci->form_validation->get_errors();
            $output['statusCode'] = STATUS_BAD_REQUEST;
            return $errorMessages;
        }
        $this->ci->Eventtag_model->resetVariable();
        $selectInput['eventId'] = $this->ci->Eventtag_model->eventid;
        $selectInput['id'] = $this->ci->Eventtag_model->tagid;
        $this->ci->Eventtag_model->setSelect($selectInput);

        $where[$this->ci->Eventtag_model->tagid . ' > '] = 0;
        $where[$this->ci->Eventtag_model->eventid] = $data['eventId'];
        $where[$this->ci->Eventtag_model->deleted] = 0;

        $this->ci->Eventtag_model->setWhere($where);
        $eventTagsList = $this->ci->Eventtag_model->get();

        if ($eventTagsList) {
            $output['status'] = TRUE;
            $output['response']['eventTagsList'] = $eventTagsList;
            $output['response']['total'] = count($eventTagsList);
            $output['response']['messages'] = array();
            $output['statusCode'] = STATUS_OK;
            return $output;
        }
        $output['status'] = TRUE;
        $output['response']['total'] = 0;
        $output['response']['messages'][] = ERROR_NO_DATA;
        $output['statusCode'] = STATUS_OK;
        return $output;
    }

    /**
     * 
     * @param type $eventTagData['newTags']
     * @param type $eventTagData['oldTags']
     */
    public function processEventTags($eventTagData) {

        $oldEventTags = isset($eventTagData['oldTags']) ? $eventTagData['oldTags'] : array();
        $newEventTags = isset($eventTagData['newTags']) ? $eventTagData['newTags'] : array();
        $removeArray = array();

        //Preparing the removed tags
        foreach ($oldEventTags as $key => $oldValue) {
            $tagKeyStatus = FALSE;
            foreach ($newEventTags as $newKey => $newValue) {
                if ($oldValue['id'] == $newValue['id']) {
                    unset($newEventTags[$newKey]);
                    $tagKeyStatus = TRUE;
                    break;
                }
            }
            if (!$tagKeyStatus) {
                $removeArray[] = $oldValue;
            }
        }
        $eventTagsResponse['insertTags'] = $newEventTags;
        $eventTagsResponse['removeTags'] = $removeArray;
        return $eventTagsResponse;
    }

    /**
     *  
     * @param type $data['tags']
     */
    public function removeEventTags($data) {
        $this->ci->load->model('Eventtag_model');
        $tagInArray = array();
        foreach ($data['tags'] as $tagKey => $tagValues) {
            $tagInArray[] = $tagValues['id'];
        }
        $this->ci->Eventtag_model->resetVariable();
        $whereInArray = array();
        $whereInArray[] = "tagid";
        $whereInArray[] = $tagInArray;

        $where[$this->ci->Eventtag_model->eventid] = $data['eventId'];
        $this->ci->Eventtag_model->setWhereIn($whereInArray);
        $this->ci->Eventtag_model->setWhere($where);
        $insertArray = array();
        $insertArray[$this->ci->Eventtag_model->deleted] = 1;
        $this->ci->Eventtag_model->setInsertUpdateData($insertArray);
        $deleteStatus = $this->ci->Eventtag_model->update_data();
        if ($deleteStatus) {
            $output['status'] = TRUE;
            $output["response"]["messages"] = array();
            $output['statusCode'] = STATUS_UPDATED;
            return $output;
        }
        $output['status'] = FALSE;
        $output["response"]["messages"][] = SOMETHING_WENT_WRONG;
        $output['statusCode'] = STATUS_SERVER_ERROR;
        return $output;
    }

    /**
     * To Process the event ticket related details
     */
    public function processEventTicketUpdateArray($data) {
        $ticketHandler = new Ticket_handler();
        foreach ($data['tickets'] as $ticketKey => $ticketValue) {
            $ticketData = array();
            $ticketData['startDate'] = $ticketValue['startDate'];
            $ticketData['startTime'] = $ticketValue['startTime'];
            $ticketData['endDate'] = $ticketValue['endDate'];
            $ticketData['endTime'] = $ticketValue['endTime'];
            $ticketData['name'] = $ticketValue['name'];
            $ticketData['type'] = getTicketType($ticketValue['type']);
            $ticketData['description'] = $ticketValue['description'];
            $ticketData['eventId'] = $data['eventId'];
            $ticketData['price'] = $ticketValue['price'];
            $ticketData['quantity'] = $ticketValue['quantity'];
            $ticketData['minOrderQuantity'] = $ticketValue['minOrderQuantity'];
            $ticketData['maxOrderQuantity'] = $ticketValue['maxOrderQuantity'];
            $ticketData['order'] = $ticketValue['order'];
            $ticketData['currencyId'] = $ticketValue['currencyId'];
            $ticketData['soldOut'] = $ticketValue['soldOut'];
            $ticketData['displayStatus'] = $ticketValue['displayStatus'];
            $ticketData['userId'] = $data['ownerId'];
            $ticketData['taxArray'] = $ticketValue['taxArray'];
            $ticketData['timeZoneName'] = $data['timeZoneName'];


            //based on ticket price, need to update the currencyid and ticket type
            if ($ticketData['price'] == 0 && $ticketData['type'] == 'free') {
                $ticketData['currencyId'] = 3;
                $ticketData['type'] = "free";
            }

            if ($ticketValue['ticketStatus'] === "oldTicket") {
                //Ticket date & time validations
                $startDate = urldecode($ticketData['startDate']);
                $startTime = urldecode($ticketData['startTime']);
                $endDate = urldecode($ticketData['endDate']);
                $endTime = urldecode($ticketData['endTime']);

                $startDate = dateFormate($ticketData['startDate'], '/');
                $endDate = dateFormate($ticketData['endDate'], '/');

                $startDateTime = convertTime($startDate . ' ' . $ticketData['startTime'], $data['timeZoneName']);
                $endDateTime = convertTime($endDate . ' ' . $ticketData['endTime'], $data['timeZoneName']);

                if (strtotime($startDateTime) > strtotime($endDateTime)) {
                    $output['status'] = FALSE;
                    $output['response']['messages'][] = ERROR_START_DATE_GREATER;
                    $output['statusCode'] = STATUS_BAD_REQUEST;
                    return $output;
                }
                $ticketData['startDateTime'] = $startDateTime;
                $ticketData['endDateTime'] = $endDateTime;
                $ticketData['ticketId'] = $ticketValue['ticketId'];

                $ticketResponse = $ticketHandler->ticketUpdate($ticketData);
            } else {

                $ticketResponse = $ticketHandler->add($ticketData);
            }

            if (!$ticketResponse['status']) {
                return $ticketResponse;
            }
        }
        $output['status'] = TRUE;
        $output["response"]["messages"][] = SUCCESS_TICKET_ADDED;
        $output['statusCode'] = STATUS_CREATED;
        return $output;
    }

    /**
     * To retrive event related tagid & name list
     *  BY passing the event tagid's array
     */
    public function getEventTagNames($data) {
        $tagIds = "";
        //Preparing the solr query
        foreach ($data as $key => $value) {
            $tagIds.='"' . $value['id'] . '",';
        }
        $tagIds = "(" . substr($tagIds, 0, -1) . ")";

        $solrHandler = new Solr_handler();
        $solrInputArray = array();
        $solrInputArray['id'] = $tagIds;
        $solrResults = $solrHandler->getTagsDetails($solrInputArray);
        return $solrResults;
    }

    /**
     * 
     * @param type $data
     * @param type $taxIndexVaue
     */
    public function processInputTaxArray($data, $ticketTAxMappingVaue) {
        $inputTaxArray = array();
        $taxIndexVaue = $data['taxmappingcount'][$ticketTAxMappingVaue];
        if (isset($data['taxArray'][$taxIndexVaue])) {
            $inputTaxArray = $data['taxArray'][$taxIndexVaue];
        }
        return $inputTaxArray;
    }

    public function updateSeoDetails($inputArray) {
        $validationStatus = $this->seoValidations($inputArray);
        if ($validationStatus['error'] == TRUE) {
            $output['status'] = FALSE;
            $output['response']['messages'] = $validationStatus['message'];
            $output['statusCode'] = STATUS_BAD_REQUEST;
            return $output;
        }
        $seo = array();
        $seo['seoTitle'] = $inputArray['seotitle'];
        $seo['eventId'] = $inputArray['eventId'];
        $seo['seoKeywords'] = $inputArray['seokeywords'];
        $seo['seoDescription'] = $inputArray['seodescription'];
        $solrHandler = new Solr_handler();
        $solrSeoData = array();
        $solrSeoData = $this->solrAddEventInputData($seo);
        $updateSolrSeo = $solrHandler->solrUpdateEvent($solrSeoData);
        if (!$updateSolrSeo['status']) {
            return $updateSolrSeo;
        }
        $seoData['seotitle'] = $inputArray['seotitle'];
        $seoData['seokeywords'] = $inputArray['seokeywords'];
        $seoData['seodescription'] = $inputArray['seodescription'];
        $seoData['conanicalurl'] = $inputArray['conanicalurl'];
        $where['eventid'] = $inputArray['eventId'];
        $this->ci->load->model('Eventdetail_model');
        $this->ci->Eventdetail_model->setInsertUpdateData($seoData);
        $this->ci->Eventdetail_model->setWhere($where);
        $response = $this->ci->Eventdetail_model->update_data();

        if ($response) {
            $output['status'] = TRUE;
            $output["response"]["messages"][] = SEO_DETAILS_UPDATE;
            $output['statusCode'] = STATUS_UPDATED;
            return $output;
        }
        $output['status'] = FALSE;
        $output["response"]["messages"][] = SOMETHING_WENT_WRONG;
        $output['statusCode'] = STATUS_SERVER_ERROR;
        return $output;
    }

    public function seoValidations($inputArray) {
        $errorMessages = array();
        $this->ci->form_validation->pass_array($inputArray);
        $this->ci->form_validation->set_rules('eventId', 'event Id', 'required_strict|is_natural_no_zero');
        $this->ci->form_validation->set_rules('seotitle', 'Seo Title');
        $this->ci->form_validation->set_rules('seokeywords', 'Seo Keywords');
        $this->ci->form_validation->set_rules('seodescription', 'Seo Description');
        $this->ci->form_validation->set_rules('conanicalurl', 'Conanical Url|valid_url_format');
        if ($this->ci->form_validation->run() === FALSE) {
            $errorMessages = $this->ci->form_validation->get_errors();
            return $errorMessages;
        }
    }

    public function getSeoDetails($inputArray) {

        parent::$CI->form_validation->pass_array($inputArray);
        parent::$CI->form_validation->set_rules('eventId', 'eventId', 'required_strict|is_natural_no_zero');
        if (parent::$CI->form_validation->run() === FALSE) {
            $errorMessages = $this->ci->form_validation->get_errors();
            $output['status'] = FALSE;
            $output['response']['messages'] = $errorMessages['message'];
            $output['statusCode'] = STATUS_BAD_REQUEST;
            return $output;
        }
        $eventExist = $this->eventExists($inputArray);
        if ($eventExist['status'] == TRUE) {
            $this->ci->load->model('Eventdetail_model');
            $this->ci->Eventdetail_model->resetVariable();
            $selectSeoData = array();
            $selectSeoData['seotitle'] = $this->ci->Eventdetail_model->eventdetail_seotitle;
            $selectSeoData['seokeywords'] = $this->ci->Eventdetail_model->eventdetail_seokeywords;
            $selectSeoData['seodescription'] = $this->ci->Eventdetail_model->eventdetail_seodescription;
            $selectSeoData['conanicalurl'] = $this->ci->Eventdetail_model->eventdetail_conanicalurl;
            $selectSeoData['limitSingleTicketType'] = $this->ci->Eventdetail_model->eventdetail_limitsingletickettype;
            $this->ci->Eventdetail_model->setSelect($selectSeoData);
            $whereDetails[$this->ci->Eventdetail_model->eventdetail_id] = $inputArray['eventId'];
            $this->ci->Eventdetail_model->setWhere($whereDetails);
            $seoDetails = $this->ci->Eventdetail_model->get();
            if (count($seoDetails) > 0) {
                $output['status'] = TRUE;
                $output['response']['seodetails'] = $seoDetails;
                $output['response']['total'] = count($seoDetails);
                $output['statusCode'] = STATUS_OK;
                return $output;
            } else {
                $output['status'] = FALSE;
                $output['response']['messages'][] = ERROR_SOMETHING_WENT_WRONG;
                $output['response']['total'] = 0;
                return $output;
            }
        } else {
            $output['status'] = TRUE;
            $output['response']['messages'][] = ERROR_NO_EVENT;
            $output['response']['total'] = 0;
            $output['statusCode'] = STATUS_OK;
            return $output;
        }
    }

    function getEventInfoById($inputArray) {
        $this->ci->form_validation->reset_form_rules();
        $this->ci->form_validation->pass_array($inputArray);
        $this->ci->form_validation->set_rules('eventid', 'eventid', 'is_natural_no_zero');
        $this->ci->form_validation->set_rules('eventids', 'eventids', 'is_array');
        if ($this->ci->form_validation->run() == FALSE) {

            $response = $this->ci->form_validation->get_errors('message');
            $output['status'] = FALSE;
            $output['response']['messages'] = $response['message'];
            $output['statusCode'] = STATUS_BAD_REQUEST;
            return $output;
        }
        $this->ci->Event_model->resetVariable();
        $select['id'] = $this->ci->Event_model->id;
        $select['title'] = $this->ci->Event_model->title;
        $select['countryid'] = $this->ci->Event_model->countryid;
        $select['stateid'] = $this->ci->Event_model->stateid;
        $select['cityid'] = $this->ci->Event_model->cityid;
        $select['url'] = $this->ci->Event_model->url;
        $this->ci->Event_model->setSelect($select);
        $whereIns=$where=array();
        if(isset($inputArray['eventid'])){
            $where[$this->ci->Event_model->id] = $inputArray['eventid'];
        }
        if(isset($inputArray['eventids'])){
            $whereIns[$this->ci->Event_model->id] = $inputArray['eventids'];
        }
        $this->ci->Event_model->setWhere($where);
        $this->ci->Event_model->setWhereIns($whereIns);
        $result = $this->ci->Event_model->get();
        if ($result) {
            $output['status'] = TRUE;
            $output['response']['eventInfo'] = $result;
            $output['response']['total'] = 1;
            $output['response']['messages'] = array();
            $output['statusCode'] = STATUS_OK;
            return $output;
        }
        $output['status'] = TRUE;
        $output['response']['total'] = 0;
        $output['response']['messages'][] = ERROR_NO_DATA;
        $output['statusCode'] = STATUS_OK;
        return $output;
    }

    function getWebhookUrl($eventId) {
        $inputArray['eventId'] = $eventId;
        $this->ci->form_validation->pass_array($inputArray);
        $this->ci->form_validation->set_rules('eventId', 'eventId', 'required_strict|is_natural_no_zero');
        if ($this->ci->form_validation->run() == FALSE) {

            $response = $this->ci->form_validation->get_errors('message');
            $output['status'] = FALSE;
            $output['response']['messages'] = $response['message'];
            $output['statusCode'] = STATUS_BAD_REQUEST;
            return $output;
        }
        $this->ci->load->model('Event_setting_model');
        $this->ci->Event_setting_model->resetVariable();
        $select['webhookurl'] = $this->ci->Event_setting_model->webhookurl;
        $this->ci->Event_setting_model->setSelect($select);
        $where[$this->ci->Event_setting_model->eventid] = $eventId;
        $this->ci->Event_setting_model->setWhere($where);
        $result = $this->ci->Event_setting_model->get();
        if ($result) {
            $output['status'] = TRUE;
            $output['response']['webhookUrl'] = $result['0']['webhookurl'];
            $output['response']['total'] = 1;
            $output['response']['messages'] = array();
            $output['statusCode'] = STATUS_OK;
            return $output;
        }
        $output['status'] = TRUE;
        $output['response']['total'] = 0;
        $output['response']['messages'][] = ERROR_NO_DATA;
        $output['statusCode'] = STATUS_OK;
        return $output;
    }

    function updateWebhookUrl($inputArray) {
        $this->ci->form_validation->pass_array($inputArray);
        $this->ci->form_validation->set_rules('webhookUrl', 'webhookUrl', 'valid_url_format|required_strict');
        $this->ci->form_validation->set_rules('eventId', 'eventId', 'required_strict|is_natural_no_zero');
        if ($this->ci->form_validation->run() == FALSE) {
            $response = $this->ci->form_validation->get_errors('message');
            $output['status'] = FALSE;
            $output['response']['messages'] = $response['message'];
            $output['statusCode'] = STATUS_BAD_REQUEST;
            return $output;
        }
        $this->ci->load->model('Event_setting_model');
        $this->ci->Event_setting_model->resetVariable();
        $where[$this->ci->Event_setting_model->eventid] = $inputArray['eventId'];
        $this->ci->Event_setting_model->setWhere($where);
        $updateArray = array('webhookurl' => $inputArray['webhookUrl']);
        $this->ci->Event_setting_model->setInsertUpdateData($updateArray);
        $updateUser = $this->ci->Event_setting_model->update_data();
        if ($updateUser) {
            $output['status'] = TRUE;
            $output['response']['messages'][] = SUCCESS_WEBHOOK_UPDATED;
            $output['statusCode'] = STATUS_UPDATED;
            return $output;
        }
        $output['status'] = FALSE;
        $output['response']['messages'][] = ERROR_SOMETHING_WENT_WRONG;
        $output['statusCode'] = STATUS_SERVER_ERROR;
        return $output;
    }

    // get events count by Types
    function getEventsCountByRegTypes($inputArray) {
        $inputArray['keyWord'] = $inputArray['keyword'];
        unset($inputArray['keyword']);
        $categoryListRemainingArray = $categoryTotalList = $categoryListRemaining = $categoriesIdsArray = $categoriesData = $searchInputs = $data = $result = $eventTypeResult = array();

        $searchInputs = $inputArray;
        if (isset($searchInputs['eventType']) && $searchInputs['eventType'] != '') {
            $eventTypeResult = eventType($searchInputs['eventType']);
            if ($eventTypeResult['registrationType'] == 4) {
                unset($eventTypeResult['registrationType']);
            }
            $searchInputs = array_merge($searchInputs, $eventTypeResult);
        }
        if (!isset($searchInputs['day'])) {
            $searchInputs['day'] = 6;
        }
        if (isset($searchInputs['cityId']) && $searchInputs['cityId'] == 0)
            unset($searchInputs['cityId']);
        if (isset($searchInputs['stateId']) && $searchInputs['stateId'] == 0)
            unset($searchInputs['stateId']);
        if (isset($searchInputs['categoryId']) && $searchInputs['categoryId'] == 0)
            unset($searchInputs['categoryId']);

        unset($searchInputs['major']);
        $searchInputs['facetType'] = 'registrationType,eventMode';

        $registrationTypeArr = array(1, 2, 3, 4, 5);
        $this->searchHandler = new Search_handler();
        $regEventCountData = array();
        $searchInputsWebinar = $searchInputs;
        $searchInputsAll = $searchInputs;
        $searchInputs['registrationType'] = '(1 2 3)';
        $searchInputsWebinar['eventMode'] = '1';
        $eventDataJson = $this->searchHandler->categotiesEventCount($searchInputs);
        $eventDataArray = json_decode($eventDataJson, true);

        //webinar count
        $eventDataWebinarJson = $this->searchHandler->categotiesEventCount($searchInputsWebinar);
        $eventDataWebinarArray = json_decode($eventDataWebinarJson, true);

        //all count
        $eventDataAllJson = $this->searchHandler->categotiesEventCount($searchInputsAll);
        $eventDataAllArray = json_decode($eventDataAllJson, true);

        foreach ($eventDataArray['response']['result']['facetCounts']['registrationType'] as $regTypeKey => $regTypeValue) {
            $regEventCountData[$regTypeValue[0]] = $regTypeValue[1];
        }

        foreach ($eventDataWebinarArray['response']['result']['facetCounts']['eventMode'] as $eventModeKey => $eventModeValue) {
            if (isset($eventModeValue[0]) && $eventModeValue[0] == 1) {
                $regEventCountData[4] = $eventModeValue[1];
            } else {
                $regEventCountData[4] = 0;
            }
        }
        $allRegCount = 0;
        foreach ($eventDataAllArray['response']['result']['facetCounts']['eventMode'] as $eventAllKey => $eventAllValue) {
            $allRegCount = $allRegCount + $eventAllValue[1];
        }
        $regEventCountData[5] = $allRegCount;

        foreach ($registrationTypeArr as $key => $value) {
            if (!isset($regEventCountData[$value])) {
                $regEventCountData[$value] = 0;
            }
        }

        $resultdata = json_decode($regEventCountData, true);
        if ($resultdata['response']['error'] == 'true') {
            $output['status'] = FALSE;
            $output['response']['messages'] = $resultdata['response']['message'];
            $output['statusCode'] = STATUS_BAD_REQUEST;
            return $output;
        } else {
            $output['status'] = TRUE;
            $output['response']['messages'][] = '';
            $output['response']['eventCountByRegTypeList'] = $regEventCountData;
            $output['statusCode'] = STATUS_OK;
            return $output;
        }
    }

    public function getTicketOptions($inputArray) {
        parent::$CI->form_validation->reset_form_rules();
        parent::$CI->form_validation->pass_array($inputArray);
        parent::$CI->form_validation->set_rules('eventId', 'eventId', 'required_strict|is_natural_no_zero');
        if (parent::$CI->form_validation->run() === FALSE) {
            $errorMessages = $this->ci->form_validation->get_errors();
            $output['status'] = FALSE;
            $output['response']['messages'] = $errorMessages['message'];
            $output['statusCode'] = STATUS_BAD_REQUEST;
            return $output;
        }
        $eventDetailReq = isset($inputArray['eventDetailReq']) ? $inputArray['eventDetailReq'] : true;
        //$eventExist = $this->eventExists($inputArray);
        //if ($eventExist['status'] == TRUE) {
        $this->ci->load->model('Event_setting_model');
        if ($eventDetailReq) {
            $this->ci->load->model('Eventdetail_model');
        }
        $this->ci->Event_setting_model->resetVariable();
        $selectEventSettingData = array();
        $selectEventSettingData['collectmultipleattendeeinfo'] = $this->ci->Event_setting_model->collectmultipleattendeeinfo;
        $selectEventSettingData['customemail'] = $this->ci->Event_setting_model->customemail;
        $selectEventSettingData['displayamountonticket'] = $this->ci->Event_setting_model->displayamountonticket;
        $selectEventSettingData['nonormalwhenbulk'] = $this->ci->Event_setting_model->nonormalwhenbulk;
        //$selectEventSettingData['sendubermails'] = $this->ci->Event_setting_model->sendubermails;
        $selectEventSettingData['geolocalitydisplay'] = $this->ci->Event_setting_model->geolocalitydisplay;
        $selectEventSettingData['calculationmode'] = $this->ci->Event_setting_model->calculationmode;

        $this->ci->Event_setting_model->setSelect($selectEventSettingData);
        $whereDetails['eventid'] = $inputArray['eventId'];
        $this->ci->Event_setting_model->setWhere($whereDetails);
        $eventSettings = $this->ci->Event_setting_model->get();

        $eventDetails = array();
        if ($eventDetailReq) {
            $selectEventDetailData['limitsingletickettype'] = $this->ci->Eventdetail_model->eventdetail_limitsingletickettype;
            $selectEventDetailData['discountaftertax'] = $this->ci->Eventdetail_model->eventdetail_discountaftertax;
            $this->ci->Eventdetail_model->setSelect($selectEventDetailData);
            $whereDetails['eventid'] = $inputArray['eventId'];
            $this->ci->Eventdetail_model->setWhere($whereDetails);
            $eventDetails = $this->ci->Eventdetail_model->get();
        }
        $ticketingOptions = array_merge($eventSettings, $eventDetails);
        if (count($ticketingOptions) > 0) {
            $output['status'] = TRUE;
            $output['response']['ticketingOptions'] = $ticketingOptions;
            $output['response']['total'] = count($ticketingOptions);
            $output['statusCode'] = STATUS_OK;
            return $output;
        } else {
            $output['status'] = FALSE;
            $output['response']['ticketingOptions'] = ERROR_SOMETHING_WENT_WRONG;
            $output['response']['total'] = 0;
            $output['statusCode'] = STATUS_SERVER_ERROR;
            return $output;
        }
//        } else {
//            $output['status'] = TRUE;
//            $output['response']['messages'][] = ERROR_NO_EVENT;
//            $output['response']['total'] = 0;
//            $output['statusCode'] = STATUS_OK;
//            return $output;
//        }
    }

    public function updateTicketOptions($inputArray) {
        $this->ci->form_validation->reset_form_rules();
        parent::$CI->form_validation->pass_array($inputArray);
        parent::$CI->form_validation->set_rules('eventId', 'eventId', 'required_strict|is_natural_no_zero');
        if (parent::$CI->form_validation->run() === FALSE) {
            $errorMessages = $this->ci->form_validation->get_errors();
            $output['status'] = FALSE;
            $output['response']['messages'] = $errorMessages['message'];
            $output['statusCode'] = STATUS_BAD_REQUEST;
            return $output;
        }
        $solrInput = array();
        $solrInput['limitsingletickettype'] = $inputArray['limitsingletickettype'];
        $solrInput['id'] = $inputArray['eventId'];
        $solrHandler = new Solr_handler();
        $solrUpdatedData = $solrHandler->solrUpdateEventLimitOne($solrInput);
        if (!$solrUpdatedData['status']) {
            return $solrUpdatedData;
        }
        $this->ci->Event_setting_model->resetVariable();
        // event settings table
        $ticketOptionSetting['collectmultipleattendeeinfo'] = $inputArray['collectmultipleattendeeinfo'];
        $ticketOptionSetting['displayamountonticket'] = $inputArray['displayamountonticket'];
        $ticketOptionSetting['nonormalwhenbulk'] = $inputArray['nonormalwhenbulk'];
        //$ticketOptionSetting['sendubermails'] = $inputArray['sendubermails'];
        $where['eventid'] = $inputArray['eventId'];
        $this->ci->load->model('Event_setting_model');
        $this->ci->Event_setting_model->setInsertUpdateData($ticketOptionSetting);
        $this->ci->Event_setting_model->setWhere($where);
        $response = $this->ci->Event_setting_model->update_data();

        // event details table
        $ticketOptionDetail['limitsingletickettype'] = $inputArray['limitsingletickettype'];
        $where['eventid'] = $inputArray['eventId'];
        $this->ci->load->model('Eventdetail_model');
        $this->ci->Eventdetail_model->setInsertUpdateData($ticketOptionDetail);
        $this->ci->Eventdetail_model->setWhere($where);
        $response = $this->ci->Eventdetail_model->update_data();

        if ($response) {
            $output['status'] = TRUE;
            $output["response"]["messages"][] = TICKET_OPTIONS_UPDATE;
            $output['statusCode'] = STATUS_UPDATED;
            return $output;
        }
        $output['status'] = FALSE;
        $output["response"]["messages"][] = SOMETHING_WENT_WRONG;
        $output['statusCode'] = STATUS_SERVER_ERROR;
        return $output;
    }

    // NEED TO PUT THIS IN EVENTSIGNUP HANDLER

    /*
     * Function to insert in orderlog table
     *
     */
    public function bookNow($inputArray) {

        parent::$CI->form_validation->pass_array($inputArray);
        parent::$CI->form_validation->set_rules('eventId', 'eventId', 'required_strict|is_natural_no_zero');
        if (isset($inputArray['donateTicketArray']) && count($inputArray['donateTicketArray']) > 0) {
            $this->ci->form_validation->set_rules('ticketArray', 'ticketArray', 'is_array');
        } else {
            $this->ci->form_validation->set_rules('ticketArray', 'ticketArray', 'required_strict|is_array');
        }

        if (parent::$CI->form_validation->run() === FALSE) {
            $errorMessages = $this->ci->form_validation->get_errors();
            $output['status'] = FALSE;
            $output['response']['messages'][] = $errorMessages['message'];
            $output['statusCode'] = STATUS_BAD_REQUEST;
            return $output;
        }

        $calculationArray = $this->getEventTicketCalculation($inputArray);
        if ($calculationArray['status']) {
            $ticketarray = array();
            $ticketData = $calculationArray['response']['calculationDetails']['ticketsData'];
            foreach ($ticketData as $ticketId => $ticketDetail) {
                $ticketarray[$ticketId] = floor($ticketDetail['selectedQuantity']);
            }

            $datavalue['eventid'] = $inputArray['eventId'];
            $datavalue['discountcode'] = $calculationArray['response']['calculationDetails']['discountCode'];
            $datavalue['referralcode'] = $calculationArray['response']['calculationDetails']['referralCode'];
            $datavalue['promotercode'] = $calculationArray['response']['calculationDetails']['promoterCode'];
            $datavalue['widgetredirecturl'] = ($inputArray['widgetRedirectUrl'] != '') ? $inputArray['widgetRedirectUrl'] : '';
            $datavalue['ticketarray'] = $ticketarray;
            $datavalue['eventid'] = $inputArray['eventId'];
            $datavalue['pageType'] = $inputArray['pageType'];
            if(!empty($inputArray['acode'])){
                $datavalue['acode'] = $inputArray['acode'];
            }
            $datavalue['soldCountUpdated'] = false;
            $datavalue['calculationDetails'] = $calculationArray['response']['calculationDetails'];
            if (isset($inputArray['addonArray'])) {
                $datavalue['addonArray'] = $inputArray['addonArray'];
            }
            $datainput['data'] = serialize($datavalue);
            $datainput['userid'] = getUserId();
            $datainput['userip'] = commonHelperGetClientIp();
            //Setting Data for inserting or update
            $datainput['orderid'] = $this->generateOrderId();
            $this->ci->load->model('Orderlog_model');
            $this->ci->Orderlog_model->resetVariable();
            //setting data for inserting
            $this->ci->Orderlog_model->setInsertUpdateData($datainput);

            //executing insert query
            $response = $this->ci->Orderlog_model->insert_data();
            if ($response) {
                if ($this->ci->config->item('tempTicketsEnabled') == TRUE) {
                    $sessionlockHandler = new Sessionlock_handler();
                    $insertSessionlock['orderId'] = $datainput['orderid'];
                    $insertSessionlock['ticketArray'] = $ticketarray;
                    $insertSessionlockResponse = $sessionlockHandler->add($insertSessionlock);
                }
                if (isset($insertSessionlockResponse) && !$insertSessionlockResponse['status']) {
                    return $insertSessionlockResponse;
                }
                $responseData = array('orderId' => $datainput['orderid']);
                $output['status'] = TRUE;
                $output['response'] = $responseData;
                $output['response']['messages'][] = SUCCESS_CREATED_ORDERID;
                $output['statusCode'] = STATUS_OK;
                return $output;
            }
        }
        $output['status'] = FALSE;
        $errorMessage = '';
        if (isset($calculationArray['response']['messages']) && count($calculationArray['response']['messages']) > 0) {
            $errorMessage = $calculationArray['response']['messages'][0];
        }
        $output['response']['messages'][] = ($errorMessage != '') ? $errorMessage : ERROR_SOMETHING_WENT_WRONG;
        $output['statusCode'] = STATUS_SERVER_ERROR;
        return $output;
    }

    function generateOrderId($length = 10, $Globali = NULL) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, strlen($characters) - 1)];
        }
//ADD CHECK TO MAKE SURE ITS UNIQUE
        $randomStringFinal = date('Ymd') . $randomString . date('His');
        return $randomStringFinal;
    }

    public function insertEventSettingDetails($input) {
        $this->ci->load->model('Event_setting_model');
        $eventSettingData = array();
        $eventSettingData['collectmultipleattendeeinfo'] = $this->setTicketOption($input); //collect single attende info
        $eventSettingData['eventid'] = $input["eventId"];
        $this->ci->Event_setting_model->resetVariable();
        $this->ci->Event_setting_model->setInsertUpdateData($eventSettingData);
        $response = $this->ci->Event_setting_model->insert_data();

        $output['status'] = TRUE;
        $output['response']['affectedRows'] = $response;
        $output["response"]["messages"] = array();
        $output['statusCode'] = STATUS_CREATED;
        return $output;
    }

    // get multiple evets data
    public function getPromoterEventsData($request) {
        $cityHandler = new City_handler();
        $this->ci->form_validation->reset_form_rules();
        parent::$CI->form_validation->pass_array($request);
        parent::$CI->form_validation->set_rules('eventId', 'eventId', 'required_strict|is_array');
        if (parent::$CI->form_validation->run() === FALSE) {
            $errorMessages = $this->ci->form_validation->get_errors();
            $output['status'] = FALSE;
            $output['response']['messages'] = $errorMessages['message'];
            $output['statusCode'] = STATUS_BAD_REQUEST;
            return $output;
        }
        $this->ci->load->model('Eventdetail_model');
        $output = array();
        $this->ci->Event_model->resetVariable();
        if (isset($request['countOnly']) && $request['countOnly']) {

            $selectInput['eventCount'] = "count(" . $this->ci->Event_model->id . ")";
        } else {
            $selectInput['id'] = $this->ci->Event_model->id;
            $selectInput['ownerId'] = $this->ci->Event_model->ownerid;
            $selectInput['title'] = $this->ci->Event_model->title;
            $selectInput['cityId'] = $this->ci->Event_model->cityid;
            $selectInput['startDate'] = $this->ci->Event_model->startdatetime;
            $selectInput['endDate'] = $this->ci->Event_model->enddatetime;
        }
        $this->ci->Event_model->setSelect($selectInput);
//        if(isset($request['userId']) && $request['userId']>0){   // if user id is set then add condition
//            $where[$this->ci->Event_model->ownerid] = $request['userId'];
//        }
        $where[$this->ci->Event_model->deleted] = 0;
        if ($request['type'] == 'currentEvents') {
            $where[$this->ci->Event_model->enddatetime . " > "] = allTimeFormats('', 11);
        }
        if ($request['type'] == 'pastEvents') {
            $where[$this->ci->Event_model->enddatetime . " < "] = allTimeFormats('', 11);
        }
        $whereInArray[$this->ci->Event_model->id] = $request['eventId'];
        $this->ci->Event_model->setWhereIns($whereInArray);
        $this->ci->Event_model->setWhere($where);
        $eventDetailsResponse = $this->ci->Event_model->get();
        if (isset($request['countOnly']) && $request['countOnly']) {
            $output['status'] = TRUE;
            $output['response']['messages'][] = array();
            $output['response'][$request['type']] = $eventDetailsResponse[0]['eventCount'];
            $output['statusCode'] = STATUS_OK;
            return $output;
        }
        if (count($eventDetailsResponse) == 0) {
            $output['status'] = TRUE;
            $output['response']['messages'][] = ERROR_NO_DATA;
            $output['response']['total'] = 0;
            $output['statusCode'] = STATUS_OK;
            return $output;
        } else {
            foreach ($eventDetailsResponse as $val) {
                $cityIds[] = $val['cityId'];
            }
            $inputArray['cityId'] = $cityIds;
            $eventDetails = $eventDetailsResponse[0];
            $cityData = array();

            //Getting the City data
            if ($eventDetails['cityId'] > 0) {
                $cityIds = $inputArray['cityId'];
                $cityData = $cityHandler->getCityNames($cityIds);
                $ciitsinfo = $cityData['response']['cityName'];
                $citys = commonHelperGetIdArray($ciitsinfo, 'id');
            }
            foreach ($eventDetailsResponse as $key => $val) {
                $eventDetailsResponse[$key]['cityname'] = $citys[$val['cityId']]['name'];
            }
            $output['status'] = TRUE;
            $output['response']['eventData'] = $eventDetailsResponse;
            $output['response']['total'] = count($eventDetailsResponse);
            $output['statusCode'] = STATUS_OK;
            return $output;
        }
    }

    /*
     * Function to get the event payment gateways
     *
     * @access	public
     * @param	$inputArray contains
     * 				eventId - integer
     * 				paymentGatewayId - Integer (optional)
     * @return	array
     */

    function getEventPaymentGateways($inputArray) {

        $this->ci->form_validation->reset_form_rules();
        $this->ci->form_validation->pass_array($inputArray);
        $this->ci->form_validation->set_rules('eventId', 'event id', 'required_strict|is_natural_no_zero');
        $this->ci->form_validation->set_rules('paymentGatewayId', 'payment gateway id', 'is_natural_no_zero');

        if ($this->ci->form_validation->run() == FALSE) {
            $response = $this->ci->form_validation->get_errors('message');
            $output['status'] = FALSE;
            $output['response']['messages'] = $response['message'];
            $output['statusCode'] = STATUS_BAD_REQUEST;
            return $output;
        }
        $eventId = $inputArray['eventId'];
        $eventPaymentGateway = new EventpaymentGateway_handler();
        $inputPaymentgateway['eventId'] = $eventId;
        if (isset($inputArray['paymentGatewayId']) && $inputArray['paymentGatewayId'] > 0) {
            $inputPaymentgateway['paymentGatewayId'] = $inputArray['paymentGatewayId'];
        }
        if (isset($inputArray['gatewayStatus']) && $inputArray['gatewayStatus']) {
            $inputPaymentgateway['gatewayStatus'] = $inputArray['gatewayStatus'];
        }
        $eventGatewaysResponse = $eventPaymentGateway->getPaymentgatewayByEventId($inputPaymentgateway);
        $gayWayTextArray = array();
        if ($eventGatewaysResponse['status'] && $eventGatewaysResponse['response']['total'] > 0) {

            $eventGatewaysList = $eventGatewaysResponse['response']['eventPaymentGatewayList'];
            foreach ($eventGatewaysList as $eventGateway) {
                $eventGatewayIdsArray[] = $eventGateway['paymentgatewayid'];
                if ($eventGateway['gatewaytext'] != '') {
                    $gayWayTextArray[] = $eventGateway['gatewaytext'];
                }
            }
            /* To get the payment gateway data ends here */
            $inputPaymentGateway['gatewayIds'] = $eventGatewayIdsArray;
            $paymentGateway = new Paymentgateway_handler();
            $eventGatewaysResponse = $paymentGateway->getPaymentgatewayList($inputPaymentGateway);
            if ($eventGatewaysResponse['status'] && $eventGatewaysResponse['response']['total'] > 0) {
                $eventGateways = commonHelperGetIdArray($eventGatewaysResponse['response']['paymentgatewayList']);
            } else {
                return $eventGatewaysResponse;
            }
            foreach ($eventGatewaysList as $gateways) {
                $gateways['gatewayName'] = $eventGateways[$gateways['paymentgatewayid']]['name'];
                $gateways['merchantid'] = $eventGateways[$gateways['paymentgatewayid']]['merchantid'];
                $gateways['hashkey'] = $eventGateways[$gateways['paymentgatewayid']]['hashkey'];
                $gateways['description'] = $eventGateways[$gateways['paymentgatewayid']]['description'];
                //$gateways['gatewaytext'] = '';
                if (count($gayWayTextArray) > 0) {
                    $gateways['gatewaytext'] = $gateways['gatewaytext'];
                } else {
                    $gateways['gatewaytext'] = $eventGateways[$gateways['paymentgatewayid']]['gatewaytext'];
                }
                $gateways['extraparams'] = '';
                if ($eventGateways[$gateways['paymentgatewayid']]['extraparams'] != '') {
                    $gateways['extraparams'] = unserialize($eventGateways[$gateways['paymentgatewayid']]['extraparams']);
                }
                $gateways['returnurl'] = $gateways['posturl'] = '';
                if (isset($eventGateways[$gateways['paymentgatewayid']]['returnurl'])) {
                    $gateways['returnurl'] = $eventGateways[$gateways['paymentgatewayid']]['returnurl'];
                }
                if (isset($eventGateways[$gateways['paymentgatewayid']]['posturl'])) {
                    $gateways['posturl'] = $eventGateways[$gateways['paymentgatewayid']]['posturl'];
                }
                $finalGatewayArr[] = $gateways;
            }
            $output['status'] = TRUE;
            $output["response"]["gatewayList"] = $finalGatewayArr;
            $output["response"]['total'] = count($finalGatewayArr);
            $output['statusCode'] = STATUS_OK;
            return $output;
        }
        $output['status'] = TRUE;
        $output["response"]["messages"][] = ERROR_EVENT_PAYMENTGATEWAYS;
        $output["response"]['total'] = 0;
        $output['statusCode'] = STATUS_OK;
        return $output;
    }

    // Function for getting the Event Extra Charges

    public function setAlertsForOrganizer() {
        $orgStatus = $this->ci->customsession->getData('isOrganizer');
        if ($orgStatus === 0) {
            $alertHandler = new Alert_handler();
            $alertResponse = $alertHandler->add();
            return $alertResponse;
        }
        $output['status'] = TRUE;
        //$output['response']['affectedRows'] = $response;
        $output["response"]["messages"] = array();
        $output['response']['total'] = 1;
        $output['statusCode'] = STATUS_OK;
        return $output;
    }

    public function extraCharge($request) {
        $this->ci->load->model('Eventextracharge_model');
        $output = array();
        $this->ci->form_validation->pass_array($request);
        $this->ci->form_validation->set_rules('eventId', 'Event Id', 'required_strict|is_natural_no_zero');
        if (!empty($request) && $this->ci->form_validation->run() == FALSE) {
            $validationStatus = $this->ci->form_validation->get_errors();
            $output['status'] = FALSE;
            $output['response']['messages'] = $validationStatus['message'];
            $output['statusCode'] = STATUS_BAD_REQUEST;
            return $output;
        } else {
            $this->ci->Eventextracharge_model->resetVariable();
            $selectInput['id'] = $this->ci->Eventextracharge_model->id;
            $selectInput['label'] = $this->ci->Eventextracharge_model->label;
            $selectInput['value'] = $this->ci->Eventextracharge_model->value;
            $selectInput['currencyId'] = $this->ci->Eventextracharge_model->currencyid;
            $selectInput['type'] = $this->ci->Eventextracharge_model->type;
            $selectInput['status'] = $this->ci->Eventextracharge_model->status;
            $this->ci->Eventextracharge_model->setSelect($selectInput);
            //fetching Event Exra charges  & not deleted
            $where[$this->ci->Eventextracharge_model->eventid] = $request['eventId'];
            $where[$this->ci->Eventextracharge_model->deleted] = 0;
            $this->ci->Eventextracharge_model->setWhere($where);
            //Order by array
            $orderBy = array();
            $orderBy[] = $this->ci->Eventextracharge_model->id;
            $this->ci->Eventextracharge_model->setOrderBy($orderBy);
            $this->ci->Eventextracharge_model->setRecords(1);
            $extraChargeDetails = $this->ci->Eventextracharge_model->get();
            if (is_array($extraChargeDetails)) {
                if (count($extraChargeDetails) > 0) {
                    $output['status'] = TRUE;
                    $output['response']['extraChargeDetail'] = $extraChargeDetails;
                    $output['statusCode'] = STATUS_OK;
                    return $output;
                } else {
                    $output['status'] = TRUE;
                    $output['response']['messages'][] = ERROR_NO_DATA;
                    $output['statusCode'] = STATUS_OK;
                    return $output;
                }
            } else {
                $output['status'] = FALSE;
                $output['response']['messages'][] = ERROR_INTERNAL_DB_ERROR;
                $output['statusCode'] = STATUS_SERVER_ERROR;
                return $output;
            }
        }
    }

    public function copyEvent($inputArray) {
        $ticketHandler = new Ticket_handler();
        $this->ci->form_validation->reset_form_rules();
        $this->ci->form_validation->pass_array($inputArray);
        $this->ci->form_validation->set_rules('eventid', 'Event ID', 'is_natural_no_zero|required_strict');
        if ($this->ci->form_validation->run() === FALSE) {
            $errorMessages = $this->ci->form_validation->get_errors();
            $output['status'] = FALSE;
            $output['response']['messages'] = $errorMessages;
            $output['statusCode'] = STATUS_BAD_REQUEST;
            return $output;
        }
        $eventId = $inputArray['eventid'];
        $inputEventExists['eventId'] = $eventId;
        $eventExistsResponse = $this->eventExists($inputEventExists);
        if ($eventExistsResponse['status']) {
            $inputEventDetails['eventId'] = $eventId;
            $inputEventDetails['copyEvent'] = 1;
            $eventDetailsResponse = $this->getEventDetails($inputEventDetails);
        } else {
            return $eventExistsResponse;
        }
        if ($eventDetailsResponse['status'] && $eventDetailsResponse['response']['total'] > 0) {
            $eventDetails = $eventDetailsResponse['response']['details'];
            $locationDetails = $eventDetails['location'];
            $inputTickets['eventId'] = $eventId;
            $inputTickets['eventTimeZoneName'] = $eventDetailsResponse['response']['details']['location']['timeZoneName'];
            $ticketDetailsResponse = $ticketHandler->getActualEventTicketList($inputTickets);
        } else {
            return $eventExistsResponse;
        }
        if ($ticketDetailsResponse['status'] && $ticketDetailsResponse['response']['total'] > 0) {
            $ticketDetails = $ticketDetailsResponse['response']['ticketList'];
            $taxList = isset($ticketDetailsResponse['response']['taxList']) ? $ticketDetailsResponse['response']['taxList'] : array();
        }
        $param['status'] = '0';
        $param['submitValue'] = 'save';
        $param['registrationType'] = $eventDetails['registrationType'];
        $param['title'] = removeScriptTag($eventDetails['title']) . ' copy';
        $param['description'] = stripslashes($eventDetails['description']);
        $param['categoryId'] = $eventDetails['categoryId'];
        $param['subcategoryId'] = $eventDetails['subCategoryName'];
        $param['url'] = cleanUrl($eventDetails['url']) . '-copy';
        $param['acceptmeeffortcommission'] = $eventDetails['acceptmeeffortcommission'];
        $urlExists = $this->checkUrlExists(array('eventUrl' => $param['url']));
        $i = 0;
        $updated = false;
        while (!$urlExists['status']) {
            $url = $param['url'] . ++$i;
            $urlExists = $this->checkUrlExists(array('eventUrl' => $url));
            $updated = true;
        }
        if ($updated) {
            $param['url'] = $param['url'] . $i;
        }
        $param['venueName'] = $locationDetails['venueName'];
        $param['venueaddress1'] = $locationDetails['address1'];
        $param['venueaddress2'] = $locationDetails['address2'];
        $eventStartDateTime = convertTime(allTimeFormats(("+2 Days"), 11), $locationDetails['timeZoneName'], TRUE);
        $eventEndDateTime = convertTime(allTimeFormats(("+2 Days"), 11), $locationDetails['timeZoneName'], TRUE);
        $currentDateTime = convertTime(allTimeFormats('', 11), $locationDetails['timeZoneName'], TRUE);
        $currentStartTime = convertTime(allTimeFormats(("+10 minutes"), 11), $locationDetails['timeZoneName'], TRUE);
        $currentEndTime = convertTime(allTimeFormats(("+15 minutes"), 11), $locationDetails['timeZoneName'], TRUE);
//        if(($('#start_date').val().valueOf() <= currentdate.valueOf()) && ($('#event_start').val() < defaultstarttime)) {
//            $enddatepicker . datepicker("setDate", eventenddate);
//            defaultendtime = $('#event_end') . val();
//        } else {
//            $enddatepicker . datepicker("setDate", eventstartdate);
//            defaultendtime = $('#event_start') . val();
//        }
        $finalStartDate = $finalStartTime = $eventStartDateTime;
        if (strtotime($eventStartDateTime) <= strtotime($currentDateTime)) {
            $finalStartDate = convertTime(allTimeFormats('', 9), $locationDetails['timeZoneName'], TRUE);
            $finalStartTime = $currentStartTime;
        }
        $finalEndDate = $finalEndTime = $eventEndDateTime;
        if (strtotime($eventEndDateTime) <= strtotime($currentDateTime)) {
            $finalEndDate = convertTime(allTimeFormats('', 9), $locationDetails['timeZoneName'], TRUE);
            $finalEndTime = $currentEndTime;
        }
        $finalStartTime = '09:00:00';
        $finalEndTime = '18:00:00';
        $param['startDate'] = allTimeFormats($finalStartDate, 1);
        $param['startTime'] = allTimeFormats($finalStartTime, 12);
        $param['endDate'] = allTimeFormats($finalEndDate, 1);
        $param['endTime'] = allTimeFormats($finalEndTime, 12);
        //print_r($param);exit;

        $param['timezoneId'] = $eventDetails['timeZoneId'];
        $param['booknowButtonValue'] = $eventDetails['eventDetails']['bookButtonValue'];
        $param['private'] = $eventDetails['private'];
        $param['latitude'] = isset($eventDetails['latitude']) ? $eventDetails['latitude'] : 0;
        $param['longitude'] = isset($eventDetails['longitude']) ? $eventDetails['longitude'] : 0;
        $param['localityId'] = $eventDetails['localityId'];
        $param['pincode'] = $eventDetails['pincode'];
        $param['popularity'] = 0;
        // $param['ownerId'] = $eventDetails['ownerId'];
        $param['ownerId'] = $this->ci->customsession->getData('userId');
        $param['bannerSource'] = $eventDetails['bannerPath'];
        $param['thumbSource'] = $eventDetails['thumbnailPath'];
        $param['iswebinar'] = $eventDetails['eventMode'];

        foreach ($ticketDetails as $key => $value) {
            $ticketStartDate = allTimeFormats($value['startDate'], 11);
            $finalStartDate = $finalStartTime = $ticketStartDate;
            if (strtotime($ticketStartDate) <= strtotime($currentDateTime)) {
                $finalStartDate = convertTime(allTimeFormats('', 9), $locationDetails['timeZoneName'], TRUE);
                $finalStartTime = $currentStartTime;
            }
            $ticketEndDate = allTimeFormats($value['endDate'], 11);
            $finalEndDate = $finalEndTime = $ticketEndDate;
            if (strtotime($ticketEndDate) <= strtotime($currentDateTime)) {
                $finalEndDate = convertTime(allTimeFormats('', 9), $locationDetails['timeZoneName'], TRUE);
                $finalEndTime = $currentEndTime;
            }
            $param['tickets'][$key]['name'] = $value['name'];
            $type = 2;
            if ($value['type'] == 'free') {
                $type = '1';
            } elseif ($value['type'] == 'donation') {
                $type = '3';
            } elseif ($value['type'] == 'addon') {
                $type = '4';
            }
            $param['tickets'][$key]['type'] = $type;
            //$ticketStrtDate = allTimeFormats($currentDate, 1);
            //$ticketEndDate = allTimeFormats($currentDate, 1);
            $param['tickets'][$key]['startDate'] = allTimeFormats($finalStartDate, 1);
            $param['tickets'][$key]['startTime'] = allTimeFormats($finalStartTime, 12);
            $param['tickets'][$key]['endDate'] = allTimeFormats($finalEndDate, 1);
            $param['tickets'][$key]['endTime'] = allTimeFormats($finalEndTime, 12);
            $param['tickets'][$key]['description'] = $value['description'];
            $param['tickets'][$key]['price'] = $value['price'];
            $param['tickets'][$key]['quantity'] = $value['quantity'];
            $param['tickets'][$key]['minOrderQuantity'] = $value['minOrderQuantity'];
            $param['tickets'][$key]['maxOrderQuantity'] = $value['maxOrderQuantity'];
            $param['tickets'][$key]['order'] = $value['order'];
            $param['tickets'][$key]['currencyId'] = $value['currencyId'];
            $param['tickets'][$key]['soldOut'] = $value['soldout'];
            $param['tickets'][$key]['displayStatus'] = $value['displayStatus'];

            $param['tickets'][$key]['taxArray'] = isset($taxList[$value['id']]) ? $taxList[$value['id']] : array();
            //$param['tickets'][$key]['taxArray'] = array();
        }
        //print_r($param);exit;
        $param['country'] = $locationDetails['countryName'];
        $param['state'] = $locationDetails['stateName'];
        $param['city'] = $locationDetails['cityName'];
        
        if (strlen($eventDetails['tags']) > 0) {
            $tags = explode(",", $eventDetails['tags']);
        } else {
            $tags = array($param['city']);
        }
        foreach ($tags as $tagKey => $tagValue) {
            $param['tags'][$tagKey]['id'] = 0;
            $param['tags'][$tagKey]['tag'] = $tagValue;
        }
        //print_r($param);exit;
        $response = $this->createEvent($param);
        //print_r($response);
        //exit;
        return $response;
    }

    public function getContactInfo($inputArray) {

        parent::$CI->form_validation->pass_array($inputArray);
        parent::$CI->form_validation->set_rules('eventId', 'eventId', 'required_strict|is_natural_no_zero');
        if (parent::$CI->form_validation->run() === FALSE) {
            $errorMessages = $this->ci->form_validation->get_errors();
            $output['status'] = FALSE;
            $output['response']['messages'] = $errorMessages['message'][0];
            $output['statusCode'] = STATUS_BAD_REQUEST;
            return $output;
        }
        $eventExist = $this->eventExists($inputArray);
        if ($eventExist['status'] == TRUE) {
            $this->ci->load->model('Eventdetail_model');
            $this->ci->Eventdetail_model->resetVariable();
            $Selectcontactinfo = array();
            $Selectcontactinfo['contactdetails'] = $this->ci->Eventdetail_model->eventdetail_contactdetails;
            $Selectcontactinfo['extrareportingemails'] = $this->ci->Eventdetail_model->eventdetail_extrareportingemails;
            $Selectcontactinfo['extratxnreportingemails'] = $this->ci->Eventdetail_model->eventdetail_extratxnreportingemails;
            $Selectcontactinfo['contactwebsiteurl'] = $this->ci->Eventdetail_model->eventdetail_contactwebsiteurl;
            $Selectcontactinfo['facebooklink'] = $this->ci->Eventdetail_model->eventdetail_facebooklink;
            $this->ci->Eventdetail_model->setSelect($Selectcontactinfo);
            $whereDetails[$this->ci->Eventdetail_model->eventdetail_id] = $inputArray['eventId'];
            $this->ci->Eventdetail_model->setWhere($whereDetails);
            $contactDetails = $this->ci->Eventdetail_model->get();
            if (count($contactDetails) > 0) {
                $output['status'] = TRUE;
                $output['response']['contactDetails'] = $contactDetails[0];
                $output['response']['total'] = count($contactDetails);
                $output['statusCode'] = STATUS_OK;
                return $output;
            } else {
                $output['status'] = FALSE;
                $output['response']['messages'][] = ERROR_SOMETHING_WENT_WRONG;
                $output['response']['total'] = 0;
                return $output;
            }
        } else {
            $output['status'] = TRUE;
            $output['response']['messages'][] = ERROR_NO_EVENT;
            $output['response']['total'] = 0;
            $output['statusCode'] = STATUS_OK;
            return $output;
        }
    }

    public function updateContactInfo($inputArray) {
        $this->ci->form_validation->pass_array($inputArray);
        $this->ci->form_validation->set_rules('eventId', 'event Id', 'required_strict|is_natural_no_zero');
        $this->ci->form_validation->set_rules('namesPhoneEmail', 'contact', 'required_strict');
        if (!empty($inputArray) && $this->ci->form_validation->run() == FALSE) {
            $validationStatus = $this->ci->form_validation->get_errors();
            $output['status'] = FALSE;
            $output['response']['messages'] = $validationStatus['message'][0];
            $output['statusCode'] = STATUS_BAD_REQUEST;
            return $output;
        } else {
            $this->ci->Eventdetail_model->resetVariable();
            $contactDetails['contactdetails'] = $inputArray['namesPhoneEmail'];
            $contactDetails['extrareportingemails'] = $inputArray['moreEmails'];
            $contactDetails['extratxnreportingemails'] = $inputArray['extratxnreportingemails'];
            $contactDetails['contactwebsiteurl'] = $inputArray['eventWebUrl'];
            $contactDetails['facebooklink'] = $inputArray['eventFBUrl'];
            $where['eventid'] = $inputArray['eventId'];
            $this->ci->load->model('Eventdetail_model');
            $this->ci->Eventdetail_model->setInsertUpdateData($contactDetails);
            $this->ci->Eventdetail_model->setWhere($where);
            $response = $this->ci->Eventdetail_model->update_data();
            if ($response) {
                $output['status'] = TRUE;
                $output["response"]["messages"][] = SEO_DETAILS_UPDATE;
                $output['statusCode'] = STATUS_UPDATED;
                return $output;
            } else {
                $output['status'] = FALSE;
                $output["response"]["messages"][] = SOMETHING_WENT_WRONG;
                $output['statusCode'] = STATUS_SERVER_ERROR;
                return $output;
            }
        }
    }

    /**
     * To set the ticketing option based on event category ticket option flag
     * @param type $inputArray
     * @return type
     */
    public function setTicketOption($inputArray) {
        $categoryHandler = new Category_handler();
        $categoryResponse = $categoryHandler->getCategoryDetails($inputArray);
        if (!$categoryResponse['status']) {
            return SINGLE_ATTENDEE;
        }
        if ($categoryResponse['response']['detail']['ticketsetting'] == "single") {
            return SINGLE_ATTENDEE;
        }
        return MULTIPLE_ATTENDEES;
    }

    public function mailInvitation($inputArray) {
        $emailHandler = new Email_handler();
        $captchadata = $this->ci->customsession->getData('captchaWord');
        $this->ci->form_validation->pass_array($inputArray);
        $this->ci->form_validation->set_rules('from_name', 'From name', 'required_strict');
        $this->ci->form_validation->set_rules('from_email', 'From email', 'required_strict|valid_email');
        $this->ci->form_validation->set_rules('captchatext', 'captchatext', 'required_strict');
        $this->ci->form_validation->set_rules('message', 'message', 'required_strict');
        if (!isset($inputArray['contactorg'])) {
            $this->ci->form_validation->set_rules('to_email', 'To email', 'required_strict');
        } else {
            $this->ci->form_validation->set_rules('mobile', 'mobile', 'required_strict');
        }
        //  $this->ci->form_validation->set_rules('eventsignupid', 'Eventsignup Id', 'is_natural_no_zero');
        if (!empty($inputArray) && $this->ci->form_validation->run() == FALSE) {
            $validationStatus = $this->ci->form_validation->get_errors();
            $output['status'] = FALSE;
            $output['response']['messages'] = $validationStatus['message'];
            $output['statusCode'] = STATUS_BAD_REQUEST;
            return $output;
        } else if ($captchadata != $inputArray['captchatext']) {
            $output['status'] = FALSE;
            $output['response']['messages'][] = "Wrong Captcha";
            $output['statusCode'] = STATUS_PRECONDITION_FAILED;
            return $output;
        } else {
            $mailInvitations = $emailHandler->sendMailInvitations($inputArray);
            if ($mailInvitations) {
                $output['status'] = TRUE;
                $output["response"]["messages"][] = Email_SENT;
                $output['statusCode'] = STATUS_MAIL_SENT;
                return $output;
            } else {
                $output['status'] = FALSE;
                $output["response"]["messages"][] = ERROR_EMAIL_NOT_SENT;
                $output['statusCode'] = STATUS_MAIL_NOT_SENT;
                return $output;
            }
        }
    }

    public function getEventDiscountCount($inputArray) {
        $this->ci->form_validation->pass_array($inputArray);
        $this->ci->form_validation->set_rules('eventId', 'eventId', 'required_strict|is_natural_no_zero');
        if ($this->ci->form_validation->run() === FALSE) {
            $errorMessages = $this->ci->form_validation->get_errors();
            $output['status'] = FALSE;
            $output['response']['messages'] = $errorMessages['message'];
            $output['statusCode'] = STATUS_BAD_REQUEST;
            return $output;
        }
        $eventId = $inputArray['eventId'];
        $discountHandler = new Discount_handler();
        $inputDis['eventid'] = $eventId;
        return $discountHandler->getDiscountCountByEventId($inputDis);
    }

    public function changeEventStatus($eventId) {
        //Checking whether the event id is available or not
        $this->ci->Event_model->resetVariable();
        $select['status'] = $this->ci->Event_model->status;
        $select['eventId'] = $this->ci->Event_model->id;
        $select['url'] = $this->ci->Event_model->url;
        $this->ci->Event_model->setSelect($select);
        $where[$this->ci->Event_model->id] = $eventId;

        $this->ci->Event_model->setWhere($where);
        $result = $this->ci->Event_model->get();
        if ($result) {
            $status = $result[0]['status'];
            $eventStatus['url'] = $result[0]['url'];
            if ($status == 1) {
                $eventStatus[$this->ci->Event_model->status] = 0;
                $eventStatus['status'] = 0;
            } else {
                $eventStatus[$this->ci->Event_model->status] = 1;
                $eventStatus['status'] = 1;
            }
            $solrInput = array();
            $solrInput['status'] = $eventStatus['status'];
            $solrHandler = new Solr_handler();
            $solrUpdate = array();
            $solrUpdate = $this->solrAddEventInputData($solrInput);
            $solrUpdateArray['status'] = $solrUpdate['status'];
            $solrUpdateArray['id'] = $eventId;
            $solrUpdatedData = $solrHandler->solrUpdateEvent($solrUpdateArray);
            if (!$solrUpdatedData['status']) {
                return $solrUpdatedData;
            }
            $whereArray[$this->ci->Event_model->id] = $eventId;
            $updateArray[$this->ci->Event_model->status] = $eventStatus['status'];
            $this->ci->Event_model->setWhere($whereArray);
            $this->ci->Event_model->setInsertUpdateData($updateArray);
            $updateResult = $this->ci->Event_model->update_data();
            if ($updateResult) {
                if ($eventStatus['status'] == 1) {//Send publish email
                    $sendMailInput['response']['id'] = $eventId;
                    $sendMail = $this->sendEventPublichedEmailToOrg($sendMailInput);
                }
                $output = parent::createResponse(TRUE, SUCCESS_UPDATED_EVENT_STATUS, STATUS_UPDATED, 1, 'eventStatus', $eventStatus);
                return $output;
            } else {
                $output = parent::createResponse(FALSE, ERROR_SOMETHING_WENT_WRONG, STATUS_SERVER_ERROR);
                return $output;
            }
        } else {
            $output = parent::createResponse(TRUE, ERROR_NO_RECORDS, STATUS_NO_DATA, 0);
            return $output;
        }
    }

    public function getEventTimeZone($eventId) {
        $timezoneHandler = new Timezone_handler();
        $this->ci->Event_model->resetVariable();
        $select['timezoneid'] = $this->ci->Event_model->timezoneid;
        $this->ci->Event_model->setSelect($select);
        $where['id'] = $eventId;
        $this->ci->Event_model->setWhere($where);
        $eventResponse = $this->ci->Event_model->get();
        $timeZoneId = $eventResponse[0]['timezoneid'];
        $timezoneDetails = $timezoneHandler->details(array('timezoneId' => $timeZoneId));
        if ($timezoneDetails['status'] && count($timezoneDetails) > 0) {
            $data = $timezoneDetails['response']['detail'];
        }
        return $data[$timeZoneId]['name'];
    }

    public function sendEventPublichedEmailToOrg($data) {
        $emailHandler = new Email_handler();
        $this->ci->load->library('parser');
        $inputArray['type'] = TYPE_EVENT_PUBLISH;
        $inputArray['mode'] = 'email';
        $this->ci->Event_model->resetVariable();
        $select[$this->ci->Event_model->title] = $this->ci->Event_model->title;
        $select[$this->ci->Event_model->url] = $this->ci->Event_model->url;
        $select[$this->ci->Event_model->venue] = $this->ci->Event_model->venue;

        $this->ci->Event_model->setSelect($select);
        $where['id'] = $data['response']['id'];
        $this->ci->Event_model->setWhere($where);
        $eventResponse = $this->ci->Event_model->get();


        $this->ci->load->model('Eventdetail_model');
        $selectDetailsInput = array();
        $selectDetailsInput['facebookLink'] = $this->ci->Eventdetail_model->eventdetail_facebooklink;
        $selectDetailsInput['twitterLink'] = $this->ci->Eventdetail_model->eventdetail_twitterlink;
        $this->ci->Eventdetail_model->setSelect($selectDetailsInput);
        $whereDetails[$this->ci->Eventdetail_model->eventdetail_id] = $data['response']['id'];
        $this->ci->Eventdetail_model->setWhere($whereDetails);
        $eventDetailsFrmTable = $this->ci->Eventdetail_model->get();

        $eventTitle = $eventResponse[0]['title'];
        $eventUrl = commonHelperGetPageUrl('preview-event', $eventResponse[0]['url']);
        $eventVenue = $eventResponse[0]['venuename'];
        $siteurl = site_url();
        $iframe = commonHelperGetPageUrl('ticketWidget','','?eventId='.$data['response']['id'].'&ucode=organizer');
        
        $this->messagetemplateHandler = new Messagetemplate_handler();
        $OrganizerEmailtemplate = $this->messagetemplateHandler->getTemplateDetail($inputArray);
        $organizerTemplate = $OrganizerEmailtemplate['response']['templateDetail']['template'];
        //SEND EMAIL AND SMS IF PAYMENT SUCCESSFULL
        $subject = SUBJECT_EVENT_PUBLISH . $eventTitle;
        $from = $OrganizerEmailtemplate['response']['templateDetail']['fromemailid'];
        $emailMsgid = $OrganizerEmailtemplate['response']['templateDetail']['id'];
        $userName = $this->ci->customsession->getData('userName');
        $templateData['siteurl'] = $siteurl;
        $templateData['eventtitle'] = $eventTitle;
        $templateData['eventurl'] = $eventUrl. '?ucode=organizer';
        $templateData['eventvenue'] = $eventVenue;
        $templateData['iframe'] = $iframe;
        $templateData['username'] = ucfirst($userName);
        $templateData['currentYear'] = allTimeFormats(' ', 17);
        $templateData['supportLink'] = commonHelperGetPageUrl('contactUs');
        // $templateData['FBSharelink'] = $eventDetailsFrmTable[0]['facebookLink'];
        // $templateData['TwitterSharelink'] = $eventDetailsFrmTable[0]['twitterLink'];

        $to = $this->ci->customsession->getData('userEmail');
        $message = $this->ci->parser->parse_string($organizerTemplate, $templateData, TRUE);
        $sentmessageInputs['messageid'] = $emailMsgid;
        $cc = $content = $filename = '';
        //$bcc = SALES_MAIL . "," . MARKETING_MAIL;
        $bcc = '';
        $hostname = strtolower($_SERVER['HTTP_HOST']);
        if(strcmp($hostname,'www.meraevents.com') == 0 || strcmp($hostname,'meraevents.com') == 0) {
            $bcc = SALES_MAIL . "," . MARKETING_MAIL;
        }
        //send mail to the sales along with organizer
        $emailResponse = $emailHandler->EmailSend($to, $cc, $bcc, $from, $subject, $message, $content, $filename, '', $sentmessageInputs);
        //$emailResponse = $emailHandler->sendEmail($from, $to, $subject, $message, '', '', '', $sentmessageInputs);
        return $emailResponse;
    }

    //To cancel the event on solr level
    public function eventCancel($data) {

        $this->ci->form_validation->reset_form_rules();
        $this->ci->form_validation->pass_array($data);
        $this->ci->form_validation->set_rules('eventId', 'Event Id', 'is_natural_no_zero|required_strict');
        $this->ci->form_validation->set_rules('keyValue', 'Key Value', 'is_natural_no_zero|required_strict');

        if ($this->ci->form_validation->run() === FALSE) {
            $output['status'] = FALSE;
            $output['response']['messages'] = $this->ci->form_validation->get_errors();
            $output['response']['eventCanceled'] = 'Failed';
            $output['statusCode'] = STATUS_BAD_REQUEST;
            return $output;
        }

        //check the user status & event status
        $inputArray['userIds'] = $data['keyValue'];
        $this->userHandler = new User_handler();
        $userData = $this->userHandler->getUserInfo($inputArray);
        //Before changing the solr event status checking admin user status
        if ($userData['status'] && $userData['response']['userData']['usertype'] == "superadmin") {

            $solrArray['status'] = 3; //Cancel event status
            $solrArray['id'] = $data['eventId'];
            $solrHandler = new Solr_handler();
            $addEventInSolr = $solrHandler->solrUpdateEvent($solrArray);
            if ($addEventInSolr['status']) {
                $output['status'] = TRUE;
                $output['response']['messages'][] = 'Successfully canceled';
                $output['response']['eventCanceled'] = 'Success';
                $output['statusCode'] = STATUS_OK;
                return $output;
            } else {
                $output['status'] = FALSE;
                $output['response']['messages'][] = ERROR_SOMETHING_WENT_WRONG;
                $output['response']['eventCanceled'] = 'Failed';
                $output['statusCode'] = STATUS_BAD_REQUEST;
                return $output;
            }
            return $addEventInSolr;
        }
        $output['status'] = FALSE;
        $output['response']['messages'][] = ERROR_NO_SESSION;
        $output['response']['eventCanceled'] = 'Failed';
        $output['statusCode'] = STATUS_BAD_REQUEST;
        return $output;
    }

    public function blockEventTitles($data) {
        if (isset($data['title'])) {
            $blockedTitles = $this->ci->config->item('blockedEventTitles');
            foreach ($blockedTitles as $bkey => $bvalue) {
                $blockT.=$bvalue . ' ';
            }
            $error = str_replace('XXXX', $blockT, ERROR_BLOCK_EVENT_TITLES);
            foreach ($blockedTitles as $key => $value) {
                if (is_int(strripos($data['title'], $value))) {
                    $output['status'] = FALSE;
                    $output["response"]["messages"][] = $error;
                    $output['statusCode'] = STATUS_BAD_REQUEST;
                    return $output;
                }
            }
        }
    }

    //To change the event ticket sold out status in solr document
    public function eventTicketSoldout($data) {
        $solrArray['ticketSoldout'] = $data['ticketSoldout'];
        $solrArray['id'] = $data['eventId'];
        $solrHandler = new Solr_handler();
        $addEventInSolr = $solrHandler->solrUpdateEvent($solrArray);
        if ($addEventInSolr['status']) {
            $output['status'] = TRUE;
            $output['response']['messages'][] = SUCCESS_UPDATED;
            $output['response']['updatedTicketSoldout'] = 'Success';
            $output['statusCode'] = STATUS_OK;
            return $output;
        } else {
            $output['status'] = FALSE;
            $output['response']['messages'][] = ERROR_SOMETHING_WENT_WRONG;
            $output['response']['updatedTicketSoldout'] = 'Failed';
            $output['statusCode'] = STATUS_BAD_REQUEST;
            return $output;
        }
    }

    /*
     * Function to get the Events list based on given Ids
     *
     * @access	public
     * @param	$inputArray contains
     * 				eventIdsArray - array of the events
     * @return	array
     */

    public function getListByEventIds($input) {
        $fileHandler = new File_handler();
        $categoryHandler = new Category_handler();
        $timezoneHandler = new Timezone_handler();
        $bookmarkHandler = new Bookmark_handler();
        $this->ci->form_validation->pass_array($input);
        $this->ci->form_validation->set_rules('eventIdsArray', 'event ids', 'required_strict|is_array');
        if ($this->ci->form_validation->run() == FALSE) {
            $response = $this->ci->form_validation->get_errors();
            $output['status'] = FALSE;
            $output['response']['messages'] = $response['message'];
            $output['statusCode'] = STATUS_BAD_REQUEST;
            return $output;
        }
        $eventIdsArray = $input['eventIdsArray'];
        $this->ci->Event_model->resetVariable();
        $select['id'] = $this->ci->Event_model->id;
        $select['title'] = $this->ci->Event_model->title;
        $select['startDateTime'] = $this->ci->Event_model->startdatetime;
        $select['endDateTime'] = $this->ci->Event_model->enddatetime;
        $select['timezoneId'] = $this->ci->Event_model->timezoneid;
        $select['venueName'] = $this->ci->Event_model->venuename;
        $select['thumbImage'] = $this->ci->Event_model->logo;
        $select['bannerImage'] = $this->ci->Event_model->banner;
        $select['url'] = $this->ci->Event_model->url;
        $select['categoryId'] = $this->ci->Event_model->categoryid;
        $select['subcategoryid'] = $this->ci->Event_model->subcategoryid;

        $this->ci->Event_model->setSelect($select);
        if (count($eventIdsArray) > 0) {
            $where_in[$this->ci->Event_model->id] = $eventIdsArray;
        }
        $where[$this->ci->Event_model->deleted] = 0;
        $where[$this->ci->Event_model->status] = 1;
        $this->ci->Event_model->setWhere($where);
        $this->ci->Event_model->setWhereIns($where_in);
        $eventResponse = $this->ci->Event_model->get();

        $categoryIdList = array();
        $timezoneIdList = array();
        foreach ($eventResponse as $rKey => $rValue) {
            $categoryIdList[] = $rValue["categoryId"];
            $timezoneIdList[] = $rValue["timezoneId"];
        }
        $categoryIdList = array_unique($categoryIdList);
        $timezoneIdList = array_unique($timezoneIdList);
        $timezoneData = array();
        $timezoneData = $timezoneHandler->timeZoneList(array('idList' => $timezoneIdList));
        if ($timezoneData['status'] && $timezoneData['response']['total'] > 0) {
            $timezoneData = commonHelperGetIdArray($timezoneData['response']['timeZoneList']);
        }
        $categoryData = array();
        $categoryData = $categoryHandler->getCategoryList(array('major' => 1));
        if ($categoryData['status'] && $categoryData['response']['total'] > 0) {
            $categoryData = commonHelperGetIdArray($categoryData['response']['categoryList']);
        }

        $bookmarkEvents = array();
        $userId = $this->ci->customsession->getUserId();
        if ($userId != '') {
            $bookmarkInputs['userId'] = $userId;
            $bookmarkInputs['returnEventIds'] = true;
            $bookmarkEvents = $bookmarkHandler->getUserBookmarks($bookmarkInputs);
            $bookmarkEventsArray = array();
            if ($bookmarkEvents['status'] && $bookmarkEvents['response']['total'] > 0) {
                $bookmarkEventsArray = $bookmarkEvents['response']['bookmarkedEvents'];
            }
        }

        foreach ($eventResponse as $key => $value) {
            if ($value['thumbImage'] != '') {
                $fileIdsArray[] = $value['thumbImage'];
            }
            if ($value['bannerImage'] != '') {
                $fileIdsArray[] = $value['bannerImage'];
            }
        }

        $eventFileidsData = array('id', $fileIdsArray);
        $fileData = $fileHandler->getFileData($eventFileidsData);

        if ($fileData['status'] && $fileData['response']['total'] > 0) {
            //converting to indexed based array
            $fileData = commonHelperGetIdArray($fileData['response']['fileData']);
        }

        foreach ($eventResponse as $recordKey => $recordValue) {
            $eventList[$recordKey]['id'] = $recordValue["id"];
            $eventList[$recordKey]['title'] = $recordValue["title"];

            $eventList[$recordKey]['thumbImage'] = $eventList[$recordKey]['bannerImage'] = '';
            if (isset($recordValue['thumbImage']) && is_array($fileData[$recordValue["thumbImage"]]) && $fileData[$recordValue["thumbImage"]]['path'] != '') {
                $eventList[$recordKey]['thumbImage'] = $this->ci->config->item('images_content_cloud_path') . $fileData[$recordValue["thumbImage"]]['path'];
            }
            if (isset($recordValue['bannerImage']) && is_array($fileData[$recordValue["bannerImage"]]) && $fileData[$recordValue["bannerImage"]]['path'] != '') {
                $eventList[$recordKey]['bannerImage'] = $this->ci->config->item('images_content_cloud_path') . $fileData[$recordValue["bannerImage"]]['path'];
            }
            $eventList[$recordKey]['startDate'] = allTimeFormats($recordValue["startDateTime"], 11);
            $eventList[$recordKey]['endDate'] = allTimeFormats($recordValue["endDateTime"], 11);
            $eventList[$recordKey]['venueName'] = $recordValue["venueName"];
            $eventList[$recordKey]['eventUrl'] = commonHelperEventDetailUrl($recordValue["url"]);
//            if(isset($recordValue["externalurl"])){
//                $eventList[$recordKey]['eventExternalUrl'] = $recordValue["externalurl"];
//            }
            if ($recordValue["categoryId"] > 0) {
                $catDetails = $categoryData[$recordValue["categoryId"]];

                $eventList[$recordKey]['categoryName'] = $catDetails['name'];
                $eventList[$recordKey]['categoryIcon'] = ''; //$catDetails['iconimagefileid'];
                $eventList[$recordKey]['themeColor'] = $catDetails['themecolor'];
            } else {
                $eventList[$recordKey]['categoryName'] = "";
                $eventList[$recordKey]['categoryIcon'] = "";
                $eventList[$recordKey]['themeColor'] = "";
            }
            $eventList[$recordKey]['timeZone'] = "";
            $timezoneId = $recordValue['timezoneId'];
            if ($timezoneId > 0) {
                $eventList[$recordKey]['timeZone'] = $timezoneData[$timezoneId]['zone'];
            }

            $eventList[$recordKey]['bookMarked'] = 0;
            if ($userId != '') {
                if (in_array($recordValue["id"], $bookmarkEventsArray)) {
                    $eventList[$recordKey]['bookMarked'] = 1;
                }
            }
        }

        if (count($eventList) == 0) {
            $output['status'] = TRUE;
            $output['response']['messages'][] = ERROR_NO_EVENTS;
            $output['response']['total'] = 0;
            $output['statusCode'] = STATUS_OK;
            return $output;
        }
        $output['status'] = TRUE;
        $output['response']['eventList'] = $eventList;
        $output['response']['total'] = count($eventList);
        $output['statusCode'] = STATUS_OK;
        return $output;
    }

    //To unpublish the event on solr level
    public function solrEventStatus($data) {

        $this->ci->form_validation->reset_form_rules();
        $this->ci->form_validation->pass_array($data);
        $this->ci->form_validation->set_rules('eventId', 'Event Id', 'is_natural_no_zero|required_strict');
        $this->ci->form_validation->set_rules('keyValue', 'Key Value', 'is_natural_no_zero|required_strict');

        if ($this->ci->form_validation->run() === FALSE) {
            $output['status'] = FALSE;
            $output['response']['messages'] = $this->ci->form_validation->get_errors();
            $output['statusCode'] = STATUS_BAD_REQUEST;
            $output['response']['status'] = FALSE;
            return $output;
        }

        //check the user status & event status
        $eventData = $this->eventExists($data);


        $inputArray['userIds'] = $data['keyValue'];
        $this->userHandler = new User_handler();
        $userData = $this->userHandler->getUserInfo($inputArray);
        $solrHandler = new Solr_handler();
        //Before changing the solr event status checking admin user status 
        //& event status in the db
        if ($userData['status'] && $userData['response']['userData']['usertype'] == "superadmin" && $eventData['status'] && $data['updatetype'] == 'eventstatus') {

            $solrArray['status'] = $data['status'];
            $solrArray['id'] = $data['eventId'];
            $addEventInSolr = $solrHandler->solrUpdateEvent($solrArray);
            if ($addEventInSolr['status']) {
                $output['status'] = TRUE;
                $output['response']['messages'][] = SUCCESS_UPDATED;
                $output['response']['statusUpdated'] = 'Success';
                $output['statusCode'] = STATUS_OK;
                return $output;
            } else {
                $output['status'] = FALSE;
                $output['response']['messages'][] = ERROR_SOMETHING_WENT_WRONG;
                $output['response']['statusUpdated'] = 'Failed';
                $output['statusCode'] = STATUS_BAD_REQUEST;
                return $output;
            }
        }

        if ($userData['status'] && $userData['response']['userData']['usertype'] == "superadmin" && $eventData['status'] && $data['updatetype'] === 'ticketstatus') {

            $solrArray['ticketSoldout'] = $data['ticketSoldout'];
            $solrArray['id'] = $data['eventId'];
            $addEventInSolr = $solrHandler->solrUpdateEvent($solrArray);
            if ($addEventInSolr['status']) {
                $output['status'] = TRUE;
                $output['response']['messages'][] = SUCCESS_UPDATED;
                $output['response']['updatedTicketSoldout'] = 'Success';
                $output['statusCode'] = STATUS_OK;
                return $output;
            } else {
                $output['status'] = FALSE;
                $output['response']['messages'][] = ERROR_SOMETHING_WENT_WRONG;
                $output['response']['updatedTicketSoldout'] = 'Failed';
                $output['statusCode'] = STATUS_BAD_REQUEST;
                return $output;
            }
        }

        //To Update the event priority field value
        if ($userData['status'] && ($userData['response']['userData']['usertype'] == "superadmin" || $userData['response']['userData']['usertype'] == "admin") && $eventData['status'] && $data['updatetype'] === 'prioritystatus') {

            $solrArray['popularity'] = $data['popularityValue'];
            $solrArray['id'] = $data['eventId'];
            $addEventInSolr = $solrHandler->solrUpdateEvent($solrArray);
            if ($addEventInSolr['status']) {
                $output['status'] = TRUE;
                $output['response']['messages'][] = SUCCESS_UPDATED;
                $output['response']['updatedPriority'] = 'Success';
                $output['statusCode'] = STATUS_OK;
                return $output;
            } else {
                $output['status'] = FALSE;
                $output['response']['messages'][] = ERROR_SOMETHING_WENT_WRONG;
                $output['response']['updatedPriority'] = 'Failed';
                $output['statusCode'] = STATUS_BAD_REQUEST;
                return $output;
            }
        }
        //To Update the event microsite URL value
        if ($userData['status'] && ($userData['response']['userData']['usertype'] == "superadmin" || $userData['response']['userData']['usertype'] == "admin") && $eventData['status'] && $data['updatetype'] === 'updateMicrositeURL') {

            $solrArray['externalurl'] = $data['externalurl'];
            $solrArray['id'] = $data['eventId'];
            $addEventInSolr = $solrHandler->solrUpdateEvent($solrArray);
            if ($addEventInSolr['status']) {
                $output['status'] = TRUE;
                $output['response']['messages'][] = SUCCESS_UPDATED;
                $output['response']['updateMicrositeURL'] = 'Success';
                $output['statusCode'] = STATUS_OK;
                return $output;
            } else {
                $output['status'] = FALSE;
                $output['response']['messages'][] = ERROR_SOMETHING_WENT_WRONG;
                $output['response']['updateMicrositeURL'] = 'Failed';
                $output['statusCode'] = STATUS_BAD_REQUEST;
                return $output;
            }
        }
        //To Update the event totalsoldtickets value
        if ($userData['status'] && $eventData['status'] && $data['updatetype'] === 'updatetotalsoldtickets') {

            $solrArray['totalsoldtickets'] = $data['totalsoldtickets'];
            $solrArray['id'] = $data['eventId'];
            $addEventInSolr = $solrHandler->solrUpdateEvent($solrArray);
            if ($addEventInSolr['status']) {
                $output['status'] = TRUE;
                $output['response']['messages'][] = SUCCESS_UPDATED;
                $output['response']['updatetotalsoldtickets'] = 'Success';
                $output['statusCode'] = STATUS_OK;
                return $output;
            } else {
                $output['status'] = FALSE;
                $output['response']['messages'][] = ERROR_SOMETHING_WENT_WRONG;
                $output['response']['updatetotalsoldtickets'] = 'Failed';
                $output['statusCode'] = STATUS_BAD_REQUEST;
                return $output;
            }
        }
        if ($userData['status'] && ($userData['response']['userData']['usertype'] == "superadmin" || $userData['response']['userData']['usertype'] == "admin") && $eventData['status'] && $data['updatetype'] == 'deleteEvent') {
            $solrDeletedData = array();
            $solrDeletedData['id'] = $data['eventId'];
            $deleteInSolr = $solrHandler->solrDeleteEvent($solrDeletedData);
            if ($deleteInSolr['status']) {
                $output['status'] = TRUE;
                $output['response']['messages'][] = SUCCESS_DELETED_EVENT;
                $output['response']['deleteEvent'] = 'Success';
                $output['statusCode'] = STATUS_OK;
                return $output;
            } else {
                $output['status'] = FALSE;
                $output['response']['messages'][] = ERROR_SOMETHING_WENT_WRONG;
                $output['response']['deleteEvent'] = 'Failed';
                $output['statusCode'] = STATUS_BAD_REQUEST;
                return $output;
            }
        }
        $output['status'] = FALSE;
        $output['response']['messages'][] = ERROR_NO_SESSION;
        $output['statusCode'] = STATUS_BAD_REQUEST;
        return $output;
    }

    public function getEventTimeZoneName($request) {
        $timezoneHandler = new Timezone_handler();
        $this->ci->form_validation->pass_array($request);
        $this->ci->form_validation->set_rules('eventId', 'Event Id', 'required_strict|is_natural_no_zero');
        if ($this->ci->form_validation->run() === FALSE) {
            $errorMessages = $this->ci->form_validation->get_errors();
            $output['status'] = FALSE;
            $output['response']['messages'][] = $errorMessages['message'];
            $output['statusCode'] = STATUS_BAD_REQUEST;
            return $output;
        }
        $this->ci->Event_model->resetVariable();
        $selectInput['id'] = $this->ci->Event_model->id;
        $selectInput['timeZoneId'] = $this->ci->Event_model->timezoneid;
        $this->ci->Event_model->setSelect($selectInput);
        $where[$this->ci->Event_model->id] = $request['eventId'];
        if (isset($request['userId']) && $request['userId'] > 0) {   // if user id is set then add condition
            $where[$this->ci->Event_model->ownerid] = $request['userId'];
        }
        $where[$this->ci->Event_model->deleted] = 0;
        $this->ci->Event_model->setWhere($where);
        $eventDetailsResponse = $this->ci->Event_model->get();
        if (count($eventDetailsResponse) == 0) {
            $output['status'] = TRUE;
            $output['response']['messages'][] = ERROR_NO_DATA;
            $output['response']['total'] = 0;
            $output['statusCode'] = STATUS_OK;
            return $output;
        } else {
            $eventDetails = $eventDetailsResponse[0];
            $timezoneDetails = $timezoneHandler->details(array('timezoneId' => $eventDetails['timeZoneId']));
            if ($timezoneDetails['status'] && count($timezoneDetails) > 0) {
                $locationDetails['timeZone'] = $timezoneDetails['response']['detail'][$eventDetails['timeZoneId']]['zone'];
                $locationDetails['timeZoneName'] = $timezoneDetails['response']['detail'][$eventDetails['timeZoneId']]['name'];
                $output['status'] = TRUE;
                $output['response']['messages'] = array();
                $output['total'] = 1;
                $output['response']['details'] = $eventDetails;
                $output['response']['details']['location'] = $locationDetails;
                $output['statusCode'] = STATUS_OK;
                return $output;
            } else {
                $output['status'] = TRUE;
                $output['response']['messages'][] = ERROR_NO_DATA;
                $output['response']['total'] = 0;
                $output['statusCode'] = STATUS_OK;
                return $output;
            }
        }
    }

    public function checkCodesAvailable($inputArray) {
        $this->ci->form_validation->pass_array($inputArray);
        $this->ci->form_validation->set_rules('eventid', 'eventid', 'required_strict|is_natural_no_zero');
        if ($this->ci->form_validation->run() === FALSE) {
            $errorMessages = $this->ci->form_validation->get_errors();
            $output['status'] = FALSE;
            $output['response']['messages'] = $errorMessages['message'];
            $output['statusCode'] = STATUS_BAD_REQUEST;
            return $output;
        }
        $eventId = $inputArray['eventid'];
        $request['eventId'] = $eventId;
        $eventHandler = new Event_handler();
        $eventDiscountList = $eventHandler->getEventDiscountCount($request);
        $eventData['normalDiscountEnabled'] = $eventData['referralDiscountEnabled'] = FALSE;
        if ($eventDiscountList['status']) {
            if ($eventDiscountList['response']['discountResponse']['discountCount'] > 0) {
                $eventData['normalDiscountEnabled'] = TRUE;
            }
        }
        $eventSignupHandler = new Eventsignup_handler();
        $reffCodeInput['eventid'] = $eventId;
        $referralCount = $eventSignupHandler->checkReffCodeAvailable($reffCodeInput);
        if ($referralCount > 0) {
            $eventData['referralDiscountEnabled'] = true;
        }
        $output['status'] = TRUE;
        $output['response']['messages'] = [];
        $output['response']['codeResponse'] = $eventData;
        $output['statusCode'] = STATUS_OK;
        return $output;
    }

    //To Bring the specific event related information for booking page
    public function getEventDetailsForBooking($request) {
        $countryHandler = new Country_handler();
        $stateHandler = new State_handler();
        $cityHandler = new City_handler();
        $timezoneHandler = new Timezone_handler();
        $this->ci->load->model('Eventdetail_model');
        $output = array();

        $validationStatus = $this->eventDetailValidation($request);
        if ($validationStatus['error'] == TRUE) {
            $output['status'] = FALSE;
            $output['response']['messages'] = $validationStatus['message'];
            $output['statusCode'] = STATUS_BAD_REQUEST;
            return $output;
        }
        $this->ci->Event_model->resetVariable();
        $selectInput['id'] = $this->ci->Event_model->id;
        $selectInput['ownerId'] = $this->ci->Event_model->ownerid;
        $selectInput['startDate'] = $this->ci->Event_model->startdatetime;
        $selectInput['endDate'] = $this->ci->Event_model->enddatetime;
        $selectInput['title'] = $this->ci->Event_model->title;
        $selectInput['description'] = $this->ci->Event_model->description;
        $selectInput['countryId'] = $this->ci->Event_model->countryid;
        $selectInput['stateId'] = $this->ci->Event_model->stateid;
        $selectInput['cityId'] = $this->ci->Event_model->cityid;
        $selectInput['localityId'] = $this->ci->Event_model->localityid;
        $selectInput['venuename'] = $this->ci->Event_model->venue;
        $selectInput['url'] = $this->ci->Event_model->url;
        $selectInput['thumbnailfileid'] = $this->ci->Event_model->logo;
        $selectInput['bannerfileid'] = $this->ci->Event_model->banner;
        $selectInput['categoryId'] = $this->ci->Event_model->categoryid;
        $selectInput['subcategoryId'] = $this->ci->Event_model->subcategoryid;
        $selectInput['pincode'] = $this->ci->Event_model->pincode;
        $selectInput['registrationType'] = $this->ci->Event_model->registrationtype;
        $selectInput['eventMode'] = $this->ci->Event_model->eventmode;
        $selectInput['timeZoneId'] = $this->ci->Event_model->timezoneid;
        $selectInput['venueName'] = $this->ci->Event_model->venuename;
        $selectInput['venueaddress1'] = $this->ci->Event_model->venueaddress1;
        $selectInput['venueaddress2'] = $this->ci->Event_model->venueaddress2;
        $selectInput['private'] = $this->ci->Event_model->private;
        $selectInput['status'] = $this->ci->Event_model->status;

        $this->ci->Event_model->setSelect($selectInput);
        $where[$this->ci->Event_model->id] = $request['eventId'];

        if (isset($request['userId']) && $request['userId'] > 0) {   // if user id is set then add condition
            $where[$this->ci->Event_model->ownerid] = $request['userId'];
        }

        $where[$this->ci->Event_model->deleted] = 0;
        $this->ci->Event_model->setWhere($where);
        $eventDetailsResponse = $this->ci->Event_model->get();
        if (count($eventDetailsResponse) == 0) {
            $output['status'] = TRUE;
            $output['response']['messages'][] = ERROR_NO_DATA;
            $output['response']['total'] = 0;
            $output['statusCode'] = STATUS_OK;
            return $output;
        } else {
            $eventDetails = $eventDetailsResponse[0];

            // Getting the event details from event_details table
            $eventDetails['eventDetails'] = array();
            $selectDetailsInput['contactdisplay'] = $this->ci->Eventdetail_model->eventdetail_contactdisplay;
            $selectDetailsInput['contactdetails'] = $this->ci->Eventdetail_model->eventdetail_contactdetails;
            $selectDetailsInput['bookButtonValue'] = $this->ci->Eventdetail_model->booknowbuttonvalue;
            $selectDetailsInput['facebookLink'] = $this->ci->Eventdetail_model->eventdetail_facebooklink;
            $selectDetailsInput['googleLink'] = $this->ci->Eventdetail_model->eventdetail_googlelink;
            $selectDetailsInput['twitterLink'] = $this->ci->Eventdetail_model->eventdetail_twitterlink;
            $selectDetailsInput['tnctype'] = $this->ci->Eventdetail_model->eventdetail_tnctype;
            $selectDetailsInput['meraeventstnc'] = $this->ci->Eventdetail_model->eventdetail_meraeventstnc;
            $selectDetailsInput['organizertnc'] = $this->ci->Eventdetail_model->eventdetail_organizertnc;
            $selectDetailsInput['contactWebsiteUrl'] = $this->ci->Eventdetail_model->eventdetail_contactwebsiteurl;
            $selectDetailsInput['limitSingleTicketType'] = $this->ci->Eventdetail_model->eventdetail_limitsingletickettype;
            $selectDetailsInput['salespersonid'] = $this->ci->Eventdetail_model->salespersonid;
            $selectDetailsInput['contactdisplay'] = $this->ci->Eventdetail_model->contactdisplay;
            $selectDetailsInput['customvalidationfunction'] = $this->ci->Eventdetail_model->customvalidationfunction;
            $selectDetailsInput['customvalidationflag'] = $this->ci->Eventdetail_model->customvalidationflag;

            $selectDetailsInput['confirmationpagescripts'] = $this->ci->Eventdetail_model->confirmationpagescripts;
            $selectDetailsInput['googleanalyticsscripts'] = $this->ci->Eventdetail_model->googleanalyticsscripts;

            $this->ci->Eventdetail_model->setSelect($selectDetailsInput);

            $whereDetails[$this->ci->Eventdetail_model->eventdetail_id] = $request['eventId'];
            $this->ci->Eventdetail_model->setWhere($whereDetails);
            $eventDetailsFrmTable = $this->ci->Eventdetail_model->get();

            if (count($eventDetailsFrmTable) > 0) {
                $eventDetails['eventDetails'] = $eventDetailsFrmTable[0];
            }
                $eventDetails['eventDetails']['contactdetails'] = "Phone or Email : " . $eventDetails['eventDetails']['contactdetails'];
            if (strlen(trim($eventDetails['eventDetails']['contactWebsiteUrl'])) > 0) {
                $eventDetails['eventDetails']['contactdetails'] = $eventDetails['eventDetails']['contactdetails'] . " |  Website Url:  " . $eventDetails['eventDetails']['contactWebsiteUrl'];
            }

            $countryData = $stateData = $cityData = array();
            //Getting the Country data
            if ($eventDetails['countryId'] > 0) {
                $countryData = $countryHandler->getCountryListById(array('countryId' => $eventDetails['countryId']));
            }
            if (count($countryData) > 0 && $countryData['status'] && $countryData['response']['total'] > 0) {
                $locationDetails['countryId'] = $eventDetails['countryId'];
                $locationDetails['countryName'] = $countryData['response']['detail']['name'];
            }

            //Getting the State data
            if ($eventDetails['stateId'] > 0) {
                $stateData = $stateHandler->getStateListById(array('stateId' => $eventDetails['stateId']));
            }
            if (count($stateData) > 0 && $stateData['status'] && $stateData['response']['total'] > 0) {
                $stateList = $stateData['response']['stateList'][0];
                $locationDetails['stateId'] = $eventDetails['stateId'];
                $locationDetails['stateName'] = $stateList['name'];
            }

            //Getting the City data
            if ($eventDetails['cityId'] > 0) {
                $request['cityId'] = $eventDetails['cityId'];
                $request['countryId'] = $eventDetails['countryId'];
                $cityData = $cityHandler->getCityDetailById($request);
            }
            if (count($cityData) > 0 && $cityData['status'] && $cityData['response']['total'] > 0) {
                $cityObject = $cityData['response']['detail'];
                $locationDetails['cityId'] = $eventDetails['cityId'];
                $locationDetails['cityName'] = $cityObject['name'];
            }

            //Preparing the venue details 
            $locationDetails['venueName'] = $eventDetails['venuename'];
            $locationDetails['address1'] = $eventDetails['venueaddress1'];
            $locationDetails['address2'] = $eventDetails['venueaddress2'];

            $eventDetails['eventUrl'] = commonHelperEventDetailUrl($eventDetails["url"]);

            $timezoneDetails = $timezoneHandler->details(array('timezoneId' => $eventDetails['timeZoneId']));
            if ($timezoneDetails['status'] && count($timezoneDetails) > 0) {
                $locationDetails['timeZone'] = $timezoneDetails['response']['detail'][$eventDetails['timeZoneId']]['zone'];
                $locationDetails['timeZoneName'] = $timezoneDetails['response']['detail'][$eventDetails['timeZoneId']]['name'];
            }
            //unseting the un wanted field names
            unset($eventDetails['stateId']);
            unset($eventDetails['cityId']);
            unset($eventDetails['venuename']);
            unset($eventDetails['venueaddress1']);
            unset($eventDetails['venueaddress2']);
            unset($eventDetails['countryId']);

            $output['status'] = TRUE;
            $output['response']['messages'] = array();
            $output['response']['total'] = 1;
            $output['response']['details'] = $eventDetails;
            $output['response']['details']['location'] = $locationDetails;
            $output['statusCode'] = STATUS_OK;
            return $output;
        }
    }

    /*
     * Function to get the event list based on the places
     *
     * @access	public
     * @param
     * @return event list based on either city or state or country
     */

    public function geteventListByPlaces($inputArray) {

        $countryHandler = new Country_handler();
        $cityHandler = new City_handler();
        $stateHandler = new State_handler();
        $timezoneHandler = new Timezone_handler();
        $categoryHandler = new Category_handler();
        $bookmarkHandler = new Bookmark_handler();
        $nextPageStatus = false;
        $eventList = array();

        if (!isset($inputArray['private'])) {
            $solrInputArray['private'] = 0;
        }
        if (!isset($inputArray['status'])) {
            $solrInputArray['status'] = 1;
        }
        if (!isset($inputArray['day'])) {
            $solrInputArray['day'] = 6;
        }

        $this->ci->form_validation->pass_array($inputArray);
        $this->ci->form_validation->set_rules('cityName', 'city name', 'required_strict');
        $this->ci->form_validation->set_rules('stateName', 'state name', 'required_strict');
        $this->ci->form_validation->set_rules('countryName', 'country name', 'required_strict');

        if ($this->ci->form_validation->run() === FALSE) {
            $errorMessages = $this->ci->form_validation->get_errors();
            $output['status'] = FALSE;
            $output['response']['messages'] = $errorMessages['message'];
            $output['statusCode'] = STATUS_BAD_REQUEST;
            return $output;
        }
        $countryId = $stateId = $cityId = 0;
        $countryName = $stateName = $cityName = '';
        $solrHandler = new Solr_handler();

        $countryInput['keyWord'] = $inputArray['countryName'];
        $countryInput['isNameExact'] = true;
        $countryResponse = $countryHandler->searchByKeyword($countryInput);
        if ($countryResponse['status'] && $countryResponse['response']['total'] > 0) {
            $countryId = $countryResponse['response']['countryList'][0]['id'];
            $countryName = $inputArray['countryName'];
        }
        // Getting city Id from city Name
        $cityInput['name'] = $inputArray['cityName'];
        $cityResponse = $cityHandler->getCityDetailsByName($cityInput);

        $cityDetails = $stateDetails = array();

        if ($cityResponse['response']['total'] == 0) {
            
        } elseif ($cityResponse['response']['total'] > 0) {
            $cityDetails = $cityResponse['response']['cityDetails'][0];
            $cityId = $cityDetails['id'];
            $cityName = $cityDetails['name'];
            $countryId = $cityDetails['countryId'];
        }

        // Getting events from solr
        $cityEventsTotal = 0;
        if ($cityId > 0) {

            $solrInputArray['cityId'] = $cityId;
            $solrInputArray['countryId'] = $countryId;
            $solrResult = $solrHandler->getSolrEventsByPlaces($solrInputArray);
            $solrResultArray = json_decode($solrResult, true);

            $countryId = $cityDetails['countryId'];
            $cityEventsTotal = $solrResultArray['response']['total'];
        }

        if ($cityId == 0 || $solrResultArray['response']['total'] == 0) {

            // Getting State Id from state Name
            $stateInput['keyWord'] = $inputArray['stateName'];
            $stateInput['countryId'] = $countryId;
            $stateInput['isNameExact'] = true;
            $stateResponse = $stateHandler->searchByKeyword($stateInput);

            if ($stateResponse['response']['total'] == 0) {
                
            } elseif ($stateResponse['response']['total'] > 0) {
                $stateDetails = $stateResponse['response']['stateList'][0];
                $stateId = $stateDetails['id'];
                $stateName = $stateDetails['name'];
            }

            $solrInput = $solrResultArray = $solrResult = array();
            unset($solrInputArray['cityId']);
            $stateEventTotal = 0;
            if ($stateId > 0) {

                $solrInputArray['stateId'] = $stateId;
                $solrResult = $solrHandler->getSolrEventsByPlaces($solrInputArray);
                $solrResultArray = json_decode($solrResult, true);

                $stateEventTotal = $solrResultArray['response']['total'];
            }

            if ($stateId == 0 || $stateEventTotal == 0) {

                $solrInput = $solrResultArray = $solrResult = array();
                unset($solrInputArray['stateId']);
                if ($countryId > 0) {

                    $solrInputArray['countryId'] = $countryId;
                    $solrResult = $solrHandler->getSolrEventsByPlaces($solrInputArray);
                    $solrResultArray = json_decode($solrResult, true);
                    $output['response']['countryId'] = $countryId;
                }
            } else {
                $output['response']['stateId'] = $stateId;
                $output['response']['stateName'] = $stateName;
                $output['response']['countryId'] = $countryId;
            }
        } else {
            $output['response']['cityId'] = $cityId;
            $output['response']['cityName'] = $cityName;
            $output['response']['countryId'] = $countryId;
        }

        if ($countryId > 0 && $countryName == '') {
            $countryNameInput['countryId'] = $countryId;
            $countryNameResponse = $countryHandler->getCountryListById($countryNameInput);
            if ($countryNameResponse['status'] && $countryNameResponse['response']['total'] > 0) {
                $countryName = $countryNameResponse['response']['detail']['name'];
            }
        }

        if ($countryName != '') {
            $output['response']['countryName'] = $countryName;
        }

        $solrEventList = $solrResultArray["response"]["events"];
        if (count($solrEventList) > 0) {
            $categoryIdList = array();
            $timezoneIdList = array();
            $cityIdList = array();
            foreach ($solrEventList as $rKey => $rValue) {
                $categoryIdList[] = $rValue["categoryId"];
                $timezoneIdList[] = $rValue["timezoneId"];
                $cityIdList[] = $rValue["cityId"];
            }
            $categoryIdList = array_unique($categoryIdList);
            $timezoneIdList = array_unique($timezoneIdList);
            $cityIdList = array_unique($cityIdList);
            $timezoneData = array();
            $timezoneData = $timezoneHandler->timeZoneList(array('idList' => $timezoneIdList));
            if ($timezoneData['status'] && $timezoneData['response']['total'] > 0) {
                $timezoneData = commonHelperGetIdArray($timezoneData['response']['timeZoneList']);
            }
            $categoryData = array();
            $categoryData = $categoryHandler->getCategoryList(array('major' => 1));
            if ($categoryData['status'] && $categoryData['response']['total'] > 0) {
                $categoryData = commonHelperGetIdArray($categoryData['response']['categoryList']);
            }

            $cityListData = array();
            //Getting the City data
            if (count($cityIdList) > 0) {
                $cityListData = $cityHandler->getCityNames($cityIdList);
            }

            if ($cityListData['status'] == TRUE && count($cityListData['response']['cityName']) > 0) {
                $cityObject = $cityListData['response']['cityName'];
                $cityListData = commonHelperGetIdArray($cityListData['response']['cityName']);
            }

            $bookmarkEvents = array();
            $userId = $this->ci->customsession->getUserId();
            if ($userId != '') {
                $bookmarkInputs['userId'] = $userId;
                $bookmarkInputs['returnEventIds'] = true;
                $bookmarkEvents = $bookmarkHandler->getUserBookmarks($bookmarkInputs);
                $bookmarkEventsArray = array();
                if ($bookmarkEvents['status'] && $bookmarkEvents['response']['total'] > 0) {
                    $bookmarkEventsArray = $bookmarkEvents['response']['bookmarkedEvents'];
                }
            }

            foreach ($solrEventList as $recordKey => $recordValue) {
                $eventList[$recordKey]['id'] = $recordValue["id"];
                $eventList[$recordKey]['title'] = $recordValue["title"];
                if (isset($recordValue['thumbImage'])) {
                    $eventList[$recordKey]['thumbImage'] = (($recordValue['thumbImage']) && !empty($recordValue['thumbImage'])) ? $this->ci->config->item('images_content_cloud_path') . $recordValue["thumbImage"] : commonHelperDefaultImage($recordValue["thumbImage"], IMAGE_EVENT_LOGO);
                }
                $eventList[$recordKey]['defaultThumbImage'] = $this->ci->config->item('images_static_path') . DEFAULT_EVENT_THUMB_IMAGE;
                if (isset($recordValue['bannerImage'])) {
                    $eventList[$recordKey]['bannerImage'] = (($recordValue["bannerImage"]) && !empty($recordValue['bannerImage'])) ? $this->ci->config->item('images_content_cloud_path') . $recordValue["bannerImage"] : commonHelperDefaultImage($recordValue["bannerImage"], IMAGE_EVENT_LOGO);
                }

                $eventList[$recordKey]['startDate'] = allTimeFormats($recordValue["startDateTime"], 11);
                $eventList[$recordKey]['endDate'] = allTimeFormats($recordValue["endDateTime"], 11);
                $eventList[$recordKey]['venueName'] = $recordValue["venueName"];
                $eventList[$recordKey]['eventUrl'] = commonHelperEventDetailUrl($recordValue["url"]);

                if ($recordValue["categoryId"] > 0) {
                    $catDetails = $categoryData[$recordValue["categoryId"]];

                    $eventList[$recordKey]['categoryName'] = $catDetails['name'];
                    $eventList[$recordKey]['themeColor'] = $catDetails['themecolor'];
                } else {
                    $eventList[$recordKey]['categoryName'] = "";
                    $eventList[$recordKey]['themeColor'] = "";
                }
                $eventList[$recordKey]['timeZone'] = "";
                $timezoneId = $recordValue['timezoneId'];
                if ($timezoneId > 0) {
                    $eventList[$recordKey]['timeZone'] = $timezoneData[$timezoneId]['zone'];
                }
                //Getting the City data
                if ($recordValue['cityId'] > 0) {
                    if (isset($cityListData[$recordValue['cityId']]['name']))
                        $eventList[$recordKey]['cityName'] = $cityListData[$recordValue['cityId']]['name'];
                    else
                        $eventList[$recordKey]['cityName'] = "";
                }

                $eventList[$recordKey]['bookMarked'] = 0;
                if ($userId != '') {
                    if (in_array($recordValue["id"], $bookmarkEventsArray)) {
                        $eventList[$recordKey]['bookMarked'] = 1;
                    }
                }
            }
            if (!isset($inputArray['page'])) {
                $inputArray['page'] = 1;
            }
            if (!isset($solrInputArray['limit'])) {
                $solrInputArray['limit'] = 12;
            }

            if ((($solrResultArray["response"]["total"] / $solrInputArray['limit'])) > $inputArray['page']) {
                $nextPageStatus = true;
            }

            $output['status'] = TRUE;
            $output['response']['eventList'] = $eventList;
            $output['response']['page'] = $inputArray['page'];
            $output['response']['limit'] = isset($inputArray['limit']) ? $inputArray['limit'] : $solrInputArray['limit'];
            $output['response']['nextPage'] = $nextPageStatus;
            $output['response']['total'] = $solrResultArray["response"]["total"];
            $output['statusCode'] = STATUS_OK;
            return $output;
        } else {
            $output['status'] = TRUE;
            $output['response']['messages'][] = ERROR_NO_DATA;
            $output['response']['total'] = 0;
            $output['statusCode'] = STATUS_OK;
            return $output;
        }
    }

    // Registration Type For the Event in create/Edit

    public function getEventRegistrationType($data) {
        $registrationType = 2;
        $count = 0;
        if (empty($data) || $data == null) {
            $registrationType = 3;
            return $registrationType;
        }
        foreach ($data as $key => $value) {
            if ($value['type'] == 2 || $value['type'] == 3 || $value['type'] == 4) {
                $count++;
            }
        }
        if ($count == 0) {
            $registrationType = 1;
            return $registrationType;
        }
        return $registrationType;
    }

    public function getOrganizationEvents($inputArray) {
        $fileHandler = new File_handler();
        $categoryHandler = new Category_handler();
        $timezoneHandler = new Timezone_handler();
        $page = 0;
        if (isset($inputArray['page']) && $inputArray['page'] > 0) {
            $page = ORGANIZATION_EVENTS_DISPLAY_LIMIT * $inputArray['page'];
        }
        $this->ci->form_validation->pass_array($inputArray);
        $this->ci->form_validation->set_rules('userids', 'User Ids', 'required_strict|is_array');
        if ($this->ci->form_validation->run() === FALSE) {
            $errorMessages = $this->ci->form_validation->get_errors();
            $output['status'] = FALSE;
            $output['response']['messages'][] = $errorMessages['message'];
            $output['statusCode'] = STATUS_BAD_REQUEST;
            return $output;
        }
        $where_in = array();
        $this->ci->Event_model->resetVariable();
        $selectInput['eventId'] = $this->ci->Event_model->id;
        $selectInput['eventName'] = $this->ci->Event_model->title;
        $selectInput['venuename'] = $this->ci->Event_model->venuename;
        $selectInput['url'] = $this->ci->Event_model->url;
        $selectInput['logoId'] = $this->ci->Event_model->logo;
        $selectInput['eventStartDate'] = $this->ci->Event_model->startdatetime;
        $selectInput['eventEndDate'] = $this->ci->Event_model->enddatetime;
        $selectInput['timezoneid'] = $this->ci->Event_model->timezoneid;
        $selectInput['categoryid'] = $this->ci->Event_model->categoryid;
        if (isset($inputArray['gettotal'])) {
            $selectInput = array();
            $selectInput['totalcount'] = 'count(id)';
        }
        $order = 'DESC';
        $this->ci->Event_model->setSelect($selectInput);
        $where_in[$this->ci->Event_model->ownerid] = $inputArray['userids'];

        if ($inputArray['type'] == 'upcoming') {
            $order = 'ASC';
            $where[$this->ci->Event_model->enddatetime . " > "] = allTimeFormats('', 11);
        } else {
            $where[$this->ci->Event_model->enddatetime . " < "] = allTimeFormats('', 11);
        }
        $where[$this->ci->Event_model->deleted] = 0;
        $where[$this->ci->Event_model->status] = 1;
        $this->ci->Event_model->setWhere($where);
        $this->ci->Event_model->setWhereIns($where_in);

        if (!isset($inputArray['gettotal'])) {
            $this->ci->Event_model->setRecords(ORGANIZATION_EVENTS_DISPLAY_LIMIT, $page);
        }
        $this->ci->Event_model->setOrderBy($orderBy = array());
        $orderBy[] = 'YEAR(' . $this->ci->Event_model->startdatetime . ') ' . $order;
        $orderBy[] = " Month( " . $this->ci->Event_model->startdatetime . ") " . $order;
        $orderBy[] = $this->ci->Event_model->status . " desc ";
        $orderBy[] = " Date( " . $this->ci->Event_model->startdatetime . ") " . $order;
        $this->ci->Event_model->setOrderBy($orderBy);
        $eventDetails = $this->ci->Event_model->get();
        if (isset($inputArray['gettotal'])) {
            $output['status'] = TRUE;
            $output['statusCode'] = STATUS_OK;
            $output['response']['totalCount'] = $eventDetails[0]['totalcount'];
            $output['response']['messages'][] = '';
            return $output;
        }
        if (count($eventDetails) == 0) {
            $output['status'] = TRUE;
            $output['statusCode'] = STATUS_OK;
            $output['response']['total'] = 0;
            $output['response']['messages'][] = ERROR_NO_RECORDS;
            return $output;
        }

        if (count($eventDetails) > 0) {
            $eventIdArray = commonHelperGetIdArray($eventDetails, 'eventId');
            $eventIds = array_keys($eventIdArray);
            $timezoneArray = commonHelperGetIdArray($eventIdArray, 'timezoneid');
            $categoryArray = commonHelperGetIdArray($eventIdArray, 'categoryid');
            $logosArray = commonHelperGetIdArray($eventIdArray, 'logoId');
            $logosArray = array_keys($logosArray);
            $timezoneIdsArray = array_keys($timezoneArray);
            $categoryidsArray = array_keys($categoryArray);
            $input = array('categoryidsArray' => '1', 'major' => '0');
            $catDetails = $categoryHandler->getCategoryList($input);
            $fileData = $fileHandler->getFileData(array('id', $logosArray));
            $fileDataTemp = array();
            if ($fileData['status'] && $fileData['response']['total'] > 0) {
                $fileDataTemp = commonHelperGetIdArray($fileData['response']['fileData']);
            }
            $categoryIdsArray = commonHelperGetIdArray($catDetails['response']['categoryList'], 'id');

            foreach ($timezoneIdsArray as $timezoneid) {
                $timezoneDetails = $timezoneHandler->details(array('timezoneId' => $timezoneid));
                if ($timezoneDetails['status'] && count($timezoneDetails) > 0) {
                    $locationDetails['id'] = $timezoneDetails['response']['detail'][$timezoneid]['id'];
                    $locationDetails['timeZone'] = $timezoneDetails['response']['detail'][$timezoneid]['zone'];
                    $locationDetails['timeZoneName'] = $timezoneDetails['response']['detail'][$timezoneid]['name'];
                }
            }
            //        echo "<pre>";print_r($categoryIdsArray);exit;
            foreach ($eventIdArray as $eventid => $data) {
                $thumbnailpath = $fileDataTemp[$data['logoId']]['path'];
                if (isset($thumbnailpath) && $data['categoryid'] > 0) {
                    $thumbnailpath = $this->ci->config->item('images_content_cloud_path') . $thumbnailpath;
                } else {
                    $thumbnailpath = $categoryIdsArray[$data['categoryid']]['categorydefaultthumbnailid'];
                }
                $eventDataArray[$eventid]['eventId'] = $eventid;
                $eventDataArray[$eventid]['title'] = $data['eventName'];
                $eventDataArray[$eventid]['url'] = commonHelperEventDetailUrl($data["url"]);
                $eventDataArray[$eventid]['logoPath'] = $thumbnailpath;
                $eventDataArray[$eventid]['defaultlogoPath'] = $categoryIdsArray[$data['categoryid']]['categorydefaultthumbnailid'];
                ;
                $eventDataArray[$eventid]['venue'] = $data['venuename'];
                $eventDataArray[$eventid]['startDateTime'] = allTimeFormats(convertTime($data['eventStartDate'], $locationDetails['timeZoneName'], true), 8) . " " . allTimeFormats(convertTime($data['eventStartDate'], $locationDetails['timeZoneName'], true), 4);
                $eventDataArray[$eventid]['endDateTime'] = allTimeFormats(convertTime($data['eventEndDate'], $locationDetails['timeZoneName'], true), 8) . " " . allTimeFormats(convertTime($data['eventEndDate'], $locationDetails['timeZoneName'], true), 4);
                foreach ($categoryIdsArray as $catid => $catdata) {
                    if ($data['categoryid'] == $catdata['id']) {
                        $eventIdArray[$eventid]['categoryName'] = $catdata['name'];
                        $eventDataArray[$eventid]['categoryName'] = $catdata['name'];
                    }
                }
                if ($locationDetails['id'] == $data['timezoneid']) {
                    $eventIdArray[$eventid]['timeZoneName'] = $locationDetails['timeZoneName'];
                    $eventIdArray[$eventid]['timeZone'] = $locationDetails['timeZone'];
                }
            }
            $output['status'] = TRUE;
            $output['statusCode'] = STATUS_OK;
            $output['response']['eventDetails'] = $eventDataArray;
            $output['response']['total'] = count($eventDataArray);
            $output['response']['messages'][] = '';
            return $output;
        }
    }

    public function getOrganizationEventsCount($inputArray) {
        $this->ci->form_validation->pass_array($inputArray);
        $this->ci->form_validation->set_rules('userids', 'User Ids', 'required_strict|is_array');
        if ($this->ci->form_validation->run() === FALSE) {
            $errorMessages = $this->ci->form_validation->get_errors();
            $output['status'] = FALSE;
            $output['response']['messages'][] = $errorMessages['message'];
            $output['statusCode'] = STATUS_BAD_REQUEST;
            return $output;
        }
        $where_in = array();
        $this->ci->Event_model->resetVariable();
        $selectInput['totalcount'] = 'count(id)';
        $this->ci->Event_model->setSelect($selectInput);
        $where_in[$this->ci->Event_model->ownerid] = $inputArray['userids'];

        if ($inputArray['type'] == 'upcoming') {
            $order = 'ASC';
            $where[$this->ci->Event_model->enddatetime . " > "] = allTimeFormats('', 11);
        } else {
            $where[$this->ci->Event_model->enddatetime . " < "] = allTimeFormats('', 11);
        }
        $where[$this->ci->Event_model->deleted] = 0;
        $where[$this->ci->Event_model->status] = 1;
        $this->ci->Event_model->setWhere($where);
        $this->ci->Event_model->setWhereIns($where_in);
        $this->ci->Event_model->setRecords(ORGANIZATION_EVENTS_DISPLAY_LIMIT, $page);
        $eventDetails = $this->ci->Event_model->get();
        if (!empty($eventDetails)) {
            $output['status'] = TRUE;
            $output['statusCode'] = STATUS_OK;
            $output['response']['totalCount'] = $eventDetails[0]['totalcount'];
            $output['response']['messages'][] = '';
            return $output;
        }
        $output['status'] = FALSE;
        $output['response']['messages'][] = ERROR_INTERNAL_DB_ERROR;
        $output['statusCode'] = STATUS_SERVER_ERROR;
        return $output;
    }

    //To check the passed event id related to particular organizer or not
    public function isOrganizerForEvent($inputArray) {
        $this->ci->form_validation->pass_array($inputArray);
        $this->ci->form_validation->set_rules('userId', 'User Id', 'required_strict|is_natural_no_zero');
        $this->ci->form_validation->set_rules('eventId', 'Event Id', 'required_strict|is_natural_no_zero');
        if ($this->ci->form_validation->run() === FALSE) {
            $errorMessages = $this->ci->form_validation->get_errors();
            $output['status'] = FALSE;
            $output['response']['messages'][] = $errorMessages['message'];
            $output['statusCode'] = STATUS_BAD_REQUEST;
            return $output;
        }
        $where = array();
        $this->ci->Event_model->resetVariable();
        $selectInput['totalcount'] = 'count(id)';
        $this->ci->Event_model->setSelect($selectInput);
        $where[$this->ci->Event_model->ownerid] = $inputArray['userId'];
        $where[$this->ci->Event_model->id] = $inputArray['eventId'];

        $this->ci->Event_model->setWhere($where);


        $eventDetails = $this->ci->Event_model->get();
        if (!empty($eventDetails)) {
            $output['status'] = TRUE;
            $output['statusCode'] = STATUS_OK;
            $output['response']['totalCount'] = $eventDetails[0]['totalcount'];
            $output['response']['messages'][] = NO_EVENTS_SUCH_USER;
            return $output;
        }
        $output['status'] = FALSE;
        $output['response']['messages'][] = ERROR_INTERNAL_DB_ERROR;
        $output['statusCode'] = STATUS_SERVER_ERROR;
        return $output;
    }

    public function getSimpleEventDetails($request) {

        $this->ci->load->model('Eventdetail_model');
        $output = array();

        $validationStatus = $this->eventDetailValidation($request);
        if ($validationStatus['error'] == TRUE) {
            $output['status'] = FALSE;
            $output['response']['messages'] = $validationStatus['message'];
            $output['statusCode'] = STATUS_BAD_REQUEST;
            return $output;
        }
        $this->ci->Event_model->resetVariable();
        $selectInput['id'] = $this->ci->Event_model->id;
        $selectInput['ownerId'] = $this->ci->Event_model->ownerid;
        $selectInput['startDate'] = $this->ci->Event_model->startdatetime;
        $selectInput['endDate'] = $this->ci->Event_model->enddatetime;
        $selectInput['title'] = $this->ci->Event_model->title;
        $selectInput['countryId'] = $this->ci->Event_model->countryid;
        $selectInput['stateId'] = $this->ci->Event_model->stateid;
        $selectInput['cityId'] = $this->ci->Event_model->cityid;
        $selectInput['localityId'] = $this->ci->Event_model->localityid;
        $selectInput['venuename'] = $this->ci->Event_model->venue;
        $selectInput['url'] = $this->ci->Event_model->url;
        $selectInput['thumbnailfileid'] = $this->ci->Event_model->logo;
        $selectInput['bannerfileid'] = $this->ci->Event_model->banner;
        $selectInput['categoryId'] = $this->ci->Event_model->categoryid;
        $selectInput['subcategoryId'] = $this->ci->Event_model->subcategoryid;
        $selectInput['pincode'] = $this->ci->Event_model->pincode;
        $selectInput['registrationType'] = $this->ci->Event_model->registrationtype;
        $selectInput['eventMode'] = $this->ci->Event_model->eventmode;
        $selectInput['timeZoneId'] = $this->ci->Event_model->timezoneid;
        $selectInput['venueName'] = $this->ci->Event_model->venuename;
        $selectInput['venueaddress1'] = $this->ci->Event_model->venueaddress1;
        $selectInput['venueaddress2'] = $this->ci->Event_model->venueaddress2;
        $selectInput['private'] = $this->ci->Event_model->private;
        $selectInput['status'] = $this->ci->Event_model->status;

        $this->ci->Event_model->setSelect($selectInput);
        $where[$this->ci->Event_model->id] = $request['eventId'];

        if (isset($request['userId']) && $request['userId'] > 0) {   // if user id is set then add condition
            $where[$this->ci->Event_model->ownerid] = $request['userId'];
        }

        $where[$this->ci->Event_model->deleted] = 0;
        $this->ci->Event_model->setWhere($where);
        $eventDetailsResponse = $this->ci->Event_model->get();
        if (count($eventDetailsResponse) == 0) {
            $output['status'] = FALSE;
            $output['response']['messages'][] = ERROR_NO_DATA;
            $output['response']['total'] = 0;
            $output['statusCode'] = STATUS_OK;
            return $output;
        } else {
            $eventDetails = $eventDetailsResponse[0];

            $output['status'] = TRUE;
            $output['response']['messages'] = array();
            $output['response']['total'] = 1;
            $output['response']['details'] = $eventDetails;
            $output['response']['details']['location'] = $locationDetails;
            $output['statusCode'] = STATUS_OK;
            return $output;
        }
    }

    //To Get the evetnts list from solr search, for sitemap
    public function getSitemapEventList($inputs) {
        //$categoryHandler = new Category_handler();
        //$cityHandler = new City_handler();
        $timezoneHandler = new Timezone_handler();
        //$bookmarkHandler = new Bookmark_handler();
        $output = array();
        $nextPageStatus = false;
        if (isset($inputs['page']) && $inputs['page'] <= 0) {
            $inputs['page'] = 1;
        }
        if (isset($inputs['eventIdsArray']) && count($inputs['eventIdsArray']) > 0) {
            
        }


        //Feacth the solr search results
        $solrHandler = new Solr_handler();

        $solrInputArray = array();
        if (!isset($inputs['status'])) {
            $inputs['status'] = 1;
        }

        $solrInputArray = $this->solrArray($inputs);
        if (!isset($solrInputArray['day'])) {
            $solrInputArray['day'] = 6;
        }


        if (isset($inputs['eventMode'])) {
            $solrInputArray['eventMode'] = $inputs['eventMode'];
        }

        if (!isset($inputs['private'])) {
            $solrInputArray['private'] = 0;
        }
        if (!isset($inputs['status'])) {
            $solrInputArray['status'] = 1;
        }
        if (isset($inputs['ticketSoldout'])) {
            $solrInputArray['ticketSoldout'] = $inputs['ticketSoldout'];
        }
		
		if (isset($inputs['year'])) {
            $solrInputArray['year'] = $inputs['year'];
        }
		
		if (isset($inputs['categoryId'])) {
            $solrInputArray['categoryId'] = $inputs['categoryId'];
        }
		

        $solrInputArray['selectFields'] = array("id", "url", "cts", "mts");

        $solrResults = $solrHandler->getSitemapSolrEvents($solrInputArray);


        //The 2nd parameter convert json_decode to array
        $solrResults = json_decode($solrResults, true);

        //print_r($solrResults);
        //solr level validations
        if ((isset($solrResults["response"]["error"])) && $solrResults["response"]["error"] == true) {
            return $solrResults;
        }

        $eventList = array();

        $solrEventList = $solrResults["response"]["events"];
        if (count($solrEventList) > 0) {

            $categoryIdList = array();
            $timezoneIdList = array();
            $cityIdList = array();

            foreach ($solrEventList as $recordKey => $recordValue) {
                $eventList[$recordKey]['id'] = $recordValue["id"];

                if (isset($recordValue["mts"])) {
                    //$eventList[$recordKey]['omts'] = $recordValue["mts"];
                    $eventList[$recordKey]['mts'] = allTimeFormats($recordValue["mts"], 11);
                } else {
                    //$eventList[$recordKey]['omts'] = $recordValue["cts"];
                    $eventList[$recordKey]['mts'] = allTimeFormats($recordValue["cts"], 11);
                }


                $eventList[$recordKey]['eventUrl'] = commonHelperEventDetailUrl($recordValue["url"]);
            }
            if (!isset($inputs['page'])) {
                $inputs['page'] = 1;
            }
            if (!isset($solrInputArray['limit'])) {
                $solrInputArray['limit'] = 12;
            }

            if ((($solrResults["response"]["total"] / $solrInputArray['limit'])) > $inputs['page']) {
                $nextPageStatus = true;
            }


            //print_r($eventList);

            $output['status'] = TRUE;
            $output['response']['eventList'] = $eventList;
            $output['response']['page'] = $inputs['page'];
            $output['response']['limit'] = isset($inputs['limit']) ? $inputs['limit'] : $solrInputArray['limit'];
            $output['response']['nextPage'] = $nextPageStatus;
            $output['response']['total'] = $solrResults["response"]["total"];
            $output['statusCode'] = STATUS_OK;
            return $output;
        } else {
            $output['status'] = TRUE;
            $output['response']['messages'][] = ERROR_NO_DATA;
            $output['response']['total'] = 0;
            $output['statusCode'] = STATUS_OK;
            return $output;
        }
    }

    /* updating event view count */

    public function updateEventViewCount($eventId) {
        $this->ci->load->model('Eventdetail_model');
        $this->ci->Eventdetail_model->resetVariable();

        $updateEventViewCountData[$this->ci->Eventdetail_model->viewcount] = ('`viewcount`+1');
        $updateEventViewCountData['mts'] = '`mts`';
        $updateEventViewCountData['modifiedby'] = '`modifiedby`';
        $where['eventid'] = $eventId;

        $this->ci->Eventdetail_model->setInsertUpdateData($updateEventViewCountData);
        $this->ci->Eventdetail_model->setWhere($where);
        $this->ci->Eventdetail_model->update_set_data();
    }
    
    
	
	//To Get the holi/newyear/dandiya evetnts list from solr search
    public function getMEDynamicMicrositesEventList($inputs) {
		
		//print_r($inputs); exit;
		
        //$categoryHandler = new Category_handler();
        $cityHandler = new City_handler();
        $timezoneHandler = new Timezone_handler();
        //$bookmarkHandler = new Bookmark_handler();
		$output = array();
		
		
		/*$this->ci->form_validation->pass_array($inputs);
        $this->ci->form_validation->set_rules('eventIdsArray', 'event ids', 'required_strict|is_array');
        if ($this->ci->form_validation->run() == FALSE) {
            $response = $this->ci->form_validation->get_errors();
            $output['status'] = FALSE;
            $output['response']['messages'] = $response['message'];
            $output['statusCode'] = STATUS_BAD_REQUEST;
            return $output;
        }*/
		
        
		
        $nextPageStatus = false;
        if (isset($inputs['page']) && $inputs['page'] <= 0) {
            $inputs['page'] = 1;
        }
        

        //Feacth the solr search results
        $solrHandler = new Solr_handler();

        $solrInputArray = array();
        if (!isset($inputs['status'])) {
            $inputs['status'] = 1;
        }

        $solrInputArray = $this->solrArray($inputs);
        if (!isset($solrInputArray['day'])) {
            $solrInputArray['day'] = 6;
        }


        if (isset($inputs['eventMode'])) {
            $solrInputArray['eventMode'] = $inputs['eventMode'];
        }

        if (!isset($inputs['private'])) {
            $solrInputArray['private'] = 0;
        }
        if (!isset($inputs['status'])) {
            $solrInputArray['status'] = 1;
        }
        if (isset($inputs['ticketSoldout'])) {
            $solrInputArray['ticketSoldout'] = $inputs['ticketSoldout'];
        }
		
		
		if (isset($inputs['categoryId'])) {
            $solrInputArray['categoryId'] = $inputs['categoryId'];
        }
		
		if (isset($inputs['subcategoryId'])) {
            $solrInputArray['subcategoryId'] = $inputs['subcategoryId'];
        }
		

        //$solrInputArray['selectFields'] = array("id", "url", "cts", "mts");

        $solrResults = $solrHandler->getSolrEvents($solrInputArray);


        //The 2nd parameter convert json_decode to array
        $solrResults = json_decode($solrResults, true);

        //print_r($solrResults);
        //solr level validations
        if ((isset($solrResults["response"]["error"])) && $solrResults["response"]["error"] == true) {
            return $solrResults;
        }
		
		
		
		
		

        $eventList = array();

        $solrEventList = $solrResults["response"]["events"];
        if (count($solrEventList) > 0) {
            //$categoryIdList = array();
            $timezoneIdList = array();
            $cityIdList = array();
            foreach ($solrEventList as $rKey => $rValue) {
                //$categoryIdList[] = $rValue["categoryId"];
                $timezoneIdList[] = $rValue["timezoneId"];
                $cityIdList[] = $rValue["cityId"];
            }
            //$categoryIdList = array_unique($categoryIdList);
            $timezoneIdList = array_unique($timezoneIdList);
            $cityIdList = array_unique($cityIdList);
            $timezoneData = array();
            $timezoneData = $timezoneHandler->timeZoneList(array('idList' => $timezoneIdList));
            if ($timezoneData['status'] && $timezoneData['response']['total'] > 0) {
                $timezoneData = commonHelperGetIdArray($timezoneData['response']['timeZoneList']);
            }
            
            $cityListData = array();

            //Getting the City data
            if (count($cityIdList) > 0) {
                $cityListData = $cityHandler->getCityNames($cityIdList);
            }

            if ($cityListData['status'] == TRUE && count($cityListData['response']['cityName']) > 0) {
                $cityObject = $cityListData['response']['cityName'];
                $cityListData = commonHelperGetIdArray($cityListData['response']['cityName']);
            }

            

            foreach ($solrEventList as $recordKey => $recordValue) {
                $eventList[$recordKey]['id'] = $recordValue["id"];
                $eventList[$recordKey]['title'] = $recordValue["title"];
                if (isset($recordValue['thumbImage']) && $recordValue['thumbImage'] != '') {
                    $eventList[$recordKey]['thumbImage'] = $this->ci->config->item('images_content_cloud_path') . $recordValue["thumbImage"];
                    /* $eventList[$recordKey]['thumbImage'] = $this->ci->config->item('images_content_cloud_path') . $recordValue["thumbImage"];
                      $eventList[$recordKey]['thumbImage'] = commonHelperDefaultImage($eventList[$recordKey]['thumbImage'], IMAGE_EVENT_LOGO); */
                } else {
                    $eventList[$recordKey]['thumbImage'] = '';
                }
                //$eventThumb = $this->ci->config->item('event_default_thumb');
                //$eventList[$recordKey]['defaultThumbImage'] = $this->ci->config->item('images_static_path') . DEFAULT_EVENT_THUMB_IMAGE;
                // $eventList[$recordKey]['defaultThumbImage'] = $eventThumb[$categoryData[$recordValue["categoryId"]]['name']];
                if (isset($recordValue['bannerImage']) && $recordValue['bannerImage'] != '') {
                    $eventList[$recordKey]['bannerImage'] = $this->ci->config->item('images_content_cloud_path') . $recordValue["bannerImage"];
                    /* $eventList[$recordKey]['bannerImage'] = ($recordValue["bannerImage"]) ? $this->ci->config->item('images_content_cloud_path') . $recordValue["bannerImage"] : "";
                      $eventList[$recordKey]['bannerImage'] = commonHelperDefaultImage($eventList[$recordKey]['bannerImage'], IMAGE_EVENT_LOGO); */
                } else {
                    $eventList[$recordKey]['bannerImage'] = '';
                }

                $eventList[$recordKey]['startDate'] = allTimeFormats($recordValue["startDateTime"], 11);
                $eventList[$recordKey]['endDate'] = allTimeFormats($recordValue["endDateTime"], 11);
                $eventList[$recordKey]['venueName'] = $recordValue["venueName"];
                $eventList[$recordKey]['eventUrl'] = commonHelperEventDetailUrl($recordValue["url"]);

                

                if ($eventList[$recordKey]['thumbImage'] == '') {
                    $eventList[$recordKey]['thumbImage'] = $catDetails['categorydefaultthumbnailid'];
                }
                if ($eventList[$recordKey]['bannerImage'] == '') {
                    $eventList[$recordKey]['bannerImage'] = $catDetails['categorydefaultbannerid'];
                }
                $eventList[$recordKey]['defaultBannerImage'] = $catDetails['categorydefaultbannerid'];
                $eventList[$recordKey]['defaultThumbImage'] = $catDetails['categorydefaultthumbnailid'];
                $eventList[$recordKey]['timeZone'] = "";
                $timezoneId = $recordValue['timezoneId'];
                if ($timezoneId > 0) {
                    $eventList[$recordKey]['timeZone'] = $timezoneData[$timezoneId]['zone'];
                }
                
                //Getting the City data
                if ($recordValue['cityId'] > 0) {
                    if (isset($cityListData[$recordValue['cityId']]['name']))
                        $eventList[$recordKey]['cityName'] = $cityListData[$recordValue['cityId']]['name'];
                    else
                        $eventList[$recordKey]['cityName'] = "";
                }
                
            }
            if (!isset($inputs['page'])) {
                $inputs['page'] = 1;
            }
            if (!isset($solrInputArray['limit'])) {
                $solrInputArray['limit'] = 12;
            }

            if ((($solrResults["response"]["total"] / $solrInputArray['limit'])) > $inputs['page']) {
                $nextPageStatus = true;
            }

            $output['status'] = TRUE;
            $output['response']['eventList'] = $eventList;
            $output['response']['page'] = $inputs['page'];
            $output['response']['limit'] = isset($inputs['limit']) ? $inputs['limit'] : $solrInputArray['limit'];
            $output['response']['nextPage'] = $nextPageStatus;
            $output['response']['total'] = $solrResults["response"]["total"];
        }
        else {
            $output['status'] = TRUE;
            $output['response']['messages'][] = ERROR_NO_DATA;
            $output['response']['total'] = 0;

            $output['statusCode'] = STATUS_OK;
            return $output;
        }
    }
    
    //To Bring the specific event related information for true semantic
    public function getEventDetailsTrueSemantic($request) {
        $countryHandler = new Country_handler();
        $stateHandler = new State_handler();
        $cityHandler = new City_handler();
        $timezoneHandler = new Timezone_handler();
        $output = array();

        $validationStatus = $this->eventDetailValidation($request);
        if ($validationStatus['error'] == TRUE) {
            $output['status'] = FALSE;
            $output['response']['messages'] = $validationStatus['message'];
            $output['statusCode'] = STATUS_BAD_REQUEST;
            return $output;
        }
        $this->ci->Event_model->resetVariable();
        
        $selectInput['id'] = $this->ci->Event_model->id;
        $selectInput['ownerId'] = $this->ci->Event_model->ownerid;
        $selectInput['startDate'] = $this->ci->Event_model->startdatetime;
        $selectInput['endDate'] = $this->ci->Event_model->enddatetime;
        $selectInput['title'] = $this->ci->Event_model->title;
        $selectInput['countryId'] = $this->ci->Event_model->countryid;
        $selectInput['stateId'] = $this->ci->Event_model->stateid;
        $selectInput['cityId'] = $this->ci->Event_model->cityid;
        $selectInput['localityId'] = $this->ci->Event_model->localityid;
        $selectInput['venuename'] = $this->ci->Event_model->venue;
        $selectInput['categoryId'] = $this->ci->Event_model->categoryid;
        $selectInput['subcategoryId'] = $this->ci->Event_model->subcategoryid;
        $selectInput['pincode'] = $this->ci->Event_model->pincode;
        $selectInput['registrationType'] = $this->ci->Event_model->registrationtype;
        $selectInput['eventMode'] = $this->ci->Event_model->eventmode;
        $selectInput['timeZoneId'] = $this->ci->Event_model->timezoneid;
        $selectInput['venueName'] = $this->ci->Event_model->venuename;
        $selectInput['venueaddress1'] = $this->ci->Event_model->venueaddress1;
        $selectInput['venueaddress2'] = $this->ci->Event_model->venueaddress2;

        $where[$this->ci->Event_model->id] = $request['eventId'];
        $where[$this->ci->Event_model->deleted] = 0;
        
        $this->ci->Event_model->setSelect($selectInput);
        $this->ci->Event_model->setWhere($where);
        
        $eventDetailsResponse = $this->ci->Event_model->get();
        if (count($eventDetailsResponse) == 0) {
            $output['status'] = TRUE;
            $output['response']['messages'][] = ERROR_NO_DATA;
            $output['response']['total'] = 0;
            $output['statusCode'] = STATUS_OK;
            return $output;
        } else {
            $eventDetails = $eventDetailsResponse[0];
            
            $eventDetails['categoryName'] = "";
            //Getting the Category data
            if (!empty($eventDetails["categoryId"])) {
                $catDetails = $this->getcategoryDetails($eventDetails["categoryId"]);
                $eventDetails['categoryName'] = $catDetails['name'];
            }
            
            //Getting the Subcategory details
            if (!empty($eventDetails["subcategoryId"])) {
                $this->subcategoryHandler = new Subcategory_handler();
                $subcatDetails = $this->subcategoryHandler->getSubcategoryDetails($eventDetails);
                if ($subcatDetails['status'] == TRUE && $subcatDetails['response']['total'] > 0) {

                    $eventDetails['subCategoryName'] = $subcatDetails['response']['subCategoryList'][0]['name'];
                }
            }            
            $countryData = $stateData = $cityData = array();
            //Getting the Country data
            if ($eventDetails['countryId'] > 0) {
                $countryData = $countryHandler->getCountryListById(array('countryId' => $eventDetails['countryId']));
            }
            if (count($countryData) > 0 && $countryData['status'] && $countryData['response']['total'] > 0) {
                $locationDetails['countryId'] = $eventDetails['countryId'];
                $locationDetails['countryName'] = $countryData['response']['detail']['name'];
            }

            //Getting the State data
            if ($eventDetails['stateId'] > 0) {
                $stateData = $stateHandler->getStateListById(array('stateId' => $eventDetails['stateId'], 'nostatus' => true));
            }
            if (count($stateData) > 0 && $stateData['status'] && $stateData['response']['total'] > 0) {
                $stateList = $stateData['response']['stateList'][0];
                $locationDetails['stateId'] = $eventDetails['stateId'];
                $locationDetails['stateName'] = $stateList['name'];
            }

            //Getting the City data
            if ($eventDetails['cityId'] > 0) {
                $request['cityId'] = $eventDetails['cityId'];
                $request['countryId'] = $eventDetails['countryId'];
                $cityData = $cityHandler->getCityDetailById($request);
            }
            if (count($cityData) > 0 && $cityData['status'] && $cityData['response']['total'] > 0) {
                $cityObject = $cityData['response']['detail'];
                $locationDetails['cityId'] = $eventDetails['cityId'];
                $locationDetails['cityName'] = $cityObject['name'];
            }


            //Preparing the venue details 
            $locationDetails['venueName'] = $eventDetails['venuename'];
            $locationDetails['address1'] = $eventDetails['venueaddress1'];
            $locationDetails['address2'] = $eventDetails['venueaddress2'];
            $locationDetails['pincode'] = $eventDetails['pincode'];


            $timezoneDetails = $timezoneHandler->details(array('timezoneId' => $eventDetails['timeZoneId']));
            if ($timezoneDetails['status'] && count($timezoneDetails) > 0) {
                $locationDetails['timeZone'] = $timezoneDetails['response']['detail'][$eventDetails['timeZoneId']]['zone'];
                $locationDetails['timeZoneName'] = $timezoneDetails['response']['detail'][$eventDetails['timeZoneId']]['name'];
            }
            //unseting the un wanted field names
            unset($eventDetails['stateId']);
            unset($eventDetails['cityId']);
            unset($eventDetails['venuename']);
            unset($eventDetails['venueaddress1']);
            unset($eventDetails['venueaddress2']);
            unset($eventDetails['countryId']);

            $output['status'] = TRUE;
            $output['response']['messages'] = array();
            $output['response']['total'] = 1;
            $output['response']['details'] = $eventDetails;
            $output['response']['details']['location'] = $locationDetails;
            $output['statusCode'] = STATUS_OK;
            return $output;
        }
    }
    public function ticketSoldout($data){
        $timezoneInput['eventId']=$data['eventId'];
        $timezoneResponse=$this->getEventTimeZoneName($timezoneInput);
        foreach($data['tickets'] as $key=>$value){           
            $endTime = convertTime(($value['endDate'].' '.$value['endTime']),$timezoneResponse['response']['details']['location']['timeZoneName']); 
            if(($endTime>allTimeFormats('', 11)) && ((isset($value['totalSoldTickets']))?($value['totalSoldTickets']<$value['quantity']):1) && ((isset($value['soldOut']))?($value['soldOut']==0):1) && ($value['displayStatus']==1)){
                $totalSoldout=0;
                break;
            }else{
                $totalSoldout=1;
            }
        }
        return $totalSoldout;
    }
    
    public function  removeEventFileData($deleteArray){
        $this->ci->load->model('File_model');
        $this->ci->File_model->resetVariable();
        $update[$this->ci->File_model->deleted] =$deleteArray['deleted'];
        $where[$this->ci->File_model->id]=$deleteArray['id'];
        $this->ci->File_model->setWhere($where);
        $this->ci->File_model->setInsertUpdateData($update);
        $response = $this->ci->File_model->update_data();
        return $response;
    }

    //To update mobile and standard api's in solr for the given eventid
     function solrAPIStatus($data){
        
        $this->ci->form_validation->reset_form_rules();
        $this->ci->form_validation->pass_array($data);
        $this->ci->form_validation->set_rules('eventId', 'Event Id', 'is_natural_no_zero|required_strict');
        $this->ci->form_validation->set_rules('keyValue', 'Key Value', 'is_natural_no_zero|required_strict');

        if ($this->ci->form_validation->run() === FALSE) {
            $output['status'] = FALSE;
           $output['response']['messages'] = $this->ci->form_validation->get_errors();
            $output['statusCode'] = STATUS_BAD_REQUEST;
            $output['response']['status'] = FALSE;
            return $output;
}
         //check the user status & event status
        $eventData = $this->eventExists($data);


        $inputArray['userIds'] = $data['keyValue'];
        $this->userHandler = new User_handler();
        $userData = $this->userHandler->getUserInfo($inputArray);
        //Before changing the solr event status checking admin user status 
        //& event status in the db
        if ($userData['status'] && $eventData['status']) {
            
            $solrArray['id'] = $data['eventId'];
            
            if ($data['type'] == 1) {
                $solrArray['isStandardApiVisible'] = $data['status'];
            }
            if ($data['type'] == 2) {
                $solrArray['isMobileApiVisible'] = $data['status'];
            }

            $solrHandler = new Solr_handler();
            $addEventInSolr = $solrHandler->solrUpdateEvent($solrArray);
            if ($addEventInSolr['status']) {
                $output['status'] = TRUE;
                $output['response']['messages'][] = SUCCESS_UPDATED;
                $output['response']['statusUpdated'] = 'Success';
                $output['statusCode'] = STATUS_OK;
                return $output;
            } else {
                $output['status'] = FALSE;
                $output['response']['messages'][] = ERROR_SOMETHING_WENT_WRONG;
                $output['response']['statusUpdated'] = 'Failed';
                $output['statusCode'] = STATUS_BAD_REQUEST;
                return $output;
            }
        }
       
        $output['status'] = FALSE;
        $output['response']['messages'][] = ERROR_NO_SESSION;
        $output['statusCode'] = STATUS_BAD_REQUEST;
        return $output;
    }

    public function deleteRequest($inputArray){
        $this->ci->form_validation->reset_form_rules();
        $this->ci->form_validation->pass_array($inputArray);
        $this->ci->form_validation->set_rules('eventId', 'event id', 'required_strict|is_natural_no_zero');
        if ($this->ci->form_validation->run() == FALSE) {
            $response = $this->ci->form_validation->get_errors('message');
            $output['status'] = FALSE;
            $output['response']['messages'] = $response['message'];
            $output['statusCode'] = STATUS_BAD_REQUEST;
            return $output;
        }
        $this->ci->Event_model->resetVariable();
        $select[$this->ci->Event_model->id] =$this->ci->Event_model->id;       
        $where[$this->ci->Event_model->id] = $inputArray['eventId'];
        $where[$this->ci->Event_model->deleterequest]= 1;
        $this->ci->Event_model->setSelect($select);
        $this->ci->Event_model->setWhere($where);
        $response = $this->ci->Event_model->get();
        if(count($response)>0){
            $output['status'] = FALSE;
            $output['response']['messages'][] = DELREQUEST_SENT_ALREADY;
            $output['statusCode'] = STATUS_BAD_REQUEST;
            return $output;
        }
        $this->ci->Event_model->startTransaction();
        $this->ci->Event_model->resetVariable();
        $eventData['deleterequest'] = 1;       
        $where = array($this->ci->Event_model->id => $inputArray['eventId']);
        $this->ci->Event_model->setInsertUpdateData($eventData);
        $this->ci->Event_model->setWhere($where);
        $response = $this->ci->Event_model->update_data();
        if($response){//Trigger the mail
            $this->ci->load->library('parser');
            $this->emailHandler = new Email_handler();
            $this->messagetemplateHandler = new Messagetemplate_handler();
            $to = $this->ci->customsession->getData('userEmail');
            $templateInputs['mode'] = 'email';   

            //Sending organizer delete request mail
            $templateInputs['type'] = TYPE_DELETE_REQUEST;
            $deleteTemplate = $this->messagetemplateHandler->getTemplateDetail($templateInputs);
            $templateId = $deleteTemplate['response']['templateDetail']['id'];
            $from = $deleteTemplate['response']['templateDetail']['fromemailid'];           
            $templateMessage = $deleteTemplate['response']['templateDetail']['template'];
            
            $data['userName'] = $this->ci->customsession->getData('userName');
            //Data for email template (by using parser)
            $eventData=$this->ci->config->item('eventData');
            if (isset($eventData["event" . $inputArray['eventId']]['eventName'])) {
                $data['eventtitle'] = $eventData["event" . $inputArray['eventId']]['eventName'];
            }
            if (isset($eventData["event" . $inputArray['eventId']]['url'])) {
                $data['eventurl'] =  commonHelperGetPageUrl('home').'event/'.$eventData["event" . $inputArray['eventId']]['url'];
            }
            $data['eventid'] = $inputArray['eventId'];
            $data['currentYear'] = date('Y'); 
            $data['supportLink'] = commonHelperGetPageUrl('contactUs');
            $eventName=' '.$eventData["event" . $inputArray['eventId']]['eventName'];
            $subject = SUBJECT_DELETE_REQUEST.$eventName;
            $message1 = $this->ci->parser->parse_string($templateMessage, $data, TRUE);
            $sentmessageInputs['messageid'] = $templateId;
            $organzerEmailResponse = $this->emailHandler->sendEmail($from, $to, $subject, $message1, '', '', '', $sentmessageInputs);

            //Sending sales person delete request mail
            $templateInputs['type'] = TYPE_SALES_DELREQUEST;          
            //Sending organizer delete request mail
            $deleteTemplate = $this->messagetemplateHandler->getTemplateDetail($templateInputs);
            $templateId = $deleteTemplate['response']['templateDetail']['id'];
            $from = $deleteTemplate['response']['templateDetail']['fromemailid'];           
            $templateMessage = $deleteTemplate['response']['templateDetail']['template'];
            $subject = SUBJECT_SALES_DELREQUEST;
            $hostname = strtolower($_SERVER['HTTP_HOST']);
            if(strcmp($hostname,'www.meraevents.com') == 0 ){
                $to = GENERAL_INQUIRY_EMAIL;
            }else{
                $to = $this->ci->customsession->getData('userEmail');
            }
            
            //Data for email template (by using parser)
            $data['comments'] = $inputArray['comments'];    
            if(empty($inputArray['comments'])){
                $data['comments'] = '-';
            }
            $message = $this->ci->parser->parse_string($templateMessage, $data, TRUE);
            $sentmessageInputs['messageid'] = $templateId;
            $salesEmailResponse = $this->emailHandler->sendEmail($from, $to, $subject, $message, '', '', '', $sentmessageInputs);           
            if($organzerEmailResponse['status'] && $salesEmailResponse['status']){
               
                $this->ci->Event_model->commitLastTransaction();
                $output['status'] = TRUE;
                $output["response"]["messages"][] = DELREQUEST_SENT;
                $output['statusCode'] = 200;
                return $output;
            }else{
                $this->ci->Event_model->rollBackLastTransaction();
                $output['status'] = FALSE;
                $output["response"]["messages"][] = ERROR_SOMETHING_WENT_WRONG;
                $output['statusCode'] = 500;
                return $output;
            }
        }else{
            $output['status'] = FALSE;
            $output["response"]["messages"][] = ERROR_SOMETHING_WENT_WRONG;
            $output['statusCode'] = 500;
            return $output;
        }
          //echo $this->ci->db->last_query();
    }
    public function apiCreateEventInputDataFormat($data) {
       
        $urlChek=array();
        $this->ci->form_validation->reset_form_rules();
        $this->ci->form_validation->pass_array($data);
        $this->ci->form_validation->set_rules('eventTitle', 'title', 'required_strict|min_length[5]|titlePattern|notOnlySpecialChars|max_length[255]');
        $this->ci->form_validation->set_rules('eventDescription', 'description', 'required_strict|min_length[50]');
        
        $this->ci->form_validation->set_rules('eventURL', 'url', 'required_strict|urlPattern');
        $this->ci->form_validation->set_rules('country', 'country', 'required_strict|countryPattern');
        $this->ci->form_validation->set_rules('state', 'state', 'required_strict|statePattern');
        $this->ci->form_validation->set_rules('city', 'city', 'required_strict|cityPattern');
        $this->ci->form_validation->set_rules('venueAddress', 'venueName', 'required_strict|venuePattern|max_length[255]');
         
        $this->ci->form_validation->set_rules('eventStartDate', 'startDate', 'required_strict|date');
        $this->ci->form_validation->set_rules('eventStartTime', 'startTime', 'required_strict|time');
        $this->ci->form_validation->set_rules('eventEndDate', 'endDate', 'required_strict|date');
        $this->ci->form_validation->set_rules('eventEndTime', 'endTime', 'required_strict|time');
        $this->ci->form_validation->set_rules('eventCategory', 'eventCategory', 'required_strict');
             
        
         if ($this->ci->form_validation->run() == FALSE) {
            $response = $this->ci->form_validation->get_errors('message');
            $output['status'] = FALSE;
            $output['response']['messages'] = $response['message'];
            $output['statusCode'] = STATUS_BAD_REQUEST;
             return $output;
        }
         $param['status'] = '0';
         $param['submitValue'] ='save';
 
 
        $param['title'] = removeScriptTag($data['eventTitle']);

        $param['description'] = $data['eventDescription'];
       
       
        $param['categoryId']=0;
        $categoryHandler = new Category_handler();
        $categoryListResponse = $categoryHandler->getCategoryDetailsByKeyword(array('keyword' => $data['eventCategory']));
        if ($categoryListResponse['response']['status'] == TRUE && $categoryListResponse['response']['total'] > 0) {
            $param['categoryId']= $categoryListResponse['response']['categoryList']['0']['id'];
        } 
        
        $urlChek['eventUrl']=$param['url'] = cleanUrl($data['eventURL']); 
        $param['venueName'] = $data['venueAddress'];
        
        $param['startDate'] = urldecode($data['eventStartDate']);
        $param['startTime'] = allTimeFormats(urldecode($data['eventStartTime']), 12);
        $param['endDate'] = urldecode($data['eventEndDate']);
        $param['endTime'] = allTimeFormats(urldecode($data['eventEndTime']), 12);
        $timezoneHandler= new Timezone_handler();
        $timeZoneList = $timezoneHandler->timeZoneList();
        
        if ($timeZoneList['status'] == TRUE && $timeZoneList['response']['total'] > 0) {
            $data['timeZoneList'] = $timeZoneList['response']['timeZoneList'];
            $param['timezoneId']=$data['timeZoneList'][1]['id'];
        }
        $param['booknowButtonValue'] = isset($data['booknowButtonValue']) ? $data['booknowButtonValue'] : 'Book Now';
        $param['private'] = isset($data['isPrivateEvent']) ? $data['isPrivateEvent'] : "0";
        $param['popularity'] = isset($data['popularity']) ? $data['popularity'] : "0";
        $param['ownerId'] = $data['ownerId'];
        $param['bannerSource'] = isset($data['eventBanner']) ? $data['eventBanner'] : '';
        $param['thumbSource'] = isset($data['eventThumbnail']) ? $data['eventThumbnail'] : '';
        $param['iswebinar'] = isset($data['isWebinar']) ? $data['isWebinar'] : "0";
         
      
//       $urlStatus=$this->checkUrlExists($urlChek);
//       if($urlStatus["statusCode"] === 400){
//           return $urlStatus;
//           
//       }
         if (isset($data['country']))
            $param['country'] = $data['country'];
        if (isset($data['state']))
            $param['state'] = $data['state'];
        if (isset($data['city']))
            $param['city'] = $data['city'];
//        if (isset($data['countryId']))
//            $param['countryId'] = $data['countryId'];
//        if (isset($data['stateId']))
//            $param['stateId'] = $data['stateId'];
//        if (isset($data['cityId']))
//            $param['cityId'] = $data['cityId'];

        $tags = array();
        if (isset($data['tags']) && $data['tags'] != '') {
            $tags = explode(",", $data['tags']);
            foreach ($tags as $tagKey => $tagValue) {
                $param['tags'][$tagKey]['id'] = 0;
                $param['tags'][$tagKey]['tag'] = $tagValue;
            }
        }
        $output['status'] = TRUE;
        $output['response']['formattedData'] = $param;
        $output['statusCode'] = STATUS_OK;
        $output['response']['total'] = 1;
        $output['response']['messages'] = array();
        return $output;
    }
    
    
    
     public function apiCreateEvent($data) {
        $countryHandler = new Country_handler();
        $stateHandler = new State_handler();
        $cityHandler = new City_handler();
        $timezoneHandler = new Timezone_handler();
        $ticketHandler = new Ticket_handler();
        // Block event titles
        $blockedTitleResponse = $this->blockEventTitles($data);
        if ($blockedTitleResponse['status'] === FALSE) {
            return $blockedTitleResponse;
        }
        // Transaction based event creation if one of them failed it will 
        // rollback if all success quries will commit
        $this->ci->Event_model->startTransaction();

        $data['eventMode'] = 0;
        
        // for webinar country, state and city will be null
        if ($data['iswebinar'] == 1) {
            $data['eventMode'] = 1;
        }

        // add country if countryId is not exist
        if (isset($data['country'])) {
            $countryData = array();
            $countryData['country'] = $data['country'];
            $countryResponse = $countryHandler->countryInsert($countryData);
            if ($countryResponse['status'] === FALSE) {
                return $countryResponse;
            }
            $data['countryId'] = $countryResponse['response']['countryId'];
        }


        // add state if stateId is not exist
        if (isset($data['state'])) {
            $stateData = array();
            $stateData['countryId'] = $data['countryId'];
            $stateData['state'] = $data['state'];
            $stateResponse = $stateHandler->stateInsert($stateData);
            if ($stateResponse['status'] === FALSE) {
                return $stateResponse;
            }
            $data['stateId'] = $stateResponse['response']['stateId'];
        }

        // add city if cityId is not exist
        if (isset($data['city'])) {
            $cityData = array();
            $cityData['countryId'] = $data['countryId'];
            $cityData['stateId'] = $data['stateId'];
            $cityData['city'] = $data['city'];
            $cityResponse = $cityHandler->cityInsert($cityData);
            if ($cityResponse['status'] === FALSE) {
                return $cityResponse;
            }
            $data['cityId'] = $cityResponse['response']['cityId'];
        }
        //Bring the sub category details
        $this->subcategoryHandler = new Subcategory_handler();
        $subcategoryData = array();
        $subcategoryData['categoryId'] = $data['categoryId'];
        $subcategoryData['countryId'] = $data['countryId'];
        $subcategory=new Subcategory_handler();
        $subdetails=$subcategory->getSubCategories($subcategoryData);
        
//        print_r($details);exit;
//        $subcategoryData['subcategoryName'] = $data['subcategoryId'];
//        $subcategoryResponse = $this->subcategoryHandler->subcategoryInsert($subcategoryData);
//        if (!$subcategoryResponse['status']) {
//            return $subcategoryResponse;
//        }

//        $data['subcategoryId'] = $subcategoryResponse['response']['subcategoryId'];
        $data['subcategoryId'] = $subdetails['response']['subCategoryList'][0]['id'];

        $data['startDate'] = urldecode($data['startDate']);
        $data['endDate'] = urldecode($data['endDate']);
        $data['startTime'] = urldecode($data['startTime']);
        $data['endTime'] = urldecode($data['endTime']);
        $startDateValidation = dateValidation($data['startDate'], '/');
        $endDateValidation = dateValidation($data['endDate'], '/');

       
        if (!$startDateValidation || !$endDateValidation) {
            $output['status'] = FALSE;
            $output["response"]["messages"][] = ERROR_DATE_VALUE_FORMAT;
            $output['statusCode'] = STATUS_BAD_REQUEST;
            return $output;
        }
        // changing date format mm/dd/yyyy to yyyy-mm-dd    
        $startDate = dateFormate($data['startDate'], '/');
        $endDate = dateFormate($data['endDate'], '/');

        
        $timeZoneData['timezoneId'] = $data['timezoneId'];
        $timeZoneData['status'] = 1;
        $timeZoneDetails = $timezoneHandler->details($timeZoneData);
        $timeZoneName = "";
        if ($timeZoneDetails['status']) {
            $timeZoneName = $timeZoneDetails['response']['detail'][1]['name'];
        } else {
            return $timeZoneDetails;
        }
        $eventStartDate = convertTime($startDate . ' ' . $data['startTime'], $timeZoneName);
        $eventEndDate = convertTime($endDate . ' ' . $data['endTime'], $timeZoneName);
        $data['utcStartDate'] = $eventStartDate;
        $data['utcEndDate'] = $eventEndDate;
        //print_r(date("Y-m-d H:i:s").' '.$eventStartDate);
        if (strtotime(date("Y-m-d H:i:s")) > strtotime($eventStartDate)) {
            $output['status'] = FALSE;
            $output["response"]["messages"][] = ERROR_EVENT_START_DATE_GREATER_THAN_NOW;
            $output['statusCode'] = STATUS_BAD_REQUEST;
            return $output;
        }

        if (strtotime($eventStartDate) > strtotime($eventEndDate)) {
            $output['status'] = FALSE;
            $output["response"]["messages"][] = ERROR_EVENT_START_DATE_GREATER;
            $output['statusCode'] = STATUS_BAD_REQUEST;
            return $output;
        }
        $data['startDateTime'] = $eventStartDate;
        $data['endDateTime'] = $eventEndDate;
        $data['registrationtype'] = $this->getEventRegistrationType($data['tickets']);

        $data['latitude'] = $data['latitude'];
        $data['longitude'] = $data['longitude'];

        /* add event in event table
         * it returns event id
         */
        $addEventResponse = $this->addEvent($data);
        // addevent respose is error it will return in arrya otherwise it's return event inserted id

        if ($addEventResponse['status'] === FALSE) {
            return $addEventResponse;
        }
        $data['eventId'] = $addEventResponse['response']['eventid'];

        //This are the html dom file object Names
        $data['thumbnail'] = "thumbImage";
        $data['banner'] = "bannerImage";
        $data['bannerSource'] = isset($data['bannerSource']) ? $data['bannerSource'] : '';
        $data['thumbSource'] = isset($data['thumbSource']) ? $data['thumbSource'] : '';
        if(isset($data['removeBanner']) && $data['removeBanner'] == 1){
            $_FILES[$data['banner']]["name"] ='';
        }
       else if (!empty($data['bannerSource']) || isset($_FILES[$data['banner']]["name"])) { 
            $bannerIds = $this->eventBannerUpload($data);
        }
         if(isset($data['removeThumb']) && $data['removeThumb'] == 1){
            $_FILES[$data['thumbnail']]["name"] ='';
        }
      else  if (!empty($data['thumbSource']) || isset($_FILES[$data['thumbnail']]["name"])) {
            $logoIds = $this->eventLogoUpload($data);
        }
        if (isset($bannerIds) && $bannerIds['status'] === FALSE) {
            return $bannerIds;
        } elseif (isset($bannerIds)) {
            $data['bannerFileId'] = $bannerIds ['response']['bannerFileId'];
            $data['bannerFilePath'] = $bannerIds ['response']['bannerFilePath'];
        }
        if (isset($logoIds) && $logoIds['status'] === FALSE) {
            return $logoIds;
        } elseif (isset($logoIds)) {
            $data['thumbnailFileId'] = $logoIds ['response']['thumbnailFileId'];
            $data['thumbnailFilePath'] = $logoIds ['response']['thumbnailFilePath'];
        }

        //update event 
        $updateEventResonse = $this->updateEvent($data);
        if ($updateEventResonse['status'] === FALSE) {
            return $updateEventResonse;
        }

        // add evetdetails in eventdetail table
        $addEventDetailResponse = $this->addEventDetail($data);
        if ($addEventDetailResponse['status'] === FALSE) {
            return $addEventDetailResponse;
        }

        /* add tags in tag table
         * it returns tags with id and name
         */

        $tagHandler = new Tag_handler();
        if (isset($data['tags']) && !empty($data['tags'])) {
            $addTagResponse = $tagHandler->addTag($data);
            if ($addTagResponse['status'] === FALSE) {
                return $addTagResponse;
            }


            $eventTagData = array();
            $eventTagData['tags'] = $addTagResponse['response']['tags'];
            $eventTagData['eventId'] = $data['eventId'];
            $addEventTagResponse = $this->addEventTag($eventTagData);
            if ($addEventTagResponse['status'] === FALSE) {
                return $addEventTagResponse;
            }
        }

        

        //Insert the default custom fields 
        $configureHandler = new Configure_handler();
        $customFieldStatus = $configureHandler->insertEventDefaultCustomFiedls($data);
        if (!$customFieldStatus['status']) {
            return $customFieldStatus;
        }
        //To insert the event setting table data
        $eventSettingStatus = $this->insertEventSettingDetails($data);
        if (!$eventSettingStatus['status']) {
            return $eventSettingStatus;
        }

        $eventPaymentGateway = new EventpaymentGateway_handler();
        $paymentGaetewayStatus = $eventPaymentGateway->insertEventDefaultPaymentEventList($data);
        if (!$paymentGaetewayStatus['status']) {
            return $paymentGaetewayStatus;
        }

        //set default alerts
        $alertsResponse = $this->setAlertsForOrganizer();
        if (!$alertsResponse['status']) {
            return $alertsResponse;
        }

        //Change the organizer status
        $organizerHandler = new Organizer_handler();
        $organizerHandler->changeOrganizerStatus();

        if ($this->ci->Event_model->transactionStatusCheck() === FALSE) {
            $this->ci->Event_model->rollBackLastTransaction();
            $output['status'] = FALSE;
            $output["response"]["messages"][] = ERROR_SOMETHING_WENT_WRONG;
            $output['statusCode'] = 200;
            return $output;
        } else {
            $solrHandler = new Solr_handler();
            // add event in slor
            $solrData = array();
            $solrData = $this->solrAddEventInputData($data);
            $solrData['limitsingletickettype'] = 0;
            $addEventInSolr = $solrHandler->solrAddEvent($solrData);
            //print_r($addEventInSolr);exit;
            if ($addEventInSolr['status'] === FALSE) {
                return $addEventInSolr;
            }
            // add tags in solr
            $solrTagData = array();
            if (isset($data['tags']) && !empty($data['tags'])) {
                foreach ($addTagResponse['response']['tags'] as $solrTagKey => $solrTagvalue) {
                    $solrTagData['id'] = $solrTagvalue['id'];
                    $solrTagData['name'] = $solrTagvalue['tag'];
                    $addTagInSolr = $solrHandler->solrAddTag($solrTagData);
                    if ($addTagInSolr['status'] === FALSE) {
                        // delete solr event while got error in adding tags   
                        $solrDeletedData = array();
                        $solrDeletedData['id'] = $data['eventId'];
                        $deleteInSolr = $solrHandler->solrDeleteEvent($solrDeletedData);
                        if ($deleteInSolr['status']) {
                            return $addTagInSolr; //because we need to throw why error came for adding tags after deleting tag
                        } else {
                            return $deleteInSolr;
                        }
                    }
                }
            }

            $this->ci->Event_model->commitLastTransaction();

            $output['status'] = TRUE;
            $output["response"]["url"] = $data['url'];
            $output["response"]["id"] = $data['eventId'];
            $output["response"]["messages"][] = SOLR_EVENT_CREATE;
            $output['statusCode'] = STATUS_CREATED;
            return $output;
        }
    }
    
    public function updateEventStatusRegistration($data) {
        $eventData['registrationtype'] = $data['registrationType'];
        $eventData['status'] = $data['status'];
        $this->ci->Event_model->resetVariable();
        $where = array($this->ci->Event_model->id => $data['id']);
        $this->ci->Event_model->setInsertUpdateData($eventData);
        $this->ci->Event_model->setWhere($where);
        $response = $this->ci->Event_model->update_data();
        if ($response) {
            $output['status'] = TRUE;
            $output["response"]["messages"] = array();
            $output['statusCode'] = STATUS_UPDATED;
            return $output;
        }
        $output['status'] = FALSE;
        $output["response"]["messages"][] = SOMETHING_WENT_WRONG;
        $output['statusCode'] = 400;
        return $output;
    }
                 
    /*
     * Function to format the calculation data for mobile from above function `getEventTicketCalculation()`
     *
     * @access	public
     * @param	$inputArray contains
     * 			Tickets Array with Ticket Id and Quantity
     * 			Event Id
     * @return	array
     */

    function getEventTicketCalculation_structured($inputArray) {

        $response = $this->getEventTicketCalculation($inputArray);
        $ticketArray = $mesageArr = array();
        $ticketDataResponse = $response['response']['calculationDetails']['ticketsData'];
        foreach ($ticketDataResponse as $ticketId => $ticketData) {
            if (is_array($ticketData['taxes']) && count($ticketData['taxes']) > 0) {
                $taxArray = array();
                foreach ($ticketData['taxes'] as $taxes) {
                    $taxArray[] = $taxes;
}
                unset($ticketData['taxes']);
                $ticketData['taxes'] = $taxArray;
            }
            $ticketArray[] = $ticketData;
        }
        $tempResp = $response['response']['calculationDetails'];
        unset($tempResp['ticketsData']);

        $finalResponse['status'] = $response['status'];
        $finalResponse['statusCode'] = $response['statusCode'];
        $finalResponse['response']['messages'] = $response['response']['messages'];
        $finalResponse['response']['calculationDetails'] = $tempResp;
        $finalResponse['response']['calculationDetails']['ticketsData'] = $ticketArray;

        return $finalResponse;
   }

  function getEventName($eventId) {
        $inputArray['eventId'] = $eventId;
        $this->ci->form_validation->pass_array($inputArray);
        $this->ci->form_validation->set_rules('eventId', 'eventId', 'required_strict|is_natural_no_zero');
        if ($this->ci->form_validation->run() == FALSE) {

            $response = $this->ci->form_validation->get_errors('message');
            $output['status'] = FALSE;
            $output['response']['messages'] = $response['message'];
            $output['statusCode'] = STATUS_BAD_REQUEST;
            return $output;
        }
        $this->ci->Event_model->resetVariable();
        $select['title'] = $this->ci->Event_model->title;
        $this->ci->Event_model->setSelect($select);
        $where[$this->ci->Event_model->id] = $eventId;
        $this->ci->Event_model->setWhere($where);
        $result = $this->ci->Event_model->get();
        if ($result) {
            $output['status'] = TRUE;
            $output['response']['eventName'] = $result['0']['title'];
            $output['response']['total'] = 1;
            $output['response']['messages'] = array();
            $output['statusCode'] = STATUS_OK;
            return $output;
        }
        $output['status'] = FALSE;
        $output['response']['messages'][] = ERROR_INVALID_EVENTID;
        $output['statusCode'] = STATUS_BAD_REQUEST;
        return $output;
    }
}
