<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
/**
 * Organization controller (Grouping of events Page)
 *
 * @package		CodeIgniter
 * @author		Qison  Dev Team
 * @copyright	Copyright (c) 2015, Meraevents.
 * @Version		Version 1.0
 * @Since       Class available since Release Version 1.0 
 * @Created     07-12-2015
 * @Last Modified On  07-12-2015
 * @Last Modified By  Raviteja
 */
require_once(APPPATH . 'handlers/seating_handler.php');
require_once(APPPATH . 'handlers/ticket_handler.php');
require_once(APPPATH . 'handlers/common_handler.php');
require_once(APPPATH . 'handlers/event_handler.php');

class Seating extends CI_Controller {

    var $organizationHandler;
    var $commonHandler;
    var $ticketHandler;
    var $eventHandler;

    public function __construct() {

        parent::__construct();
        $this->seatingHandler = new seating_handler();
        $this->ticketHandler = new Ticket_handler();
        $this->commonHandler = new Common_handler();
        $this->eventHandler = new Event_handler;

        $this->defaultCountryId = $this->defaultCityId = $this->defaultCategoryId = 0;
        $this->defaultCustomFilterId = 1;
    }

    public function index() {
        $getVar = $this->input->get();
        $orderId = $getVar['orderid'];

        $orderLogData = $this->soldTicketValidation($orderId);
        $redirectUrl = site_url();
        if (($orderLogData['status'] && $orderLogData['response']['total'] == 0) || !$orderLogData['status']) {
            redirect($redirectUrl);
        }
        //print_r($orderLogData);exit;
        $orderLogSessionData = unserialize($orderLogData['response']['orderLogData']['data']);
        //$ticketCount = count($orderLogSessionData['ticketarray']);
        $selectedTicketData = $orderLogSessionData['ticketarray'];
        $eventId = ($orderLogSessionData['eventid']) ? $orderLogSessionData['eventid'] : '';
        $eventsignupId = $orderLogData['response']['orderLogData']['eventsignup'];
        //print_r($orderLogData['response']['orderLogData']);exit;
        if ($eventId == '') {
            redirect($redirectUrl);
        }

        $data = $data['countryList'] = array();
        $isExisted = FALSE;
        $orderLogData = $orderLogData['response']['orderLogData'];
        if ($orderLogData['eventsignup'] > 0 && isset($orderLogSessionData['paymentResponse']) && is_array($orderLogSessionData['paymentResponse']) &&
                ($orderLogSessionData['paymentResponse']['TransactionID'] > 0 || $orderLogSessionData['paymentResponse']['mode'] != '')) {
            $isExisted = TRUE;
        }
        $paymentGatewaySelected = $orderLogSessionData['paymentGatewaySelected'];
        $addressStr = $orderLogSessionData['addressStr'];
        //print_r($orderLogSessionData);exit;
        if ($orderLogSessionData['widgetredirecturl'] != '') {
            $data['redirectUrl'] = $orderLogSessionData['widgetredirecturl'];
        }
        if ($orderLogSessionData['referralcode'] != '') {
            $data['referralcode'] = $orderLogSessionData['referralcode'];
        }
        if ($orderLogSessionData['promotercode'] != '') {
            $data['promotercode'] = $orderLogSessionData['promotercode'];
        }
        $cookieData = $this->commonHandler->headerValues();
        // print_r($cookieData);
        // exit;
        if (count($cookieData) > 0) {
            // $data['countryList'] = isset($cookieData['countryList']) ? $cookieData['countryList'] : array();
            $this->defaultCountryId = isset($cookieData['defaultCountryId']) ? $cookieData['defaultCountryId'] : $this->defaultCountryId;
            $data = $cookieData;
        }

        $footerValues = $this->commonHandler->footerValues();
        $data['categoryList'] = $footerValues['categoryList'];
        $data['defaultCountryId'] = $this->defaultCountryId;
        $data['calculationDetails'] = $orderLogSessionData['calculationDetails'];
        $data['addonArray'] = isset($orderLogSessionData['addonArray']) ? $orderLogSessionData['addonArray'] : array();
        if (!$isExisted) {
            $eventNameResponse = $this->eventHandler->getEventName($eventId);
            if ($eventNameResponse['status'] && $eventNameResponse['response']['total'] > 0) {
                $eventTitle = $eventNameResponse['response']['eventName'];
            }
            $eventData['title'] = $eventTitle;
            $data['eventData'] = $eventData;
            $data['orderLogId'] = $orderId;
            $data['eventTitle'] = $eventTitle;

            /* Code to get the event related gateways starts here */
            $eventGateways = array();
            $gateWayInput['eventId'] = $eventId;
            $gateWayInput['gatewayStatus'] = true;
            $gateWayData = $this->eventHandler->getEventPaymentGateways($gateWayInput);
            if ($gateWayData['status'] && count($gateWayData['response']['gatewayList']) > 0) {
                $eventGateways = $gateWayData['response']['gatewayList'];
            }
            foreach ($eventGateways as $value) {
                if ($value['gatewayName'] == 'ebs') {
                    $ebsKey = $value['paymentgatewayid'];
                } elseif ($value['gatewayName'] == 'paytm') {
                    $paytmKey = $value['paymentgatewayid'];
                } elseif ($value['gatewayName'] == 'mobikwik') {
                    $mobikwikKey = $value['paymentgatewayid'];
                } elseif ($value['gatewayName'] == 'paypal') {
                    $paypalKey = $value['paymentgatewayid'];
                }
            }
            $data['paymentGatewaySelected'] = $paymentGatewaySelected;
            if ($paymentGatewaySelected == 'ebs') {
                $data['ebsGateway'] = 1;
                $data['ebsKey'] = $ebsKey;
            } elseif ($paymentGatewaySelected == 'paytm') {
                $data['paytmGateway'] = 1;
                $data['paytmKey'] = $paytmKey;
            } elseif ($paymentGatewaySelected == 'mobikwik') {
                $data['mobikwikGateway'] = 1;
                $data['mobikwikKey'] = $mobikwikKey;
            } elseif ($paymentGatewaySelected == 'paypal') {
                $data['paypalGateway'] = 1;
                $data['paypalKey'] = $paypalKey;
            }
            $data['primaryAddress'] = $addressStr;
            $inputAvail['eventsignupid'] = $eventsignupId;
            $inputAvail['eventid'] = $eventId;
            $updateResponse = $this->seatingHandler->updateToAvailable($inputAvail);
            if ($updateResponse['status'] && $updateResponse['response']['total'] > 0) {
                
            }
            $data['availableImage'] = $this->config->item('images_static_path') . 'available.png';
            $data['otherAreaImage'] = $this->config->item('images_static_path') . 'other-area.png';
            $data['bookedImage'] = $this->config->item('images_static_path') . 'booked.png';
            $data['currentBookingImage'] = $this->config->item('images_static_path') . 'current-booking.png';
            /* Getting the Event Details starts here */
            $inputGetData['eventid'] = $eventId;
            $response = $this->seatingHandler->getData($inputGetData);
            //print_r($response);exit;
            $data['ResSeatslevel1'] = $response['data']['ResSeats']['Rookie'];
            $data['ResSeatslevel2'] = $response['data']['ResSeats']['Trainee'];
            $data['ResSeatslevel3'] = $response['data']['ResSeats']['Scouted'];
            //$data['ResSeatslevel4'] = $response['data']['ResSeats']['Balcony'];
           // print_r($response['data']['ResSeats']);exit;
            $data['eventId'] = $eventId;
            $data['eventsignupId'] = $eventsignupId;
            $data['selectedTicketData'] = $selectedTicketData;
            $data['defaultCityId'] = '';
            $inputArray['eventId'] = $eventId;
            $tickets = $this->ticketHandler->getEventTicketList($inputArray);
            if ($tickets['status'] && $tickets['response']['total'] > 0) {
                $data['ticketsData'] = commonHelperGetIdArray($tickets['response']['ticketList']);
            }
        }
        $inputVenue['eventid'] = $eventId;
        $venueResponse = $this->seatingHandler->getVenue($inputVenue);
        //print_r($venueResponse);exit;
        $venue = '';
        if ($venueResponse['status'] && $venueResponse['response']['total'] > 0) {
            $venue = $venueResponse['response']['venue'];
        }
        $data['pageTitle'] = isset($eventData['title']) ? $eventData['title'] . ' | ' : '';
        $data['pageTitle'].='Book tickets online for music concerts, live shows and professional events. Be informed about upcoming events in your city';
        $data['content'] = $venue;
        $data['isExisted'] = $isExisted;
//        print_r($data);
//        exit;
        //$data['defaultCountryId'] = $this->defaultCountryId;
        $data['jsArray'] = array(
            $this->config->item('js_public_path') . 'seatinglayout', $this->config->item('js_public_path') . 'common');
        //$data['cssArray'] = array($this->config->item('protocol'). $_SERVER['HTTP_HOST'] . '/css/public/styles-seating');
        $this->load->view('templates/user_template', $data);
    }

