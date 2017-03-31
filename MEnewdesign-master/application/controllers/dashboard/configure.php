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
 * @Created     24-06-2015
 * @Last Modified On  24-06-2015
 * @Last Modified By  Sridevi
 */
require_once(APPPATH . 'handlers/event_handler.php');
require_once(APPPATH . 'handlers/dashboard_handler.php');
require_once(APPPATH . 'handlers/discount_handler.php');
require_once(APPPATH . 'handlers/configure_handler.php');
require_once(APPPATH . 'handlers/gallery_handler.php');
require_once(APPPATH . 'handlers/eventpaymentgateway_handler.php');

class Configure extends CI_Controller {

    var $dashboardHandler;
    var $eventHandler;
    var $configure_handler;
    var $paymentGatewayHandler;
    
    public function __construct() {
        parent::__construct();
        $this->eventHandler = new Event_handler();
    }

    /*
     * Function to get the form for creating event
     *
     * @access	public
     * @return	Html that contains create event form
     */

    public function webhookUrl($eventId) {
        $inputArray['eventId'] = $eventId;
        $inputFormArray = $this->input->post('submit');
        if ($inputFormArray) {
            $inputArray['webhookUrl'] = $this->input->post('webhookUrl');
            $data['output'] = $this->eventHandler->updateWebhookUrl($inputArray);
        }
        $data['eventName'] = commonHelperGetEventName($eventId);
        $data['webhookUrl'] = "";
        $webhookUrlDetail = $this->eventHandler->getWebhookUrl($eventId);
        if ($webhookUrlDetail['status'] && $webhookUrlDetail['response']['total'] > 0 ) {
            $data['webhookUrl'] = $webhookUrlDetail['response']['webhookUrl'];
        }
        $data['hideLeftMenu'] = 0;
        $data['content'] = 'webhook_view';
        $data['pageName'] = 'Web hook Url';
        $data['pageTitle'] = 'MeraEvents | Web hook Url';
        $data['jsArray'] = array($this->config->item('js_public_path') . 'dashboard/dashboard_configure');
        $this->load->view('templates/dashboard_template', $data);
    }

    public function customFields($eventId) {
        $data = array();
        $data['hideLeftMenu'] = 0;
        $data['eventId'] = $eventId;
        $input['eventId'] = $eventId;
        $data['eventTitle'] = commonHelperGetEventName($eventId);
        $inputTickets['eventId'] = $eventId;
        $inputTickets['statuslabels'] = 1;
        $ticketResponse = $this->eventHandler->getEventTicketDetails($inputTickets);
        if ($ticketResponse['status'] && $ticketResponse['response']['total'] > 0) {
            $data['eventTickets'] = $ticketResponse['response']['ticketList'];
            //$data['indexedEventTickets'] = commonHelperGetIdArray($ticketResponse['response']['ticketList']);
        } else {
            $data['errors'][] = $ticketResponse['response']['messages'][0];
        }
        $inputCustomfields['eventId'] = $eventId;
        // $inputCustomfields['collectMultipleAttendeeInfo']=0;
        $inputCustomfields['allfields'] = 1;
        $inputCustomfields['statuslabels'] = 1;
        $this->configure_handler = new Configure_handler();
        $customfieldsResponse = $this->configure_handler->getCustomFields($inputCustomfields);
        if ($customfieldsResponse['status'] && $customfieldsResponse['response']['total'] > 0) {
            $data['customFieldData'] = $customfieldsResponse['response']['customFields'];
        } else {
            $data['errors'][] = $customfieldsResponse['response']['messages'][0];
        }
        $data['jsArray'] = array($this->config->item('js_public_path') . 'dashboard/customfieldslisting' );
        $data['content'] = 'custom_fields_view';
        $data['pageTitle'] = 'MeraEvents | Custom Fields';
        $this->load->view('templates/dashboard_template', $data);
    }

    public function seo($eventId) {
        $inputArray['eventId'] = $eventId;
        $data['eventTitle'] = commonHelperGetEventName($eventId);
        $seoDetails = $this->eventHandler->getSeoDetails($inputArray);
        if ($seoDetails['status'] == TRUE && $seoDetails['response']['total'] > 0) {
            $update = $this->input->post('submit');
            if ($update) {
                $inputArray['seotitle'] = $this->input->post("seotitle");
                $inputArray['seokeywords'] = $this->input->post("seokeywords");
                $inputArray['seodescription'] = $this->input->post("seodescription");
                $inputArray['conanicalurl'] = $this->input->post("conanicalurl");
                $updateSeoDetails = $this->eventHandler->updateSeoDetails($inputArray);
                $data['seo'] =$updateSeoDetails;
                $data['message'] = $updateSeoDetails['response']['messages']['0'];
                $seoDetails = $this->eventHandler->getSeoDetails($inputArray);
            }
            $data['seoDetails'] = $seoDetails['response']['seodetails']['0'];
            $data['hideLeftMenu'] = 0;
            $data['content'] = 'seo_view';
            $data['pageName'] = 'Seo';
            $data['pageTitle'] = 'MeraEvents | Seo';
             $data['jsArray'] = array($this->config->item('js_public_path') . 'dashboard/dashboard_configure' );
            $this->load->view('templates/dashboard_template', $data);
        } else {
            $redirectUrl = commonHelperGetPageUrl('dashboard-myevent');
            redirect($redirectUrl);
        }
    }

