<?php
/**
 * Reports related business logic will be defined in this class
 * @package		CodeIgniter
 * @author		Qison  Dev Team
 * @param		
 * @addTicket		
 * @copyright	Copyright (c) 2015, Meraevents.
 * @Version		Version 1.0
 * @Since       Class available since Release Version 1.0
 * @Created     03-08-2015
 * @Last Modified 03-08-2015
 */
require_once (APPPATH . 'handlers/handler.php');
require_once (APPPATH . 'handlers/eventsignup_handler.php');
require_once (APPPATH . 'handlers/eventsignupticketdetail_handler.php');
require_once (APPPATH . 'handlers/configure_handler.php');
require_once (APPPATH . 'handlers/email_handler.php');
require_once (APPPATH . 'handlers/event_handler.php');
require_once (APPPATH . 'handlers/tickettax_handler.php');
require_once (APPPATH . 'handlers/file_handler.php');
require_once (APPPATH . 'handlers/attendee_handler.php');
require_once (APPPATH . 'handlers/attendeedetail_handler.php');
require_once (APPPATH . 'handlers/paymentgateway_handler.php');
require_once (APPPATH . 'handlers/eventsignuptax_handler.php');
require_once (APPPATH . 'handlers/taxmapping_handler.php');
require_once (APPPATH . 'handlers/tax_handler.php');
//require_once (APPPATH . 'handlers/event_handler.php');
class Reports_handler extends Handler {
    var $ci;
    var $eventSignupHandler;
    var $eventSignupTicketdetailHandler;
    var $configureHandler;
    var $eventHandler;
    var $tickettaxHandler;
    var $taxesHeader;
    var $customFields;
    var $attendeeHandler;
    var $attendeedetailHandler;
    var $paymentgatewayHandler;
    var $eventsignupTaxHandler;
    var $taxMappingHandler;
    var $taxHandler;

    public function __construct() {
        parent::__construct();
        $this->ci = parent::$CI;
        // $this->ci->load->model('Ticket_model');
        $this->eventSignupHandler = new Eventsignup_handler();
        $this->eventSignupTicketdetailHandler = new Eventsignup_Ticketdetail_handler();
        $this->configureHandler = new Configure_handler();
        $this->eventHandler = new Event_handler();
        $this->tickettaxHandler = new Tickettax_handler();
        $this->attendeeHandler = new Attendee_handler();
        $this->taxesHeader = $this->customFields = array();
        $this->attendeedetailHandler = new Attendeedetail_handler();
        $this->paymentgatewayHandler = new Paymentgateway_handler();
    }

    public function getTransactions($inputArray) {
//        $eventId = $inputArray['eventid'];
//        $reportType = $inputArray['reporttype'];
//        $transactionType = $inputArray['transactiontype'];
//        $page = $inputArray['page'];
//        $eventExistsResponse = $this->eventHandler->eventExists($inputArray);
//        if (!$eventExistsResponse['status']) {
//            return $eventExistsResponse;
//        }
        $validateTypesResponse = $this->validateTypes($inputArray);
        if (!$validateTypesResponse['status']) {
            return $validateTypesResponse;
        }
        $inputArray['reporttype'] = $validateTypesResponse['response']['validTypes']['reportType'];
        $inputArray['transactiontype'] = $validateTypesResponse['response']['validTypes']['transactionType'];
        $transactions = $this->eventSignupHandler->getTransByEventId($inputArray);
        return $transactions;
    }

    public function validateTypes($inputArray) {
        $reportType = isset($inputArray['reporttype']) ? $inputArray['reporttype'] : '';
        $transactionType = $inputArray['transactiontype'];
        $validReportTypes = $this->ci->config->item('report_types');
        $output['status'] = TRUE;
        $output['response']['messages'] = array();
        $output['response']['total'] = 1;
        if (!in_array($reportType, $validReportTypes)) {
            $reportType = 'summary';
        }
        $validTransTypes = $this->ci->config->item('transaction_types');
        if (!in_array($transactionType, $validTransTypes)) {
            $output['status'] = FALSE;
            $output['response']['total'] = 0;
            $output['response']['messages'][] = ERROR_INVALID_TRANSACTION_TYPE;
            return $output;
        }
        $output['response']['validTypes'] = array('reportType' => $reportType, 'transactionType' => $transactionType);
        return $output;
    }

