<?php

/**
 * Ticket related business logic will be defined in this class
 * Getting Banners Related data
 * @package		CodeIgniter
 * @author		Qison  Dev Team
 * @param		CountryId - required
 *                      cityId,categoryId,type (optional)
 * @addTicket		name,type,description,eventId,price,quantity,
 *                     minOrderQuantity,maxOrderQuantity,startTime,endTime,order,currencyId
 *                    soldOut,endDate,startDate,displayStatus,label[0],value[0]
 *                    @lable and value should be arrays
 * @copyright	Copyright (c) 2015, MeraEvents.
 * @Version		Version 1.0
 * @Since       Class available since Release Version 1.0
 * @Created     16-06-2015
 * @Last Modified 16-06-2015
 */
require_once (APPPATH . 'handlers/handler.php');
require_once (APPPATH . 'handlers/currency_handler.php');
require_once (APPPATH . 'handlers/sessionlock_handler.php');
require_once(APPPATH . 'handlers/eventsignup_handler.php');
require_once(APPPATH . 'handlers/event_handler.php');
require_once(APPPATH . 'handlers/timezone_handler.php');
class Ticket_handler extends Handler {

    var $ci;
    var $tickettaxHandler;
    var $currencyHandler;

    public function __construct() {
        parent::__construct();
        $this->ci = parent::$CI;
        $this->ci->load->model('Ticket_model');
    }

    public function getEventTicketList($request) {
        
        
        $output = array();
        $validationStatus = $this->eventTicketListValidation($request);
        if ($validationStatus['error'] == TRUE) {
            $output['status'] = FALSE;
            $output['response']['messages'] = $validationStatus['message'];
            $output['statusCode'] = STATUS_BAD_REQUEST;
            return $output;
        }
        $this->ci->Ticket_model->resetVariable();
        $selectInput['id'] = $this->ci->Ticket_model->id;
        $selectInput['name'] = $this->ci->Ticket_model->name;
        $selectInput['description'] = $this->ci->Ticket_model->description;
        $selectInput['eventId'] = $this->ci->Ticket_model->eventid;
        $selectInput['price'] = $this->ci->Ticket_model->price;
        $selectInput['quantity'] = $this->ci->Ticket_model->quantity;
        $selectInput['minOrderQuantity'] = $this->ci->Ticket_model->minorderquantity;
        $selectInput['maxOrderQuantity'] = $this->ci->Ticket_model->maxorderquantity;
        $selectInput['startDate'] = $this->ci->Ticket_model->startdatetime;
        $selectInput['endDate'] = $this->ci->Ticket_model->enddatetime;
        $selectInput['status'] = $this->ci->Ticket_model->status;
        $selectInput['quantity'] = $this->ci->Ticket_model->quantity;
        $selectInput['totalSoldTickets'] = $this->ci->Ticket_model->totalsoldtickets;
        $selectInput['type'] = $this->ci->Ticket_model->type;
        $selectInput['order'] = $this->ci->Ticket_model->order;
        $selectInput['displayStatus'] = $this->ci->Ticket_model->displaystatus;
        $selectInput['currencyId'] = $this->ci->Ticket_model->currencyid;
        $selectInput['soldout'] = $this->ci->Ticket_model->soldout;
        $this->ci->Ticket_model->setSelect($selectInput);

        //fetching active tickets & not deleted
        $where[$this->ci->Ticket_model->eventid] = $request['eventId'];

        //if ticketids parameter is set pass in IN CONDITION and ignore ticketid
        $this->ci->Ticket_model->whereInArray = array();
        if (isset($request['ticketIds']) && is_array($request['ticketIds']) && count($request['ticketIds']) > 0) {
            $this->ci->Ticket_model->whereInArray = array('id', $request['ticketIds']);
        } elseif (isset($request['ticketId']) && $request['ticketId'] > 0) {
            $where[$this->ci->Ticket_model->id] = $request['ticketId'];
        }

        $where[$this->ci->Ticket_model->status] = (isset($request['status'])) ? $request['status'] : 1;
        $where[$this->ci->Ticket_model->deleted] = 0;
        if (isset($request['allTickets']) && $request['allTickets'] == 0) {
            $where[$this->ci->Ticket_model->displaystatus] = 1;
        }
        $this->ci->Ticket_model->setWhere($where);
        $this->ci->Ticket_model->setWhereNotIn(array());
        $whereNotIn = array();
        if (isset($request['ticketCurrencyId']) && $request['ticketCurrencyId'] > 0) {
            $whereNotIn[$this->ci->Ticket_model->currencyid] = $request['ticketCurrencyId'];

            $whereNotIn[$this->ci->Ticket_model->type] = array('free');
            $this->ci->Ticket_model->setWhereNotIn($whereNotIn);
        }

        //Order by array
        $orderBy = array();
        $orderBy[] = $this->ci->Ticket_model->order;
        if (isset($request['statuslabels']) && $request['statuslabels'] == 1) {
            $orderBy[] = $this->ci->Ticket_model->displaystatus . " DESC ";
        }
        $this->ci->Ticket_model->setOrderBy($orderBy);

        $tempTicketDetails = $this->ci->Ticket_model->get();

        if (count($tempTicketDetails) == 0) {
            $output['status'] = TRUE;
            $output['response']['messages'][] = ERROR_NO_TICKET;
            $output['response']['total'] = 0;
            $output['statusCode'] = STATUS_OK;
            return $output;
        }
        // getting currency details based on currencyid
        $ticketArray = array();
        foreach ($tempTicketDetails as $ticket) {
            $ticketArray[] = $ticket['currencyId'];
            $ticketIds[] = $ticket['id'];
        }
        $ticketList['idList'] = array_unique($ticketArray);
        $this->ci->Ticket_model->setWhereNotIn(array());
        
        $this->currencyHandler = new Currency_handler();
        $currencyListResponse = $this->currencyHandler->getCurrencyList($ticketList);


        if ($currencyListResponse['status'] == TRUE && $currencyListResponse['response']['total'] > 0) {
            $currencyList = $currencyListResponse['response']['currencyList'];
            foreach ($currencyList as $value) {
                $currencyId[$value['currencyId']] = $value;
            }
        }
        $disableSessionLockTickets = false;
        if (isset($request['disableSessionLockTickets']) && $request['disableSessionLockTickets']) {
            $disableSessionLockTickets = $request['disableSessionLockTickets'];
        }
        
        $tempTickets = array();
        if ($this->ci->config->item('tempTicketsEnabled') == TRUE && !$disableSessionLockTickets) {
            $sessionlockHandler = new Sessionlock_handler();
            $inputSessionlock['ticketIds'] = $ticketIds;
            $tempTicketsResponse = $sessionlockHandler->getCountByTicketIds($inputSessionlock);
            if (!$tempTicketsResponse['status']) {
                return $tempTicketsResponse;
            }
            $tempTickets = $tempTicketsResponse['response']['sessionlockList'];
        }
        $ticketmapdetails = $ticketTaxArray = $ticketDetails = array();

        // getting event time zone and append to date format
        if (isset($request['eventTimezoneId']) && $request['eventTimezoneId'] > 0) {

            $this->timezoneHandler = new Timezone_handler();
            $inputZoneArray['timezoneId'] = $request['eventTimezoneId'];
            $timeZoneResponse = $this->timezoneHandler->details($inputZoneArray);
            $request['timeZoneName'] = $timeZoneResponse['response']['detail'][$request['eventTimezoneId']]['name'];
        } elseif ($request['eventTimezoneName']) {
            $request['timeZoneName'] = $request['eventTimezoneName'];
        } else {
            $this->eventHandler = new Event_handler();
            $event['eventId'] = $request['eventId'];
            $event = $this->eventHandler->getEventTimeZoneName($event);
            if ($event['total'] == 0) {
                return $event;
            }
            $request['timeZoneName'] = $event['response']['details']['location']['timeZoneName'];
        }

        foreach ($tempTicketDetails as $ticket) {
            $whereCurrency['currencyId'] = $ticket['currencyId'];
            $currencyDetails = $currencyId[$ticket['currencyId']];

            $ticket['currencyCode'] = $currencyDetails['currencyCode'];
            $ticket['currencySymbol'] = $currencyDetails['currencySymbol'];
            $ticket['startDate'] = convertTime($ticket['startDate'], $request['timeZoneName'], TRUE);
            $ticket['endDate'] = convertTime($ticket['endDate'], $request['timeZoneName'], TRUE);

            $ticketTaxArray['ticketIds'][] = $ticket['id'];
            if (isset($tempTickets) && count($tempTickets) > 0) {
                $ticket['totalSoldTickets'] += $tempTickets[$ticket['id']];
                $availableCount = $ticket['quantity'] - $ticket['totalSoldTickets'];
                if ($ticket['maxOrderQuantity'] > $availableCount) {
                    if ($availableCount > 0) {
                        $ticket['maxOrderQuantity'] = $availableCount;
                    } else {
                        $ticket['soldout'] = 1;
                        $ticket['maxOrderQuantity'] = $ticket['minOrderQuantity'] = 0;
                    }
                }
                // for guest booking purpose  we didnt check min,max limit quantitys
                if (isset($request['bookingType']) && $request['bookingType']) {
                    $ticket['soldout'] = 0;
                    $ticket['maxOrderQuantity'] = $this->ci->config->item('guestListMaxOrderQuantity');
                    $ticket['minOrderQuantity'] = $this->ci->config->item('guestListMinOrderQuantity');
                }
            }
            $ticket['ticketIds'][] = $ticketDetails[] = $ticket;
        }

        if (count($ticketDetails) > 0) {
            //Apend the ticket tax list
            $taxresult = $this->getTaxMappingDetails($ticketTaxArray);
            if ($taxresult['status'] && $taxresult['response']['total'] > 0) {
                foreach ($taxresult['response']['taxList'] as $key => $val) {
                    $ticketmapdetails[$val['ticketid']][] = $val['taxmappingid'];
                    $ticketMappingIds[] = $val['taxmappingid'];
                }
                $output['response']['taxList'] = $ticketmapdetails;
            }
            if (isset($ticketMappingIds) && count($ticketMappingIds) > 0) {
                $taxMappingHandler = new Taxmapping_handler();
                $inputTM['ids'] = $ticketMappingIds;
                $taxMappingResponse = $taxMappingHandler->getTaxmapping($inputTM);
            }
            if (isset($taxMappingResponse)) {
                if ($taxMappingResponse['status'] && $taxMappingResponse['response']['total'] > 0) {
                    $indexedTaxMappingData = commonHelperGetIdArray($taxMappingResponse['response']['taxMappingList']);
                } else {
                    return $taxMappingResponse;
                }
            }
            if (isset($indexedTaxMappingData)) {
                $taxHandler = new Tax_handler();
                $taxList = $taxHandler->getTaxList();
            }
            if (isset($taxList)) {
                if ($taxList['status'] && $taxList['response']['total'] > 0) {
                    $indexedTaxList = commonHelperGetIdArray($taxList['response']['taxList']);
                } else {
                    return $taxList;
                }
            }

            $i = 0;
            $ticketmapData = array();
            if ($taxresult['status'] && $taxresult['response']['total'] > 0) {
                foreach ($taxresult['response']['taxList'] as $key => $val) {
                    $ticketmapData[$val['ticketid']][$i]['taxmappingid'] = $val['taxmappingid'];
                    $ticketmapData[$val['ticketid']][$i]['label'] = $indexedTaxList[$indexedTaxMappingData[$val['taxmappingid']]['taxid']]['label'];
                    $ticketmapData[$val['ticketid']][$i]['value'] = $indexedTaxMappingData[$val['taxmappingid']]['value'];
                    $i++;
                }
            }
            $output['response']['taxDetails'] = $ticketmapData;
            $output['status'] = TRUE;
            $output['response']['ticketList'] = $ticketDetails;
            $output['response']['total'] = count($ticketDetails);
            $output['response']['messages'] = array();
            $output['statusCode'] = STATUS_OK;
            return $output;
        } else {
            $output['status'] = TRUE;
            $output['response']['messages'][] = ERROR_NO_TICKET;
            $output['response']['total'] = 0;
            $output['statusCode'] = STATUS_OK;
            return $output;
        }
    }

