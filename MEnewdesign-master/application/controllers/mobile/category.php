<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
require_once(APPPATH . 'libraries/REST_Controller.php');
require_once(APPPATH . 'handlers/category_handler.php');

class Category extends REST_Controller {

    public function __construct() {
        parent::__construct();

        $this->categoryHandler = new Category_handler();
    }

    /*
     * Function to get the Category list
     *
     * @access	public
     * @param	string (major - 1 or 0)
     * @return	array
     */

    public function list_post() {
        $inputArray = $this->post();
        $categoryList = $this->categoryHandler->getCategoryList_mobile($inputArray);

        $resultArray = array('response' => $categoryList['response']);
        $statusCode = $categoryList['statusCode'];
        $this->response($resultArray, $statusCode);
    }

}
