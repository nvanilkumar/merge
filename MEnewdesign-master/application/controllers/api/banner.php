<?php

/**
 * Maintaing Banners related data
 *
 * @author     Lenin <lenin.komatipall@qison.com>
 * @copyright  2015-2005 The PHP Group
 * @version    CVS: $Id:$
 * @since      Banner available since Sprint 1
 * @deprecated File deprecated in Release 2.0.0
 */
/*
 * Place includes, constant defines and $_GLOBAL settings here.
 */

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
require(APPPATH . 'libraries/REST_Controller.php');
require(APPPATH . 'handlers/banner_handler.php');

class Banner extends REST_Controller {

    var $bannerHandler;

    public function __construct() {
        parent::__construct();
        $this->bannerHandler = new Banner_handler();
    }

    public function list_get() {
        $inputArray = $this->get();
        $bannerList = $this->bannerHandler->getBannerList($inputArray);
        $resultArray = array('response' => $bannerList['response']);
        $this->response($resultArray, $bannerList['statusCode']);
    }

}
