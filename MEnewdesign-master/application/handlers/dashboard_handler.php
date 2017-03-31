<?php

/**
 * Event related business logic will be defined in this class
 *
 * @package		CodeIgniter
 * @author		Qison  Dev Team
 * @copyright	Copyright (c) 2015, MeraEvents.
 * @Version		Version 1.0
 * @Since       Class available since Release Version 1.0 
 * @Created     11-06-2015
 * @Last Modified 11-06-2015
 */
require_once(APPPATH . 'handlers/event_handler.php');
require_once(APPPATH . 'handlers/eventdetail_handler.php');
require_once(APPPATH . 'handlers/ticket_handler.php');
require_once(APPPATH . 'handlers/city_handler.php');
require_once(APPPATH . 'handlers/eventsignup_handler.php');
require_once(APPPATH . 'handlers/eventsignupticketdetail_handler.php');
require_once(APPPATH . 'handlers/email_handler.php');
require_once(APPPATH . 'handlers/collaborator_handler.php');
require_once(APPPATH . 'handlers/timezone_handler.php');
require_once (APPPATH . 'handlers/currency_handler.php');


class Dashboard_handler extends Event_handler {

    var $eventCi;
    var $ticketHandler;
    var $cityHandler;
    var $userHandler;
    var $timezoneHandler;

    public function __construct() {
        parent::__construct();
        $this->eventCi = & get_instance();
        $this->eventCi->load->model('Event_model');
        $this->ticketHandler = new Ticket_handler();
        $this->cityHandler = new City_handler();
    }

