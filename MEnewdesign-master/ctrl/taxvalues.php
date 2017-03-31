<?php

session_start();

include_once("MT/cGlobali.php");
$Global = new cGlobali();

include 'loginchk.php';

if (isset($_POST['currFrmSub']) && $_POST['currFrmSub'] != "") {
    //INSERT CODE
    $postArr = $_POST;
    $insStateId = 0;
    if (isset($postArr['frmStateId']) && $postArr['frmStateId'] !="" && $postArr['frmStateId'] !="0") {
        $insStateId = $postArr['frmStateId'];
    }
    
    $insCityId = 0;        
    if (isset($postArr['frmCityId']) && $postArr['frmCityId'] !="" && $postArr['frmCityId'] !="0") {
        $insCityId = $postArr['frmCityId'];
    }
    
    $checkTaxArray['countryid']=$postArr['frmCountryId'];
    $checkTaxArray['cityid']=$insCityId;
    $checkTaxArray['stateid']=$insStateId;
    $checkTaxArray['type']=$postArr['taxType'];
    $checkTaxArray['status']=0;
    $checkTaxArray['taxid']=$postArr['taxId'];
    
    //change the status of old tax mapping details
    if(checkTaxMappingList($Global,$checkTaxArray) > 0){
        updateTaxMappingList($Global,$checkTaxArray);
    }
    
    $insetTaxMapQry = "INSERT INTO `taxmapping` "
            . "(`taxid`, `value`, `type`, `countryid`, `stateid`, `cityid`, `createdby`) VALUES "
            . "('".$postArr['taxId']."', '".mysqli_real_escape_string($Global->dbconn,$postArr['taxVal'])."', '".mysqli_real_escape_string($Global->dbconn,$postArr['taxType'])."', '".$postArr['frmCountryId']."', '".$insStateId."', '".$insCityId."', '".$_SESSION['uid']."') ";
    $insetTaxMapRes = $Global->ExecuteQuery($insetTaxMapQry);
    $message = "Problem in adding tax, please try later.";
    if ($insetTaxMapRes) {
        $message = "Tax added successfully.";
    }
}

if (isset($_POST['srchSubmit']) && $_POST['srchSubmit'] != "") {
    $taxMappingData=taxMappingLis($Global);
    $taxMappingDataCount = count($taxMappingData);
    $srchtaxId=$_POST['srchtaxId'];
    $srchCountryId=$_POST['srchCountryId'];
    $srchStateId=$_POST['srchStateId'];
    $srchCityId=$_POST['srchCityId'];

    /*echo "<pre>";
    print_r($taxMappingData);
    echo "</pre>";*/
}

//Get the tax mapping list details
function taxMappingLis($Global){
        $taxConcat = $countryConcat = $stateConcat = $cityConcat = "";
        
    
    if (isset($_POST['srchtaxId']) && $_POST['srchtaxId'] != "0" && $_POST['srchtaxId'] != "") {
        $taxConcat = " AND tm.taxid = '".$_POST['srchtaxId']."' ";
    } 
    
    $countryConcat = " AND tm.countryid = 0 ";
    if (isset($_POST['srchCountryId']) && $_POST['srchCountryId'] != "0" && $_POST['srchCountryId'] != "") {
        $countryConcat = " AND tm.countryid = '".$_POST['srchCountryId']."' ";
    }
      
    $stateConcat = " AND (tm.stateid = 0 or  tm.stateid IS NULL)";
    if (isset($_POST['srchStateId']) && $_POST['srchStateId'] != "0" && $_POST['srchStateId'] != "") {
        $stateConcat = " AND tm.stateid = '".$_POST['srchStateId']."' ";
    }
    $cityConcat = " AND (tm.cityid=0 or tm.cityid IS NULL)";
    if (isset($_POST['srchCityId']) && $_POST['srchCityId'] != "0" && $_POST['srchCityId'] != "") {
        $cityConcat = " AND tm.cityid = '".$_POST['srchCityId']."' ";
    }
    // SEARCH Criteria
    $taxMappingQuery = "SELECT tm.id,tm.status,tm.value taxvalue, tm.type taxlevel, "
            . "tm.countryid, tm.stateid, tm.cityid, "
            . "t.label, t.id as taxid, "
            . "country.name countryname "
            . "FROM tax t JOIN taxmapping tm ON t.id = tm.taxid "
            . "JOIN country ON country.id =tm.countryid "
            . "WHERE t.deleted = 0 AND t.status = 1 "
            . "AND tm.deleted = 0 "
            . " ".$taxConcat . $countryConcat . $stateConcat . $cityConcat." "
            . "ORDER BY tm.cts";
    $taxMappingData = $Global->SelectQuery($taxMappingQuery);
    return $taxMappingData;
    
}

