<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
/**
 * Default landing page controller
 *
 * @package     CodeIgniter
 * @author      Qison  Dev Team
 * @copyright   Copyright (c) 2015, MeraEvents.
 * @Version     Version 1.0
 * @Since       Class available since Release Version 1.0 
 * @Created     31-07-2015
 * @Last Modified On  31-07-2015
 * @Last Modified By  Qison  Dev Team
 */
require_once(APPPATH . 'handlers/ticket_handler.php');
require_once(APPPATH . 'handlers/event_handler.php');
require_once(APPPATH . 'handlers/promoter_handler.php');
require_once(APPPATH . 'handlers/discount_handler.php');
require_once(APPPATH . 'handlers/timezone_handler.php');
require_once(APPPATH . 'handlers/user_handler.php');
require_once(APPPATH . 'handlers/eventsignup_handler.php');
require_once (APPPATH . 'handlers/guestlistbooking_handler.php');
require_once (APPPATH . 'handlers/offlinepromoterticketmapping_handler.php');
require_once (APPPATH . 'handlers/offlinepromoterdiscounts_handler.php');

class Promote extends CI_Controller {

    var $eventHandler;
    var $ticketHandler;
    var $userHandler;
    var $promoterHandler;
    var $guestlistbookingHandler;

    public function __construct() {
        parent::__construct();
        $this->promoterHandler = new Promoter_handler();
        $this->ticketHandler = new Ticket_handler();
        $this->eventHandler = new Event_handler();
        $this->discountHandler = new Discount_handler();
        $this->userHandler = new User_handler();
        $this->eventsignupHandler = new Eventsignup_handler();
        $this->guestlistbookingHandler = new Guestlistbooking_handler();
        $this->offlinepromoterticketmappingHandler = new Offlinepromoterticketmapping_handler();
        $this->offlinepromoterdiscountsHandler = new Offlinepromoterdiscounts_handler();
        $_GET['eventId'] = $this->uri->segment(4);
    }

    public function viralTicket($eventId) {
        $inputArray['eventId'] = $eventId;
        $data = array();
        $data['eventName'] = commonHelperGetEventName($eventId);
        $data['eventId'] = $eventId;
        $inputArray['addonTicket']=TRUE;
        $eventTicketDetails = $this->ticketHandler->getTickets($inputArray);
        $data['ticketData'] = $eventTicketDetails['response']['viralTicketData'];
        if ($eventTicketDetails['status'] == TRUE) {
            $update = $this->input->post('viralTicketSubmit');
            if ($update) {
                foreach ($eventTicketDetails['response']['viralTicketData'] as $key => $val) {
                    $tktId = $val['id'];
                    $eventId = $val['eventId'];
                    $viral['ticketId'] = $tktId;
                    $viral['eventId'] = $eventId;
                    $type = $this->input->post('type'.$tktId);
                    $status = $this->input->post('status'.$tktId);
					if($type){
                    $viral['type'] = ($type == 'flat') ? 1 : 2;
					}else{ 
                    $viral['type'] = ($val['type'] == 'flat') ? 1 : 2;
                    }
                    $viral['status'] = ($status == 1) ? 1 : 0;
                    $viral['salesDone'] = $this->input->post('salesDone'.$tktId);
                    $viral['referrercommission'] = $this->input->post('referrercommission'.$tktId);
                    $viral['receivercommission'] = $this->input->post('receivercommission'.$tktId);
                    $viralData[] = $viral;
                }
                $viralInfo = $viralData;
                $updateViralTicket = $this->ticketHandler->updateViralTicket($viralData);
                if ($updateViralTicket['status'] == TRUE) {
                    $data['messages'] = $updateViralTicket['response']['messages'][0];
                    $eventTicketDetails = $this->ticketHandler->getTickets($inputArray);
                    $data['ticketData'] = $eventTicketDetails['response']['viralTicketData'];
                    $this->customsession->setData('viralTicketSuccessMessage', SUCCESS_VIRAL_TICKET_SAVED);
                    $redirectUrl = commonHelperGetPageUrl('dashboard-eventhome', $eventId);
                    redirect($redirectUrl);
                } else {
                    $data['messages'] = $updateViralTicket['response']['messages']['0'];
                }
            }
        } else {
            $data['messages'] = $eventTicketDetails['response']['messages']['0'];
        }

        $data['hideLeftMenu'] = 0;
        $data['content'] = 'viral_ticket_view';
        $data['pageName'] = 'Viral Ticket';
        $data['pageTitle'] = 'MeraEvents | Viral Ticketing';
        $data['jsArray'] = array($this->config->item('js_public_path') . 'dashboard/promote',);
        $this->load->view('templates/dashboard_template', $data);
    }