    public function getUserUpcomingEvent($inputArray = array()) {
        $this->eventCi->Event_model->resetVariable();
        $selectInput = $orderBy = $whereCondition = $upcomingEventList = array();
        $userId = getUserId();
        $page = 0;
        if (isset($inputArray['page']) && $inputArray['page'] > 0) {
            $page = EVENTS_DISPLAY_LIMIT * $inputArray['page'];
        }
        //collaborative events
        $collaborativeEventData = array();
        if ($this->eventCi->customsession->getData('isCollaborator') == 1) {
            $collaborator = new Collaborator_handler();
            $inputCollaboratorEvents['userids'] = array($userId);
            $inputCollaboratorEvents['getacesslevel'] = TRUE;
            $collaboratorResponse = $collaborator->getListByUserIds($inputCollaboratorEvents);
            if ($collaboratorResponse['status']) {
                if ($collaboratorResponse['response']['total'] > 0) {
                    foreach ($collaboratorResponse['response']['collaboratorList'] as $value) {
                        $collaborativeEventData[$value['eventid']] = $value['module'];
                    }
                }
            } else {
                return $collaboratorResponse;
            }
        }

        $whereCondition[$this->eventCi->Event_model->deleted] = 0;
        // Search By Keyword Condition
        $setlike = array();
        if (isset($inputArray['keyword']) && strlen($inputArray['keyword']) > 0) {
            if (!ctype_alpha($inputArray['keyword'])) {
                $setlike[$this->eventCi->Event_model->id] = $inputArray['keyword'];
            }
            $setlike[$this->eventCi->Event_model->title] = $inputArray['keyword'];
        }

        $setOrWhere[$this->eventCi->Event_model->ownerid] = $userId;
        $whereCondition[$this->eventCi->Event_model->enddatetime . " > "] = allTimeFormats('', 11);
        if (count($collaborativeEventData) > 0) {
            $setOrWhere[$this->eventCi->Event_model->id] = array_keys($collaborativeEventData);
        }
        //get total count

        $orderBy[] = 'YEAR(' . $this->eventCi->Event_model->startdatetime . ') ASC';
        $orderBy[] = " Month( " . $this->eventCi->Event_model->startdatetime . ") ASC";
        $orderBy[] = $this->eventCi->Event_model->status . " desc ";
        $orderBy[] = " Date( " . $this->eventCi->Event_model->startdatetime . ") ASC ";
        $this->eventCi->Event_model->setSelect($selectInput);
        $this->eventCi->Event_model->setWhere($whereCondition);
        $this->eventCi->Event_model->setWhereins($whereIn = array());
        $this->eventCi->Event_model->setOrWhere($setOrWhere);
        //  $this->eventCi->Event_model->setConditionLike($setlike);
        $this->eventCi->Event_model->setOrWhere($setlike, 'or', 'like');
        $this->eventCi->Event_model->setOrderBy($orderBy);
        $totalCount = $this->eventCi->Event_model->getCount();
       // echo $this->ci->db->last_query();exit;
        $selectInput['eventId'] = $this->eventCi->Event_model->id;
        $selectInput['eventName'] = $this->eventCi->Event_model->title;
        $selectInput['eventStartDate'] = $this->eventCi->Event_model->startdatetime;
        $selectInput['eventEndDate'] = $this->eventCi->Event_model->enddatetime;
        $selectInput['eventStatus'] = $this->eventCi->Event_model->status;
		$selectInput['url'] = $this->eventCi->Event_model->url;
        $selectInput['eventCityId'] = $this->eventCi->Event_model->cityid;
        $selectInput['eventCountryId'] = $this->eventCi->Event_model->countryid;
        $selectInput['eventMonth'] = " MonthName( " . $this->eventCi->Event_model->startdatetime . ") ";
        $selectInput['timezoneId'] = $this->eventCi->Event_model->timezoneid;
        $selectInput['eventmode'] = $this->eventCi->Event_model->eventmode;
        $whereCondition[$this->eventCi->Event_model->deleted] = 0;
        $setOrWhere[$this->eventCi->Event_model->ownerid] = $userId;
        $whereCondition[$this->eventCi->Event_model->enddatetime . " > "] = allTimeFormats('', 11);
        if (count($collaborativeEventData) > 0) {
            $setOrWhere[$this->eventCi->Event_model->id] = array_keys($collaborativeEventData);
        }
        $this->eventCi->Event_model->setOrderBy($orderBy = array());
        $orderBy[] = 'YEAR(' . $this->eventCi->Event_model->startdatetime . ') ASC';
        $orderBy[] = " Month( " . $this->eventCi->Event_model->startdatetime . ") ASC";
        $orderBy[] = $this->eventCi->Event_model->status . " desc ";
        $orderBy[] = " Date( " . $this->eventCi->Event_model->startdatetime . ") ASC ";
        $this->eventCi->Event_model->setSelect($selectInput);
        $this->eventCi->Event_model->setWhere($whereCondition);
        $this->eventCi->Event_model->setWhereins($whereIn = array());
        $this->eventCi->Event_model->setOrWhere($setOrWhere);
        // $this->eventCi->Event_model->setConditionLike($setlike);
        $this->eventCi->Event_model->setOrWhere($setlike, 'or', 'like');

        $this->eventCi->Event_model->setOrderBy($orderBy);
        $this->eventCi->Event_model->setRecords(EVENTS_DISPLAY_LIMIT, $page);
        //get data
        $upcomingEventList = $this->eventCi->Event_model->get();
        
        // For setting the User redirection URL After Login
        if (isset($inputArray['loginredirectCheck']) && $inputArray['loginredirectCheck'] == true) {
            $count = 0;
            if ($upcomingEventList != FALSE && count($upcomingEventList) > 0) {
                $count = count($upcomingEventList);
            }
            $output['status'] = TRUE;
            $output['messages'][] = '';
            $output['response']['total'] = $count;
            $output['statusCode'] = STATUS_OK;
            return $output;
        }
        if ($upcomingEventList != FALSE && count($upcomingEventList) > 0) {//No records are fetched
            $eloop = 0;
            $eventIdsData = commonHelperGetIdArray($upcomingEventList, 'eventId');
            $eventCityIds = commonHelperGetIdArray($upcomingEventList, 'eventCityId');
            $timezoneIds = commonHelperGetIdArray($upcomingEventList, 'timezoneId');
            unset($eventCityIds['']);
            unset($eventCityIds['0']);
            $eventCityIdArray = array_keys($eventCityIds);
            $eventCityNameArray = array();
            $this->timezoneHandler = new Timezone_handler();
            if (count($timezoneIds) > 0) {
                $timezoneResData = $this->timezoneHandler->timeZoneList(array('idList' => $timezoneIds));
                if ($timezoneResData['status'] != FALSE && count($timezoneResData['response']['timeZoneList']) > 0) {
                    $timezoneDataArray = commonHelperGetIdArray($timezoneResData['response']['timeZoneList']);
                }
            }

            if (count($eventCityIdArray) > 0) {
                $eventCityNames = $this->cityHandler->getCityNames($eventCityIdArray);
                if ($eventCityNames['status'] == TRUE && count($eventCityNames['response']['cityName']) > 0) {
                    $eventCityNameArray = commonHelperGetIdArray($eventCityNames['response']['cityName']);
                }
            }

            $eventIdArray = array_unique(array_keys($eventIdsData));
            $inputArray['eventIdList'] = $eventIdArray;
            if (count($eventIdArray) > 0) {
                $this->eventdetailHandler =new Eventdetail_handler();
                $eventDetailInputs['eventIdList'] = $eventIdArray;
                $eventDetails=$this->eventdetailHandler->geteventDetailsByList($eventDetailInputs);  
                if ($eventDetails['status'] == TRUE && count($eventDetails['response']['eventDetail']['total']) > 0) {
                    $eventidIndexedPageCount = commonHelperGetIdArray($eventDetails['response']['eventDetail'],'eventid');            
                }
            }
            $eventSignupHandler = new Eventsignup_handler(); 
            $ticketData = $eventSignupHandler->getSoldTicketCount($inputArray);
            $ticketArrayData = array();
            if ($ticketData['status'] == TRUE && $ticketData['response']['total'] > 0) {
                $ticketArrayData = commonHelperGetIdArray($ticketData['response']['ticketSaleCount'], 'eventid');
            }
            foreach($eventIdsData as $key => $eventData) {
                if (count($ticketArrayData) > 0 && isset($ticketArrayData[$key])) {
                    $eventIdsData[$key]['soldOutTickets'] = $ticketArrayData[$key]['totalsoldtickets'];
                } else {
                    $eventIdsData[$key]['soldOutTickets'] = 0;
                }
                if (count($eventCityNameArray) > 0 && isset($eventCityNameArray[$eventData['eventCityId']])) {
                    $eventIdsData[$key]['eventCityName'] = $eventCityNameArray[$eventData['eventCityId']]['name'];
                } else {
                    $eventIdsData[$key]['eventCityName'] = "";
                }
                
                if($eventIdsData[$key]['eventmode'] == 1 && $eventIdsData[$key]['eventCityName'] == "") {
                    $eventIdsData[$key]['eventCityName'] = "Webinar";
                }
                
                if (count($timezoneDataArray) > 0 && isset($timezoneDataArray[$eventData['timezoneId']])) {//converting dates to timezones
                    $eventIdsData[$key]['eventStartDate'] = convertTime($eventIdsData[$key]['eventStartDate'], $timezoneDataArray[$eventData['timezoneId']]['name'], true);
                    $eventIdsData[$key]['eventEndDate'] = convertTime($eventIdsData[$key]['eventEndDate'], $timezoneDataArray[$eventData['timezoneId']]['name'], true);
                    $eventIdsData[$key]['ActualeventStartDate'] = $eventIdsData[$key]['eventStartDate'];
                    $eventIdsData[$key]['ActualeventEndDate'] = $eventIdsData[$key]['eventEndDate'];
                    $eventIdsData[$key]['eventStartDate'] = allTimeFormats($eventIdsData[$key]['eventStartDate'], 15);
                    $eventIdsData[$key]['eventEndDate'] = allTimeFormats($eventIdsData[$key]['eventEndDate'], 15);
                }
                if($key==$eventidIndexedPageCount[$key]['eventid']){
                    $eventIdsData[$key]['viewcount'] = $eventidIndexedPageCount[$key]['viewcount'];
                }
            }
            //var_dump($eventIdsData);
            $output['status'] = TRUE;
            if ($inputArray['callType'] == 'ajax') {
                $eventIdsData = array_values($eventIdsData);
            }
            $output['response']['eventList'] = $eventIdsData;
            $output['response']['collaborativeEventData'] = $collaborativeEventData;
            $output['response']['messages'] = array();
            $output['response']['total'] = count($eventIdsData);
            $output['response']['totalcount'] = $totalCount;
            $output['statusCode'] = STATUS_OK;
        } else{
            $output['status'] = TRUE;
            $output['response']['messages'][] = ERROR_NO_DATA;
            $output['response']['total'] = 0;
            $output['statusCode'] = STATUS_OK;
        }
        return $output;
    }