    public function getActualEventTicketList($request) {
        $output = array();
        $validationStatus = $this->eventTicketListValidation($request);
        if ($validationStatus['error'] == TRUE) {
            $output['status'] = FALSE;
            $output['response']['messages'] = $validationStatus['message'];
            $output['statusCode'] = STATUS_BAD_REQUEST;
            return $output;
        }
        $this->ci->Ticket_model->resetVariable();
        $selectInput['id'] = $this->ci->Ticket_model->id;
        $selectInput['name'] = $this->ci->Ticket_model->name;
        $selectInput['description'] = $this->ci->Ticket_model->description;
        $selectInput['eventId'] = $this->ci->Ticket_model->eventid;
        $selectInput['price'] = $this->ci->Ticket_model->price;
        $selectInput['quantity'] = $this->ci->Ticket_model->quantity;
        $selectInput['minOrderQuantity'] = $this->ci->Ticket_model->minorderquantity;
        $selectInput['maxOrderQuantity'] = $this->ci->Ticket_model->maxorderquantity;
        $selectInput['startDate'] = $this->ci->Ticket_model->startdatetime;
        $selectInput['endDate'] = $this->ci->Ticket_model->enddatetime;
        $selectInput['status'] = $this->ci->Ticket_model->status;
        $selectInput['quantity'] = $this->ci->Ticket_model->quantity;
        $selectInput['totalSoldTickets'] = $this->ci->Ticket_model->totalsoldtickets;
        $selectInput['type'] = $this->ci->Ticket_model->type;
        $selectInput['order'] = $this->ci->Ticket_model->order;
        $selectInput['displayStatus'] = $this->ci->Ticket_model->displaystatus;
        $selectInput['currencyId'] = $this->ci->Ticket_model->currencyid;
        $selectInput['soldout'] = $this->ci->Ticket_model->soldout;
        $this->ci->Ticket_model->setSelect($selectInput);

//fetching active tickets & not deleted
        $where[$this->ci->Ticket_model->eventid] = $request['eventId'];

        //if ticketids parameter is set pass in IN CONDITION and ignore ticketid
        if (isset($request['ticketIds']) && is_array($request['ticketIds']) && count($request['ticketIds']) > 0) {
            $this->ci->Ticket_model->whereInArray = array('id', $request['ticketIds']);
        } elseif (isset($request['ticketId']) && $request['ticketId'] > 0) {
            $where[$this->ci->Ticket_model->id] = $request['ticketId'];
        }

        $where[$this->ci->Ticket_model->status] = (isset($request['status'])) ? $request['status'] : 1;
        $where[$this->ci->Ticket_model->deleted] = 0;
        if (isset($request['allTickets']) && $request['allTickets'] == 0) {
            $where[$this->ci->Ticket_model->displaystatus] = 1;
        }
        $this->ci->Ticket_model->setWhere($where);

//Order by array
        $orderBy = array();
        $orderBy[] = $this->ci->Ticket_model->order;
        $this->ci->Ticket_model->setOrderBy($orderBy);

        $tempTicketDetails = $this->ci->Ticket_model->get();

        if (count($tempTicketDetails) == 0) {
            $output['status'] = TRUE;
            $output['response']['messages'][] = ERROR_NO_TICKET;
            $output['response']['total'] = 0;
            $output['statusCode'] = STATUS_OK;
            return $output;
        }
        // getting currency details based on currencyid
        $ticketArray = array();
        foreach ($tempTicketDetails as $ticket) {
            $ticketArray[] = $ticket['currencyId'];
            $ticketIds[] = $ticket['id'];
        }
        $ticketList['idList'] = array_unique($ticketArray);

        $this->currencyHandler = new Currency_handler();
        $currencyListResponse = $this->currencyHandler->getCurrencyList($ticketList);


        if ($currencyListResponse['status'] == TRUE && $currencyListResponse['response']['total'] > 0) {
            $currencyList = $currencyListResponse['response']['currencyList'];
            foreach ($currencyList as $value) {
                $currencyId[$value['currencyId']] = $value;
            }
        }
        $ticketmapdetails = $ticketTaxArray = $ticketDetails = array();

        foreach ($tempTicketDetails as $ticket) {
            $whereCurrency['currencyId'] = $ticket['currencyId'];
            $currencyDetails = $currencyId[$ticket['currencyId']];
            $ticket['currencyCode'] = $currencyDetails['currencyCode'];
            $ticket['startDate'] = convertTime($ticket['startDate'], $request['eventTimeZoneName'], true);
            $ticket['endDate'] = convertTime($ticket['endDate'], $request['eventTimeZoneName'], true);
            $ticketTaxArray['ticketIds'][] = $ticket['id'];
            $ticket['ticketIds'][] = $ticketDetails[] = $ticket;
        }
        if (count($ticketDetails) > 0) {
            //Apend the ticket tax list
            $taxresult = $this->getTaxMappingDetails($ticketTaxArray);

            if ($taxresult['status'] && $taxresult['response']['total'] > 0) {
                foreach ($taxresult['response']['taxList'] as $key => $val) {
                    $ticketmapdetails[$val['ticketid']][] = $val['taxmappingid'];
                    $ticketMappingIds[] = $val['taxmappingid'];
                }
                $output['response']['taxList'] = $ticketmapdetails;
            }
            if (isset($ticketMappingIds) && count($ticketMappingIds) > 0) {
                $taxMappingHandler = new Taxmapping_handler();
                $inputTM['ids'] = $ticketMappingIds;
                $taxMappingResponse = $taxMappingHandler->getTaxmapping($inputTM);
            }
            if (isset($taxMappingResponse)) {
                if ($taxMappingResponse['status'] && $taxMappingResponse['response']['total'] > 0) {
                    $indexedTaxMappingData = commonHelperGetIdArray($taxMappingResponse['response']['taxMappingList']);
                } else {
                    return $taxMappingResponse;
                }
            }
            if (isset($indexedTaxMappingData)) {
                $taxHandler = new Tax_handler();
                $taxList = $taxHandler->getTaxList();
            }
            if (isset($taxList)) {
                if ($taxList['status'] && $taxList['response']['total'] > 0) {
                    $indexedTaxList = commonHelperGetIdArray($taxList['response']['taxList']);
                } else {
                    return $taxList;
                }
            }
            $ticketmapData = array();
            foreach ($taxresult['response']['taxList'] as $key => $val) {
                $ticketmapData[$val['ticketid']][$indexedTaxMappingData[$val['taxmappingid']]['taxid']]['taxmappingid'] = $val['taxmappingid'];
                $ticketmapData[$val['ticketid']][$indexedTaxMappingData[$val['taxmappingid']]['taxid']]['taxid'] = $indexedTaxMappingData[$val['taxmappingid']]['taxid'];
                $ticketmapData[$val['ticketid']][$indexedTaxMappingData[$val['taxmappingid']]['taxid']]['label'] = $indexedTaxList[$indexedTaxMappingData[$val['taxmappingid']]['taxid']]['label'];
                $ticketmapData[$val['ticketid']][$indexedTaxMappingData[$val['taxmappingid']]['taxid']]['value'] = $indexedTaxMappingData[$val['taxmappingid']]['value'];
            }
            $output['response']['taxDetails'] = $ticketmapData;
            // }
            $output['status'] = TRUE;
            $output['response']['ticketList'] = $ticketDetails;
            $output['response']['total'] = count($ticketDetails);
            $output['response']['messages'] = array();
            $output['statusCode'] = STATUS_OK;
            return $output;
        } else {
            $output['status'] = TRUE;
            $output['response']['messages'][] = ERROR_NO_TICKET;
            $output['response']['total'] = 0;
            $output['statusCode'] = STATUS_OK;
            return $output;
        }
    }

//event ticket details vaidation
    public function eventTicketListValidation($inputs) {
        $errorMessages = array();
        $this->ci->form_validation->reset_form_rules();
        $this->ci->form_validation->pass_array($inputs);

        $this->ci->form_validation->set_rules('eventId', 'eventId', 'required_strict|is_natural_no_zero');
        $this->ci->form_validation->set_rules('ticketIds', 'ticketIds', 'is_array');
        $this->ci->form_validation->set_rules('status', 'status', 'is_natural');

        if ($this->ci->form_validation->run() === FALSE) {
            return $this->ci->form_validation->get_errors();
        }
        $errorMessages['error'] = FALSE;
        return $errorMessages;
    }