    public function addDiscount($eventId, $id = '') {
        $this->eventHandler = new Event_handler();
        $this->timezoneHandler = new Timezone_handler();
        $inputArray['eventId'] = $eventId;
        $data['output'] = array();

        $eventDetails = $this->eventHandler->getEventDetails($inputArray);
        $timeZoneName = "";
        if ($eventDetails) {
            $data['eventName'] = $eventDetails['response']['details']['title'];
            $data['eventEndDate'] = $eventDetails['response']['details']['endDate'];
            $timeZoneData['timezoneId'] = $eventDetails['response']['details']['timeZoneId'];
            $timeZoneDetails = $this->timezoneHandler->details($timeZoneData);
            if ($timeZoneDetails['status']) {
                $timeZoneName = $timeZoneDetails['response']['detail'][1]['name'];
            }
        }
        $data['eventTimeZoneName'] = $timeZoneName;
        $ticketsDetails = $this->ticketHandler->getTicketName($inputArray);
        if ($ticketsDetails['status']) {
            $data['ticketDetails'] = $ticketsDetails;
        } else {
            $data['ticketDetails'] = array();
        }

        //For editing the discount details
        if (!empty($id)) {
            $inputs['eventId'] = $eventId;
            $inputs['id'] = $id;
            $inputs['type'] = 'normal';
            $discountDetails = $this->discountHandler->getDiscountList($inputs);
            $data['discountDetails'] = $discountDetails;
            if ($discountDetails) {
                //Getting discountId              
                $discountId = $discountDetails['response']['discountList'][0]['id'];

                //Getting all ticket ids related to the discount code you want to edit           
                $this->ticketDiscountHandler = new Ticketdiscount_handler();
                $ticketDiscountData = $this->ticketDiscountHandler->getTicketDiscountData($discountId);
                $ticketDiscountData = $ticketDiscountData['response']['ticketDiscountList'];
                $selectedTicketIdList = $ticketIdList = array();
                foreach ($ticketDiscountData as $key => $value) {
                    $ticketIdList[] = $value['ticketid'];
                    if ($value['status'] == 1) {
                        $selectedTicketIdList[] = $value['ticketid'];
                    }
                }
                $data['ticketIdList'] = $selectedTicketIdList;
                $discountData = $this->input->post('discountSubmit');
                if ($discountData) {
                    $inputArray = $this->input->post(); //Storing all the input form values in the inputArray
                    $inputArray['eventId'] = $eventId;
                    $inputArray['eventTimeZoneName'] = $timeZoneName;
                    $inputArray['type'] = 'normal';
                    $inputArray['id'] = $id;
                    $inputArray['dbTicketIdList'] = $ticketIdList;
                    $discountData = $this->discountHandler->update($inputArray);
                    if ($discountData['status']) {
                        $this->customsession->setData('discountFlashMessage', SUCCESS_UPDATED);
                        $redirectUrl = commonHelperGetPageUrl('dashboard-list-discount', $eventId);
                        redirect($redirectUrl);
                    } else {
                        $data['addDiscountOutput'] = $discountData;
                    }
                }
            }
        } else {
            //For adding the discount
            $discountData = $this->input->post('discountSubmit');
            if ($discountData) {
                
                $inputArray = $this->input->post(); //Storing all the input form values in the inputArray
                $inputArray['eventTimeZoneName'] = $timeZoneName;
                $inputArray['eventId'] = $eventId;
                $inputArray['type'] = 'normal';
                $discountData = $this->discountHandler->add($inputArray);
                if (!$discountData['status']) {
                    $data['addDiscountOutput'] = $discountData;
                } else {
                    $this->customsession->setData('discountFlashMessage', SUCCESS_DISCOUNT_ADDED);
                    $redirectUrl = commonHelperGetPageUrl('dashboard-list-discount', $eventId);
                    redirect($redirectUrl);
                }
            }
        }
        $data['content'] = 'add_discount_view';
        $data['pageName'] = 'Add/Edit Discount';
        $data['pageTitle'] = 'MeraEvents | Add/Edit Discount';
        $data['hideLeftMenu'] = 0;
        $data['eventId'] = $eventId;
        $data['jsArray'] = array($this->config->item('js_public_path') . 'bootstrap',
             $this->config->item('js_public_path') . 'jQuery-ui',
            $this->config->item('js_public_path') . 'bootstrap-timepicker',            
            $this->config->item('js_public_path') . 'dashboard/discount');
        $data['cssArray'] = array($this->config->item('css_public_path') . 'dashboard-timepicker',
             $this->config->item('css_public_path') . 'bootstrap',
            $this->config->item('css_public_path') . 'jquery-ui');
        $this->load->view('templates/dashboard_template', $data);
    }

