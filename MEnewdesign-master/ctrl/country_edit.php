<?php

session_start();
//include 'loginchk.php';
include_once("MT/cGlobali.php");
include_once 'includes/common_functions.php';
include_once("MT/cCountries.php");

$Global = new cGlobali();
$commonFunctions = new functions();

if ($_POST['Submit'] == "Update") {
    $inputArray = array();
    $inputArray['country_name'] = trim($_POST['country_name']);
    $inputArray['country_short_name'] = trim($_POST['country_short_name']);
	 $inputArray['country_code'] = trim($_POST['country_code']);
    $inputArray['currency_id'] = $_POST['country_currency'];
    $inputArray['timezone_id'] = $_POST['country_timezone'];
    $inputArray['country_status'] = $_POST['country_status'];
    if (isset($_POST['country_featured']) && $_POST['country_featured'] == '1') {
        $inputArray['country_featured'] = $_POST['country_featured'];
    } else
        $inputArray['country_featured'] = 0;
    if (isset($_POST['country_default']) && $_POST['country_default'] == '1') {
        $inputArray['country_default'] = $_POST['country_default'];
    } else
        $inputArray['country_default'] = 0;
    if (isset($_POST['country_order']) && $_POST['country_order'] != '0' && $_POST['country_order'] != '') {
        $inputArray['country_order'] = $_POST['country_order'];
    } else
        $inputArray['country_order'] = 0;

    //$country_name = trim($country_name);
    //$country_name = strtolower($country_name);
    //$country_name = ucfirst($country_name);

    $Id = $_POST['country_id'];

    if ((isset($_POST['country_status'])) && $_POST['country_status'] == 0) {
        
        $defQuery = "SELECT `default`  FROM country WHERE id = '" . $Id . "'";
        $defRelust = $Global->SelectQuery($defQuery);
        if ($defRelust[0]['default'] == 1) {
            $error_message = "You are trying to make default country as inactive, make some other country as default and make this inactive ";
			 $EditCountry = get_contry($Global, $Id);
        $currencyList = $commonFunctions->getCurrencyList($Global);
        $timezoneList = $commonFunctions->getTimezoneList($Global);
        $CountryId = $Id;
        include 'templates/country_edit.tpl.php';
        exit;
           
        }else{
       $defEQuery = "UPDATE country set `status` = ".$_POST['country_status']." WHERE id = " . $Id ;
       $defRelust = $Global->ExecuteQuery($defEQuery);
        $CountryId=$Id;
        $EditCountry = get_contry($Global, $Id);
        $currencyList = $commonFunctions->getCurrencyList($Global);
        $timezoneList = $commonFunctions->getTimezoneList($Global);
        if ($defRelust > 0) {
        header("location:editcountry.php");
          }
		}
        //exit;
    }

    if (!check_country_value($Global, $inputArray, $Id)) {
        // UPDATE country master WITH UPDATED VALUE
        if (isset($_FILES['flagfile']) && !empty($_FILES['flagfile']) && $_FILES['flagfile']['error'] == 0) {
            $flagId = $commonFunctions->fileUpload($Global, $_FILES['flagfile'], array('png', 'jpg', 'jpeg', 'gif'), "/country",'countrythumb');
            if ($flagId !== false) {
                $inputArray['flagid'] = $flagId;
            } else
                $inputArray['flagid'] = 0;
        } elseif(isset($_POST['fileId'])){
             $inputArray['flagid'] =$_POST['fileId'];
        }else
            $inputArray['flagid'] = 0;
        $UpdateCountry = new cCountries($Id, $inputArray);
        $Id = $UpdateCountry->Save();
        if ((isset($inputArray['country_default'])) && $inputArray['country_default'] == 1) {
            $defEQuery = "UPDATE country set `default` = 0 WHERE id != '" . $Id . "'";
            $defRelust = $Global->ExecuteQuery($defEQuery);
        }
    } else {////Duplicate value has updated
        $error_message = "You are trying to inserting the duplicate value";
        $EditCountry = get_contry($Global, $Id);
        $currencyList = $commonFunctions->getCurrencyList($Global);
        $timezoneList = $commonFunctions->getTimezoneList($Global);
        $CountryId = $Id;
        include 'templates/country_edit.tpl.php';
        exit;
    }



    if (isset($_GET["type"])) {
        header("location: auto_suggest_review.php?&update_record=" . $_GET["type"]);
        exit;
    }
    if ($Id > 0) {
        header("location:editcountry.php");
    }
}

$CountryId = $_GET['id'];
$EditCountry = get_contry($Global, $CountryId);
$currencyList = $commonFunctions->getCurrencyList($Global);
$timezoneList = $commonFunctions->getTimezoneList($Global);

function get_contry($Global, $CountryId) {
    $CountryQuery = "SELECT *  FROM country WHERE id = '" . $CountryId . "'";
    $countryRelust = $Global->SelectQuery($CountryQuery);
    if ($countryRelust[0]['logofileid'] != "" && !empty($countryRelust[0]['logofileid'])) {
        $flagImageQuery = "SELECT *  FROM file WHERE id = '" . $countryRelust[0]['logofileid'] . "'";
        $flagRelust = $Global->SelectQuery($flagImageQuery);
        $countryRelust[0]['logofile'] = $flagRelust[0]['path'];
//        unset($countryRelust[0]['logofileid']);
    }
    return $countryRelust;
}

function check_country_value($global, $inputArray, $ignore_id) {
   $query = "SELECT * FROM country "
            . " where name= '" . $inputArray['country_name'] . "' and id!=" . $ignore_id . " and deleted=0 and shortname='".$inputArray['country_short_name']."' and code='".$inputArray['country_code']."' and status=".$inputArray['country_status']." and featured=".$inputArray['country_featured']." and `default`=". $inputArray['country_default']." and timezoneid=".$inputArray['timezone_id'];
    $record_list = $global->SelectQuery($query);
    if (count($record_list) > 0) {
        return true;
    }
    return false;
}

include 'templates/country_edit.tpl.php';
?>