    public function insert() {
        $inputArray['eventId'] = '83087';
        $inputArray['venueseatid'] = 7;
        $inputArray['level'] = array('Level1' => 'AJ', 'Level2' => 'KS', 'Level3' => 'TZ', 'Balcony' => 'AM');
        $inputArray['seattype'] = array(
            'Available',
            'Booked',
            'Reserved',
            'Inprocess'
        );
        $inputArray['rowwise'] = array(
            'Level1-A' => '35,14x-09i-04x-17i-05x-09i-L',
            'Level1-B' => '38,13x-10i-04x-17i-05x-11i-L',
            'Level1-C' => '41,12x-11i-04x-18i-04x-12i-L',
            'Level1-D' => '42,12x-11i-03x-19i-04x-12i-L',
            'Level1-E' => '42,12x-11i-03x-19i-04x-12i-L',
            'Level1-F' => '47,10x-13i-03x-20i-03x-14i-L',
            'Level1-G' => '49,09x-14i-03x-20i-03x-15i-L',
            'Level1-H' => '50,09x-14i-03x-21i-02x-15i-L',
            'Level1-I' => '50,09x-14i-03x-21i-02x-15i-L',
            'Level1-J' => '53,08x-15i-02x-22i-02x-16i-L',
            'Level2-K' => '54,08x-15i-01x-23i-02x-16i-L',
            'Level2-L' => '53,08x-15i-01x-23i-02x-15i-L',
            'Level2-M' => '24,24x-24i-L',
            'Level2-N' => '61,3x-20i-03x-21i-04x-20i-L',
            'Level2-O' => '61,3x-20i-03x-21i-04x-20i-L',
            'Level2-P' => '60,04x-19i-03x-22i-03x-20i-01x-L',
            'Level2-Q' => '62,03x-20i-03x-22i-03x-21i-L',
            'Level2-R' => '63,03x-20i-03x-23i-02x-21i-L',
            'Level2-S' => '41,05x-18i-02x-23i-L',
            'Level3-T' => '41,06x-17i-02x-24i-L',
            'Level3-U' => '56,07x-16i-02x-24i-02x-16i-L',
            'Level3-V' => '55,08x-15i-01x-25i-02x-15i-L',
            'Level3-W' => '53,09x-14i-01x-25i-02x-14i-L',
            'Level3-X' => '54,09x-14i-01x-26i-01x-14i-L',
            'Level3-Y' => '50,11x-12i-01x-26i-01x-12i-L',
            'Level3-Z' => '52,09x-14i-02x-24i-02x-14i-L',
            'Balcony-A' => '68,23i-02x-22i-03x-23i-L',
            'Balcony-B' => '69,23i-02x-23i-02x-23i-L',
            'Balcony-C' => '69,23i-02x-23i-02x-23i-L',
            'Balcony-D' => '68,01x-22i-01x-24i-02x-22i-L',
            'Balcony-E' => '68,01x-22i-01x-24i-02x-22i-L',
            'Balcony-F' => '71,23i-01x-25i-01x-23i-L',
            'Balcony-G' => '66,02x-21i-01x-25i-01x-20i-L',
            'Balcony-H' => '52,06x-17i-05x-18i-04x-17i-L',
            'Balcony-I' => '50,07x-16i-05x-18i-04x-16i-L',
            'Balcony-J' => '50,07x-16i-04x-19i-04x-15i-L',
            'Balcony-K' => '49,08x-15i-04x-19i-04x-15i-L',
            'Balcony-L' => '52,07x-16i-03x-20i-04x-16i-L',
            'Balcony-M' => '58,04x-19i-04x-20i-03x-19i-L'
        );
        $data['insertData'] = $this->seatingHandler->insertSeats($inputArray);
        $data['categoryList'] = $footerValues['categoryList'];
        $data['content'] = 'seating_insert_view';
        $data['defaultCountryId'] = $this->defaultCountryId;
        $this->load->view('templates/user_template', $data);
    }