    public function addPromoter($eventId, $id = '') {
        $this->eventHandler = new Event_handler();
        $inputArray['eventId'] = $eventId;
        $eventDetails = $this->eventHandler->getEventDetails($inputArray);
        $eventUrl = $eventDetails['response']['details']['eventUrl'];
        if ($eventDetails) {
            $data['eventId'] = $eventDetails['response']['details']['id'];
            $data['eventName'] = $eventDetails['response']['details']['title'];
        }

        if ($this->input->post('submit')) {
            $inputArray['name'] = $this->input->post('promoterName');
            $inputArray['email'] = $this->input->post('promoterEmail');
            $inputArray['code'] = $this->input->post('promoterCode');
            $inputArray['type'] = 'affliate';
            $inputArray['templateType'] = TYPE_PROMOTER_INVITE;
            $inputArray['templateMode'] = 'email';
            $output = $this->promoterHandler->insertPromoter($inputArray, $eventDetails);
            if ($output['status']) {
                $this->customsession->setData('promoterSuccessAdded', SUCCESS_ADDED_PROMOTER);
                redirect(commonHelperGetPageUrl('dashboard-affliate', $eventId));
            } else {
                $data['output'] = $output['response']['messages'][0];
            }
        }
        
        $data['iframeURL'] = commonHelperGetPageUrl('ticketWidget','','?eventId=' . $inputArray['eventId']);
        if (!empty($id)) {
            $inputPromoter['promoterid'] = $id;
            $inputPromoter['eventid'] = $eventId;
            $promoterExistsResponse = $this->promoterHandler->isPromoterForEvent($inputPromoter);
            if(!$promoterExistsResponse['status']){
                $data['output']=$promoterExistsResponse['response']['messages'][0];
            }elseif ($promoterExistsResponse['response']['total']==0) {
                $data['errors'][] = 'THE URL YOU ENTERED SEEMS TO BE INCORRECT';
                //$this->load->view('templates/dashboard_template', $data);
            } else {
                $promoterData = $promoterExistsResponse['response']['promoterResponse'];
                $data['code'] = $promoterData[0]['code'];
                $data['name'] = $promoterData[0]['name'];

                $data['promoterEventURL'] = $eventUrl . '?ucode=' . $promoterData[0]['code'];
                $data['iframeURL'] .= '&ucode=' . $promoterData[0]['code'];
            }
        }
        $data['promoterId'] = $id;
        $data['eventUrl'] = $eventUrl;
        $data['content'] = 'add_promoter_view';
        $data['pageName'] = 'Add Promoter';
        $data['pageTitle'] = 'MeraEvents | Add Promoters';
        $data['hideLeftMenu'] = 0;
        $data['jsArray'] = array($this->config->item('js_public_path') . 'dashboard/promote');
        $this->load->view('templates/dashboard_template', $data);
    }

    public function affiliate($eventId) {
        $this->eventHandler = new Event_handler();
        $inputArray['eventId'] = $eventId;
        $eventDetails = $this->eventHandler->getEventDetails($inputArray);
        if ($eventDetails) {
            $data['eventId'] = $eventDetails['response']['details']['id'];
            $data['eventName'] = $eventDetails['response']['details']['title'];
            $timeZoneData['timezoneId'] = $eventDetails['response']['details']['timeZoneId'];
            $timeZoneData['status'] = 1;
            $this->timezoneHandler = new Timezone_handler();
            $timeZoneDetails = $this->timezoneHandler->details($timeZoneData);
            $timeZoneName = "";
            if ($timeZoneDetails['status']) {
                $timeZoneName = $timeZoneDetails['response']['detail'][1]['name'];
            }
            $data['eventTimeZoneName'] = $timeZoneName;
        }
        $inputArray['type'] = 'affliate';
        $data['promoterDetails'] = $this->promoterHandler->getPromoterList($inputArray);
        $data['content'] = 'promoter_list_view';
        $data['pageName'] = 'Promoter List';
        $data['pageTitle'] = 'MeraEvents | Promoter List';
        $data['hideLeftMenu'] = 0;
        $data['jsArray'] = array($this->config->item('js_public_path') . 'dashboard/promote');
        $this->load->view('templates/dashboard_template', $data);
    }