    public function getHeaderFields($inputArray) {
        $this->ci->form_validation->reset_form_rules();
        $this->ci->form_validation->pass_array($inputArray);
        $this->ci->form_validation->set_rules('eventid', 'eventid', 'required_strict|is_natural_no_zero');
        //$this->ci->form_validation->set_rules('transactiontype', 'transactiontype', 'required_strict|is_valid_type[transaction]');
        if ($this->ci->form_validation->run() === FALSE) {
            $errorMessages = $this->ci->form_validation->get_errors();
            return $errorMessages;
        }
        $validateTypesResponse = $this->validateTypes($inputArray);
        if (!$validateTypesResponse['status']) {
            return $validateTypesResponse;
        }
        $inputArray['reporttype'] = $validateTypesResponse['response']['validTypes']['reportType'];
        $inputArray['transactiontype'] = $validateTypesResponse['response']['validTypes']['transactionType'];
        $transactionType = $inputArray['transactiontype'];
        $eventId = $inputArray['eventid'];
        $export = isset($inputArray['export']) ? true : false;
        $reportType = $inputArray['reporttype'];
        if ($export) {
            switch ($transactionType) {
                case 'incomplete':
                    $headerFields = $this->ci->config->item('incomplete_export_' . $reportType . '_header_fields');
                    break;
                case 'cod':
                    $headerFields = $this->ci->config->item('cod_export_' . $reportType . '_header_fields');
                    break;
                case 'cancel':
                    $headerFields = $this->ci->config->item('cancel_export_' . $reportType . '_header_fields');
                    break;
                case 'affiliate':
                    $headerFields = $this->ci->config->item('affiliate_export_' . $reportType . '_header_fields');
                    break;
                case 'refund':
                    $headerFields = $this->ci->config->item('refund_export_header_fields');
                    break;
                default:
                    $headerFields = $this->ci->config->item('common_export_' . $reportType . '_header_fields');
                    break;
            }
            if ($reportType == 'detail' && $transactionType != 'refund' && $transactionType != 'cancel') {
                $inputEventInfo['eventid'] = $eventId;
                $eventInfoResponse = $this->eventHandler->getEventInfoById($inputEventInfo);
                if ($eventInfoResponse['status'] && $eventInfoResponse['response']['total'] > 0) {
                    $inputTax['countryid'] = $eventInfoResponse['response']['eventInfo'][0]['countryid'];
                    $inputTax['stateid'] = $eventInfoResponse['response']['eventInfo'][0]['stateid'];
                    $inputTax['cityid'] = $eventInfoResponse['response']['eventInfo'][0]['cityid'];
                    $taxListResponse = $this->tickettaxHandler->getTaxes($inputTax);
                } else {
                    return $eventInfoResponse;
                }
                if ($taxListResponse['status']) {
                    if ($taxListResponse['response']['total'] > 0) {
                        foreach ($taxListResponse['response']['taxList'] as $value) {
                            $taxesHeader[] = $value['label'];
                        }
                        $this->taxesHeader = $taxesHeader;
                        $headerFields = array_merge($headerFields, $taxesHeader);
                    }
                } else {
                    return $taxListResponse;
                }
            }
            if ($reportType == 'detail') {
                $inputConfigure['eventId'] = $eventId;
                $inputConfigure['allfields'] = 1;
                $customFieldsResponse = $this->configureHandler->getCustomFields($inputConfigure);

                if ($customFieldsResponse['status'] && $customFieldsResponse['response']['total'] > 0) {
                    foreach ($customFieldsResponse['response']['customFields'] as $value) {
                        if ($value['displaystatus']) {
                            $this->customFields[] = $value['fieldname'];
                    }
                    }
                } else {
                    return $customFieldsResponse;
                }
                $headerFields = array_merge($headerFields, $this->customFields);
            }
        } else {
            switch ($transactionType) {
                case 'incomplete':
                    $headerFields = $this->ci->config->item('incomplete_header_fields');
                    break;
                case 'cod':
                    $headerFields = $this->ci->config->item('cod_header_fields');
                    break;
                case 'cancel':
                    $headerFields = $this->ci->config->item('cancel_header_fields');
                    break;
                case 'affiliate':
                    $headerFields = $this->ci->config->item('affiliate_header_fields');
                    break;
                case 'refund':
                    $headerFields = $this->ci->config->item('refund_header_fields');
                    break;
                default:$headerFields = $this->ci->config->item('common_header_fields');
                    break;
            }
        }
        if (count($headerFields) > 0) {
            $output['status'] = TRUE;
            $output['response']['headerFields'] = $headerFields;
            $output['response']['total'] = count($headerFields);
            return $output;
        } else {
            $output['status'] = FALSE;
            $output['response']['messages'][] = 'No header fields data';
            $output['response']['total'] = 0;
            return $output;
        }
    }