    public function getUserPastEvent($inputArray = array()) {
        $this->eventCi->Event_model->resetVariable();
        $selectInput = $orderBy = $whereCondition = $upcomingEventList = array();
        $userId = getUserId();
        $page = 0;
        if (isset($inputArray['page']) && $inputArray['page'] > 0) {
            $page = EVENTS_DISPLAY_LIMIT * $inputArray['page'];
        }
        //collaborative events
        $collaborativeEventData = array();
        if ($this->eventCi->customsession->getData('isCollaborator') == 1) {
            $collaborator = new Collaborator_handler();
            $inputCollaboratorEvents['userids'] = array($userId);
            $inputCollaboratorEvents['getacesslevel'] = TRUE;
            $collaboratorResponse = $collaborator->getListByUserIds($inputCollaboratorEvents);
            if ($collaboratorResponse['status']) {
                if ($collaboratorResponse['response']['total'] > 0) {
                    foreach ($collaboratorResponse['response']['collaboratorList'] as $value) {
                        $collaborativeEventData[$value['eventid']] = $value['module'];
                    }
                }
            } else {
                return $collaboratorResponse;
            }
        }
      
        // Search By Keyword Condition
        $setlike = array();
        if (isset($inputArray['keyword']) && strlen($inputArray['keyword']) > 0) {
            $setlike[$this->eventCi->Event_model->title] = $inputArray['keyword'];
            if (!ctype_alpha($inputArray['keyword'])) {
                $setlike[$this->eventCi->Event_model->id] = $inputArray['keyword'];
            }
        }
        $whereCondition[$this->eventCi->Event_model->deleted] = 0;
        $setOrWhere[$this->eventCi->Event_model->ownerid] = $userId;
        $whereCondition[$this->eventCi->Event_model->enddatetime . " <= "] = allTimeFormats('', 11);
        if (count($collaborativeEventData) > 0) {
        	$setOrWhere[$this->eventCi->Event_model->id] = array_keys($collaborativeEventData);
        }
        //get total count
        $this->eventCi->Event_model->setOrderBy($orderBy = array());
        $orderBy[] = 'YEAR(' . $this->eventCi->Event_model->startdatetime . ') DESC';
        $orderBy[] = " Month( " . $this->eventCi->Event_model->startdatetime . ") DESC";
        $orderBy[] = $this->eventCi->Event_model->status . " desc ";
        $orderBy[] = " Date( " . $this->eventCi->Event_model->startdatetime . ") DESC ";
        $this->eventCi->Event_model->setSelect($selectInput);
        $this->eventCi->Event_model->setWhere($whereCondition);
        $this->eventCi->Event_model->setWhereins($whereIn = array());
        $this->eventCi->Event_model->setOrWhere($setOrWhere);
        $this->eventCi->Event_model->setOrWhere($setlike, 'or', 'like');
        $this->eventCi->Event_model->setOrderBy($orderBy);
        $totalCount = $this->eventCi->Event_model->getCount();
        $selectInput['eventId'] = $this->eventCi->Event_model->id;
        $selectInput['eventName'] = $this->eventCi->Event_model->title;
        $selectInput['eventStartDate'] = $this->eventCi->Event_model->startdatetime;
        $selectInput['eventEndDate'] = $this->eventCi->Event_model->enddatetime;
        $selectInput['eventStatus'] = $this->eventCi->Event_model->status;
		$selectInput['url'] = $this->eventCi->Event_model->url;
        $selectInput['eventCityId'] = $this->eventCi->Event_model->cityid;
        $selectInput['eventCountryId'] = $this->eventCi->Event_model->countryid;
        $selectInput['eventMonth'] = " MonthName( " . $this->eventCi->Event_model->startdatetime . ") ";
        $selectInput['timezoneId'] = $this->eventCi->Event_model->timezoneid;

        $whereCondition[$this->eventCi->Event_model->deleted] = 0;
        $setOrWhere[$this->eventCi->Event_model->ownerid] = $userId;
        $whereCondition[$this->eventCi->Event_model->enddatetime . " < "] = allTimeFormats('', 11);
        $this->eventCi->Event_model->setRecords(EVENTS_DISPLAY_LIMIT, $page);
        if (count($collaborativeEventData) > 0) {
            $setOrWhere[$this->eventCi->Event_model->id] = array_keys($collaborativeEventData);
        }
        $orderBy[] = 'YEAR(' . $this->eventCi->Event_model->startdatetime . ') DESC';
        $orderBy[] = " Month( " . $this->eventCi->Event_model->startdatetime . ") DESC";
        $orderBy[] = $this->eventCi->Event_model->status . " desc ";
        $orderBy[] = " Date( " . $this->eventCi->Event_model->startdatetime . ") DESC ";
        $this->eventCi->Event_model->setSelect($selectInput);
        $this->eventCi->Event_model->setWhere($whereCondition);
        $this->eventCi->Event_model->setOrWhere($setOrWhere);
        $this->eventCi->Event_model->setOrWhere($setlike, 'or', 'like');
        // $this->eventCi->Event_model->setConditionLike($setlike);
        $this->eventCi->Event_model->setOrderBy($orderBy);
        $upcomingEventList = $this->eventCi->Event_model->get();
        
        if ($upcomingEventList != FALSE && count($upcomingEventList) > 0) {//No records are fetched
            $eloop = 0;
            $eventIdsData = commonHelperGetIdArray($upcomingEventList, 'eventId');
            $eventCityIds = commonHelperGetIdArray($upcomingEventList, 'eventCityId');
            $timezoneIds = commonHelperGetIdArray($upcomingEventList, 'timezoneId');

            unset($eventCityIds['']);
            unset($eventCityIds['0']);
            $eventCityIdArray = array_keys($eventCityIds);
            if (count($eventCityIdArray) > 0) {
                $eventCityNames = $this->cityHandler->getCityNames($eventCityIdArray);
            }
            $eventCityNameArray = array();

            $this->timezoneHandler = new Timezone_handler();
            if (count($timezoneIds) > 0) {
                $timezoneResData = $this->timezoneHandler->timeZoneList(array('idList' => $timezoneIds));
                if ($timezoneResData['status'] != FALSE && count($timezoneResData['response']['timeZoneList']) > 0) {
                    $timezoneDataArray = commonHelperGetIdArray($timezoneResData['response']['timeZoneList']);
                }
            }
            if ($eventCityNames['status'] == TRUE && count($eventCityNames['response']['cityName']) > 0) {
                $eventCityNameArray = commonHelperGetIdArray($eventCityNames['response']['cityName']);
            }
            
            $eventIdArray = array_unique(array_keys($eventIdsData));
            if (count($eventIdArray) > 0) {
                $this->eventdetailHandler =new Eventdetail_handler();
                $eventDetailInputs['eventIdList'] = $eventIdArray;
                $eventDetails=$this->eventdetailHandler->geteventDetailsByList($eventDetailInputs);  
                if ($eventDetails['status'] == TRUE && count($eventDetails['response']['eventDetail']['total']) > 0) {
                    $eventidIndexedPageCount = commonHelperGetIdArray($eventDetails['response']['eventDetail'],'eventid');            
                }
            }
            
            $inputArray['eventIdList'] = $eventIdArray;
            $eventSignupHandler = new Eventsignup_handler();
            $ticketData = $eventSignupHandler->getSoldTicketCount($inputArray);
            $ticketArrayData = array();
            if ($ticketData['status'] == TRUE && $ticketData['response']['total'] > 0) {
                $ticketArrayData = commonHelperGetIdArray($ticketData['response']['ticketSaleCount'], 'eventid');
            }
            foreach ($eventIdsData as $key => $eventData) {
                if (count($ticketArrayData) > 0 && isset($ticketArrayData[$key])) {
                    $eventIdsData[$key]['soldOutTickets'] = $ticketArrayData[$key]['totalsoldtickets'];
                } else {
                    $eventIdsData[$key]['soldOutTickets'] = 0;
                }
                if (count($eventCityNameArray) > 0 && isset($eventCityNameArray[$eventData['eventCityId']])) {
                    $eventIdsData[$key]['eventCityName'] = $eventCityNameArray[$eventData['eventCityId']]['name'];
                } else {
                    $eventIdsData[$key]['eventCityName'] = "";
                }
                
                if($eventIdsData[$key]['eventmode'] == 1 && $eventIdsData[$key]['eventCityName'] == "") {
                    $eventIdsData[$key]['eventCityName'] = "Webinar";
                }

                if (count($timezoneDataArray) > 0 && isset($timezoneDataArray[$eventData['timezoneId']])) {//converting dates to timezones
                    $eventIdsData[$key]['eventStartDate'] = convertTime($eventIdsData[$key]['eventStartDate'], $timezoneDataArray[$eventData['timezoneId']]['name'], true);
                    $eventIdsData[$key]['eventEndDate'] = convertTime($eventIdsData[$key]['eventEndDate'], $timezoneDataArray[$eventData['timezoneId']]['name'], true);
                    $eventIdsData[$key]['ActualeventStartDate'] = $eventIdsData[$key]['eventStartDate'];
                    $eventIdsData[$key]['ActualeventEndDate'] = $eventIdsData[$key]['eventEndDate'];
                    $eventIdsData[$key]['eventStartDate'] = allTimeFormats($eventIdsData[$key]['eventStartDate'], 15);
                    $eventIdsData[$key]['eventEndDate'] = allTimeFormats($eventIdsData[$key]['eventEndDate'], 15);
                }
                if($key==$eventidIndexedPageCount[$key]['eventid']){
                    $eventIdsData[$key]['viewcount'] = $eventidIndexedPageCount[$key]['viewcount'];
                }
            }
            
            if ($inputArray['callType'] == 'ajax') {
                $eventIdsData = array_values($eventIdsData);
            }
            $output['status'] = TRUE;
            $output['response']['eventList'] = $eventIdsData;
            $output['response']['totalcount'] = $totalCount;
            $output['response']['collaborativeEventData'] = $collaborativeEventData;
            $output['response']['messages'] = array();
            $output['response']['total'] = count($eventIdsData);
            $output['statusCode'] = STATUS_OK;
        } else if (count($upcomingEventList) == 0) {//No records are fetched
            $output['status'] = TRUE;
            $output['response']['messages'][] = ERROR_NO_DATA;
            $output['response']['total'] = 0;
            $output['statusCode'] = STATUS_OK;
        } else {
            $output['status'] = FALSE;
            $output['response']['messages'][] = ERROR_INTERNAL_DB_ERROR;
            $output['response']['total'] = 0;
            $output['statusCode'] = STATUS_SERVER_ERROR;
        }
        return $output;
    }