    function add($inputArray) {

        $validationStatus = $this->validateAddTicket($inputArray);
        if ($validationStatus['error'] == TRUE) {
            $output['status'] = FALSE;
            $output['response']['messages'][] = $validationStatus['message'];
            $output['statusCode'] = STATUS_BAD_REQUEST;
            return $output;
        }
        if ($inputArray['type'] == 1) {
            $inputArray['price'] = 0;
        }
        $createTicket = array();

        $startDate = urldecode($inputArray['startDate']);
        $startTime = urldecode($inputArray['startTime']);
        $endDate = urldecode($inputArray['endDate']);
        $endTime = urldecode($inputArray['endTime']);

        $startDate = dateFormate($inputArray['startDate'], '/');
        $endDate = dateFormate($inputArray['endDate'], '/');

        $startDateTime = convertTime($startDate . ' ' . $inputArray['startTime'], $inputArray['timeZoneName']);
        $endDateTime = convertTime($endDate . ' ' . $inputArray['endTime'], $inputArray['timeZoneName']);

//        if (strtotime(date("Y-m-d H:i:s")) > strtotime($startDateTime)) {
//            $output['status'] = FALSE;
//            $output['response']['messages'][] = ERROR_START_DATE_GREATER_THAN_NOW;
//            $output['statusCode'] = 400;
//            return $output;
//        }
        if (strtotime($startDateTime) > strtotime($endDateTime)) {
            $output['status'] = FALSE;
            $output['response']['messages'][] = ERROR_TICKET_START_DATE_GREATER;
            $output['statusCode'] = STATUS_BAD_REQUEST;
            return $output;
        }
        $this->ci->Ticket_model->resetVariable();
        $createTicket[$this->ci->Ticket_model->name] = $inputArray['name'];
        $createTicket[$this->ci->Ticket_model->type] = $inputArray['type'];
        $createTicket[$this->ci->Ticket_model->description] = isset($inputArray['description'])?$inputArray['description']:'';
        $createTicket[$this->ci->Ticket_model->eventid] = $inputArray['eventId'];
        $createTicket[$this->ci->Ticket_model->price] = isset($inputArray['price'])?$inputArray['price']:0;
        $createTicket[$this->ci->Ticket_model->quantity] = $inputArray['quantity'];
        $createTicket[$this->ci->Ticket_model->minorderquantity] = $inputArray['minOrderQuantity'];
        $createTicket[$this->ci->Ticket_model->maxorderquantity] = $inputArray['maxOrderQuantity'];
        $createTicket[$this->ci->Ticket_model->startdatetime] = $startDateTime;
        $createTicket[$this->ci->Ticket_model->enddatetime] = $endDateTime;
        $createTicket[$this->ci->Ticket_model->order] = $inputArray['order'];
        $createTicket[$this->ci->Ticket_model->currencyid] = $inputArray['currencyId'];
        if (isset($inputArray['soldOut'])) {
            $createTicket[$this->ci->Ticket_model->soldout] = $inputArray['soldOut'];
        }
        if (isset($inputArray['displayStatus'])) {
            $createTicket[$this->ci->Ticket_model->displaystatus] = $inputArray['displayStatus'];
        }

        $this->ci->Ticket_model->setInsertUpdateData($createTicket);
        $ticketId = $this->ci->Ticket_model->insert_data();
        $result['status'] = TRUE;
        $arrayCounter = 0;

        if ($ticketId && count($inputArray['taxArray']) > 0) {
            foreach ($inputArray['taxArray'] as $taxkey => $taxValues) {
                $taxArray[$arrayCounter]['ticketid'] = $ticketId;
                $taxArray[$arrayCounter]['taxmappingid'] = $taxValues;
                $taxArray[$arrayCounter]['status'] = 1;
                $arrayCounter++;
            }

            $result = $this->addTicketTax($taxArray);
            if ($result['status']) {
                $output['status'] = TRUE;
                $output["response"]["message"][] = SUCCESS_TICKET_ADDED;
                $output['statusCode'] = STATUS_CREATED;
                return $output;
            }
        } else if ($ticketId && count($inputArray['taxArray']) == 0) {
            $output['status'] = TRUE;
            $output["response"]["message"][] = SUCCESS_TICKET_ADDED;
            $output['statusCode'] = STATUS_CREATED;
            return $output;
        }
        $output['status'] = FALSE;
        $output["response"]["message"][] = ERROR_ADD_TICKET;
        $output['statusCode'] = STATUS_BAD_REQUEST;
        return $output;
    }