    public function exportTransactions($inputArray) {
        $this->ci->form_validation->reset_form_rules();
        $this->ci->form_validation->pass_array($inputArray);
        $this->ci->form_validation->set_rules('eventid', 'eventid', 'is_natural_no_zero|required_strict');
        $this->ci->form_validation->set_rules('reporttype', 'reporttype', 'required_strict');
        $this->ci->form_validation->set_rules('transactiontype', 'transactiontype', 'required_strict|is_valid_type[transaction]');
        //$this->ci->form_validation->set_rules('page', 'page', 'is_natural_no_zero|required_strict');
        if ($this->ci->form_validation->run() == FALSE) {
            $response = $this->ci->form_validation->get_errors();
            $output['status'] = FALSE;
            $output['response']['messages'] = $response['message'];
            $output['statusCode'] = STATUS_BAD_REQUEST;
            return $output;
        }
        $inputArray['page'] = 1;
        $eventId = $inputArray['eventid'];
        $reportType = $inputArray['reporttype'];
        $transactionType = $inputArray['transactiontype'];
        $inputArray['export'] = 1;
        if ($reportType == 'detail') {
            $transactionListResponse = $this->eventSignupHandler->getDetailExportInfo($inputArray);
        } else {
            $transactionListResponse = $this->eventSignupHandler->getSummaryExportInfo($inputArray);
        }
        // print_r($transactionListResponse);
        $transactionTotal = 0;
        if ($transactionListResponse['status'] && $transactionListResponse['response']['total'] > 0) {
            $transactionTotal = $transactionListResponse['response']['totalTransactionCount'];
            $transactionList = $transactionListResponse['response']['transactionList'];
        } else {
            return $transactionListResponse;
    }
        $seatingLayout = $transactionListResponse['response']['seatingLayout'];
        $loopLength = ceil($transactionTotal / REPORTS_TRANSACTION_LIMIT);
        $handle = NULL;
        $inputArray['page'] = 1;
        $transresult = 0;
        for ($exportLoop = 0; $exportLoop < $loopLength; $exportLoop++) { 
            if ($exportLoop == 0) {
                if ($transactionType == 'refund') {
                    $filename = appendTimeStamp($transactionType .'_'.$eventId . '_reports.csv');
                } else {
                    $filename = appendTimeStamp($reportType .'_'.$eventId. '_reports.csv');
                }
                $handle = fopen($this->ci->config->item('file_upload_temp_path') . $filename, 'wa+');
                $inputHeader['eventid'] = $eventId;
                $inputHeader['reporttype'] = $reportType;
                $inputHeader['transactiontype'] = $transactionType;
                $inputHeader['export'] = 1;
                $headerFieldsResponse = $this->getHeaderFields($inputHeader);
                //print_r($headerFieldsResponse);exit;
                if ($headerFieldsResponse['status'] && $headerFieldsResponse['response']['total'] > 0) {
                    $headers = $headerFieldsResponse['response']['headerFields']; 
                    // Adding Extra Column Seats when Event has Seating Layout
                    //$transactionListResponse['response']['seatingLayout']=1;
                    if ($transactionListResponse['response']['seatingLayout'] == 1) {
                        $headers = array_merge($headers, array('seats'));
                    }
                    $header = implode($headers, ',');
                    $header2 = $header . "\n";
                    $header2 = str_replace(array("\r\n", "\n\r", "\n", "\r"), " ", $header2);
                    fwrite($handle, $header2);
                } else {
                    return $headerFieldsResponse;
                }
                $rowData = '';

                $this->writeToCSV($handle, $transactionList, $reportType, $transactionType, $seatingLayout);
            } else {
                $inputArray['page'] = $inputArray['page'] + 1;
                if ($reportType == 'detail') {
                    $transactionListResponse = $this->eventSignupHandler->getDetailExportInfo($inputArray);
                } else {
                    $transactionListResponse = $this->eventSignupHandler->getSummaryExportInfo($inputArray);
                }
                if ($transactionListResponse['status'] && $transactionListResponse['response']['total'] > 0) {
                    $transactionList = $transactionListResponse['response']['transactionList'];
                    $rowData = '';
                    $this->writeToCSV($handle, $transactionList, $reportType, $transactionType, $seatingLayout);
                } else {
                    return $transactionListResponse;
                }
            }
        }
            $output['status'] = true;
            $output['statusCode'] = STATUS_OK;
            $output['response']['filename'] = 'reports/' . $filename;
            $output['response']['sourcepath'] = $this->ci->config->item('file_upload_temp_path') . $filename;
            $output['response']['total'] = 1;
            return $output;
    }

    public function emailTransactions($inputArray) {
        $promotercode = isset($getVar['promotercode']) ? $getVar['promotercode'] : '';
        if (!empty($promotercode)) {
            $inputArray['promotercode'] = $promotercode;
        }
        $exportFileResponse = $this->exportTransactions($inputArray);
        $emailHandler = new Email_handler();
        $userName = ucfirst($this->ci->customsession->getData('userName'));
        if ($exportFileResponse['status'] && $exportFileResponse['response']['total'] > 0) {
            $eventId = $inputArray['eventid'];
            $reportType = $inputArray['reporttype'];
            $transactionType = $inputArray['transactiontype'];
            $from = 'admin@meraevents.com';
            $to = $this->ci->customsession->getData('userEmail');
            $request['eventId'] = $eventId;
            $eventInfo = $this->eventHandler->eventExists($request);
            $title = '';
            if ($eventInfo['status']) {
                $eventNameResponse = $this->eventHandler->getEventName($eventId);
                if($eventNameResponse['status']){
                    $title = $eventNameResponse['response']['eventName'];
                }
            } else {
                return $eventInfo;
            }
            if ($transactionType == 'refund') {
                $message = '';
            } else {
                $message = 'Dear ' . $userName . ',Please find the attached ' . ucfirst($reportType) . ' report.';
            }
            if ($transactionType == 'refund') {
                
                $subject = 'Refund list as Attachment for event: '.$title;
            } else {
                $subject = ucfirst($reportType) . ' report as Attachment for : ' . ucwords($transactionType) . ' Transactions Event: ' . $title;
            }           
            $attachmentFilename = $exportFileResponse['response']['sourcepath'];
            $sentmessageInputs['notInsertIntoSentMessage'] = true;
            $emailSentResponse = $emailHandler->sendEmail($from, $to, $subject, $message, $attachmentFilename, '', '', $sentmessageInputs);
            $emailSentResponse['response']['emailSentTo'] = $to;
            $contactInfo = array();
            $contactInfo['eventId']=$inputArray['eventid'];
            $eventContactInfo = $this->eventHandler->getContactInfo($contactInfo);
            if($eventContactInfo['status'] == TRUE && $eventContactInfo['response']['total'] > 0 && strlen($eventContactInfo['response']['contactDetails']['extrareportingemails'])>0){
            $contacEmails=$eventContactInfo['response']['contactDetails']['extrareportingemails'];
            $cc= explode(',', $contacEmails);
            foreach($cc as $val){
            $emailSentResponse = $emailHandler->sendEmail($from, $val, $subject, $message, $attachmentFilename, '', '', $sentmessageInputs);
            }
            }
            return $emailSentResponse;
        } else {
            return $exportFileResponse;
        }
    }