    public function getTncDetail($inputArray) {
        $eventId = $inputArray['eventId'];
        $this->eventCi->load->model('Eventdetail_model');
        $this->eventCi->Eventdetail_model->resetVariable();
        $selectInput['eventId'] = $this->eventCi->Eventdetail_model->eventdetail_id;
        $selectInput['organizertnc'] = $this->eventCi->Eventdetail_model->eventdetail_organizertnc;

        //$whereCondition[$this->eventCi->Event_model->deleted] = 0;
        //$whereCondition[$this->eventCi->Event_model->ownerid] = $userId;
        $whereCondition[$this->eventCi->Eventdetail_model->eventdetail_id] = $eventId;

        $this->eventCi->Eventdetail_model->setSelect($selectInput);
        $this->eventCi->Eventdetail_model->setWhere($whereCondition);
        $eventData = $this->eventCi->Eventdetail_model->get();
        if ($eventData != FALSE && count($eventData) > 0) {
            $output['status'] = TRUE;
            $output['response']['eventData'] = $eventData[0];
            $output['messages'] = array();
            $output['response']['total'] = count($eventData);
            $output['statusCode'] = STATUS_OK;
        } else if ($eventData != FALSE && count($eventData) == 0) {//No records are fetched
            $output['status'] = TRUE;
            $output['messages'][] = ERROR_NO_DATA;
            $output['response']['total'] = 0;
            $output['statusCode'] = STATUS_OK;
        } else {
            $output['status'] = FALSE;
            $output['messages'][] = ERROR_INTERNAL_DB_ERROR;
            $output['response']['total'] = 0;
            $output['statusCode'] = STATUS_SERVER_ERROR;
        }
        return $output;
    }

    public function getUserCurrentTicketEvent($inputArray) {
        $selectInput = $orderBy = $whereCondition = $upcomingEventList = array();
        $eventListIds = $inputArray['eventList'];
        $ticketType = isset($inputArray['ticketType']) ? $inputArray['ticketType'] : "current";
        $this->eventCi->Event_model->resetVariable();
        $selectInput['eventId'] = $this->eventCi->Event_model->id;
        $selectInput['eventName'] = $this->eventCi->Event_model->title;
        $selectInput['venuename'] = $this->eventCi->Event_model->venuename;
        $selectInput['eventStartDate'] = $this->eventCi->Event_model->startdatetime;
        $selectInput['eventEndDate'] = $this->eventCi->Event_model->enddatetime;
        $selectInput['timezoneid'] = $this->eventCi->Event_model->timezoneid;
        $selectInput['categoryid'] = $this->eventCi->Event_model->categoryid;


        $whereCondition[$this->eventCi->Event_model->deleted] = 0;
        if ($ticketType == "past") {
            $whereCondition[$this->eventCi->Event_model->enddatetime . " < "] = allTimeFormats('', 11);
        } else {
            $whereCondition[$this->eventCi->Event_model->enddatetime . " > "] = allTimeFormats('', 11);
        }

        $this->eventCi->Event_model->setSelect($selectInput);
        $this->eventCi->Event_model->setWhere($whereCondition);
        $this->eventCi->Event_model->setWhereIns(array($this->eventCi->Event_model->id => $eventListIds));
        $eventList = $this->eventCi->Event_model->get();
        if (count($eventList) > 0) {//No records are fetched
            $eloop = 0;
            $eventIdsData = commonHelperGetIdArray($eventList, 'eventId');
            $output['status'] = TRUE;
            $output['response']['eventList'] = $eventIdsData;
            $output['messages'] = array();
            $output['response']['total'] = count($eventIdsData);
            $output['statusCode'] = STATUS_OK;
        } else {//No records are fetched
            $output['status'] = TRUE;
            $output['messages'][] = ERROR_NO_DATA;
            $output['response']['total'] = 0;
            $output['statusCode'] = STATUS_OK;
        }
        return $output;
    }

