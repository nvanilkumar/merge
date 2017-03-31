<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
require_once(APPPATH . 'libraries/REST_Controller.php');
require_once(APPPATH . 'handlers/bookmark_handler.php');

class Bookmark extends REST_Controller {

    public function __construct() {
        parent::__construct();
        $this->bookmarkHandler = new Bookmark_handler();
    }

    /*
     * Function to bookmark the event
     *
     * @access	public
     * @param	eventId - integer
     * @return	json response with status and message
     */

    public function index_post() {

        $this->loginCheck();

        $inputParams = $this->input->post();
        $saveBookmarkResponse = $this->bookmarkHandler->saveBookmark($inputParams);

        $resultArray = array('response' => $saveBookmarkResponse['response']);
        $statusCode = $saveBookmarkResponse['statusCode'];
        $this->response($resultArray, $statusCode);
    }

    /*
     * Function to remove the bookmark of an event
     *
     * @access	public
     * @param	eventId - integer
     * 			userId - integer
     * @return	json response with status and message
     */

    public function remove_get() {

        $this->loginCheck();

        $inputParams = $this->input->get();
        $removeBookmarkResponse = $this->bookmarkHandler->removeBookmark($inputParams);

        $resultArray = array('response' => $removeBookmarkResponse['response']);
        $statusCode = $removeBookmarkResponse['statusCode'];
        $this->response($resultArray, $statusCode);
    }

    /*
     * Function to get the bookmark of an event
     *
     * @access	public
     * @param	userId - integer
     * @return	json response with status and message
     */

    public function index_get() {

        $this->loginCheck();

        $inputParams = $this->input->post();
        $userBookmarkResponse = $this->bookmarkHandler->getUserBookmarks($inputParams);

        $resultArray = array('response' => $userBookmarkResponse['response']);
        $statusCode = $userBookmarkResponse['statusCode'];
        $this->response($resultArray, $statusCode);
    }

    /*
     * Function to check for logged in user
     *
     * @access	public
     * @return	json response with status and message
     */

    public function loginCheck() {

        $loginCheck = $this->customsession->loginCheck();
        if ($loginCheck != 1 && !$loginCheck['status']) {
            $output['status'] = FALSE;
            $output['response']['messages'] = $loginCheck['response']['messages'][0];
            $output['statusCode'] = STATUS_INVALID_SESSION;

            $resultArray = array('response' => $output['response']);
            $this->response($resultArray, $output['statusCode']);
        }
    }

}
