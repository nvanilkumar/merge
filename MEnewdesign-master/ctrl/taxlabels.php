<?php

session_start();

include_once("MT/cGlobali.php");
$Global = new cGlobali();

include 'loginchk.php';

/* Add Tax label */
if (isset($_POST['currFrmSub']) && $_POST['currFrmSub']=="Add") {
    $label = $_POST['taxLabel'];
    
    $insetTaxQry = "INSERT INTO `tax` "
            . "(`label`, `createdby`, `status`) VALUES "
            . "('".mysqli_real_escape_string($Global->dbconn, $label)."', '".$_SESSION['uid']."', 1) ";
    $insetTaxRes = $Global->ExecuteQuery($insetTaxQry);
    if ($insetTaxRes) {
        $message = "Tax label added successfully.";
    }
    else {
        $message = "Problem in adding tax label, please try later.";
    }
}
/* Edit Tax label */
if (isset($_POST['currFrmSub']) && $_POST['currFrmSub']=="Edit") {
    $label = $_POST['taxLabel'];
    $id = $_POST['taxLabelId'];
    
    $updateTaxQry = "UPDATE `tax` "
            . "SET `label`='".mysqli_real_escape_string($Global->dbconn, $label)."', "
            . "`modifiedby`='".$_SESSION['uid']."' "
            . "WHERE `id`='".$id."' ";
    $updateTaxRes = $Global->ExecuteQuery($updateTaxQry);
    if ($updateTaxRes) {
        unset($_REQUEST['edit']);
        $message = "Tax label updated successfully.";
    }
    else {
        $message = "Problem in updating tax label, please try later.";
    }
}
if (isset ($_REQUEST['edit']) && $_REQUEST['edit'] != "" &&  $_REQUEST['edit'] != 0) {
    $editQuery = "SELECT `id`, `label`,`status` "
            . "FROM `tax` "
            . "WHERE `status`='1' AND deleted = 0 "
            . "AND `id`='".$_REQUEST['edit']."' ";
    $ediRes = $Global->SelectQuery($editQuery);
    if (count($ediRes)<1) {
       unset($_REQUEST['edit']);
       header ("Location: taxlabels.php");
    }
    
} 
//Query For Tax
$TQuery = "SELECT `id`, `label`,`status` "
        . "FROM `tax` "
        . "WHERE `status`='1' AND deleted = 0 "
        . "ORDER BY `id` ASC";
$TData = $Global->SelectQuery($TQuery);
$TDataCount = count($TData);


include 'templates/taxlabels.tpl.php';