    public function eventAccessVerify($inputArray) {
        $this->ci->form_validation->pass_array($inputArray);
        $this->ci->form_validation->set_rules('eventId', 'eventId', 'required_strict|is_natural_no_zero');
        if ($this->ci->form_validation->run() == FALSE) {
            $response = $this->ci->form_validation->get_errors('message');
            $output['status'] = FALSE;
            $output['response']['messages'] = $response['message'];
            $output['statusCode'] = STATUS_BAD_REQUEST;
            return $output;
        }
        $selectInput = array();
        $this->ci->load->model('Event_model');
        $this->ci->Event_model->resetVariable();
        $selectInput['eventId'] = $this->ci->Event_model->id;
        $selectInput['eventName'] = $this->ci->Event_model->title;
        $selectInput['ownerId'] = $this->ci->Event_model->ownerid;
        $selectInput['timezoneId'] = $this->ci->Event_model->timezoneid;
        $selectInput['startDateTime'] = $this->ci->Event_model->startdatetime;
        $selectInput['endDateTime'] = $this->ci->Event_model->enddatetime;
        $selectInput['url'] = $this->ci->Event_model->url;
        $selectInput['venueName'] = $this->ci->Event_model->venuename;
        $selectInput['venueaddress1'] = $this->ci->Event_model->venueaddress1;
        $selectInput['venueaddress2'] = $this->ci->Event_model->venueaddress2;

        $this->ci->Event_model->setSelect($selectInput);
        $where[$this->ci->Event_model->id] = $inputArray['eventId'];
        $where[$this->ci->Event_model->deleted] = 0;
        $this->ci->Event_model->setWhere($where);
        $eventData = $this->ci->Event_model->get();
        if (count($eventData) > 0) {
            $output['status'] = TRUE;
            $output['response']['eventData'] = $eventData[0];
            $output['response']['total'] = count($eventData);
            $output['statusCode'] = STATUS_OK;
            return $output;
        } else {
            $output['status'] = TRUE;
            $output['response']['messages'][] = ERROR_NO_EVENT;
            $output['response']['total'] = 0;
            $output['statusCode'] = STATUS_OK;
            return $output;
        }
    }

    public function updateTncDetails($inputArray) {
        $this->ci->load->model('Eventdetail_model');
        $this->ci->Eventdetail_model->resetVariable();
        $where[$this->eventCi->Eventdetail_model->eventdetail_id] = $inputArray['eventId'];
        $this->ci->Eventdetail_model->setWhere($where);
        $updateArray = array('organizertnc' => $inputArray['tncDescription']);
        $this->ci->Eventdetail_model->setInsertUpdateData($updateArray);
        $updateTnc = $this->ci->Eventdetail_model->update_data();
        if ($updateTnc) {
            $output['status'] = TRUE;
            $output['response']['messages'][] = SUCCESS_TNC_UPDATED;
            $output['statusCode'] = STATUS_UPDATED;
            return $output;
        }
        $output['status'] = FALSE;
        $output['response']['messages'][] = ERROR_SOMETHING_WENT_WRONG;
        $output['statusCode'] = STATUS_SERVER_ERROR;
        return $output;
    }

    public function getUserAndTicketDetail($inputArray) {
        $this->userHandler = new User_handler();
        $userData = $this->userHandler->getUserInfo($inputArray);
        $data['userName'] = " ";
        $data['email'] = ' ';
        $data['ticketDetails'] = " ";
        if ($userData['response']['total'] > 0 || $userData['status']) {
            $data['userName'] = $userData['response']['userData']['name'];
            $data['email'] = $userData['response']['userData']['email'];
        }//elseif ($userData['response']['total'] == 0) {
//            $output['status'] = TRUE;
//            $output['response']['message'][] = ERROR_NOT_REGESTRED;
//            $output['statusCode'] = 462;
//            return $output;
//        }
        //       $this->eventHandler = new Event_handler();
//        $event['eventId']=$inputArray['eventId'];
//        $event=$this->eventHandler->getEventTimeZoneName($event);
//        $inputArray['timeZoneName'] = $event['response']['details']['location']['timeZoneName'];
        $ticketsDetails = $this->ticketHandler->getEventTicketList($inputArray);
        if ($ticketsDetails['status'] && $ticketsDetails['response']['total'] > 0) {
            $ticketData = $ticketsDetails['response']['ticketList'];
            $data['ticketDetails'] = $ticketData;
        }
        return $data;
    }

    public function sendEmailToAttendees($inputArray) {

        $subject = $inputArray['subject'];
        $message = $inputArray['emailAttendeeMessage'];
        $replyEmail = $inputArray['replyEmail'];
		$from = $this->ci->config->item('me_not_exist_mailid');

        if (isset($inputArray['emailAttendeesSendMail'])) {
            //Finding email ids of the attendees to the selected ticket ids
            $emailList = $this->getSelectedAttendeesEmails($inputArray);
            ini_set('MAX_EXECUTION_TIME', -1);
            if ($emailList['status'] = TRUE && $emailList['response']['total'] > 0) {
                $this->emailHandler = new Email_handler();
                foreach ($emailList['response']['userData'] as $key => $value) {
                    $to = $value['email'];
                    $sentmessageInputs['notInsertIntoSentMessage'] = true;
                    $emailResponse = $this->emailHandler->sendEmail($from, $to, $subject, $message, '', $replyEmail, '', $sentmessageInputs);
                }
                if ($emailResponse['status']) {
                    $output = parent::createResponse(TRUE, SUCCESS_EMAIL_ATTENDEES, STATUS_OK);
                    return $output;
                } else {
                    $output = parent::createResponse(FALSE, ERROR_SOMETHING_WENT_WRONG, STATUS_SERVER_ERROR);
                    return $output;
                }
            } else {
                return $emailList;
            }
        } elseif (isset($inputArray['emailAttendeesSendTestMail'])) {
            $this->emailHandler = new Email_handler();
            $to = $inputArray['testMail'];
            $sentmessageInputs['notInsertIntoSentMessage'] = true;
            $emailResponse = $this->emailHandler->sendEmail($from, $to, $subject, $message, '',  $replyEmail, '', $sentmessageInputs);
            if ($emailResponse['status']) {
                $output = parent::createResponse(TRUE, SUCCESS_TEST_EMAIL_ATTENDEES, STATUS_OK);
                return $output;
            } else {
                $output = parent::createResponse(FALSE, ERROR_SOMETHING_WENT_WRONG, STATUS_SERVER_ERROR);
                return $output;
            }
        }
    }