    private function writeToCSV($handle, $transactionList, $reportType, $transactionType, $seatingLayout = 0) {
        fwrite($handle, PHP_EOL);
        if ($reportType == 'summary') {
            //print_r($transactionList);exit;
            foreach ($transactionList as $value) {

                //$signupSeats = explode(',', $value['seats']);
                $rowData = '';
                $loop = 1;
                $bookingTikcetsCount = count($value['ticketDetails']);
                foreach ($value['ticketDetails'] as $ticket) {
                    //	$signupSeats = explode(',', $ticket['seats']);
                    if ($loop == 1) {
                        if ($transactionType != 'incomplete') {
                            $rowData.='"'.$value['id'] . '",';
                        }
                        $rowData.='"'.$value['signupDate'] . '",';
                    } else {
                        $rowData.=',';
                        if ($transactionType != 'incomplete') {
                            $rowData.=',';
                        }
                    }
                    $rowData.=$ticket['tickettype'] . ',';
                    if ($transactionType == 'affiliate') {
                        $rowData.='"'.$ticket['promotercode'] . '",';
                    }
                    //promoter name
                    $rowData.=',';
                    //name
                    $rowData.='"'.$ticket['Name'] . '",';
                    //city
                    $rowData.='"'.$ticket['City'] . '",';
                    //email
                    $rowData.='"'.$ticket['Email'] . '",';
                    //mobile
                    $rowData.='"'.$ticket['Mobile'] . '",';
                    $rowData.='"'.$ticket['quantity'] . '",';
                    $rowData.='"'.$ticket['amountcurrency'] . '",';
                    $rowData.='"'.$ticket['ticketamount'] . '",';
                    if ($transactionType != 'incomplete') {
                        if ($bookingTikcetsCount == $loop) {
                            $transStatus = 'Captured';
                            $rowData.=$value['paidcurrency']. ',';
                            $rowData.=$value['paidamount'] . ',' . $value['discount'] . ',' . $value['barcodenumber']; //. ',';
                        }
                    } else {
                        $rowData.=$value['failedcount'] . ',"' . $value['comment'].'"'; // . ',';
                    }
                    if ($seatingLayout == 1) {
                        if ($bookingTikcetsCount == $loop) {
                            $seatVals = "\"" . $ticket['seats'] . "\"";
                            $rowData.= ',' . $seatVals;
                        } else {
                            $seatVals = "\"" . $ticket['seats'] . "\"";
                            // For empty paid,discount and barcode values
                            $rowData.= ',,,,' . $seatVals;
                        }
                    }
                    fwrite($handle, $rowData . PHP_EOL);
                    $rowData = '';
                    $loop++;
                }
            }
        } elseif ($reportType == 'detail') { 
            foreach ($transactionList as $transactionData) {
                foreach ($transactionData as $value) {
                    $rowData='';
                    $seatNum = $value['seats'];
                    // Seats for the EventSignup
                    foreach ($value['ticketDetails'] as $ticket) {
                        //Receipt No                     
                        if ($transactionType != 'incomplete') {
                            $rowData.='"'.$value['id'] . '",';
                            //array_push($rowArray, $value['id']);
                        }
                        //Signup Date
                        $rowData.='"'.$value['signupDate'] . '",';
                        //Ticket Type 
                        $rowData.= '"'.$ticket['tickettype'] . '",';

                        //Promoter Name
                        if ($transactionType != 'refund' && $transactionType != 'cancel') {
                            $rowData.= '"'.$value['promotername'] . '",';
                        }
                        if ($transactionType != 'cancel') {
                            //Promotion Code
                            $rowData.='"'.$value['discountcode'] . '",';
                        }
                        //quantity
                        $rowData.='"'.$ticket['quantity'] . '",';
                        //ticket amount currency 
                        $rowData.= '"'.$ticket['amountcurrency'] . '",';
                        //Amount
                        $rowData.= '"'.$ticket['ticketamount'] . '",';
                        //paid amount currency 
                         $rowData.=$value['paidcurrency'] . ',';
                        //Paid
                        $rowData.=$value['paidamount'] . ',';
                        if ($transactionType == 'cancel') {
                            $rowData.='"'.$value['comment'] . '","' . $value['barcodenumber'] . '",';
                        }
                        //Discount
//                        if ($transactionType != 'refund' && $transactionType != 'cancel') {
//                            $rowData.= $value['discount'] . ',';
//                        }
                        //Bulk Discount
//                        if ($transactionType != 'refund' && $transactionType != 'cancel') {
//                            $rowData.= $value['bulkdiscount'] . ',';
//                        }
                        //Discount,Bulk Discount,Referral Discount,barcodenumber
                        if ($transactionType != 'refund' && $transactionType != 'cancel') {
                            $rowData.= '"'.$value['discount'] . '",';
                            $rowData.= '"'.$value['bulkdiscount'] . '",';
                            $rowData.= '"'.$value['referraldiscount'] . '",';
                            $rowData.= '"'.$value['barcodenumber'] . '",';
                        }

                        //Taxes
                        if ($transactionType != 'refund' && $transactionType != 'cancel') {
                            foreach ($this->taxesHeader as $value) {
                                if (isset($ticket['taxesData'][$value])) {
                                    $rowData.='"' . $ticket['taxesData'][$value] . '",';
                                } else {
                                    $rowData.='0,';
                                }
                            }
                        }
//                        if ($transactionType == 'refund') {
//                            //Full Name
//                            $fullName = isset($ticket['customfields']['Full Name']) ? $ticket['customfields']['Full Name'] : '';
//                            $rowData.= $fullName . ',';
//                            //Email Id
//                            $email = isset($ticket['customfields']['Email Id']) ? $ticket['customfields']['Email Id'] : '';
//                            $rowData.= $email . ',';
//                            //Mobile Number
//                            $mobileNum = isset($ticket['customfields']['Mobile No']) ? $ticket['customfields']['Mobile No'] : '';
//                            $rowData.= $mobileNum . ',';
//                            //Address
//                            $address = isset($ticket['customfields']['Address']) ? $ticket['customfields']['Address'] : '';
//                            $rowData.= $address . ',';
//                            //State
//                            $state = isset($ticket['customfields']['State']) ? $ticket['customfields']['State'] : '';
//                            $rowData.= $state . ',';
//                            //City
//                            $city = isset($ticket['customfields']['City']) ? $ticket['customfields']['City'] : '';
//                            $rowData.= $city . ',';
//                            //Pin Code
//                            $pincode = isset($ticket['customfields']['Pin Code']) ? $ticket['customfields']['Pin Code'] : '';
//                            $rowData.= $pincode . ',';
//                            //Company
//                            $company = isset($ticket['customfields']['Company']) ? $ticket['customfields']['Company'] : '';
//                            $rowData.= $company . ',';
//                            //Designation
//                            $rowData.= $ticket['customfields']['Designation'] . ',';
//                            $designation = isset($ticket['customfields']['Designation']) ? $ticket['customfields']['Designation'] : '';
//                            $rowData.= $designation . ',';
//                        }else{
                        foreach ($this->customFields as $value) {
                            $rowData.=isset($ticket['customfields'][$value]) ? '"'.$ticket['customfields'][$value].'"' : '';
                            $rowData.=',';
                        }
                        //     $seatingLayout=1;
                    }
                    if ($seatingLayout == 1) {
                        $rowData.= $seatNum[0];
                    }
                    fwrite($handle, $rowData . PHP_EOL);
                    $rowData = '';
                    $loop++;
                }
            }
        }
    }