    public function validateAddTicket($inputs) {
        //print_r($inputs);exit;
        $this->ci->form_validation->pass_array($inputs);
//The name field here contains alpha,space and symbols , - &		
        $this->ci->form_validation->set_rules('name', 'Ticket Name', 'required_strict|min_length[2]|max_length[75]');
        $this->ci->form_validation->set_rules('type', 'Ticket Type', 'required_strict');
//        $this->ci->form_validation->set_rules('description', 'Ticket Description', 'max_length[120]');
        $this->ci->form_validation->set_rules('eventId', 'EventId', 'is_natural_no_zero|required_strict');
        $this->ci->form_validation->set_rules('quantity', 'quantity', 'is_natural|required_strict');
        $this->ci->form_validation->set_rules('minOrderQuantity', 'Minorder Quantity', 'is_natural_no_zero|required_strict');
        $this->ci->form_validation->set_rules('maxOrderQuantity', 'MaxorderQuantity', 'is_natural_no_zero|required_strict');
        $this->ci->form_validation->set_rules('startDate', 'Ticket Start Date', 'required_strict');
        $this->ci->form_validation->set_rules('startTime', 'Ticket Start Time', 'required_strict|time');
        $this->ci->form_validation->set_rules('endDate', 'Ticket End Date', 'required_strict');
        $this->ci->form_validation->set_rules('endTime', 'Ticket End Time', 'required_strict|time');
        $this->ci->form_validation->set_rules('order', 'Order', 'is_natural|required_strict');
        $this->ci->form_validation->set_rules('currencyId', 'CurrencyId', 'is_natural_no_zero|required_strict');
        $this->ci->form_validation->set_rules('soldOut', 'SoldOut', 'enable|is_natural');
        $this->ci->form_validation->set_rules('displayStatus', 'DisplayStatus', 'enable|is_natural');
// ticketTax feild validation
//$taxArray=array('field'=>array('label'),'rules'=>array('name'));
//        $taxArray[0]['field']='';
//        $taxArray[0]['label']='Label';
//        $taxArray[0]['rules']='name';
//        $taxArrays[0]['field']='value[]';
//        $taxArrays[0]['label']='Value';
//        $taxArrays[0]['rules']='numeric';
//        
//        $this->ci->form_validation->set_rules($taxArray);
// $this->ci->form_validation->set_rules($taxArrays);
        //  $this->ci->form_validation->set_rules('label', 'Lable');
        //  $this->ci->form_validation->set_rules('value', 'Value');
        if ($inputs['type'] == 'paid') {
            $this->ci->form_validation->set_rules('price', 'Price', 'price|required_strict');
        }
        if ($this->ci->form_validation->run() === FALSE) {

            $error_messages = $this->ci->form_validation->get_errors('message');
            return $error_messages;
        }
    }

    public function ticketUpdate($inputArray) {

        // check transactions available or not
        $eventsignupHandler = new Eventsignup_handler();
        $transactions = $eventsignupHandler->getSuccessfullTransactionsByEventId($inputArray['eventId'], '', 'count', $inputArray['ticketId']);
        if ($transactions['status'] == TRUE) {
            if ($transactions['response']['eventsignupData'][0]['count'] > 0) {
                $this->ci->Ticket_model->resetVariable();
                $selectInput[$this->ci->Ticket_model->name] = $this->ci->Ticket_model->name;
                $selectInput[$this->ci->Ticket_model->type] = $this->ci->Ticket_model->type;
                $selectInput[$this->ci->Ticket_model->price] = $this->ci->Ticket_model->price;
                $selectInput[$this->ci->Ticket_model->currencyid] = $this->ci->Ticket_model->currencyid;
                $selectWhere[$this->ci->Ticket_model->id] = $inputArray['ticketId'];
                $this->ci->Ticket_model->setSelect($selectInput);
                $this->ci->Ticket_model->setWhere($selectWhere);
                $response = $this->ci->Ticket_model->get();

                if (is_array($response)) {
                    if ((count($response) > 0) && ($response[0]['name'] != $inputArray['name'])) {
                        $output['status'] = FALSE;
                        $output["response"]["messages"][] = ERROR_TICKETNAME_TRANSACTIONS_AVAILABLE;
                        $output['statusCode'] = STATUS_BAD_REQUEST;
                        return $output;
                    }
                    if ((count($response) > 0) && ($response[0]['type'] != $inputArray['type'])) {
                        $output['status'] = FALSE;
                        $output["response"]["messages"][] = ERROR_TICKETTYPE_TRANSACTIONS_AVAILABLE;
                        $output['statusCode'] = STATUS_BAD_REQUEST;
                        return $output;
                    }
                    if ((count($response) > 0) && ($response[0]['price'] != $inputArray['price'])) {
                        $output['status'] = FALSE;
                        $output["response"]["messages"][] = ERROR_TICKETPRICE_TRANSACTIONS_AVAILABLE;
                        $output['statusCode'] = STATUS_BAD_REQUEST;
                        return $output;
                    }
                    if ((count($response) > 0) && ($response[0]['currencyid'] != $inputArray['currencyId'])) {
                        $output['status'] = FALSE;
                        $output["response"]["messages"][] = ERROR_TICKETCURRENCY_TRANSACTIONS_AVAILABLE;
                        $output['statusCode'] = STATUS_BAD_REQUEST;
                        return $output;
                    }
                }
            }
        }

        $validationStatus = $this->validateAddTicket($inputArray);
        if ($validationStatus['error']) {
            $output['status'] = FALSE;
            $output['response']['messages'][] = $validationStatus['message'];
            $output['statusCode'] = STATUS_BAD_REQUEST;
            return $output;
        }

        if ($inputArray['type'] == 1) {
            $inputArray['price'] = 0;
        }
        $updateTicket = array();

        $updateTicket[$this->ci->Ticket_model->name] = $inputArray['name'];
        $updateTicket[$this->ci->Ticket_model->type] = $inputArray['type'];
        $updateTicket[$this->ci->Ticket_model->description] = $inputArray['description'];
        $updateTicket[$this->ci->Ticket_model->eventid] = $inputArray['eventId'];
        $updateTicket[$this->ci->Ticket_model->price] = $inputArray['price'];
        $updateTicket[$this->ci->Ticket_model->quantity] = $inputArray['quantity'];
        $updateTicket[$this->ci->Ticket_model->minorderquantity] = $inputArray['minOrderQuantity'];
        $updateTicket[$this->ci->Ticket_model->maxorderquantity] = $inputArray['maxOrderQuantity'];
        $updateTicket[$this->ci->Ticket_model->startdatetime] = $inputArray['startDateTime'];
        $updateTicket[$this->ci->Ticket_model->enddatetime] = $inputArray['endDateTime'];
        $updateTicket[$this->ci->Ticket_model->order] = $inputArray['order'];
        $updateTicket[$this->ci->Ticket_model->currencyid] = $inputArray['currencyId'];
        if (isset($inputArray['soldOut'])) {
            $updateTicket[$this->ci->Ticket_model->soldout] = $inputArray['soldOut'];
        }
        if (isset($inputArray['displayStatus'])) {
            $updateTicket[$this->ci->Ticket_model->displaystatus] = $inputArray['displayStatus'];
        }

        $this->ci->Ticket_model->setInsertUpdateData($updateTicket);

        $where[$this->ci->Ticket_model->id] = $inputArray['ticketId'];
//        $where[$this->ci->Ticket_model->deleted] = 0;
//	$where[$this->ci->Ticket_model->status] = 1;
        $this->ci->Ticket_model->setWhere($where);

        $status = $this->ci->Ticket_model->update_data();
        if (!$status) {
            $output['status'] = FALSE;
            $output["response"]["message"][] = ERROR_ADD_TICKET;
            $output['statusCode'] = STATUS_BAD_REQUEST;
            return $output;
        }
        //Process the tax information
        // if transaction happen taxes won't update
        // taxes can't edit for tickets
        if ($transactions['status'] == TRUE && $transactions['response']['eventsignupData'][0]['count'] > 0) {
            $output['status'] = FALSE;
            $output["response"]["message"][] = ERROR_TAX_TRANSACTIONS_AVAILABLE;
            $output['statusCode'] = STATUS_BAD_REQUEST;
        } else {
            $taxResponse = $this->updateTaxInformation($inputArray);
            if (!$taxResponse) {
                $output['status'] = FALSE;
                $output["response"]["message"][] = ERROR_ADD_TICKET;
                $output['statusCode'] = STATUS_BAD_REQUEST;
                return $output;
            }
        }
        $output['status'] = TRUE;
        $output["response"]["message"][] = SUCCESS_TICKET_ADDED;
        $output['statusCode'] = 201;

        return $output;
    }