    //Finding email ids of the attendees to the selected ticket ids
    public function getSelectedAttendeesEmails($inputArray) {
        $this->eventSignupHandler = new Eventsignup_handler();
        $this->eventSignupTicketDetailHandler = new Eventsignup_Ticketdetail_handler();
        $userIdList = array();
        if ($inputArray['ticketId'] == 0) {
            //As ticket id is not selected,we will send emails to all the attendees of the event
            if ($inputArray['toValue'] == 'ALL_ATTENDEES') {
                $eventSignupIds = $this->eventSignupHandler->getEventSignupId($inputArray);
                if ($eventSignupIds['status'] && $eventSignupIds['response']['total'] > 0) {
                    $eventSignupIds = $eventSignupIds['response']['eventsignupids'][0];
                    foreach ($eventSignupIds as $key => $value) {
                        $userIdList[] = $value['userid'];
                    }
                } elseif ($eventSignupIds['status'] && $eventSignupIds['response']['total'] == 0) {
                    $output = parent::createResponse(FALSE, 'No Attendee for this event', STATUS_NO_DATA, 0);
                    return $output;
                }
            } elseif ($inputArray['toValue'] == 'INCOMPLETE_TRANSACTIONS') {
                $incompleteTranscationSignupIds = $this->eventSignupHandler->getEventSignupId($inputArray, $transactiontype = 0, $paymentstatus = 0);
                if ($incompleteTranscationSignupIds['status'] && $incompleteTranscationSignupIds['response']['total'] > 0) {
                    $incompleteTranscationSignupIds = $incompleteTranscationSignupIds['response']['eventsignupids'][0];
                    foreach ($incompleteTranscationSignupIds as $key => $value) {
                        $userIdList[] = $value['userid'];
                    }
                } elseif ($incompleteTranscationSignupIds['status'] && $incompleteTranscationSignupIds['response']['total'] == 0) {
                    $output = parent::createResponse(FALSE, 'No Attendee for this event with incomplete transactions', STATUS_NO_DATA, 0);
                    return $output;
                }
            }
        } elseif ($inputArray['ticketId'] > 0) {
            //As ticketId is selected we will send emails to the specified ticket attendees
            if ($inputArray['toValue'] == 'ALL_ATTENDEES') {
                $eventSignUpIdList = array();
                $eventSignupIds = $this->eventSignupHandler->getEventSignupId($inputArray);
                if ($eventSignupIds['status'] && $eventSignupIds['response']['total'] > 0) {
                    $eventSignupIds = $eventSignupIds['response']['eventsignupids'][0];
                    $eventSignupInfo = commonHelperGetIdArray($eventSignupIds, 'id');
                    //Storing ids to select eventsignup ids specified to the ticket id
                    foreach ($eventSignupIds as $key => $value) {
                        $eventSignUpIdList[] = $value['id'];
                    }
                    $inputs['eventSignUpIdList'] = $eventSignUpIdList;
                    $inputs['ticketId'] = $inputArray['ticketId'];
                    //Finding eventSignup id who has specified ticketid from these eventSignup ids(success and not refunded/cancelled)
                    $ticketSpecificSignUpIds = $this->eventSignupTicketDetailHandler->getListByTicketAndSignupIds($inputs);
                    if ($ticketSpecificSignUpIds['status'] && $ticketSpecificSignUpIds['response']['total'] > 0) {
                        $ticketSpecificSignUpIds = $ticketSpecificSignUpIds['response']['eventSignupIdList'];
                        //Getting useridList
                        foreach ($ticketSpecificSignUpIds as $key => $value) {
                            $userIdList[] = $eventSignupInfo[$value['eventsignupid']]['userid'];
                        }
                    } elseif ($ticketSpecificSignUpIds['status'] && $ticketSpecificSignUpIds['response']['total'] == 0) {
                        $output = parent::createResponse(FALSE, 'No Attendee for this event ticket', STATUS_NO_DATA, 0);
                        return $output;
                    }
                } elseif ($eventSignupIds['status'] && $eventSignupIds['response']['total'] == 0) {
                    $output = parent::createResponse(FALSE, 'No Attendee for this event ticket', STATUS_NO_DATA, 0);
                    return $output;
                }
            } elseif ($inputArray['toValue'] == 'INCOMPLETE_TRANSACTIONS') {
                $incompleteTranscationSignupIds = $this->eventSignupHandler->getEventSignupId($inputArray, $transactiontype = 0, $paymentstatus = 0);
                if ($incompleteTranscationSignupIds['status'] && $incompleteTranscationSignupIds['response']['total'] > 0) {
                    $incompleteTranscationSignupIds = $incompleteTranscationSignupIds['response']['eventsignupids'][0];
                    $incompleteEventSignupInfo = commonHelperGetIdArray($incompleteTranscationSignupIds, 'id');
                    //Storing ids to select eventsignup ids specified to the ticket id
                    foreach ($incompleteTranscationSignupIds as $key => $value) {
                        $incompleteEventSignUpIdList[] = $value['id'];
                    }
                    $inputs['eventSignUpIdList'] = $incompleteEventSignUpIdList;
                    $inputs['ticketId'] = $inputArray['ticketId'];
                    //Finding eventSignup id  who has specified ticketid  from these eventSignup ids(success and not refunded/cancelled)
                    $ticketSpecificSignUpIds = $this->eventSignupTicketDetailHandler->getListByTicketAndSignupIds($inputs);
                    if ($ticketSpecificSignUpIds['status'] && $ticketSpecificSignUpIds['response']['total'] > 0) {
                        $ticketSpecificSignUpIds = $ticketSpecificSignUpIds['response']['eventSignupIdList'];
                        //Getting useridList
                        foreach ($ticketSpecificSignUpIds as $key => $value) {
                            $userIdList[] = $incompleteEventSignupInfo[$value['eventsignupid']]['userid'];
                        }
                    } elseif ($ticketSpecificSignUpIds['status'] && $ticketSpecificSignUpIds['response']['total'] == 0) {
                        $output = parent::createResponse(FALSE, 'No Attendee for this event ticket', STATUS_NO_DATA, 0);
                        return $output;
                    }
                } elseif ($incompleteTranscationSignupIds['status'] && $incompleteTranscationSignupIds['response']['total'] == 0) {
                    $output = parent::createResponse(FALSE, 'No Attendee for this event ticket with incomplete transactions', STATUS_NO_DATA, 0);
                    return $output;
                }
            }
        }

        //Getting mail ids from the userids
        $this->userHandler = new User_handler();
        $userInputs['userIdList'] = array_unique($userIdList); //To pass as an array to the method
        $emailList = $this->userHandler->getUserDetails($userInputs);
        return $emailList;
    }

