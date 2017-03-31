<?php

/**
 * Maintaing Tags related data
 *
 * @author    Qison  Dev Team
 * @copyright  2015-2005 The PHP Group
 * @version    CVS: $Id:$
 * @since      Tags available since Sprint 2
 * @deprecated File deprecated in Release 2.0.0
 */
/*
 * Place includes, constant defines and $_GLOBAL settings here.
 */

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
require(APPPATH . 'libraries/REST_Controller.php');
require(APPPATH . 'handlers/tag_handler.php');

class Tag extends REST_Controller {

    var $tagHandler;

    public function __construct() {
        parent::__construct();
        $this->tagHandler = new Tag_handler();
    }

    public function list_get() {
        $inputArray = $this->get();
        $tagList = $this->tagHandler->getTags($inputArray);
        //$this->response($tagList); 
         $resultArray = array('response' => $tagList['response']);
        $this->response($resultArray, $tagList['statusCode']);
    }
}