    public function getGrandTotal($input) {
        return $this->eventSignupHandler->getTransactionsTotal($input);
    }

    public function getSalesEffortData($input) {
        return $this->eventSignupHandler->getSalesEffortTotals($input);
    }

    public function getWeekwiseSales($input) {
        return $this->eventSignupHandler->getWeekwiseSales($input);
    }

    public function getFileUploadImages($input) {
        return $this->eventSignupHandler->downloadAllImages($input);
    }

    public function getReportDetails($input) {
        if (isset($input['reporttype']) && $input['reporttype'] == 'detail') {
            return $this->eventSignupHandler->getDetailDisplayInfo($input);
        }
        return $this->eventSignupHandler->getSummaryDisplayInfo($input);
    }

    public function getExportReports($input) {
        if (isset($input['reporttype']) && $input['reporttype'] == 'detail') {
            return $this->eventSignupHandler->getDetailExportInfo($input);
        }
        return $this->eventSignupHandler->getSummaryExportInfo($input);
    }

    public function checkFileUploadExists($inputArray) {
        $this->ci->form_validation->reset_form_rules();
        $this->ci->form_validation->pass_array($inputArray);
        $this->ci->form_validation->set_rules('eventid', 'eventid', 'is_natural_no_zero|required_strict');
        // $this->ci->form_validation->set_rules('reporttype', 'reporttype', 'required_strict');
        //  $this->ci->form_validation->set_rules('transactiontype', 'transactiontype', 'required_strict|is_valid_type[transaction]');
        //$this->ci->form_validation->set_rules('page', 'page', 'is_natural_no_zero|required_strict');
        if ($this->ci->form_validation->run() == FALSE) {
            $response = $this->ci->form_validation->get_errors();
            $output['status'] = FALSE;
            $output['response']['messages'] = $response['message'];
            $output['statusCode'] = STATUS_BAD_REQUEST;
            return $output;
        }
        $eventId = $inputArray['eventid'];
        $inputFile['eventid'] = $eventId;
        return $this->eventSignupHandler->getFileTypeCustomFieldData($inputFile);
    }

