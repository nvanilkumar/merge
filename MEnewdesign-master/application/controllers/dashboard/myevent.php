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
 * @Created     31-07-2015
 * @Last Modified On  31-07-2015
 * @Last Modified By  Sridevi
 */
require_once(APPPATH . 'handlers/dashboard_handler.php');

class Myevent extends CI_Controller {

    var $dashboardHandler;

    public function __construct() {
        parent::__construct();
        $this->dashboardHandler = new Dashboard_handler();
    }

    public function upComingEventList() {
        $getVar = $this->input->get();
        $post = $this->input->post();
        $keyword = $this->input->post('keyword');
        $searchword = isset($keyword)?$keyword:'';
        $eventList = $this->dashboardHandler->getUserUpcomingEvent($post);
        $data['eventList'] = array();
        if ($eventList['status'] == TRUE) {
            $data['eventList'] = $eventList['response']['eventList'];
            $data['collaborativeEventData'] = $eventList['response']['collaborativeEventData'];
            $data['totalEventCount'] = $eventList['response']['totalcount'];
        }
        //$data['messageType'] = $this->session->flashdata('message');
        //$data['messageType'] = (isset($getVar['message'])) ? $getVar['message'] : '';
        $data['pageName'] = 'My Events';
        $data['pageTitle'] = 'MeraEvents | Organizer View | Current Events';
        $data['hideLeftMenu'] = 1;
        $data['content'] = 'myevent_view';
        $data['pageType'] = "upcoming";
        $data['page'] = 1;
        $data['searchword']= $searchword;
        $data['jsArray'] = array($this->config->item('js_public_path') . 'dashboard/fs-match-height',
                                 $this->config->item('js_public_path') . 'dashboard/current_past_events');
//        print_r($data);
//        exit;
        $this->load->view('templates/dashboard_template', $data);
    }

    public function pastEventList() {
    	$post = $this->input->post();
    	$keyword = $this->input->post('keyword');
    	$searchword = isset($keyword)?$keyword:'';
        $eventList = $this->dashboardHandler->getUserPastEvent($post);
        $data['eventList'] = array();
        if ($eventList['status'] == TRUE) {
            $data['eventList'] = $eventList['response']['eventList'];
            $data['collaborativeEventData'] = $eventList['response']['collaborativeEventData'];
            $data['totalEventCount'] = $eventList['response']['totalcount'];
        }
        $data['pageName'] = 'My Events';
        $data['pageTitle'] = 'MeraEvents | Organizer View | Past Events';
        $data['hideLeftMenu'] = 1;
        $data['content'] = 'myevent_view';
        $data['pageType'] = "past";
        $data['page'] = 1;
        $data['searchword']= $searchword;
        $data['jsArray'] = array($this->config->item('js_public_path') . 'dashboard/fs-match-height',
        		$this->config->item('js_public_path') . 'dashboard/current_past_events');
        $this->load->view('templates/dashboard_template', $data);
    }

}