    public function ticketOptions($eventId) {
        $inputArray['eventId'] = $eventId;
        $data = array();
        $data['eventTitle'] = commonHelperGetEventName($eventId);
        $ticketOptions = $this->eventHandler->getTicketOptions($inputArray);
        if ($ticketOptions['status'] == TRUE && $ticketOptions['response']['total'] > 0) {
            $update = $this->input->post('submit');
            $postVars = $this->input->post();
            if ($update) {
                if ($postVars['collectmultipleattendeeinfo'] == 1) {
                    $inputArray['collectmultipleattendeeinfo'] = 1;
                } else {
                    $inputArray['collectmultipleattendeeinfo'] = 0;
                }
                if (isset($postVars['displayamountonticket'])) {
                    $inputArray['displayamountonticket'] = 1;
                } else {
                    $inputArray['displayamountonticket'] = 0;
                }
                $inputArray['nonormalwhenbulk'] = 0;
                if (isset($postVars['nonormalwhenbulk'])) {
                    $inputArray['nonormalwhenbulk'] = 1;
                }
//                if (isset($postVars['sendubermails'])) {
//                    $inputArray['sendubermails'] = 1;
//                } else {
//                    $inputArray['sendubermails'] = 0;
//                }
                if (isset($postVars['limitsingletickettype'])) {
                    $inputArray['limitsingletickettype'] = 1;
                } else {
                    $inputArray['limitsingletickettype'] = 0;
                }
                $updateeventSettings = $this->eventHandler->updateTicketOptions($inputArray);
                $data['ticketSettings']=$updateeventSettings;
                $data['message'] = $updateeventSettings['response']['messages']['0'];
                $ticketOptions = $this->eventHandler->getTicketOptions($inputArray);
            }
            $data['ticketOptions'] = $ticketOptions['response']['ticketingOptions'];
            $data['content'] = 'ticket_options_view';
            $data['pageName'] = 'Ticket Options';
            $data['pageTitle'] = 'MeraEvents | Ticket Options';
            $data['hideLeftMenu'] = 0;
            $this->load->view('templates/dashboard_template', $data);
        } else {
            $redirectUrl = commonHelperGetPageUrl('dashboard-myevent');
            redirect($redirectUrl);
            $data['ticketOptionsMessage'] = $ticketOptions['response']['messages'][0];
        }
    }

    /**
     * To Manage the custom fields add & update
     */
    public function manageCustomFields($eventId, $ticketId = 0, $customfieldId = 0) {
        $data = array();
        $this->configure_handler = new Configure_handler();
        $input['eventId'] = $eventId;
        $data['eventTitle'] = commonHelperGetEventName($eventId);
        if ($this->input->post('submit')) {
            $inputArray = $this->input->post();
            $inputArray['ticketId'] = $ticketId;
            $inputArray['customFieldId'] = $customfieldId;
            $saveOrUpdateResponse = $this->configure_handler->manageCustomField($inputArray);
            if ($saveOrUpdateResponse['status'] && $saveOrUpdateResponse['response']['total'] > 0) {
                 redirect(commonHelperGetPageUrl('dashboard-customField', $eventId));
            } else {
                $data['message'] = $saveOrUpdateResponse['response']['messages'][0];
            }
        }
        // $customFieldId = $this->input->get("customFieldId");
        //var_dump($customFieldId);
        $data['customFieldData'] = $data['customFieldValues'] = array();
        if ($customfieldId > 0) {
            $inputArray["eventId"] = $eventId;
            $inputArray["customFieldId"] = $customfieldId;
            $customFiledResponse = $this->configure_handler->getCustomFields($inputArray);
            if ($customFiledResponse['status'] && $customFiledResponse["response"]["total"] > 0) {
                $data['customFieldData'] = $customFiledResponse["response"]["customFields"][0];
            }
            $customFieldValues = $this->configure_handler->getCustomFieldValues($inputArray);
            if ($customFieldValues['status'] && $customFieldValues["response"]['total'] > 0) {
                $data['customFieldValues'] = $customFieldValues["response"]["fieldValuesInArray"];
            }
        }
        $data['eventId'] = $eventId;
        $data['pageTitle'] = 'MeraEvents | Add New Event Custom Field';
        $data['content'] = 'manage_custom_fields_view';
        $data['hideLeftMenu'] = 0;
        $data['jsArray'] = array(
            $this->config->item('js_public_path') . 'dashboard/customfields',
            $this->config->item('js_public_path') . 'dashboard/dashboard_configure');
        $this->load->view('templates/dashboard_template', $data);
    }