    public function discount($eventId, $id = '') {
        $this->eventHandler = new Event_handler();
        $this->timezoneHandler = new Timezone_handler();
        $inputArray['eventId'] = $eventId;
        $eventDetails = $this->eventHandler->getEventDetails($inputArray);
        if ($eventDetails) {
            $data['eventName'] = $eventDetails['response']['details']['title'];
            $data['eventId'] = $eventDetails['response']['details']['id'];
            $timeZoneData['timezoneId'] = $eventDetails['response']['details']['timeZoneId'];
            $timeZoneData['status'] = 1;
            $timeZoneDetails = $this->timezoneHandler->details($timeZoneData);
            $timeZoneName = "";
            if ($timeZoneDetails['status']) {
                $timeZoneName = $timeZoneDetails['response']['detail'][1]['name'];
            }
            $data['eventTimeZoneName'] = $timeZoneName;
            $data['timezoneId'] = $timeZoneData['timezoneId'];
        }

        //Getting all discounts related to the event
        $inputArray['type'] = 'normal'; //Setting field 'type' as 'normal' to get normal discount list     
        //Updating the status depending on the start date and usage limit(availability)        
        $statusUpdated=$this->discountHandler->updateDiscountStatus($inputArray);
        $allDiscountList = $this->discountHandler->getDiscountList($inputArray);
        if ($allDiscountList['response']['total'] == 0) {
            $data['noDiscountMessage'] = $allDiscountList['response']['messages'][0];
        } else {
            $discountList = $allDiscountList['response']['discountList'];
            if ($discountList) {
                $data['discountList'] = $discountList;
            }

            //Getting all discount ids related to the event
            $discountIdList = array();
            foreach ($discountList as $key => $value) {
                $discountIdList[] = $value['id'];
            }

            //Getting all ticketdiscount data to get the tickets related to discounts of the event.
            $this->ticketDiscountHandler = new Ticketdiscount_handler();
            $allTicketDiscountList = $this->ticketDiscountHandler->getTicketDiscountData($discountIdList);
            $ticketDiscountList = $allTicketDiscountList['response']['ticketDiscountList'];

            //Deleting the discount
            if ($id) {
                $inputArray['id'] = $id;
                $inputArray['type'] = 'normal';
                $deletedStatus = $this->discountHandler->deleteDiscount($inputArray);
                if ($deletedStatus) {
                    $redirectUrl = commonHelperGetPageUrl('dashboard-list-discount', $eventId);
                    redirect($redirectUrl);
                }
            }
        }
        $data['pageName'] = 'Event Dashboard';
        $data['pageTitle'] = 'MeraEvents | Discount Codes';
        $data['hideLeftMenu'] = 0;
        $data['content'] = 'discount_list_view';
        $data['eventId'] = $eventId;
        $data['jsArray'] = array($this->config->item('js_public_path') . 'dashboard/promote');
        $this->load->view('templates/dashboard_template', $data);
    }