//To update the taxmapping id status
if(isset($_GET['taxmapstatus']) && $_GET['taxmapstatus'] != "0"){
    
    //Bring the taxmapping details
    $taxdata['id']=$_GET['taxmapstatus'];
    $details=getTaxMappingDetails($Global, $taxdata);
    if(count($details) > 0){
        $taxArray['countryid']=$details[0]['countryid'];
        $taxArray['cityid']=(strlen($details[0]['cityid'])>0)?$details[0]['cityid']:0;
        $taxArray['stateid']=(strlen($details[0]['stateid'])>0)?$details[0]['stateid']:0;
        $taxArray['type']=$details[0]['type'];
        $taxArray['status']=0;
        $taxArray['taxid']=$details[0]['taxid'];
        //changing all mapping tax
        updateTaxMappingList($Global,$taxArray);
       
        $taxArray['status']=1;
        $taxArray['id']=$taxdata['id'];
        updateTaxMappingList($Global,$taxArray);
        
        //setting the search criteria values afte changing the taxmapping value
        $srchtaxId=$details[0]['taxid'];
        $srchCountryId=$details[0]['countryid'];
        $srchStateId=$details[0]['stateid'];
        $srchCityId=$details[0]['cityid'];

        //getting the mapping list
        $taxConcat = " AND tm.taxid = '".$srchtaxId."' ";
        $countryConcat = " AND tm.countryid = '".$srchCountryId."' ";
        $stateConcat = " AND tm.stateid = '".$srchStateId."' ";
        $cityConcat = " AND tm.cityid = '".$srchCityId."' ";
        
        $taxMappingQuery = "SELECT tm.id,tm.status,tm.value taxvalue, tm.type taxlevel, "
            . "tm.countryid, tm.stateid, tm.cityid, "
            . "t.label, t.id as taxid, "
            . "country.name countryname "
            . "FROM tax t JOIN taxmapping tm ON t.id = tm.taxid "
            . "JOIN country ON country.id =tm.countryid "
            . "WHERE t.deleted = 0 AND t.status = 1 "
            . "AND tm.deleted = 0 "
            . " ".$taxConcat . $countryConcat . $stateConcat . $cityConcat." "
            . "ORDER BY tm.cts";
        
        
    $taxMappingData = $Global->SelectQuery($taxMappingQuery);
        
        $taxMappingDataCount = count($taxMappingData);
    }
    
    
}

function getStateName ($Global, $id) {
    if ($id != 0) {
        $query = "SELECT name "
                . "FROM `state` "
                . "WHERE `deleted` = 0 AND `status` =1 "
                . "AND `id` = ".$id." "
                . "ORDER BY `name` ASC";
        $stateyName = $Global->SelectQuery($query);
        if (count($stateyName)>0) {
            return $stateyName[0]['name'];
        }
        else {
            return "-";
        }
    }
    else
        return "-";
}


function getCityName($Global, $id) {
    if ($id != 0) {
        $query = "SELECT name "
                . "FROM `city` "
                . "WHERE `deleted` = 0 AND `status` =1 "
                . "AND `id` = " . $id . " "
                . "ORDER BY `name` ASC";
        $cityName = $Global->SelectQuery($query);
        if (count($cityName) > 0) {
            return $cityName[0]['name'];
        }
    }

    return "-";
}

//To check the previous taxmapping details list
function checkTaxMappingList($Global, $data) {
    $mapCount = array();

    $query = "select  count(id) as count
               from taxmapping
              where countryid = " . $data['countryid'] . " and stateid = " . $data['stateid'] . " and cityid = " . $data['cityid'] . "
             and type = '".$data['type']."' and taxid=". $data['taxid'] ;
    $mapCount = $Global->SelectQuery($query);
    return count($mapCount);
}
//To check the previous taxmapping details list
function getTaxMappingDetails($Global, $data) {
    $query = "select  *
               from taxmapping
              where id = " . $data['id'];
    $result = $Global->SelectQuery($query);
    return $result;
}
//Update the status of the taxmapping details list
function updateTaxMappingList($Global, $data) {
    
    $idText="";
    if(isset($data['id'])){
        $idText=" and id=".$data['id'];
        
    }
    $uQuery="update taxmapping set status=" . $data['status'] . "   where countryid = " . $data['countryid'] . 
            " and stateid = " . $data['stateid'] . " and cityid = " . $data['cityid'] . "
             and type = '".$data['type']."' and taxid=". $data['taxid'].$idText;
   $ResUp= $Global->ExecuteQuery($uQuery);
}

$countryDataCount = $stateDataCount = $cityDataCount = 0;
//Country list
$countryQry = "SELECT `id`, `name`"
        . "FROM `country` "
        . "WHERE `status`='1' AND deleted = 0 "
        . "ORDER BY `name` ASC";
$countryData = $Global->SelectQuery($countryQry);
$countryDataCount = count($countryData);


if (isset($_REQUEST['srchCountryId']) && $_REQUEST['srchCountryId'] != 0 && $_REQUEST['srchCountryId'] != '') {
    $StateQuery = "SELECT `id`,`name` FROM state WHERE `countryid` = '" . $_REQUEST['srchCountryId'] . "' AND `status` = 1 AND `deleted` = 0 "
            . "ORDER BY `name`";
    $stateData = $Global->SelectQuery($StateQuery);
    $stateDataCount = count($stateData);
    
    if (isset($_REQUEST['srchStateId']) && $_REQUEST['srchStateId'] != 0 && $_REQUEST['srchStateId'] != '') {
        $CityQuery = "SELECT c.id, c.name FROM city c JOIN statecity sc ON c.id = sc.cityid "
                . "WHERE sc.stateid = '" . $_REQUEST['srchStateId'] . "' AND c.name NOT LIKE '%Other%' AND c.status = 1 AND c.deleted = 0 "
                . "ORDER BY `name`";
        $cityData = $Global->SelectQuery($CityQuery);
        $cityDataCount = count($cityData);
    }
}

////Query For Tax
$TQuery = "SELECT `id`, `label`,`status` "
        . "FROM `tax` "
        . "WHERE `status`='1' AND deleted = 0 "
        . "ORDER BY `id` ASC";
$TData = $Global->SelectQuery($TQuery);
$TDataCount = count($TData);

include 'templates/taxvalues.tpl.php';