    public function selectseat($inputArray) {

        $inputArray['eventId'] = '82970';
        $tickets = $this->ticketHandler->getEventTicketList($inputArray);
        echo "<pre>";
        print_r($tickets);
        exit;
    }

    function soldTicketValidation($orderId) {

        $orderLogInput['orderId'] = $orderId;
        $redirectUrl = site_url();  // Need to replace it after finializing the error response page

        $this->orderlogHandler = new Orderlog_handler();
        $orderLogData = $this->orderlogHandler->getOrderlog($orderLogInput);
        if (($orderLogData['status'] && $orderLogData['response']['total'] == 0) || !$orderLogData['status']) {
            redirect($redirectUrl);
        }
        $orderLogSessionData = $orderLogData['response']['orderLogData']['data'];
        $orderLogSessionDataArr = unserialize($orderLogSessionData);
        $ticketArray = $orderLogSessionDataArr['ticketarray'];

        $ticketIds = array_keys($ticketArray);
        $ticketDataInput['eventId'] = $orderLogSessionDataArr['eventid'];
        $ticketDataInput['ticketIds'] = $ticketIds;
        $ticketsData = $this->ticketHandler->getTicketsbyIds($ticketDataInput);
        $ticketDataArr = $ticketsData['response']['ticketdetails'];

        foreach ($ticketDataArr as $ticket) {
            $ticketSoldQty = $ticket['totalsoldtickets'];
            $availableTktQty = $ticket['quantity'];

            $ticketNewSoldQty = $ticketSoldQty + $ticketArray[$ticket['id']];

            // If the selected quantity with already sold tickets exceeded total quantity
            if ($ticketNewSoldQty > $availableTktQty) {
                $errorMessage = $ticket['name'] . ERROR_TICKET_EXCEEDED;
                $this->customsession->setData('booking_message', $errorMessage);
                redirect($redirectUrl);
            }
        }
        return $orderLogData;
    }

}

?>