    public function getUpcomingPastEventsCount() {
        $userId = getUserId();
        //collaborative events
        $collaborativeEventData = array();
        if ($this->eventCi->customsession->getData('isCollaborator') == 1) {
            $collaborator = new Collaborator_handler();
            $inputCollaboratorEvents['userids'] = array($userId);
            $inputCollaboratorEvents['getacesslevel'] = TRUE;
            $collaboratorResponse = $collaborator->getListByUserIds($inputCollaboratorEvents);
            if ($collaboratorResponse['status']) {
                if ($collaboratorResponse['response']['total'] > 0) {
                    foreach ($collaboratorResponse['response']['collaboratorList'] as $value) {
                        $collaborativeEventData[$value['eventid']] = $value['module'];
                    }
                }
            } else {
                return $collaboratorResponse;
            }
        }
        // echo 'collabor';print_r($collaborativeEventData);echo 'collabor';
        $this->eventCi->Event_model->resetVariable();
        $selectInput['eventCount'] = "count(" . $this->eventCi->Event_model->id . ")";
        if (count($collaborativeEventData) > 0) {
            $setOrWhere[$this->eventCi->Event_model->id] = array_keys($collaborativeEventData);
        }
        $setOrWhere[$this->eventCi->Event_model->ownerid] = $userId;
        $whereCondition[$this->eventCi->Event_model->enddatetime . " > "] = allTimeFormats('', 11);
        if (count($collaborativeEventData) > 0) {
            $setOrWhere[$this->eventCi->Event_model->id] = array_keys($collaborativeEventData);
        }
        $this->eventCi->Event_model->setSelect($selectInput);
        $this->eventCi->Event_model->setWhere($whereCondition);
        $this->eventCi->Event_model->setOrWhere($setOrWhere);
        $upcomingEventList = $this->eventCi->Event_model->get();
        // echo $this->ci->db->last_query();
        //  print_r($upcomingEventList);
        if ($upcomingEventList[0]['eventCount'] > 0) {
            $response = parent::createResponse(TRUE, "", STATUS_OK, ' ', 'upcomingEventsCount', $upcomingEventList[0]['eventCount']);
            return $response;
        } else if ($upcomingEventList[0]['eventCount'] == 0) {
            $this->eventCi->Event_model->resetVariable();
            $select['eventCount'] = "count(" . $this->eventCi->Event_model->id . ")";
            if (count($collaborativeEventData) > 0) {
                $whereOR[$this->eventCi->Event_model->id] = array_keys($collaborativeEventData);
            }
            $whereOR[$this->eventCi->Event_model->ownerid] = $userId;
            $where[$this->eventCi->Event_model->enddatetime . " < "] = allTimeFormats('', 11);
            if (count($collaborativeEventData) > 0) {
                $whereOR[$this->eventCi->Event_model->id] = array_keys($collaborativeEventData);
            }
            $this->eventCi->Event_model->setSelect($select);
            $this->eventCi->Event_model->setWhere($where);
            $this->eventCi->Event_model->setOrWhere($whereOR);
            $pastEventList = $this->eventCi->Event_model->get();
            if ($pastEventList[0]['eventCount'] > 0) {
                $response = parent::createResponse(TRUE, "", STATUS_OK, "", 'pastEventCount', $pastEventList[0]['eventCount']);
                return $response;
            } else if ($pastEventList[0]['eventCount'] == 0) {
                $response = parent::createResponse(TRUE, ERROR_NO_EVENTS, STATUS_OK, '', 'EventCount', 0);
                return $response;
            }
        }
    }

    public function getUserCurrentTicketEventCount($inputArray) {
        $selectInput = $orderBy = $whereCondition = array();
        $eventListIds = $inputArray['eventList'];
        $ticketType = isset($inputArray['ticketType']) ? $inputArray['ticketType'] : "current";
        $this->eventCi->Event_model->resetVariable();
        $selectInput['eventCount'] = 'count(' . $this->eventCi->Event_model->id . ')';
        $whereCondition[$this->eventCi->Event_model->deleted] = 0;
        if ($ticketType == "past") {
            $whereCondition[$this->eventCi->Event_model->enddatetime . " < "] = allTimeFormats('', 11);
        } else {
            $whereCondition[$this->eventCi->Event_model->enddatetime . " > "] = allTimeFormats('', 11);
        }

        $this->eventCi->Event_model->setSelect($selectInput);
        $this->eventCi->Event_model->setWhere($whereCondition);
        $this->eventCi->Event_model->setWhereIns(array($this->eventCi->Event_model->id => $eventListIds));
        $eventList = $this->eventCi->Event_model->get();
        if ($eventList != FALSE && $eventList[0]['eventCount'] > 0) {//No records are fetched
            $output['status'] = TRUE;
            $output['messages'] = array();
            $output['response']['total'] = $eventList[0]['eventCount'];
            $output['statusCode'] = STATUS_OK;
        } else {//No records are fetched
            $output['status'] = TRUE;
            $output['messages'][] = ERROR_NO_DATA;
            $output['response']['total'] = 0;
            $output['statusCode'] = STATUS_OK;
        }
        return $output;
    }

    //To retrive the organizer related events list
    public function getOrganizerEventList($data) {
        $this->fileHandler = new File_handler();
        $this->timezoneHandler = new Timezone_handler();
        $selectInput['Id'] = $this->ci->Event_model->id;
        $selectInput['StartDt'] = $this->ci->Event_model->startdatetime;
        $selectInput['EndDt'] = $this->ci->Event_model->enddatetime;
        $selectInput['Title'] = $this->ci->Event_model->title;
        $selectInput['eventURL'] = $this->ci->Event_model->url;
        $selectInput['Logo'] = $this->ci->Event_model->logo;
        $selectInput['Banner'] = $this->ci->Event_model->banner;
        $selectInput['categoryid'] = $this->ci->Event_model->categoryid;
        $selectInput['Banner'] = $this->ci->Event_model->banner;
        $selectInput['timezoneid'] = $this->ci->Event_model->timezoneid;

        $whereCondition[$this->eventCi->Event_model->deleted] = 0;
        $whereCondition[$this->eventCi->Event_model->ownerid] = $data['ownerId'];

        $orderBy[] = $this->eventCi->Event_model->id . " desc ";

        $this->eventCi->Event_model->setSelect($selectInput);
        $this->eventCi->Event_model->setWhere($whereCondition);


        $this->eventCi->Event_model->setOrderBy($orderBy);
        $upcomingEventList = $this->eventCi->Event_model->get();
//        echo $this->eventCi->db->last_query();exit;


        if (count($upcomingEventList) > 0) {
            $response = $output = array();

            //retrive the event thumb & log id information
            foreach ($upcomingEventList as $value) {
                $fileIdsArray[] = $value['Logo'];
                $fileIdsArray[] = $value['Banner'];
            }

            $eventFileidsData = array('id', $fileIdsArray);
            $fileData = $this->fileHandler->getFileData($eventFileidsData);

            if ($fileData['status'] && $fileData['response']['total'] > 0) {
                //converting to indexed based array
                $fileData = commonHelperGetIdArray($fileData['response']['fileData']);
            }
            
            //Bring the timezone list
           
            

         //format the result data   
         foreach ($upcomingEventList as $rKey => $rValue) {
                $response[$rKey]['Id'] = $rValue['Id'];
                $timezoneDetails = $this->timezoneHandler->details(array('timezoneId' => $rValue['timezoneid']));
                
                $timeZoneName="";
                if ($timezoneDetails['status'] && count($timezoneDetails) > 0) {
                 
                    $timeZoneName= $timezoneDetails['response']['detail'][$rValue['timezoneid']]['name'];
                }
//                 $response[$rKey]['StartDt']['timezonename']=$timeZoneName;
                $response[$rKey]['StartDt'] = convertTime($rValue['StartDt'], $timeZoneName, true);
//                allTimeFormats($rValue['StartDt'], 11);
                $response[$rKey]['EndDt'] = convertTime($rValue['EndDt'], $timeZoneName, true);
                $response[$rKey]['Title'] = $rValue['Title'];
                $response[$rKey]['eventURL'] = commonHelperEventDetailUrl($rValue['eventURL']);

                $defaultPath="";
//                if($rValue['Id']==83294){
//                    echo 111;
//                    print_r($rValue);
//                    print_r($fileData);
//                    exit;
//                }
                if((strlen($fileData[$rValue['Logo']]['path']) ==0 ) || (strlen($fileData[$rValue['Banner']]['path']) ==0) ){
                    $catDetails = $this->getcategoryDetails($rValue["categoryid"]);
                    $defaultPath=$catDetails['categorydefaultthumbanilid'];
                }
                $response[$rKey]['Logo'] = $response[$rKey]['Banner'] = $defaultPath;
                if (isset($rValue['Logo']) && is_array($fileData[$rValue['Logo']]) && $fileData[$rValue['Logo']]['path'] != '') {
                    $response[$rKey]['Logo'] = $this->ci->config->item('images_content_cloud_path') . $fileData[$rValue['Logo']]['path'];
                }
                if (isset($rValue['Banner']) && is_array($fileData[$rValue['Banner']]) && $fileData[$rValue['Banner']]['path'] != '') {
                    $response[$rKey]['Banner'] = $this->ci->config->item('images_content_cloud_path') . $fileData[$rValue['Banner']]['path'];
                }
                
                
            }
            $output['status'] = TRUE;
            $output['response'] = $response;
           // $output['response']['total'] = count($upcomingEventList);
            $output['statusCode'] = STATUS_OK;
        } else {
            $output['status'] = TRUE;
            $output['response']['message'] = ERROR_NO_DATA;
            $output['response']['total'] = 0;
            $output['statusCode'] = STATUS_OK;
        }


        return $output;
    }
    