    public function bulkDiscount($eventId, $id = '') {
        $this->eventHandler = new Event_handler();
        $this->timezoneHandler = new Timezone_handler();
        $inputArray['eventId'] = $eventId;
        $eventDetails = $this->eventHandler->getEventDetails($inputArray);
        if ($eventDetails) {
            $data['eventName'] = $eventDetails['response']['details']['title'];
            $data['eventId'] = $eventDetails['response']['details']['id'];
            $timeZoneData['timezoneId'] = $eventDetails['response']['details']['timeZoneId'];
            $timeZoneData['status'] = 1;
            $timeZoneDetails = $this->timezoneHandler->details($timeZoneData);
            $timeZoneName = "";
            if ($timeZoneDetails['status']) {
                $timeZoneName = $timeZoneDetails['response']['detail'][1]['name'];
            }
            $data['eventTimeZoneName'] = $timeZoneName;
            $data['timezoneId'] = $timeZoneData['timezoneId'];
        }

        //Getting all discounts related to the event
        $inputArray['type'] = 'bulk'; //Setting field 'type' as 'bulk' to get bulk discount list     
        //Updating the status depending on the start date and usage limit(availability)        
        $statusUpdated=$this->discountHandler->updateDiscountStatus($inputArray);        
        $allDiscountList = $this->discountHandler->getDiscountList($inputArray);
        if ($allDiscountList['response']['total'] == 0) {
            $data['noDiscountMessage'] = $allDiscountList['response']['messages'][0];
        } else {
            $discountList = $allDiscountList['response']['discountList'];
            if ($discountList) {
                $data['discountList'] = $discountList;
            }

            //Getting all discount ids related to the event
            $discountIdList = array();
            foreach ($discountList as $key => $value) {
                $discountIdList[] = $value['id'];
            }

            //Getting all ticketdiscount data to get the tickets related to discounts of the event.
            $this->ticketDiscountHandler = new Ticketdiscount_handler();
            $allTicketDiscountList = $this->ticketDiscountHandler->getTicketDiscountData($discountIdList);
            $ticketDiscountList = $allTicketDiscountList['response']['ticketDiscountList'];

            //Deleting the discount
            if ($id) {
                $inputArray['id'] = $id;
                $inputArray['type'] = 'bulk';
                $deletedStatus = $this->discountHandler->deleteDiscount($inputArray);
                if ($deletedStatus) {
                    $redirectUrl = commonHelperGetPageUrl('dashboard-bulkdiscount', $eventId);
                    redirect($redirectUrl);
                }
            }
        }
        $data['pageName'] = 'Event Dashboard';
        $data['pageTitle'] = 'MeraEvents | Bulk Discount Codes';
        $data['hideLeftMenu'] = 0;
        $data['content'] = 'bulk_discount_list_view';
        $data['eventId'] = $eventId;
        $data['jsArray'] = array($this->config->item('js_public_path') . 'dashboard/promote');
        $this->load->view('templates/dashboard_template', $data);
    }