    /**
     * To remove the ticket means need to change the deleted status to 1
     * @param type $data
     */
    public function deleteTicket($data) {
        $eventsignupHandler = new Eventsignup_handler();
        $transactions = $eventsignupHandler->getSuccessfullTransactionsByEventId($data['eventId'], '', 'count', $data['ticketId']);
        if ($transactions['status'] == TRUE) {
            if ($transactions['response']['eventsignupData'][0]['count'] > 0) {
                $output['status'] = FALSE;
                $output["response"]["messages"][] = ERROR_DELETE_TRANSACTIONS_AVAILABLE;
                $output['statusCode'] = STATUS_BAD_REQUEST;
                return $output;
            }
        }
        if (is_array($data) && count($data) > 0) {
            $this->ci->Ticket_model->resetVariable();
            $whereInArray = array();
            $whereInArray[] = "id";
            $whereInArray[] = $data;

            $this->ci->Ticket_model->setWhereIn($whereInArray);

            $where[$this->ci->Ticket_model->status] = 1;
            $this->ci->Ticket_model->setWhere($where);

            $insertArray = array();
            $insertArray[$this->ci->Ticket_model->deleted] = 1;
            $this->ci->Ticket_model->setInsertUpdateData($insertArray);

            $status = $this->ci->Ticket_model->update_data();

            if (!$status) {
                $output['status'] = FALSE;
                $output["response"]["message"][] = ERROR_DELETE_TICKET;
                $output['statusCode'] = STATUS_BAD_REQUEST;
                return $output;
            }
            $output['status'] = TRUE;
            $output["response"]["message"][] = SUCCESS_TICKET_ADDED;
            $output['statusCode'] = STATUS_CREATED;
            return $output;
        } else {
            $validationStatus = $this->deleteTicketValidation($data);
            if ($validationStatus['error']) {
                $output['status'] = FALSE;
                $output['response']['messages'][] = $validationStatus['message'];
                $output['statusCode'] = STATUS_BAD_REQUEST;
                return $output;
            }

            $updateTicket = array();

            $updateTicket[$this->ci->Ticket_model->deleted] = 1;
            $this->ci->Ticket_model->setInsertUpdateData($updateTicket);

            $where[$this->ci->Ticket_model->id] = $data['ticketId'];
            $this->ci->Ticket_model->setWhere($where);
            $status = $this->ci->Ticket_model->update_data();
            if (!$status) {
                $output['status'] = FALSE;
                $output["response"]["message"][] = ERROR_DELETE_TICKET;
                $output['statusCode'] = STATUS_BAD_REQUEST;
                return $output;
            }
            $output['status'] = TRUE;
            $output["response"]["message"][] = SUCCESS_TICKET_ADDED;
            $output['statusCode'] = STATUS_CREATED;

            return $output;
        }
    }

    public function deleteTicketValidation($inputs) {
        $errorMessages = array();
        $this->ci->form_validation->pass_array($inputs);
        $this->ci->form_validation->set_rules('ticketId', 'Ticket Id', 'is_natural_no_zero|required_strict');

        if ($this->ci->form_validation->run() === FALSE) {
            return $this->ci->form_validation->get_errors('message');
        }
        $errorMessages['error'] = FALSE;
        return $errorMessages;
    }

    /**
     * To add multiple ticket taxes
     * @param type $ticketTax
     * $tickettax[][taxid]
     * $tickettax[][ticektid]
     * @param type  $userData['createdby']
     * @param type  $userData['modifiedby']
     * @return int
     */
    public function addTicketTax($ticketTax) {
        $this->ci->Ticket_model->resetVariable();
        $this->ci->Ticket_model->setInsertUpdateData($ticketTax);
        $this->ci->Ticket_model->setTableName('tickettax');
        $response = $this->ci->Ticket_model->insertMultiple_data();

        $this->ci->Ticket_model->setTableName('ticket');
        if ($response) {
            $output['status'] = TRUE;
            $output['response']['affectedRows'] = $response;
            $output["response"]["messages"] = array();
            $output['statusCode'] = STATUS_CREATED;
            return $output;
        } else {
            $output['status'] = FALSE;
            $output["response"]["messages"][] = ERROR_SOMETHING_WENT_WRONG;
            $output['statusCode'] = STATUS_SERVER_ERROR;
            return $output;
        }
    }

    /**
     * To get the ticket related taxmapping ids
     */
    public function getTaxMappingDetails($data) {
        $this->ci->Ticket_model->resetVariable();
        $this->ci->Ticket_model->setTableName('tickettax');
        $where = $whereIn = array();
        $whereIn['ticketid'] = $data['ticketIds'];
        $selectInput['ticketid'] = 'ticketid';
        $selectInput['taxmappingid'] = 'taxmappingid';
        $this->ci->Ticket_model->setSelect($selectInput);
        $this->ci->Ticket_model->setOrderBy(array());
        $this->ci->Ticket_model->setWhereIns($whereIn);

        $where[$this->ci->Ticket_model->status] = 1;
        $where[$this->ci->Ticket_model->deleted] = 0;
        $where['taxmappingid > '] = 0;
        $this->ci->Ticket_model->setWhere($where);

        $response = $this->ci->Ticket_model->get();

        //reseting to normal model name
        $this->ci->Ticket_model->setWhereIns(array());
        $this->ci->Ticket_model->setTableName('ticket');
        if ($response) {
            $output['status'] = TRUE;
            $output['response']['taxList'] = $response;
            $output['response']['total'] = count($response);
            $output["response"]["messages"] = array();
            $output['statusCode'] = STATUS_OK;
            return $output;
        }
        $output['status'] = TRUE;
        $output["response"]["messages"][] = ERROR_NO_DATA;
        $output['response']['total'] = 0;
        $output['statusCode'] = STATUS_OK;
        return $output;
    }

    public function updateTaxInformation($inputArray) {
        //Get the old tax information
        $ticketmapdetails = array();
        $ticketIdArray = array("ticketIds" => $inputArray["ticketId"]);
        $taxresult = $this->getTaxMappingDetails($ticketIdArray);
        $ticketData['oldTaxData'] = array();
        if ($taxresult) {
            foreach ($taxresult['response']['taxList'] as $val) {
                $ticketData['oldTaxData'][] = $val['taxmappingid'];
            }
        }
        $inputTaxArray = $inputArray['taxArray'];
        $insertTaxArray = $removeArray = $inputAllTaxes = array();
        $inputAllTaxes = array_merge($ticketData['oldTaxData'], $inputArray['taxArray']);
        if (count($inputAllTaxes) > 0) {
            //compareing the old & new taxmapping ids
            foreach ($inputAllTaxes as $inputTaxValue) {
                if (in_array($inputTaxValue, $inputTaxArray) && !in_array($inputTaxValue, $ticketData['oldTaxData'])) {
                    $insertTaxArray[] = $inputTaxValue;
                } else if (!in_array($inputTaxValue, $inputTaxArray) && in_array($inputTaxValue, $ticketData['oldTaxData'])) {
                    $removeArray[] = $inputTaxValue;
                }
            }
            //Change the status of the removed taxmapping ids
            if ((count($removeArray) > 0)) {
                $changeStatusArray = array();
                $changeStatusArray['ticketId'] = $inputArray["ticketId"];
                $changeStatusArray['taxids'] = $removeArray;
                $this->changeTicketTaxMappingRecordsStatus($changeStatusArray);
            }
            //Insert the New tax values
            if (count($insertTaxArray) > 0) {
                $taxArray = array();
                foreach ($insertTaxArray as $taxkey => $taxValues) {
                    $taxArray[$taxkey]['ticketid'] = $inputArray["ticketId"];
                    $taxArray[$taxkey]['taxmappingid'] = $taxValues;
                    $taxArray[$taxkey]['status'] = 1;
                }
                $this->addTicketTax($taxArray);
            }
        }
//        else {
//            $changeStatusArray = array();
//            $changeStatusArray['ticketId'] = $inputArray["ticketId"];
//
//            $changeStatusArray['taxids'] = $ticketData['oldTaxData'];
//            $this->changeTicketTaxMappingRecordsStatus($changeStatusArray);
//        }

        $output['status'] = TRUE;
        $output["response"]["message"][] = SUCCESS_TICKET_ADDED;
        $output['statusCode'] = STATUS_CREATED;
        return $output;
    }

    /**
     * 
     * @param type $data
     * @param type $data[ticketid]
     * @param type $data[taxids]
     * @return int
     */
    public function changeTicketTaxMappingRecordsStatus($data) {
        $this->ci->Ticket_model->resetVariable();
        $this->ci->Ticket_model->setTableName('tickettax');

        $where = $whereInArray = array();
        $whereInArray[] = "taxmappingid";
        $whereInArray[] = $data['taxids'];

        $where['ticketid'] = $data['ticketId'];
        $this->ci->Ticket_model->setWhereIn($whereInArray);
        $this->ci->Ticket_model->setWhere($where);
        $insertArray = array();
        $insertArray['deleted'] = 1;
        $this->ci->Ticket_model->setInsertUpdateData($insertArray);
        $deleteStatus = $this->ci->Ticket_model->update_data();
        $this->ci->Ticket_model->setWhereIn(array());
        $this->ci->Ticket_model->setTableName('ticket');
        if ($deleteStatus) {
            $output['status'] = TRUE;
            $output["response"]["messages"] = array();
            $output['statusCode'] = STATUS_OK;
            return $output;
        }
        $output['status'] = FALSE;
        $output["response"]["messages"][] = SOMETHING_WENT_WRONG;
        $output['statusCode'] = STATUS_SERVER_ERROR;
        return $output;
    }

