<?php

session_start();

include_once("MT/cGlobali.php");

$Global = new cGlobali();
include 'loginchk.php';
$uid =	$_SESSION['uid'];
$id = $sqlAdd = NULL;

$btype = "newyear";
if (isset($_GET['btype'])) {
    if (strlen(trim($_GET['btype'])) > 0) {
       $returntype= $btype = $_GET['btype'];
    }
}


if (isset($_POST['nye_discounts_form'])) {
    $title = $_POST['title'];
    $promo_code = $_POST['promo_code'];
    $eventid = isset($_POST['eventid'])?$_POST['eventid']:0;
    $editid = $_POST['editid'];
    $type = (strlen($_POST['returntype']) > 0)?$_POST['returntype']:$btype;
    $cityId=$_POST['searchCT'];
    

    $datatime = date('Y-m-d H:i:s', time());

     $sql = "insert into specialdiscount (id,type,eventid,title,promocode,cts,mts,createdby,cityid) values ('" . $editid . "','" . $btype . "','" . $eventid . "','" . mysqli_real_escape_string($Global->dbconn,$title) . "','" . $promo_code . "','" . $datatime . "','" . $datatime . "',".$uid.",".$cityId
            . ") on duplicate key update `eventid`='".$eventid."', `title`='".mysqli_real_escape_string($Global->dbconn,$title)."', `promocode`='".$promo_code."', `mts`='".$datatime."',`modifiedby`= ".$uid.",cityid=".$cityId;
    if ($Global->ExecuteQuery($sql)) {
        $_SESSION['nye_disc_created'] = true;
        header("Location: newyeardiscounts.php?btype=" . $type);
        exit;
    }
}// END IF 
 


$title = $eventid = NULL;

$cityid=0;
if (isset($_GET['edit'])) {
    $id = $_GET['edit'];
    $nye_disc_Query = "SELECT id,eventid,type,title,promocode,cts,status,cityid FROM specialdiscount where id= " . $id; //using all -pH
    $nye_disc_list = $Global->SelectQuery($nye_disc_Query);
    $title = $nye_disc_list[0]['title'];
    $promo_code = $nye_disc_list[0]['promocode'];
    $eventid = $nye_disc_list[0]['eventid'];
    $returntype=$nye_disc_list[0]['type'];
    $cityid=$nye_disc_list[0]['cityid'];
}

  $cityQuery = "SELECT id, `name` "
            . "FROM city "
            . "WHERE countryid = 14 "
            . "AND (`name` NOT LIKE '%Other%' AND `name` NOT LIKE '%Not From%') "
            . "AND `featured`=1 "
            . "AND status = 1 AND deleted = 0 "
            . "ORDER BY `name`";
    $cityList=$Global->SelectQuery($cityQuery);
 

include 'templates/nye_discounts_edit.tpl.php';
?>