    public function getEventAttendeesDetails($inputArray) {
        $this->ci->form_validation->reset_form_rules();
        $this->ci->form_validation->pass_array($inputArray);
        $this->ci->form_validation->set_rules('eventId', 'eventid', 'is_natural_no_zero|required_strict');
        $this->ci->form_validation->set_rules('date', 'Date', 'mysqldateFotmate');
        if ($this->ci->form_validation->run() == FALSE) {
            $response = $this->ci->form_validation->get_errors();
            $output['status'] = FALSE;
            $output['response']['messages'] = $response['message'];
            $output['statusCode'] = STATUS_BAD_REQUEST;
            return $output;
        }
        $organizerDetails = $this->eventHandler->isOrganizerForEvent($inputArray);
        if (!$organizerDetails['status'] || $organizerDetails['response']['totalCount'] == 0) {
            $response["status"] = "failure";
            $response['statusCode'] = STATUS_OK;
            $response['response']["message"] = NO_EVENTS_SUCH_USER;
            return $response;
        }
        $apiArray = $gateWayInput = $paymentGatewayIndexed = $customfields = array();
        $apiArray['eventid'] = $inputArray['eventId'];
        $apiArray['page'] = '1';
        $apiArray['reporttype'] = isset($inputArray['reportType']) ? $inputArray['reportType'] : 'detail';
        $apiArray['transactiontype'] = isset($inputArray['transactionType']) ? $inputArray['transactionType'] : 'all';
        $validateTypesResponse = $this->validateTypes($apiArray);
        if (!$validateTypesResponse['status']) {
            return $validateTypesResponse;
        }
        if (isset($inputArray['date'])) {
            $apiArray['modifiedDate'] =  $inputArray['date'];
        }
        if(isset($inputArray['REPORTS_TRANSACTION_LIMIT'])){
            $apiArray['REPORTS_TRANSACTION_LIMIT']=$inputArray['REPORTS_TRANSACTION_LIMIT'];
        }
        if(isset($inputArray['orderStatus'])){
            $apiArray['orderStatus']=$inputArray['orderStatus'];
        }
        if(isset($inputArray['eventSignupId'])){
            $apiArray['eventSignupId']=$inputArray['eventSignupId'];
        }
        $validateTypesResponse = $this->getExportReports($apiArray);
        if ($validateTypesResponse['status'] == true && $validateTypesResponse['response']['total'] == 0) {
            $output['status'] = TRUE;
            $output['statusCode'] = STATUS_OK;
            $output['response']['total'] = 0;
            $output['response']['message'] = 'No attendees available';
            $output['response']['status'] = 'sucess';
            return $output;
        }
        $ticketDataIdIndexed = commonHelperGetIdArray($validateTypesResponse['response']['transactionList']);
        $gateWayData = $this->paymentgatewayHandler->getPaymentgatewayList();
        $paymentGatewayIndexed = commonHelperGetIdArray($gateWayData['response']['paymentgatewayList'], 'id');
        $paymentGateway = $gateWayData['response']['gatewayList']['0']['gatewayName'];
        $finalArray = $inputTicketId = $ticketTax = array();
        $count = 0;
        foreach ($validateTypesResponse['response']['transactionList'] as $keys => $value) {
           
            foreach ($value as $key => $val) {
                $ticketids = array();
                $finalArray[$count]['EventSIgnupId'] = $val['id'];
                $finalArray[$count]['eventId'] = $inputArray['eventId'];
                $finalArray[$count]['referralDAmount'] = $val['referraldiscountamount'];
                $finalArray[$count]['Signup Date'] = $val['signupDate'];
                if (isset($val['paymentgatewayid'])) {
                    $finalArray[$count]['PaymentGateway'] = $paymentGatewayIndexed[$val['paymentgatewayid']]['name'];
                } else {
                    $finalArray[$count]['PaymentGateway'] = '';
                }
                $finalArray[$count]['BarcodeNumber'] = $val['barcodenumber'];
                $finalArray[$count]['PaymentTransId'] = $val['paymenttransactionid'];
                $finalArray[$count]['UserName'] = '';
                $finalArray[$count]['attendeeId'] = $val['attendeeid'];
                $finalArray[$count]['currencyCode'] = $val['currencyCode'];
                $finalArray[$count]['Email'] = '';
                $finalArray[$count]['PaymentModeId'] = $val['paymentmodeid'];
                $finalArray[$count]['Company'] = '';
                $finalArray[$count]['Phone'] = '';
                $finalArray[$count]['Mobile'] = '';
                foreach ($val['ticketDetails'] as $t => $v) {
                    foreach ($val['ticketDetails'][$t]['taxesData'] as $c => $cv) {
                        $finalArray[$count][$c] = $cv;
                        $finalArray[$count]['Total Tax'] += $cv;
                    }
                    $finalArray[$count]['Fees'] = substr($val['paid'], strpos($val['paid'], ' '));
                    $finalArray[$count]['ticket_name'] = $val['ticketDetails'][$t]['tickettype'];
                    $finalArray[$count]['ticketPrice'] = substr($val['ticketDetails'][$t]['amount'], strpos($val['ticketDetails'][$t]['amount'], ' '));
                    $finalArray[$count]['TicketAmt'] = substr($val['ticketDetails'][$t]['amount'], strpos($val['ticketDetails'][$t]['amount'], ' '));
                    $finalArray[$count]['Amount'] = substr($val['ticketDetails'][$t]['amount'], strpos($val['ticketDetails'][$t]['amount'], ' '));
                    $finalArray[$count]['NumOfTickets'] = $val['quantity'];
                    $cc = 0;
                    foreach ($val['ticketDetails'][$t]['customfields'] as $c => $cv) {
                        if ($c == 'Full Name') {
                            $finalArray[$count]['UserName'] = $cv;
                        }
                        if ($c == 'Email Id') {
                            $finalArray[$count]['Email'] = $cv;
                        }
                        if ($c == 'Mobile No') {
                            if (isset($cv)) {
                                $finalArray[$count]['Mobile'] = $cv;
                            }
                        }
                        if ($c == 'Phone') {
                            $finalArray[$count]['Phone'] = $cv;
                        }
                        if ($c == 'Company Name') {
                            $finalArray[$count]['Company'] = $cv;
                        }
                        $finalArray[$count]['customfields'][$cc]['name'] = $c;
                        $finalArray[$count]['customfields'][$cc]['value'] = '';
                        if (isset($cv)) {
                            $finalArray[$count]['customfields'][$cc]['value'] = $cv;
                        }
                        $cc++;
                    }
                }
                $finalArray[$count]['cts'] = $val['cts'];
                $finalArray[$count]['mts'] = $val['mts'];
            }
             $count++;
        }
        $output['status'] = TRUE;
        $output['response'] = $finalArray;
        $output['statusCode'] = STATUS_OK;
        return $output;
    }
    