    public function addBulkDiscount($eventId, $id = '') {
        $this->eventHandler = new Event_handler();
        $this->timezoneHandler = new Timezone_handler();
        $inputArray['eventId'] = $eventId;
        $data['output'] = array();
        $eventDetails = $this->eventHandler->getEventDetails($inputArray);
        $timeZoneName = "";
        if ($eventDetails) {
            $data['eventName'] = $eventDetails['response']['details']['title'];
            $data['eventEndDate'] = $eventDetails['response']['details']['endDate'];
            $timeZoneData['timezoneId'] = $eventDetails['response']['details']['timeZoneId'];
            $timeZoneData['status'] = 1;
            $timeZoneDetails = $this->timezoneHandler->details($timeZoneData);

            if ($timeZoneDetails['status']) {
                $timeZoneName = $timeZoneDetails['response']['detail'][1]['name'];
            }
        }
        $data['eventTimeZoneName'] = $timeZoneName;
        $ticketsDetails = $this->ticketHandler->getTicketName($inputArray);
        if ($ticketsDetails['status']) {
            $data['ticketDetails'] = $ticketsDetails;
        } else {
            $data['ticketDetails'] = array();
        }

        //For editing the discount details
        if (!empty($id)) {
            $inputs['eventId'] = $eventId;
            $inputs['id'] = $id;
            $inputs['type'] = 'bulk';
            $discountDetails = " ";
            $discountDetails = $this->discountHandler->getDiscountList($inputs);
            $data['discountDetails'] = $discountDetails;
            if ($discountDetails) {
                //Getting discountId              
                $discountId = $discountDetails['response']['discountList'][0]['id'];

                //Getting all ticket ids related to the discount code you want to edit           
                $this->ticketDiscountHandler = new Ticketdiscount_handler();
                $ticketDiscountData = $this->ticketDiscountHandler->getTicketDiscountData($discountId);
                $ticketDiscountData = $ticketDiscountData['response']['ticketDiscountList'];
                $selectedTicketIdList = $ticketIdList = array();
                foreach ($ticketDiscountData as $key => $value) {
                    $ticketIdList[] = $value['ticketid'];
                    if ($value['status'] == 1) {
                        $selectedTicketIdList[] = $value['ticketid'];
                }
                }
                $data['ticketIdList'] = $selectedTicketIdList;
                $discountData = $this->input->post('discountSubmit');
                if ($discountData) {
                    $inputArray = $this->input->post(); //Storing all the input form values in the inputArray
                    $inputArray['eventId'] = $eventId;
                    $inputArray['eventTimeZoneName'] = $timeZoneName;
                    $inputArray['type'] = 'bulk';
                    $inputArray['id'] = $id;
                    $inputArray['dbTicketIdList'] = $ticketIdList;
                    $discountData = $this->discountHandler->update($inputArray);
                    if ($discountData['status']) {
                        $this->customsession->setData('discountFlashMessage', SUCCESS_UPDATED);
                        $redirectUrl = commonHelperGetPageUrl('dashboard-bulkdiscount', $eventId);
                        redirect($redirectUrl);
                    } else {
                        $data['addDiscountOutput'] = $discountData;
                    }
                }
            }
        } else {
            //For adding the discount
            $discountData = $this->input->post('discountSubmit');
            if ($discountData) {
                $inputArray = $this->input->post(); //Storing all the input form values in the inputArray
                $inputArray['eventTimeZoneName'] = $timeZoneName;
                $inputArray['eventId'] = $eventId;
                $inputArray['type'] = 'bulk';
                $discountData = $this->discountHandler->add($inputArray);
                if (!$discountData['status']) {
                    $data['addDiscountOutput'] = $discountData;
                } else {
                    $this->customsession->setData('discountFlashMessage', SUCCESS_DISCOUNT_ADDED);
                    $redirectUrl = commonHelperGetPageUrl('dashboard-bulkdiscount', $eventId);
                    redirect($redirectUrl);
                }
            }
        }
        $data['content'] = 'add_bulk_discount_view';
        $data['pageName'] = 'Add/Edit Bulk Discount';
        $data['pageTitle'] = 'MeraEvents | Add/Edit Bulk Discount';
        $data['hideLeftMenu'] = 0;
        $data['eventId'] = $eventId;
        $data['jsArray'] = array($this->config->item('js_public_path') . 'bootstrap',
             $this->config->item('js_public_path') . 'jQuery-ui',
            $this->config->item('js_public_path') . 'bootstrap-timepicker',            
           $this->config->item('js_public_path') . 'dashboard/discount');
        $data['cssArray'] = array($this->config->item('css_public_path') . 'dashboard-timepicker',
             $this->config->item('css_public_path') . 'bootstrap',
            $this->config->item('css_public_path') . 'jquery-ui');
        $this->load->view('templates/dashboard_template', $data);
    }

    public function offlinePromoter($eventId) {
        $inputArray['eventId'] = $eventId;
        $data['eventId'] = $eventId;
        $data['eventName'] = commonHelperGetEventName($eventId);
        $inputArray['type'] = 'offline';
        $offlinePromotorList = $this->promoterHandler->getOfflinePromoterList($inputArray);
        $data['pageName'] = 'Offline Promoter Sale';
        $data['pageTitle'] = 'MeraEvents | Offline Promoter Sale';
        $data['hideLeftMenu'] = 0;
        $data['content'] = 'ofline_promoter_list_view';
        $data['jsArray'] = array(
            $this->config->item('js_public_path') . 'dashboard/promote',
            $this->config->item('js_public_path') . 'dashboard/offlinePromoter');
        $data['offlinePromotorList'] = $offlinePromotorList;
        $this->load->view('templates/dashboard_template', $data);
    }