    public function getTickets($inputArray) {
        $this->ci->load->model('ViralticketSale_Model');
        parent::$CI->form_validation->pass_array($inputArray);
        parent::$CI->form_validation->set_rules('eventId', 'eventId', 'required_strict|is_natural_no_zero');
        if (parent::$CI->form_validation->run() === FALSE) {
            $errorMessages = $this->ci->form_validation->get_errors();
            $output['status'] = FALSE;
            $output['response']['messages'] = $errorMessages['message'];
            $output['statusCode'] = STATUS_BAD_REQUEST;
            return $output;
        }
        $this->ci->Ticket_model->resetVariable();
        $selectInput['id'] = $this->ci->Ticket_model->id;
        $selectInput['name'] = $this->ci->Ticket_model->name;
        $selectInput['eventId'] = $this->ci->Ticket_model->eventid;
        $selectInput['price'] = $this->ci->Ticket_model->price;
        $selectInput['currencyid'] = $this->ci->Ticket_model->currencyid;
        $this->ci->Ticket_model->setSelect($selectInput);
        $where[$this->ci->Ticket_model->eventid] = $inputArray['eventId'];
        $where[$this->ci->Ticket_model->status] = (isset($request['status'])) ? $request['status'] : 1;
        $where[$this->ci->Ticket_model->deleted] = 0;
        if (isset($inputArray['addonTicket']) && $inputArray['addonTicket']) {
            $whereIn[$this->ci->Ticket_model->type] = array('paid', 'addon');
            $this->ci->Ticket_model->setWhereIns($whereIn);
        } else {
            $where[$this->ci->Ticket_model->type] = 'paid';
        }

        $this->ci->Ticket_model->setWhere($where);
        $ticketData = $this->ci->Ticket_model->get();
        $TicketInfo = commonHelperGetIdArray($ticketData, 'id');
        $this->currencyHanlder = new Currency_handler();
        $currencyResponse = $this->currencyHanlder->getCurrencyList();
        if ($currencyResponse['status'] && $currencyResponse['response']['total'] > 0) {
            $indexedCurrencyListById = commonHelperGetIdArray($currencyResponse['response']['currencyList'], 'currencyId');
        } else {
            return $currencyResponse;
        }
        foreach ($TicketInfo as $value) {
            $TicketInfo[$value['id']]['currencyCode'] = $indexedCurrencyListById[$value['currencyid']]['currencyCode'];
        }
        foreach ($ticketData as $ticket) {
            $ticketIds['ticketId'] = $ticket['id'];
            $ticketDetails[] = $ticketIds;
        }
        $idsOfViralTicket = $ticketDetails;
        $ticketDetails['status'] = 0;
        if (count($idsOfViralTicket) > 0) {
            $viralTicketIds = $this->getViralTickets($ticketDetails);
            $viralTicketData = $viralTicketIds['response'];
            $viralInfomation = commonHelperGetIdArray($viralTicketIds['response']['viralData'], 'ticketid');
            foreach ($viralInfomation as $viralvalue) {
                $TicketInfo[$viralvalue['ticketid']]['referrercommission'] = $viralvalue['referrercommission'];
                $TicketInfo[$viralvalue['ticketid']]['receivercommission'] = $viralvalue['receivercommission'];
                $TicketInfo[$viralvalue['ticketid']]['viralticketsettingId'] = $viralvalue['id'];
                $TicketInfo[$viralvalue['ticketid']]['type'] = $viralvalue['type'];
                $TicketInfo[$viralvalue['ticketid']]['status'] = $viralvalue['status'];
            }
            $ticketCounts = count($TicketInfo);
            // Checking Any Viral Tickets booked for Viral Ticket or Not
            foreach ($TicketInfo as $key => $value) {
                $TicketInfo[$key]['salesDone'] = 0;
                $viralticketsettingId = $value['viralticketsettingId'];
                $viralselectInput['id'] = $this->ci->ViralticketSale_Model->id;
                $this->ci->ViralticketSale_Model->setSelect($viralselectInput);
                $viralwhere[$this->ci->ViralticketSale_Model->viralticketsettingid] = $viralticketsettingId;
                $this->ci->ViralticketSale_Model->setWhere($viralwhere);
                $viralTicketsaleData = $this->ci->ViralticketSale_Model->get();
                if ($viralTicketsaleData) {
                    $TicketInfo[$key]['salesDone'] = 1;
                }
            }

            if ($TicketInfo) {
                $output['status'] = TRUE;
                $output['response']['viralTicketData'] = $TicketInfo;
                $output['statusCode'] = STATUS_OK;
                return $output;
            } else {
                $output['status'] = FALSE;
                $output["response"]["messages"][] = 'No Viral Tickets';
                $output['statusCode'] = STATUS_NO_DATA;
                return $output;
            }
        } else {
            $output['status'] = TRUE;
            $output['response']['viralTicketData'] = $ticketData;
            $output['statusCode'] = STATUS_OK;
            return $output;
        }
    }



    public function getViralTickets($data) {
        if (!isset($data['eventId'])) {
            $tickets = commonHelperGetIdArray($data, 'ticketId');
            $ticketIds = array_keys($tickets);
        }
        $this->ci->load->model('ViralticketSetting_Model');
        $this->ci->ViralticketSetting_Model->resetVariable();
        $selectInput['id'] = $this->ci->ViralticketSetting_Model->id;
        $selectInput['eventid'] = $this->ci->ViralticketSetting_Model->eventid;
        $selectInput['ticketid'] = $this->ci->ViralticketSetting_Model->ticketid;
        $selectInput['type'] = $this->ci->ViralticketSetting_Model->type;
        $selectInput['referrercommission'] = $this->ci->ViralticketSetting_Model->referrercommission;
        $selectInput['receivercommission'] = $this->ci->ViralticketSetting_Model->receivercommission;
        $selectInput['status'] = $this->ci->ViralticketSetting_Model->status;
        $this->ci->ViralticketSetting_Model->setSelect($selectInput);
        if (!isset($data['eventId'])) {
            //$this->ci->ViralticketSetting_Model->setWhereIn(array('ticketid', $ticketIds));
            if (is_array($data['ticketIdArr']) && count($data['ticketIdArr']) > 0) {
                $this->ci->ViralticketSetting_Model->setWhereIn(array('ticketid', $data['ticketIdArr']));
            } else {
                $this->ci->ViralticketSetting_Model->setWhereIn(array('ticketid', $ticketIds));
            }
        } else {
            $where[$this->ci->ViralticketSetting_Model->eventid] = $data['eventId'];
        }
        if (!isset($data['status'])) {
            $where[$this->ci->ViralticketSetting_Model->status] = 1;
        }

        $this->ci->ViralticketSetting_Model->setWhere($where);
        $orderBy[] = $this->ci->ViralticketSetting_Model->receivercommission . " DESC ";
        $this->ci->ViralticketSetting_Model->setOrderBy($orderBy);
        $viralTicketData = $this->ci->ViralticketSetting_Model->get();
        if ($viralTicketData) {
            $output['status'] = TRUE;
            $output['response']['viralData'] = $viralTicketData;
            $output['response']['total'] = count($viralTicketData);
            $output['statusCode'] = STATUS_OK;
            return $output;
        }
        $output['status'] = TRUE;
        $output["response"]["messages"][] = 'No Viral Tickets';
        $output['response']['total'] = 0;
        $output['statusCode'] = STATUS_OK;
        return $output;
    }