    public function ticketWidget($eventId) {
        $inputArray['eventId'] = $eventId;
        $data['eventName'] = commonHelperGetEventName($eventId);
        $data['content'] = 'ticket_widget_view';
        $data['pageName'] = 'Ticket Widget';
        $data['pageTitle'] = 'MeraEvents | Ticket Widget';
        $data['eventId'] = $eventId;
        $data['hideLeftMenu'] = 0;
        $data['jsArray'] = array($this->config->item('js_public_path') . 'dashboard/jscolor',
            $this->config->item('js_public_path') . 'dashboard/dashboard_configure');
        $this->load->view('templates/dashboard_template', $data);
    }
    
    public function gallery($eventId,$imageFileId='') {       
        $this->galleryHandler = new Gallery_handler();        
        $eventId = $this->input->get('eventId');
        //Deleting the image
        if($imageFileId){
            $data['deleteGallery'] = $this->galleryHandler->deleteGallery($imageFileId);            
        }      
        $data['eventName'] = commonHelperGetEventName($eventId);
        $data['pageName'] = 'Event Dashboard';
        $data['pageTitle'] = 'MeraEvents | Gallery';
        $data['hideLeftMenu'] = 0;
        $data['content'] = 'gallery_view';
        $data['eventId'] = $eventId;
        $addGallery = $this->input->post('gallerySubmit');
        if ($addGallery) {
            $inputArray['type'] = 'eventgallery';
            $inputArray['eventId'] = $eventId;
            $data['insertedGallery'] = $this->galleryHandler->insertGallery($inputArray);            
        }      
        $request['eventId']=$eventId;
        $data['galleryImages'] =$this->galleryHandler->getEventGalleryList($request);   
        $data['jsArray'] = array($this->config->item('js_public_path') . 'dashboard/dashboard_configure');
        $this->load->view('templates/dashboard_template', $data);
    }

    public function addTermsAndCondition($eventId) {
        $inputArray['eventId'] = $eventId;
        $this->dashboardHandler = new Dashboard_handler();
        $update = $this->input->post('tncSubmit');
        if ($update) {            
            if(empty(strip_tags(str_replace('&nbsp;','', $this->input->post("tncDescription"))))){
                $inputArray['tncDescription']='';
            }else{
                $inputArray['tncDescription'] = $this->input->post("tncDescription"); 
            } 
            $data['updateTnc'] = $this->dashboardHandler->updateTncDetails($inputArray);
        }
        $data['eventName'] = commonHelperGetEventName($eventId);
        $tncDetail = $this->dashboardHandler->getTncDetail($inputArray);
        if ($tncDetail['status'] && $tncDetail['response']['total'] > 0) {
            $data['organizertnc'] = $tncDetail['response']['eventData']['organizertnc'];
        } else {
            $data['organizertnc'] = "";
        }
        $data['hideLeftMenu'] = 0;
        $data['content'] = 'terms_condition_view';
        $data['pageName'] = 'Terms And Conditions';
        $data['pageTitle'] = 'MeraEvents | Add Terms And Conditions';
        $data['jsArray'] = array(
            $this->config->item('js_public_path') . 'tinymce/tinymce',
            $this->config->item('js_public_path') . 'customTmc',
            $this->config->item('js_public_path') . 'dashboard/dashboard_configure');
        $this->load->view('templates/dashboard_template', $data);
    }

