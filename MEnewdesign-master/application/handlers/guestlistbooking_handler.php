<?php

/**
 *
 * @package		CodeIgniter
 * @author		Qison  Dev Team
 * @copyright	Copyright (c) 2015, MeraEvents.
 * @Version		Version 1.0
 * @Since       Class available since Release Version 1.0 
 * @Created     11-06-2015
 * @Last Modified 11-06-2015
 */
require_once(APPPATH . 'handlers/handler.php');
require_once(APPPATH . 'handlers/user_handler.php');
require_once(APPPATH . 'handlers/booking_handler.php');
require_once (APPPATH . 'handlers/email_handler.php');
require_once (APPPATH . 'handlers/verificationtoken_handler.php');
require_once (APPPATH . 'handlers/file_handler.php');
require_once(APPPATH . 'handlers/messagetemplate_handler.php');

class Guestlistbooking_handler extends Handler {

    var $ci;
    var $bookingHandler;
    var $emailHandler;
    var $verificationtokenHandler;
    var $fileHandler;

    public function __construct() {
        parent::__construct();
        $this->ci = parent::$CI;
        $this->userHandler = new User_handler();
        $this->bookingHandler = new Booking_handler();
        $this->emailHandler = new Email_handler();
        $this->verificationtokenHandler = new Verificationtoken_handler();
        $this->fileHandler = new File_handler();
    }

    public function guestListBooking($inputArray) {
        $eventId = $inputArray['eventId'];
        $uploadFileArray = array();
        $details = $this->fileHandler->uploadGuestListFile($eventId);
        if ($details['status'] == TRUE && $details['response']['total'] == 0) {
            $output['status'] = TRUE;
            $output['response']['messages'][] = ERROR_NO_GUEST_DATA;
            $output['statusCode'] = STATUS_INVALID_INPUTS;
            $output['response']['total'] = 0;
            return $output;
        }
        /* error for not csv format */
         if ($details['status'] == FALSE) {
             $output['status'] = false;
             $output['response']['messages'] =  $details['response']['messages'];
             return $output;
        }
        $guestListData = $user = $userDetals = $finalGuestData = array();
        $guestData = $details['response']['guestUserData'];
        $totalBookingQuantity = 0;
        /* getting first 20 records from upload file */
        $finalGuestData = array_slice($guestData, 0, 20);
        $invalidData = array();
        foreach ($finalGuestData as $key => $value) {
            if (strlen($value['Email Id']) > 0 && strlen($value['Name']) > 0 && strlen($value['Mobile Number']) > 0 && strlen($value['Quantity']) > 0) {
                $user['email'] = $value['Email Id'];
                $user['name'] = $value['Name'];
                $user['mobile'] = $value['Mobile Number'];
                $user['quantity'] = $value['Quantity'];
                $this->ci->form_validation->reset_form_rules();
                $this->ci->form_validation->pass_array($user);
                $this->ci->form_validation->set_rules('email', 'Email', 'required_strict|valid_email');               
                $this->ci->form_validation->set_rules('name', 'Name', 'required_strict');
                $this->ci->form_validation->set_rules('mobile', 'Mobile', 'required_strict|numeric|min_length[10]|max_length[10]');
                $this->ci->form_validation->set_rules('quantity', 'Quantity', 'required_strict|is_natural_no_zero');
                if ($this->ci->form_validation->run() == TRUE) {
                    /* check wether user existed or not */
                    $isUserExist = $this->userHandler->getUserDetails($user);
                    if ($isUserExist['response']['total'] == 0) {
                        /* create new user */
                        $userDetals['email'] = $value['Email Id'];
                        $userDetals['name'] = $value['Name'];
                        $userDetals['mobile'] = $value['Mobile Number'];
                        $addUser = $this->userHandler->add($userDetals);
                        if ($addUser['status'] == TRUE) { 
                            $type = 'password';
                            $tokenType = 'alnum';
                            $userId = $addUser['response']['userId'];
                            $generateToken = $this->verificationtokenHandler->create($userId, $type, $tokenType, $expirayDate);
                            if ($generateToken['status'] == FALSE) {
                                $output['status'] = FALSE;
                                $output["response"]['messages'][] = ERROR_SOMETHING_WENT_WRONG;
                                $output['response']['total'] = 0;
                                $output['statusCode'] = STATUS_SERVER_ERROR;
                                return $output;
                            }
                            $token = $generateToken['response']['token'];
                            $this->ci->load->library('parser');
                            $guestTemplete['type'] = TYPE_GUEST_BOOKING;
                            $guestTemplete['mode'] = 'email';
                            $this->messagetemplateHandler = new Messagetemplate_handler();
                            $guestUser = $this->messagetemplateHandler->getTemplateDetail($guestTemplete);
                            $templateId = $guestUser['response']['templateDetail']['id'];
                            $from = $guestUser['response']['templateDetail']['fromemailid'];
                            $to = $userDetals['email'];
                            $templateMessage = $guestUser['response']['templateDetail']['template'];
                            $subject = 'Account details for ' . $userDetals['name'] . ' as Delegate at MeraEvents.com';
                            $data['userName'] = ucfirst($userDetals['name']);
                            $data['email'] = $userDetals['email'];
                            $data['link'] = commonHelperGetPageUrl('user-changePassword',$token);
                            $data['year'] = allTimeFormats(' ',17);
                            $data['supportLink'] = commonHelperGetPageUrl('contactUs');
                            $message = $this->ci->parser->parse_string($templateMessage, $data, TRUE);
                            $sentmessageInputs['messageid'] = $templateId;
                            $emailResponse = $this->emailHandler->sendEmail($from, $to, $subject, $message, '', '', '', $sentmessageInputs);
                        }
                    } else {
                        /* existed user */
                        $userId = $isUserExist['response']['userData'][0]['id'];
                    }
                    $inputArray['userId'] = $userId;
                    $inputArray['name'] = $value['Name'];
                    $inputArray['email'] = $value['Email Id'];
                    $inputArray['mobile'] = $value['Mobile Number'];
                    $inputArray['quantity'] = $value['Quantity'];
                    $totalBookingQuantity += $inputArray['quantity'];
                    $booking = $this->bookingHandler->guestBooking($inputArray);
                } else { 
                    $invalidData[] = $user['email'];
                    $booking['status'] == false;
                }
            }
        }
        $errorEmails = implode(',', $invalidData);
        if ($booking['status'] == TRUE) {
            if (count($invalidData) > 0) {
                $output['status'] = true;
                $output['response']['messages'][] = "Successfully booked the tickets. Total <b>" . $totalBookingQuantity . "</b> transactions imported successfully. $errorEmails  records are not insterted";
            } else {
                $output['status'] = true;
                $output['response']['messages'][] = "Successfully booked the tickets. Total <b>" . $totalBookingQuantity . "</b> transactions imported successfully.";
            }
        } else {
            $output['status'] = false;
            $output['response']['messages'][] =  $errorEmails  ." records are not inserted";
        }
        return $output;
    }

}