    public function getEventAttendeesSummary($inputArray) {
        $this->ci->form_validation->reset_form_rules();
        $this->ci->form_validation->pass_array($inputArray);
        $this->ci->form_validation->set_rules('eventId', 'eventid', 'is_natural_no_zero|required_strict');
        $this->ci->form_validation->set_rules('date', 'Date', 'mysqldateFotmate');
        if ($this->ci->form_validation->run() == FALSE) {
            $response = $this->ci->form_validation->get_errors();
            $output['status'] = FALSE;
            $output['response']['message'] = $response['message'];
            $output['response']['status'] = FALSE;
            $output['statusCode'] = STATUS_BAD_REQUEST;
            return $output;
        }
        $organizerDetails = $this->eventHandler->isOrganizerForEvent($inputArray);
        if (!$organizerDetails['status'] || $organizerDetails['response']['totalCount'] == 0) {
            $output["status"] = "failure";
            $output['statusCode'] = STATUS_OK;
            $output['response']['message'] = NO_EVENTS_SUCH_USER;
            $output['response']['status'] = FALSE;
            return $output;
        }
        $eventId = $inputArray['eventId'];
        $apiArray = $gateWayInput = $paymentGatewayIndexed = $customfields = array();
        $apiArray['eventid'] = $inputArray['eventId'];
        $apiArray['page'] = '1';
        $apiArray['reporttype'] = isset($inputArray['reportType']) ? $inputArray['reportType'] : 'detail';
        $apiArray['transactiontype'] = isset($inputArray['transactionType']) ? $inputArray['transactionType'] : 'all';
        if(isset($inputArray['REPORTS_TRANSACTION_LIMIT'])){
            $apiArray['REPORTS_TRANSACTION_LIMIT']=$inputArray['REPORTS_TRANSACTION_LIMIT'];
        } 
        if(isset($inputArray['orderStatus'])){
            $apiArray['orderStatus']=$inputArray['orderStatus'];
        }
        $validateTypesResponse = $this->validateTypes($apiArray);
        if (!$validateTypesResponse['status']) {
            return $validateTypesResponse;
        }
        if (isset($inputArray['date'])) {
            $apiArray['modifiedDate'] = $inputArray['date'];
        }
        if(isset($inputArray['eventSignupId'])){
            $apiArray['eventSignupId']=$inputArray['eventSignupId'];
        }
        $validateTypesResponse = $this->getExportReports($apiArray);
        if ($validateTypesResponse['status'] == true && $validateTypesResponse['response']['total'] == 0) {
            $output['status'] = TRUE;
            $output['statusCode'] = STATUS_OK;
            $output['response']['total'] = 0;
//            $output['response']['message'] = ERROR_NO_RECORDS;
            $output['response']['message'] = 'No attendees available';
            $output['response']['status'] = 'sucess';
            return $output;
        }
        $eventSignupIds = array();
        foreach ($validateTypesResponse['response']['transactionList'] as $r1 => $value1) {
            $eventSignupIds[] = $r1;
        }
        if (count($eventSignupIds) > 0) {
            $inputESTD['eventsignupids'] = $eventSignupIds;
            $inputESTD['transactiontype'] = 'all';
            $selectESTDResponse = $this->eventSignupTicketdetailHandler->getListByEventsignupIds($inputESTD);
        }
        if ($selectESTDResponse['status'] && $selectESTDResponse['response']['total'] > 0) {
            $inputAttendees['eventsignupids'] = $eventSignupIds;
            if ($ticketId > 0) {
                $inputAttendees['ticketids'] = array($ticketId);
            }
            $inputAttendees['primary'] = 1;
            $selectAttendeeResponse = $this->attendeeHandler->getListByEventsignupIds($inputAttendees);
        } else {
            $output['status'] = TRUE;
            $output['statusCode'] = STATUS_OK;
            $output['response']['total'] = 0;
//            $output['response']['message'] = ERROR_NO_RECORDS;
//            $output['response']['message'] = 'No attendees available';
            $output['response']['status'] = 'sucess';
            $output['response']['status'] = TRUE;
            return $output;
        }
        $this->eventsignupTaxHandler = new Eventsignuptax_handler();
        $this->taxMappingHandler = new Taxmapping_handler();
        $this->taxHandler = new Tax_handler();
        $inputESTaxes['eventsignupids'] = $eventSignupIds;
        $getTaxes = $this->eventsignupTaxHandler->getTaxes($inputESTaxes);
        if ($getTaxes['status'] && $getTaxes['response']['total'] > 0) {
            foreach ($getTaxes['response']['taxList'] as $value) {
                $taxMappingIds[] = $value['taxmappingid'];
            }
            if (count($taxMappingIds) > 0) {
                $inputTaxMapping['ids'] = $taxMappingIds;
                $taxMappingResponse = $this->taxMappingHandler->getTaxmapping($inputTaxMapping);
            }
            if ($taxMappingResponse['status'] && $taxMappingResponse['response']['taxMappingList']) {
                $indexedTaxMapping = commonHelperGetIdArray($taxMappingResponse['response']['taxMappingList']);
                $taxes = $this->taxHandler->getTaxList();
            } else {
                return $taxMappingResponse;
            }
            if ($taxes['status'] && $taxes['response']['total'] > 0) {
                $indexedTaxes = commonHelperGetIdArray($taxes['response']['taxList']);
            } else {
                return $taxes;
            }
            $responseTaxes = array();
            foreach ($getTaxes['response']['taxList'] as $value) {
                $taxLabelIndex = $indexedTaxMapping[$value['taxmappingid']]['taxid'];
                $responseTaxes[$value['eventsignupid']][$indexedTaxes[$taxLabelIndex]['label']] += $value['taxamount'];
            }
        }
        if ($selectAttendeeResponse['status'] && $selectAttendeeResponse['response']['total'] > 0) {
            foreach ($selectAttendeeResponse['response']['attendeeList'] as $value) {
                $attendeeIds[] = $value['id'];
            }
        } else {
            return $selectAttendeeResponse;
        }
        if (count($attendeeIds) > 0) {
            $inputCustomFieldData['attendeeids'] = $attendeeIds;
            $selectAllCustomFieldDataResponse = $this->attendeedetailHandler->getListByAttendeeIds($inputCustomFieldData);
        }
        if ($selectAllCustomFieldDataResponse['status'] && $selectAllCustomFieldDataResponse['response']['total'] > 0) {
            $inputCustomFields['eventId'] = $eventId;
            $inputCustomFields['allfields'] = 1;
            $inputCustomFields['activeCustomField'] = 1;
            $eventCustomFields = $this->configureHandler->getCustomFields($inputCustomFields);
        } else {
            return $selectAllCustomFieldDataResponse;
        }
        if ($eventCustomFields['status'] && $eventCustomFields['response']['total'] > 0) {
            $indexedCustomFieldNames = commonHelperGetIdArray($eventCustomFields['response']['customFields']);
            foreach ($selectAllCustomFieldDataResponse['response']['attendeedetailList'] as $value) {
                $IndexedCustomFieldDataArray[$value['attendeeid']][$indexedCustomFieldNames[$value['customfieldid']]['fieldname']] = $value['value'];
            }
            $responseCustomFields = array();
            $i = 0;
            $attendeesList = $selectAttendeeResponse['response']['attendeeList'];
            foreach ($attendeesList as $key => $value) {
                if ($attendeesList[$key]['eventsignupid'] != $attendeesList[$key + 1]['eventsignupid'] || ($attendeesList[$key]['eventsignupid'] == $attendeesList[$key + 1]['eventsignupid'] && $attendeesList[$key]['ticketid'] != $attendeesList[$key + 1]['ticketid'])) {
                    $i = 0;
                }
                foreach ($indexedCustomFieldNames as $customField) {
                    $responseCustomFields[$value['eventsignupid']][$value['ticketid']][$i][$customField['fieldname']] = $IndexedCustomFieldDataArray[$value['id']][$customField['fieldname']];
                }
                $i++;
            }
        } else {
            return $eventCustomFields;
        }
        $ticketDataIdIndexed = commonHelperGetIdArray($validateTypesResponse['response']['transactionList']);
        $gateWayData = $this->paymentgatewayHandler->getPaymentgatewayList();
        $paymentGatewayIndexed = commonHelperGetIdArray($gateWayData['response']['paymentgatewayList'], 'id');
        $finalArray = $inputTicketId = $ticketTax = array();
        $count = 0;
        foreach ($validateTypesResponse['response']['transactionList'] as $keys => $val) {
            
            $ticketids = array();
            $finalArray[$count]['EventSIgnupId'] = $val['id'];
            $finalArray[$count]['eventId'] = $inputArray['eventId'];
            $finalArray[$count]['referralDAmount'] = $val['referraldiscountamount'];
            $finalArray[$count]['Normal Discount'] = substr($val['discount'], strpos($val['discount'], ' '));
            $finalArray[$count]['Signup Date'] = $val['signupDate'];
            if (isset($val['paymentgatewayid'])) {
                $finalArray[$count]['PaymentGateway'] = $paymentGatewayIndexed[$val['paymentgatewayid']]['name'];
            } else {
                $finalArray[$count]['PaymentGateway'] = '';
            }
            $finalArray[$count]['BarcodeNumber'] = $val['barcodenumber'];
            $finalArray[$count]['PaymentTransId'] = $val['paymenttransactionid'];
            $finalArray[$count]['Total Tickets'] = $val['quantity'];
            $finalArray[$count]['currencyCode'] = $val['currencyCode'];
            $finalArray[$count]['TicketAmt'] = $val['totalamount'];
            foreach ($responseTaxes[$val['id']] as $label => $value) {
                $finalArray[$count][$label] = $value;
            }
            $finalArray[$count]['PaymentModeId'] = $val['paymentmodeid'];
            $finalArray[$count]['UserName'] = '';
            $finalArray[$count]['Email'] = '';
            $finalArray[$count]['Company'] = '';
            $finalArray[$count]['Phone'] = '';
            $finalArray[$count]['Mobile'] = '';
            $cc = 0;
            $finalArray[$count]['ticketTypeCount']=count($val['ticketDetails']);
            foreach ($val['ticketDetails'] as $t => $v) {
                $finalArray[$count]['ticketDetails'][$cc]['ticket_name'] = $val['ticketDetails'][$t]['tickettype'];
                $finalArray[$count]['ticketDetails'][$cc]['ticket_amount'] = substr($val['ticketDetails'][$t]['amount'], strpos($val['ticketDetails'][$t]['amount'], ' '));
                $finalArray[$count]['ticketDetails'][$cc]['NumOfTickets'] = $val['ticketDetails'][$t]['quantity'];
                if(strlen($finalArray[$count]['UserName'])==0)
                    $finalArray[$count]['UserName'] = $responseCustomFields[$val['id']][$t]['0']['Full Name'];
                 if(strlen($finalArray[$count]['Email'])==0)
                    $finalArray[$count]['Email'] = $responseCustomFields[$val['id']][$t]['0']['Email Id'];
                    if (isset($responseCustomFields[$val['id']][$t]['0']['Company Name'])) {
                        $finalArray[$count]['Company'] = $responseCustomFields[$val['id']][$t]['0']['Company Name'];
                    }
                    if (isset($responseCustomFields[$val['id']][$t]['0']['Phone'])) {
                        $finalArray[$count]['Phone'] = $responseCustomFields[$val['id']][$t]['0']['Phone'];
                    }
                    if (isset($responseCustomFields[$val['id']][$t]['0']['Mobile No'])) {
                        $finalArray[$count]['Mobile'] = $responseCustomFields[$val['id']][$t]['0']['Mobile No'];
                    }
                
                $cc++;
            }
             $finalArray[$count]['cts'] = $val['cts'];
             $finalArray[$count]['mts'] = $val['mts'];
            $count++;
        }
        $output['status'] = TRUE;
        $output['response'] = $finalArray;
        $output['statusCode'] = STATUS_OK;
        return $output;
    }
    }