    public function emailAttendees($eventId) {
       
        $this->dashboardHandler = new Dashboard_handler();
        $emailAttendeesSendMail = $this->input->post('emailAttendeesSendMail');
        $emailAttendeesSendTestMail = $this->input->post('emailAttendeesSendTestMail');
        if ($emailAttendeesSendMail || $emailAttendeesSendTestMail) {
            $inputArray=$this->input->post(); //Appending input fields values along wiht eventid and userid written above
            $inputArray['eventId'] = $eventId;
            $inputArray['userIds'] = getUserId();
            $response = $this->dashboardHandler->sendEmailToAttendees($inputArray);
            if($response['status']){
                $this->customsession->setData('emailAttendeeSuccessMessage', $response['response']['messages'][0]);
            }else{
               $this->customsession->setData('emailAttendeeErrorMessage', $response['response']['messages'][0]);
            }
            $redirectUrl = commonHelperGetPageUrl('dashboard-emailAttendees', $eventId);
            redirect($redirectUrl);
        }
        $data['eventName'] = commonHelperGetEventName($eventId);
        //Getting the user and ticket details to load in view
        $userInputArray['eventId'] = $eventId;
        $userInputArray['userIds'] = getUserId();
        $userAndTicketDetail = $this->dashboardHandler->getUserAndTicketDetail($userInputArray);
        $data['userName'] = $userAndTicketDetail['userName'];
        $data['email'] = $userAndTicketDetail['email'];
        $data['ticketDetails'] = $userAndTicketDetail['ticketDetails'];

        $data['hideLeftMenu'] = 0;
        $data['content'] = 'email_attendees_view';
        $data['pageName'] = 'Email Attendees';
        $data['pageTitle'] = 'Email Attendees';
        $data['jsArray'] = array(
            $this->config->item('js_public_path') . 'dashboard/dashboard_configure',
            $this->config->item('js_public_path') . 'tinymce/tinymce',
            $this->config->item('js_public_path') . 'emailAttendeeTinyMce');
        $this->load->view('templates/dashboard_template', $data);
    }
    
    public function paymentMode($eventId) {
        $inputArray['eventId'] = $eventId;
        $this->dashboardHandler = new Dashboard_handler(); 
        $this->paymentGatewayHandler = new EventpaymentGateway_handler();
        $data['eventName'] = commonHelperGetEventName($eventId);        
        $update = $this->input->post('paymentSubmit');
        if ($update) {
            $inputArray+=$this->input->post();
            $data['paymentGatewayOutput'] = $this->paymentGatewayHandler->updateEventPaymentGatewayDetail($inputArray);
        }
        //Storing event gateway Ids for this event 
        $data['eventPaymentGateways']=$eventPaymentGateways = $this->eventHandler->getEventPaymentGateways($inputArray);        
        $data['hideLeftMenu'] = 0;
        $data['content'] = 'payment_mode_view';
        $data['pageName'] = 'Payment Mode';
        $data['pageTitle'] = 'MeraEvents | Payment Mode';
        $data['jsArray'] = array(
            $this->config->item('js_public_path') . 'dashboard/dashboard_configure');
        $this->load->view('templates/dashboard_template', $data);
    }
    
      public function contactInfo($eventId) {
        $inputArray['eventId'] = $eventId;
        $data['eventName'] = commonHelperGetEventName($eventId);
        $contactDetails = $this->eventHandler->getcontactInfo($inputArray);
        if($contactDetails['status'] == FALSE || $contactDetails['response']['total'] == 0){
            $data['error'] = $contactDetails['response']['messages'][0];
        }else{
            $update = $this->input->post();
            if (isset($update) && $update['submit'] == Update ) {
                $update['eventId'] = $eventId;
                    if ($update) {
                         $updateContactDetails = $this->eventHandler->updateContactInfo($update);
                         $data['updateContactDetails']=$updateContactDetails;
                         $data['message'] = $updateContactDetails['response']['messages'][0];
                         $contactDetails = $this->eventHandler->getcontactInfo($inputArray);
                         $data['contactDetails'] = $contactDetails['response']['contactDetails'];
                    }
            }else{
                $data['contactDetails'] = $contactDetails['response']['contactDetails'];
            }
        }
        $data['content'] = 'contactinfo_view';
        $data['pageName'] = 'Contact Information';
        $data['pageTitle'] = 'MeraEvents | Contact Information';
        $data['eventId'] = $eventId;
        $data['hideLeftMenu'] = 0;
        $data['jsArray'] = array($this->config->item('js_public_path') . 'dashboard/jscolor',
                                 $this->config->item('js_public_path') . 'dashboard/dashboard_configure');
        $this->load->view('templates/dashboard_template', $data);
    }
    
    public function deleteRequest($eventId) {
        $inputArray['eventId'] = $eventId;
        $inputFormArray = $this->input->post('deleteSubmit');
        if ($inputFormArray) {
            $inputArray['comments']=$this->input->post('deleteComment');
            $data['output'] = $this->eventHandler->deleteRequest($inputArray);
        }
        $data['eventName'] = commonHelperGetEventName($eventId);
        $data['eventId'] = $eventId;
        
        
        $data['hideLeftMenu'] = 0;
        $data['content'] = 'deleterequest_view';
        $data['pageName'] = 'Request for delete';
        $data['pageTitle'] = 'MeraEvents | Request for delete';   
        $data['jsArray'] = array($this->config->item('js_public_path') . 'dashboard/deleterequest');
        $this->load->view('templates/dashboard_template', $data);
    }    
}

?>