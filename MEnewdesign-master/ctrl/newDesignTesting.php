<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
$system_path = '../system';
$system_path = realpath($system_path).'/';
$application_folder = '../ctrl';
define('APPPATH', $application_folder.'/');
	
// The name of THIS file
	define('SELF', pathinfo(__FILE__, PATHINFO_BASENAME));

	// The PHP file extension
	// this global constant is deprecated.
	define('EXT', '.php');

	// Path to the system folder
	define('BASEPATH', str_replace("\\", "/", $system_path));

	// Path to the front controller (this file)
	define('FCPATH', str_replace(SELF, '', __FILE__));

	// Name of the "system folder"
	define('SYSDIR', trim(strrchr(trim(BASEPATH, '/'), '/'), '/'));
        
include_once '../application/config/config.php';
include_once  '../system/core/CodeIgniter.php';
include_once  '../system/core/Common.php';
include_once '../system/core/Model.php';
include_once '../application/models/city_model.php';
echo "m here";

$city_model = new City_model();

$selelctCountry['id'] = $city_model->id;
$whereCountry[$city_model->id] = 14;

$cityResponse = $city_model->get($selelctCountry, $whereCountry);

print_r($cityResponse);

class  newDesignTestingController{
    
}