    public function addOfflinePromoter($eventId, $id = '') {
        $inputArray['eventId'] = $eventId;
        $inputArray['id'] = $id;
        $inputArray['ticketType'] = 'paidfree';
        $inputArray['soldout'] = 0;
        $inputArray['feature'] = 'Coming Soon';
        $eventDetails = $this->eventHandler->getEventDetails($inputArray);
        $offlinePromoter = $this->promoterHandler->getOfflinePromoterData($inputArray);
        $tickets = $this->ticketHandler->getTicketName($inputArray);
        
        $dInputArray['eventId'] = $eventId;
        $dInputArray['type'] = 'normal';
        $discounts = $this->discountHandler->getDiscountList($dInputArray);
        $data['discountsInfo']=$discounts['response']['discountList'];
         //Getting all discount ids related to the event
        $discountIdList = array();
        foreach ($data['discountsInfo'] as $key => $value) {
            $discountIdList[] = $value['id'];
        }

        //Getting all ticketdiscount data to get the tickets related to discounts of the event.
        $ticketDiscountMappingArray = array();
        if(count($discountIdList)> 0){
            $this->ticketDiscountHandler = new Ticketdiscount_handler();
            
            $allTicketDiscountList = $this->ticketDiscountHandler->getTicketDiscountData($discountIdList);
            $ticketDiscountList = $allTicketDiscountList['response']['ticketDiscountList'];
            foreach ($ticketDiscountList as $key => $value) {
                $ticketDiscountMappingArray[$value['ticketid']][] = $value['discountid'];
            }
        }
        
        $data['ticketDiscountMappingArray']=$ticketDiscountMappingArray;
        $update = $this->input->post('formSubmit');		
        if (!empty($id)) {
                $inputArray = $this->input->post();
                $inputArray['eventId'] = $eventId;
                $inputArray['id'] = $id;
                $inputArray['promoterId'] = $id;
                $inputArray['name'] = $this->input->post('promoterName');
                $inputArray['mobile'] = $this->input->post('promoterMobile');
                $inputArray['ticketIds'] = $this->input->post('ticketIds');
                $offlineTickets = $this->offlinepromoterticketmappingHandler->getOfflineTickets($inputArray);
                $promoterTickets = $offlineTickets['response']['offline'];
                $inputArray['ticketslimit'] = $this->input->post('ticketslimit'); 
                
            if($inputArray['ticketslimit']==''){
                    $inputArray['ticketslimit']=null;
                }
                $ticketIdList = array();
                foreach ($promoterTickets as $key => $value) {
                    $ticketIdList[] = $value['ticketid'];
                    if($value['status']==1){
                        $selectedTicketIdList[] = $value['ticketid'];
                    }
                }
                $data['selectedTicketIdList']=$selectedTicketIdList;
                $data['editStatus']=TRUE;
                
                //selected ticket related discount list
                $selecteddiscountListArray['eventId']=$eventId;
                $selecteddiscountListArray['promoterId']=$id;
                
                $selecteddiscountList = $this->offlinepromoterdiscountsHandler->getPrometerEvetTicketDiscounts($selecteddiscountListArray);

                foreach ($selecteddiscountList['response']['prometerDiscountList'] as $key =>$value){
                    $data['selectedDiscountList'][$value['ticketid']][]=$value['discountid'];
                    
                }
                
                $inputArray['dbTicketIdList'] = $ticketIdList;
                $update = $this->input->post('formSubmit');//For editing
                if ($update) {
                    $ticError='';
                    $pdArray['id']=$id;
                    $promoterDetails=$this->promoterHandler->getPromoterDataById($pdArray);
                    if($promoterDetails['status']){
                        $pArray['userId'] = $promoterDetails['response']['promoters'][0]['userid'];
                        $promoters = $this->promoterHandler->getPromoterEvents($pArray, $eventId);
                        
                         if ($promoters['status']) {
                            if (isset($promoters['response']['promoters']) && !empty($promoters['response']['promoters'])) {
                                $totalSoldTickets = isset($promoters['response']['promoters'][0]['quantity']) ? $promoters['response']['promoters'][0]['quantity'] : 0;
                                if(($inputArray['ticketslimit']!='' || $inputArray['ticketslimit']!=null) && ($inputArray['ticketslimit'] < $totalSoldTickets)){
                                   $ticError=str_replace('XXXX', $totalSoldTickets, ERROR_TICKET_LIMIT_UPDATE);
                                   $data['messages'] =$ticError;
                                }
                            }
                        }
                    }
                    
                //collecting the selected discount info
                foreach ($inputArray['ticketIds'] as $tId) {
                    $value = $this->input->post("ticketDiscount" . $tId);
                    if (isset($value)) {
                        $ticketDiscount = $this->input->post("ticketDiscount" . $tId);
                        foreach ($ticketDiscount as $discId) {
                            $inputArray['ticketDiscount'][] = $tId . "-" . $discId;
                        }
                    }
                }

                if($ticError==''){
                $offlinePromoter = $this->promoterHandler->updateOfflinePromoterData($inputArray);
                if (!$offlinePromoter['status']) {
                    $data['messages'] = $offlinePromoter['response']['messages']['0'];
                } else {
                    $this->customsession->setData('offlinePromoterFlashMessage', SUCCESS_UPDATED);
                    $redirectUrl = commonHelperGetPageUrl('dashboard-offlinepromoter', $eventId);
                    redirect($redirectUrl);
                }
                }
                }
            } elseif($update) {
                $inputArray['templateType'] = 'offlinePromoterInvite';
                $inputArray['templateMode'] = 'email';
                $inputArray['name'] = $this->input->post('promoterName');
                $inputArray['email'] = $this->input->post('promoterEmail');
                $inputArray['mobile'] = $this->input->post('promoterMobile');
                $inputArray['ticketIds'] = $this->input->post('ticketIds');
                foreach ($inputArray['ticketIds'] as $tId) {
                    $value=$this->input->post("ticketDiscount" . $tId);
                    if (isset($value)) {
                    $ticketDiscount = $this->input->post("ticketDiscount" . $tId);
                    foreach ($ticketDiscount as $discId) {
                        $inputArray['ticketDiscount'][] = $tId . "-" . $discId;
                        }
                    }
                }
            
            $inputArray['ticketslimit'] = $this->input->post('ticketslimit');
                if($inputArray['ticketslimit']==''){
                    $inputArray['ticketslimit']=null;
                }
                $offlinePromoter = $this->promoterHandler->addOfflinePromoter($inputArray, $eventDetails);
                if (!$offlinePromoter['status']) {
                    $data['messages'] = $offlinePromoter['response']['messages']['0'];
                } else {
                    $this->customsession->setData('offlinePromoterFlashMessage', SUCCESS_ADDED_OFFLINE_PROMOTER);
                    $redirectUrl = commonHelperGetPageUrl('dashboard-offlinepromoter', $eventId);
                    redirect($redirectUrl);
                }
            }        
        $data['tickets'] = $tickets['response']['ticketName'];
        $data['eventId'] = $eventDetails['response']['details']['id'];
        $data['eventName'] = $eventDetails['response']['details']['title'];
        $data['pageName'] = 'Add Promoter Sale';
        $data['pageTitle'] = 'MeraEvents | Add Offline Promoter Sale';
        $data['hideLeftMenu'] = 0;
        $data['offlinePromoter'] = $offlinePromoter['response']['offlinePromoter'];
        $data['content'] = 'add_ofline_promoter_view';  
        $data['jsArray'] = array($this->config->item('js_public_path').'dashboard/offlinePromoter');
        $this->load->view('templates/dashboard_template', $data);
    }
    public function guestListBooking($eventId) {
        $inputArray['eventId'] = $eventId;
        $data['eventName'] = commonHelperGetEventName($eventId);
        $inputArray['ticketType']='donation';
        $tickets = $this->ticketHandler->getTicketName($inputArray);
        if($tickets['status'] == TRUE && $tickets['response']['total'] == 0){
            $data['status'] = false;
            $data['messages'] = $tickets['response']['messages'][0];
        }
        $update = $this->input->post('guestBooking');
        $ticketId = $this->input->post('ticketId');
        $inputArray['ticketId'] = $ticketId;
        if ($update) {
             $booking = $this->guestlistbookingHandler->guestListBooking($inputArray);
              if ($booking['status'] == TRUE) { 
                $data['status'] = true;
                $data['messages'] = $booking['response']['messages'][0];
                $successMessage=$booking['response']['messages'][0];
                $this->customsession->setData('guestListBookingSuccessMessage', $successMessage);
                $redirectUrl = commonHelperGetPageUrl('dashboard-guestlist-booking', $eventId);
                redirect($redirectUrl);
            } else {
                $data['status'] = false;
                $data['messages'] = $booking['response']['messages'][0];
            } 
        }
        $data['tickets'] = $tickets['response']['ticketName'];
        $data['pageName'] = 'Guest List Booking';
        $data['pageTitle'] = 'MeraEvents | Guest List Bookings';
        $data['hideLeftMenu'] = 0;
        $data['content'] = 'guestlist_booking_view';
        $data['jsArray'] = array(
            $this->config->item('js_public_path') . 'dashboard/offlinePromoter'
        );
        $this->load->view('templates/dashboard_template', $data);
    }
}

?>