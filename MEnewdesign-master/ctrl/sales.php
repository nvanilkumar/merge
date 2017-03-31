<?php

session_start();

include_once("MT/cGlobali.php");
include 'loginchk.php';


$Global = new cGlobali();
$MsgCountryExist = '';
if (isset($_REQUEST['SalesId']) && $_REQUEST['SalesId'] != '' && $_REQUEST['SalesId'] != '0') {
    $SalesId = $_REQUEST['SalesId'];
}
if (!empty($_POST)) {
    $sales_name = $_POST['sales_name'];
    $sales_name = trim($sales_name);
    $sales_mobile = trim($_REQUEST['sales_mobile']);
    $sales_email = trim($_REQUEST['sales_email']);
    $esign = $_REQUEST['esign'];


    if ($_POST['Submit'] == "Add") {




        $Query = "SELECT id FROM  salesperson WHERE `name`='" . $sales_name . "' AND `deleted` = 0 ";
        $SalesId = $Global->SelectQuery($Query);
        if (count($SalesId) > 0) {
            $MsgcategoryExist = 'Name already exist!';
        } else {
            $insQuery = "INSERT INTO salesperson(`name`,`mobile`,`email`,`signature`, `createdby`) VALUES ('" . $sales_name . "','" . $sales_mobile . "','" . $sales_email . "','" . $esign . "', '".$_SESSION['uid']."')";
            $SalesId = $Global->ExecuteQuery($insQuery);
            $MsgcategoryExist = 'Added Successfully!';
        }
    }
    if ($_POST['Submit'] == "Save" && $SalesId != "") {

        $insQuery = "UPDATE salesperson SET `name`='" . $sales_name . "',`mobile`='" . $sales_mobile . "',`email`='" . $sales_email . "',`signature`='" . $esign . "', `modifiedby` = '".$_SESSION['uid']."' where id=" . $SalesId;
        $SalesId = $Global->ExecuteQuery($insQuery);
        $MsgcategoryExist = 'Updates Successfully!';
        $_REQUEST['SalesId'] = '';
        $SalesId = '';  
    }
}
if ($SalesId != "" && $_REQUEST['del'] == "true") {
    $sqldelsales = "UPDATE  salesperson SET `deleted`= 1, `modifiedby` = '".$_SESSION['uid']."' WHERE `id`=" . $SalesId;
    $Global->ExecuteQuery($sqldelsales);
    header("location:sales.php");
} else if ($SalesId != "") {
    $sqlsales = "SELECT * FROM salesperson WHERE id=" . $SalesId;
    $resSales = $Global->SelectQuery($sqlsales);
}


$listSales = "SELECT * FROM salesperson WHERE `deleted` = 0";
$resList = $Global->SelectQuery($listSales);


include 'templates/newsales.tpl.php';
?>