    public function updateViralTicket($viralInfo) {
        $count = count($viralInfo);
        $this->ci->load->model('ViralticketSetting_Model');
        foreach ($viralInfo as $val) {
            $viralTicket = array();
            $viralTicket['eventid'] = $val['eventId'];
            $viralTicket['ticketid'] = $val['ticketId'];
            $viralTicket['type'] = $val['type'];
            $viralTicket['referrercommission'] = $val['referrercommission'];
            $viralTicket['salesDone'] = $val['salesDone'];
            $viralTicket['receivercommission'] = $val['receivercommission'];
            $viralTicket['status'] = $val['status'];
            parent::$CI->form_validation->pass_array($viralTicket);
            parent::$CI->form_validation->set_rules('eventid', 'EventId', 'required_strict|is_natural_no_zero');
            parent::$CI->form_validation->set_rules('ticketid', 'Ticket Id', 'required_strict|is_natural_no_zero');
            parent::$CI->form_validation->set_rules('referrercommission', 'referrercommission', 'is_natural_no_zero');
            parent::$CI->form_validation->set_rules('receivercommission', 'receivercommission', 'is_natural_no_zero');

            if (parent::$CI->form_validation->run() === FALSE) {
                $errorMessages = $this->ci->form_validation->get_errors();
                $output['status'] = FALSE;
                $output['response']['messages'] = $errorMessages['message'];
                $output['statusCode'] = STATUS_BAD_REQUEST;
                return $output;
            }
            if (($viralTicket['referrercommission'] + $viralTicket['receivercommission'] > 100 ) && $viralTicket['type'] == 2) {
                $output['status'] = FALSE;
                $output["response"]["messages"][] = ERROR_DISCOUNT_PERCENTAGE_EXCEEDED;
                $output['statusCode'] = STATUS_INVALID_INPUTS;
                return $output;
            }

            $select = array();
            $where = array();
            $update = array();
            $this->ci->ViralticketSetting_Model->resetVariable();
            $select[$this->ci->ViralticketSetting_Model->ticketid] = $viralTicket['ticketid'];
            $select[$this->ci->ViralticketSetting_Model->eventid] = $viralTicket['eventid'];
            $select[$this->ci->ViralticketSetting_Model->referrercommission] = $viralTicket['referrercommission'];
            $select[$this->ci->ViralticketSetting_Model->receivercommission] = $viralTicket['receivercommission'];
            $select[$this->ci->ViralticketSetting_Model->status] = $viralTicket['status'];
            $select[$this->ci->ViralticketSetting_Model->type] = $viralTicket['type'];
            $where[$this->ci->ViralticketSetting_Model->eventid] = $viralTicket['eventid'];
            $where[$this->ci->ViralticketSetting_Model->ticketid] = $viralTicket['ticketid'];
            $this->ci->ViralticketSetting_Model->setWhere($where);
            $this->ci->ViralticketSetting_Model->setWhereIn($wherein = array());
            $ticketViralsetting = $this->ci->ViralticketSetting_Model->get();
            if ($ticketViralsetting) {
                if ($viralTicket['salesDone'] <= 0) {
                    $update[$this->ci->ViralticketSetting_Model->referrercommission] = (strlen($viralTicket['referrercommission']) > 0) ? $viralTicket['referrercommission'] : 0;
                    $update[$this->ci->ViralticketSetting_Model->receivercommission] = (strlen($viralTicket['receivercommission']) > 0) ? $viralTicket['receivercommission'] : 0;
                }
                $update[$this->ci->ViralticketSetting_Model->status] = $viralTicket['status'];
                $update[$this->ci->ViralticketSetting_Model->type] = $viralTicket['type'];
                $this->ci->ViralticketSetting_Model->setInsertUpdateData($update);
                $this->ci->ViralticketSetting_Model->setWhere($where);
                $this->ci->ViralticketSetting_Model->setWhereIn($wherein = array());
                $response = $this->ci->ViralticketSetting_Model->update_data();
            } else {
                if ($viralTicket['referrercommission'] > 0 || $viralTicket['receivercommission'] > 0) {
                    $this->ci->ViralticketSetting_Model->setWhereIn($wherein = array());
                    $this->ci->ViralticketSetting_Model->setInsertUpdateData($select);
                    $response = $this->ci->ViralticketSetting_Model->insert_data();
                }
            }
            // $response = $this->ci->ViralticketSetting_Model->onduplicate($viralTicket);
        }

        $final = $response;
        if ($final) {
            $output['status'] = TRUE;
            $output['response']['messages'][] = VIRAL_TICKET_UPDATE;
            $output['response']['total'] = count($final);
            $output['statusCode'] = STATUS_UPDATED;
            return $output;
        }
        $output['status'] = FALSE;
        $output["response"]["messages"][] = VIRAL_TICKET_ERROR;
        $output['statusCode'] = STATUS_SERVER_ERROR;
        return $output;
    }

    public function addTicketDiscount($inputArray) {
        $this->ci->load->model('Ticketdiscount_model');
        $this->ci->Ticketdiscount_model->resetVariable();
        foreach ($inputArray['ticketList'] as $key => $value) {
            $createTicketDiscount[$this->ci->Ticketdiscount_model->ticketid] = $value;
            $createTicketDiscount[$this->ci->Ticketdiscount_model->discountid] = $inputArray['discountId'];
            $createTicketDiscount[$this->ci->Ticketdiscount_model->status] = 1;
            $this->ci->Ticketdiscount_model->setInsertUpdateData($createTicketDiscount);
            $ticketDiscountData = $this->ci->Ticketdiscount_model->insert_data();
        }
        if ($ticketDiscountData) {
            $output = parent::createResponse(TRUE, SUCCESS_ADDED_TICKETDISCOUNT, STATUS_CREATED);
            return $output;
        }
    }

    // Function to get Tickets By Ids

