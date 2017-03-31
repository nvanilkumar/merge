<?php

/**
 * Maintaing Country related data
 *
 * @author     Lenin <lenin.komatipall@qison.com>
 * @copyright  2015-2005 The PHP Group
 * @version    CVS: $Id:$
 * @since      File available since Sprint 1
 * @deprecated File deprecated in Release 2.0.0
 */
/*
 * Place includes, constant defines and $_GLOBAL settings here.
 */

if (!defined('BASEPATH'))
	exit('No direct script access allowed');
require (APPPATH . 'libraries/REST_Controller.php');
require (APPPATH . 'handlers/country_handler.php');

class Country extends REST_Controller {

	public function __construct() {
		parent::__construct();

		$this -> load -> helper('common_helper');
		$this -> countryHandler = new Country_handler();
	}

	/*
	 * Function to get the Country list
	 *
	 * @access	public
	 * @param	$inputArray contains
	 * 		string (major - 1 or 0)
	 * @return	array
	 */

	public function list_get() {
		$inputArray = $this -> get();
		$countryList = $this -> countryHandler -> getCountryList($inputArray);

		$resultArray = array('response' => $countryList['response']);
		$statusCode = $countryList['statusCode'];
		$this -> response($resultArray, $statusCode);
	}

	/*public function userLocationCountry_get() {
		$data = array();
		$data['userLocationCountry'] = 14;
		$resultArray = array('response' => $data);
		$this -> response($resultArray, 200);
	}*/

	//To search countries which matches the entered keyword
	public function search_get() {
		$inputKeyword = $this -> get();
		$mathedCountryList = $this -> countryHandler -> searchByKeyword($inputKeyword);
		$resultArray = array('response' => $mathedCountryList['response']);
		$statusCode = $mathedCountryList['statusCode'];
		$this -> response($resultArray, $statusCode);
	}
    public function details_get() {
        $statusCode=array();
		$inputArray = $this -> get();
		$details = $this -> countryHandler -> getCountryListById($inputArray);
		$resultArray = array('response' => $details['response']['detail']);
		$statusCode = $details['statusCode'];
		$this -> response($resultArray, $statusCode);
	}

}
