<?php

session_start();

include 'loginchk.php';

include_once("MT/cGlobali.php");
include_once 'includes/common_functions.php';
include_once("MT/cCountries.php");

$Global = new cGlobali();
$commonFunctions=new functions();
$MsgCountryExist = '';

if ($_POST['Submit'] == "Add") {    
    $inputArray = array();
    $inputArray['country_name'] = ucfirst(strtolower(trim($_POST['country_name'])));
    $inputArray['country_short_name'] = trim($_POST['country_short_name']);
	$inputArray['country_code'] = trim($_POST['country_code']);
    $inputArray['currency_id'] = $_POST['country_currency'];
    $inputArray['timezone_id'] = $_POST['country_timezone'];
    if(isset($_POST['country_featured']) &&  $_POST['country_featured'] == '1') {
        $inputArray['country_featured'] = 1;
    }
    else
         $inputArray['country_featured'] = 0;
    
    if(isset($_POST['country_default']) &&  $_POST['country_default'] == '1') {
        $inputArray['country_default'] = 1;
    }
    else
         $inputArray['country_default'] = 0;
    if(isset($_POST['country_order']) &&  $_POST['country_order'] != '0' &&  $_POST['country_order'] != '') {
        $inputArray['country_order'] = $_POST['country_order'];
    }
    else
         $inputArray['country_order'] = 0;
    if (isset($_FILES['logofile'])) {
        $flagId = $commonFunctions->fileUpload($Global, $_FILES['logofile'], array('png', 'jpg', 'jpeg', 'gif'), "/country",'countrythumb');
        if ($flagId !== false) {
            $inputArray['flagid'] = $flagId;
        }
        else
              $inputArray['flagid'] = 0;
    }
    else
        $inputArray['flagid'] = 0;
    $CountryQuery = "SELECT id FROM country WHERE name='" . $inputArray['country_name'] . "' AND deleted = 0";
    $CountryId = $Global->SelectQuery($CountryQuery);
    if (count($CountryId) > 0) {
        $MsgCountryExist = 'Country Name already exist!';
    } else {
        try {
            $Countries = new cCountries(0, $inputArray);

            if ($Countries->Save()) {
                header("location:editcountry.php");
            }
        } catch (Exception $Ex) {
            echo $Ex->getMessage();
        }
    }
}
/* timezone list */

$currencyList = $commonFunctions->getCurrencyList($Global);
$timezoneList = $commonFunctions->getTimezoneList($Global);

include 'templates/addcountry.tpl.php';
?>