    public function getTicketsbyIds($inputArray) {
        $this->ci->form_validation->reset_form_rules();
        $this->ci->form_validation->pass_array($inputArray);
        $this->ci->form_validation->set_rules('eventId', 'eventId', 'required_strict');
        $this->ci->form_validation->set_rules('ticketIds', 'ticketIds', 'required_strict|is_array');
        if ($this->ci->form_validation->run() == FALSE) {
            $response = $this->ci->form_validation->get_errors();
            $output['status'] = FALSE;
            $output['response']['messages'] = $response['message'];
            $output['statusCode'] = STATUS_BAD_REQUEST;
            return $output;
        }
        $this->ci->Ticket_model->resetVariable();
        $selectInput['id'] = $this->ci->Ticket_model->id;
        $selectInput['name'] = $this->ci->Ticket_model->name;
        $selectInput['description'] = $this->ci->Ticket_model->description;
        $selectInput['eventId'] = $this->ci->Ticket_model->eventid;
        $selectInput['quantity'] = $this->ci->Ticket_model->quantity;
        $selectInput['price'] = $this->ci->Ticket_model->price;
        $selectInput['totalsoldtickets'] = $this->ci->Ticket_model->totalsoldtickets;

        $this->ci->Ticket_model->setSelect($selectInput);
        $this->ci->Ticket_model->setWhereIn(array('id', $inputArray['ticketIds']));
        $where[$this->ci->Ticket_model->eventid] = $inputArray['eventId'];

        $where[$this->ci->Ticket_model->status] = 1;
        $where[$this->ci->Ticket_model->deleted] = 0;

        $this->ci->Ticket_model->setWhere($where);
        $ticketData = $this->ci->Ticket_model->get();
        $taxRequired = isset($inputArray['taxRequired']) ? $inputArray['taxRequired'] : true;
        if ($taxRequired) {
            $this->ci->load->model('Tickettax_model');
            $selinput['ticketid'] = $this->ci->Tickettax_model->ticketid;
            $selinput['taxmappingid'] = $this->ci->Tickettax_model->taxmappingid;
            $this->ci->Tickettax_model->setSelect($selinput);
            $this->ci->Tickettax_model->setWhereIn(array('ticketid', $inputArray['ticketIds']));
            $where = array();
            $where[$this->ci->Tickettax_model->status] = 1;
            $where[$this->ci->Tickettax_model->deleted] = 0;

            $this->ci->Tickettax_model->setWhere($where);
            $tickettaxData = $this->ci->Tickettax_model->get();
        }
        if (is_array($ticketData)) {
            if (count($ticketData) > 0) {
                $output['status'] = TRUE;
                $output['response']['ticketdetails'] = $ticketData;
                if ($taxRequired) {
                    $output['response']['tickettaxdetails'] = $tickettaxData;
                }
                $output['response']['total'] = count($ticketData);
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

    public function getsoloutTicketCount($inputArray) {
        $this->ci->Ticket_model->resetVariable();
        $selectInput['eventid'] = $this->ci->Ticket_model->eventid;
        $selectInput['totalsoldtickets'] = " sum(" . $this->ci->Ticket_model->totalsoldtickets . ") ";
        $this->ci->Ticket_model->setSelect($selectInput);
        $where[$this->ci->Ticket_model->deleted] = 0;
        $this->ci->Ticket_model->setWhereIns(array($this->ci->Ticket_model->eventid => $inputArray['eventIdList']));
        $this->ci->Ticket_model->setWhere($where);
        $this->ci->Ticket_model->setGroupBy($this->ci->Ticket_model->eventid);
        $ticketData = $this->ci->Ticket_model->get();

        if ($ticketData != FALSE && count($ticketData) > 0) {
            if (count($ticketData) > 0) {
                $output['status'] = TRUE;
                $output['response']['ticketdetail'] = $ticketData;
                $output['statusCode'] = STATUS_OK;
                return $output;
            } else {
                $output['status'] = TRUE;
                $output['response']['messages'][] = ERROR_NO_DATA;
                $output['statusCode'] = STATUS_OK;
                return $output;
            }
        }
    }

    public function getTicketCalcluations($inputArray) {

        $ticketWiseArray = array('totalTicketAmount' => 18400,
            'totalTicketQuantity' => 15,
            'totalCodeDiscount' => 750,
            'totalBulkDiscount' => 3680,
            'totalTaxDetails' => array(
                0 => array(
                    "label" => "Entertainment Tax",
                    "id" => 2,
                    "value" => 14,
                    "taxAmount" => 1848
                ),
                1 => array(
                    "label" => "Service Tax",
                    "id" => 1,
                    "value" => 24,
                    "taxAmount" => 4859.52
                )
            ),
            'totalAffiliateDiscount' => 0,
            'totalTaxAmount' => 6707.52,
            'totalPurchaseAmount' => 20677.52,
            'currencyCode' => 'INR');

        $responseArray['response']['calculationDetails'] = $ticketWiseArray;
        $responseArray['response']['messages'] = array();
        return $responseArray;
    }

    public function getTicketName($inputArray) {
        $this->ci->Ticket_model->resetVariable();
        $selectInput['id'] = $this->ci->Ticket_model->id;
        $selectInput['name'] = $this->ci->Ticket_model->name;
        $selectInput['minorderquantity'] = $this->ci->Ticket_model->minorderquantity;
        $selectInput['maxorderquantity'] = $this->ci->Ticket_model->maxorderquantity;
        $selectInput['price'] = $this->ci->Ticket_model->price;
        $selectInput['currencyid'] = $this->ci->Ticket_model->currencyid;
        $selectInput['type'] = $this->ci->Ticket_model->type;
        $selectInput['displaystatus'] = $this->ci->Ticket_model->displaystatus;
        $selectInput['totalsoldtickets'] = $this->ci->Ticket_model->totalsoldtickets;
        $selectInput['quantity'] = $this->ci->Ticket_model->quantity;
        $selectInput['soldout'] = $this->ci->Ticket_model->soldout;
        $selectInput['description'] = $this->ci->Ticket_model->description;
        $selectInput['eventid'] = $this->ci->Ticket_model->eventid;
        $selectInput['quantity'] = $this->ci->Ticket_model->quantity;
        $selectInput['startdatetime'] = $this->ci->Ticket_model->startdatetime;
        $selectInput['enddatetime'] = $this->ci->Ticket_model->enddatetime;
        $selectInput['status'] = $this->ci->Ticket_model->status;
        $selectInput['order'] = $this->ci->Ticket_model->order;
        $where_not_in = array();
        $this->ci->Ticket_model->setSelect($selectInput);
        if (isset($inputArray['ticketId'])) {
            $whereInArray[$this->ci->Ticket_model->id] = $inputArray['ticketId'];
        }
        if (isset($inputArray['eventId'])) {
            $whereInArray[$this->ci->Ticket_model->eventid] = $inputArray['eventId'];
        }
        if (isset($inputArray['ticketType'])) {
            if ($inputArray['ticketType'] == "paid only") {
                $where_not_in[$this->ci->Ticket_model->type] = 'free';
            } else {
                $where_not_in[$this->ci->Ticket_model->type] = 'donation';
            }
        }
        if (isset($inputArray['soldout'])) {
            $where[$this->ci->Ticket_model->soldout] = 0;
        }
        $status = (isset($inputArray['status'])) ? $inputArray['status'] : 1;
        if ($status > 0) {
            $where[$this->ci->Ticket_model->status] = $status;
        }
        if (isset($inputArray['feature'])) {
            $where[$this->ci->Ticket_model->startdatetime . " < "] = allTimeFormats('', 11);
        }
        $where[$this->ci->Ticket_model->deleted] = 0;
        $this->ci->Ticket_model->setWhere($where);
        $this->ci->Ticket_model->setWhereIns($whereInArray);
        $this->ci->Ticket_model->setWhereNotIn($where_not_in);
        $ticketNames = $this->ci->Ticket_model->get();
        if (count($ticketNames) > 0) {
            $output['status'] = TRUE;
            $output['response']['ticketName'] = $ticketNames;
            $output['response']['total'] = count($ticketNames);
            $output['statusCode'] = STATUS_OK;
            return $output;
        } else {
            $output['status'] = TRUE;
            $output['response']['messages'][] = ERROR_NO_TICKET;
            $output['response']['total'] = 0;
            $output['statusCode'] = STATUS_OK;
            return $output;
        }
    }

    /*
     * Function to update the ticket individual data
     *
     * @access	public
     * @param
     *      	ticketId - Ticket Id(required)
     *      	totalSoldTickets - total sold tickets with updated count
     * @return	response with messages
     */

    public function ticketIndividualUpdate($inputArray) {

        $this->ci->form_validation->reset_form_rules();
        $this->ci->form_validation->pass_array($inputArray['condition']);
        $this->ci->form_validation->set_rules('ticketId', 'ticket id', 'trim|xss_clean|required_strict');
        if ($this->ci->form_validation->run() == FALSE) {
            $response = $this->ci->form_validation->get_errors();
            $output['status'] = FALSE;
            $output['response']['messages'] = $response['message'];
            $output['statusCode'] = STATUS_BAD_REQUEST;
            return $output;
        }

        $updateTicket = array();
        $this->ci->Ticket_model->resetVariable();
        if (isset($inputArray['update']['totalSoldTickets'])) {
            $updateTicket[$this->ci->Ticket_model->totalsoldtickets] = $inputArray['update']['totalSoldTickets'];
        }
        if (isset($inputArray['update']['displayStatus'])) {
            $updateTicket[$this->ci->Ticket_model->displaystatus] = $inputArray['update']['displayStatus'];
        }

        $this->ci->Ticket_model->setInsertUpdateData($updateTicket);

        $where[$this->ci->Ticket_model->id] = $inputArray['condition']['ticketId'];
        $this->ci->Ticket_model->setWhere($where);
        //$status = $this->ci->Ticket_model->update_data();
        $status = $this->ci->Ticket_model->update_set_data();
        if (!$status) {
            $output['status'] = FALSE;
            $output["response"]["message"][] = ERROR_TICKET_UPDATE;
            $output['statusCode'] = STATUS_SERVER_ERROR;
            return $output;
        }
        $output['status'] = TRUE;
        $output["response"]["message"][] = SUCCESS_TICKET_UPDATE;
        $output['statusCode'] = STATUS_UPDATED;

        return $output;
    }

    public function sendTicketsoldDataToorganizer($inputArray) {

        $this->ci->form_validation->reset_form_rules();
        $this->ci->form_validation->set_rules('eventId', 'event Id', 'is_natural_zero|required_strict');
        $this->ci->form_validation->set_rules('userEmail', 'user Email', 'email|required_strict');
        if ($this->ci->form_validation->run() == FALSE) {
            $response = $this->ci->form_validation->get_errors();
            $output['status'] = FALSE;
            $output['response']['messages'] = $response['message'];
            $output['statusCode'] = STATUS_BAD_REQUEST;
            return $output;
        }
        $this->eventHandler = new Event_handler();
        $timeZoneName = $this->eventHandler->getEventTimeZone($inputArray['eventId']);
        $inputArray['eventTimeZoneName'] = $timeZoneName;
        $ticketData = $this->getActualEventTicketList($inputArray);
        if ($ticketData['status']) {
            require_once(APPPATH . 'handlers/email_handler.php');
            $emailHandler = new Email_handler();
            $inputArray['ticketData'] = $ticketData['response']['ticketList'];
            $inputArray['taxDetails'] = $ticketData['response']['taxDetails'];
            $response = $emailHandler->SendticketSolddataToOrganizer($inputArray);
            return $response;
        }
        $output['status'] = TRUE;
        $output['response']['messages'] = STATUS_NO_DATA;
        $output['statusCode'] = STATUS_OK;
        return $output;
    }

} 