    //To retrive the organizer related particular event ticket details
    public function getOrganizerEventTicketList($request) {
        //check the valid organaizer event
        $organizerDetails = $this->isOrganizerForEvent($request);
        if (!$organizerDetails['status'] || $organizerDetails['response']['totalCount'] == 0) {
            $response["status"] = "failure";
            $response['statusCode'] = STATUS_OK;
            $response["message"] = $organizerDetails['response']['messages'];

            return $response;
        }

        $ticketList = $this->ticketHandler->getTicketName($request);
        if($ticketList['response']['total']==0){
           return  $ticketList;
        }

        //currencies list to display beside amounts
        $currencyHanlder = new Currency_handler();
        $indexedCurrencyListById = $indexedCurrencyListByCode = array();
        $currencyResponse = $currencyHanlder->getCurrencyList();
        if ($currencyResponse['status'] && $currencyResponse['response']['total'] > 0) {
            $indexedCurrencyListById = commonHelperGetIdArray($currencyResponse['response']['currencyList'], 'currencyId');
            $indexedCurrencyListByCode = commonHelperGetIdArray($currencyResponse['response']['currencyList'], 'currencyCode');
        }
        //event details
        $eventDetails = $this->getEventName($request['eventId']);
        $response['EventId'] = $request['eventId'];
        $response['EventName'] = $eventDetails['response']['eventName'];
        
        //Ticket details
        $tickets_array = array();
        foreach ($ticketList['response']['ticketName'] as $key => $ticket) {
            $tickets_array[$key]['Id'] = $ticket['id'];
            $tickets_array[$key]['Name'] = $ticket['name'];
            $tickets_array[$key]['Description'] = $ticket['description'];
            $tickets_array[$key]['Price'] = $ticket['price'];
            $tickets_array[$key]['currencyId'] = $indexedCurrencyListById[$ticket['currencyid']]['currencyCode'];
        }
        $response["status"] = "success";
        $response["tickets_array"] = $tickets_array;
        return $response;
    }
    
    //To get the organizer related specific event information
    public function getOrganizerEventDetails($request) {
        //check the valid organaizer event
        $organizerDetails = $this->isOrganizerForEvent($request);
        if (!$organizerDetails['status'] || $organizerDetails['response']['totalCount'] == 0) {
            $response["status"] = "failure";
            $response['statusCode'] = STATUS_OK;
            $response["message"] = $organizerDetails['response']['messages'];
            return $response;
        }
        //bring the event details
        $eventDetails = $this->getEventDetails($request);
        $timezoneId = $eventDetails['response']['details']['timeZoneId'];
        $this->timezoneHandler = new Timezone_handler();
        $timezoneDetails = $this->timezoneHandler->details(array('timezoneId' => $timezoneId));
        $eventResponse = $response = array();
        $eventResponse['Id'] = $eventDetails['response']['details']['id'];
        
        $this->userHandler = new User_handler();
        $userInput['userIds']=$eventDetails['response']['details']['ownerId'];
        $userDeails=$this->userHandler->getUserInfo($userInput);
         
        $eventResponse['UserName'] = $userDeails['response']['userData']['username'];
        $timeZoneName = "";
        if ($timezoneDetails['status'] && count($timezoneDetails) > 0) {

            $timeZoneName = $timezoneDetails['response']['detail'][$timezoneId]['name'];
        }

        $eventResponse['StartDt'] = convertTime($eventDetails['response']['details']['startDate'], $timeZoneName, true);
        $eventResponse['EndDt'] = convertTime($eventDetails['response']['details']['endDate'], $timeZoneName, true);
        $eventResponse['Title'] = $eventDetails['response']['details']['title'];
        $eventResponse['Description'] = $eventDetails['response']['details']['description'];
        $eventResponse['Country'] = $eventDetails['response']['details']['location']['countryName'];
        $eventResponse['State'] = $eventDetails['response']['details']['location']['stateName'];
        $eventResponse['City'] = $eventDetails['response']['details']['location']['cityName'];
        $eventResponse['Loc'] = $eventDetails['response']['details']['location']['address1'];
        $eventResponse['Venue'] = $eventDetails['response']['details']['location']['venueName'];
        $eventResponse['URL'] = $eventDetails['response']['details']['eventUrl'];
        $eventResponse['Logo'] = $eventDetails['response']['details']['thumbnailPath'];
        $eventResponse['Banner'] = $eventDetails['response']['details']['bannerPath'];
        $eventResponse['CatName'] = $eventDetails['response']['details']['categoryName'];
        $eventResponse['SubCatName'] = $eventDetails['response']['details']['subCategoryName'];
        $eventResponse['Pincode'] = $eventDetails['response']['details']['pincode'];

        $response['event_detail_arr'] = $eventResponse;
        $response["message"] = "success";
        $response['statusCode'] = STATUS_OK;
        return $response;
    }
    
    public function getOrganizerEventReport($request){
        //check the valid organaizer event
        $organizerDetails = $this->isOrganizerForEvent($request);
        if (!$organizerDetails['status'] || $organizerDetails['response']['totalCount'] == 0) {
            $response["status"] = "failure";
            $response['statusCode'] = STATUS_OK;
            $response["message"] = "There is no such an event for the User";
            return $response;
        }
        
    }

}
