<?php

include 'loginchk.php';
include_once("MT/cGlobali.php");
$Global = new cGlobali();

if (isset($_REQUEST['EventId'])) {
    $EventId = $_REQUEST['EventId'];

    $sql = 'select `id` AS Id from `event` where deleted=0 and `id`=' . $EventId;
    //echo $sql;
    $data = $Global->GetSingleFieldValue($sql);

    if (strlen($data) > 0) {


        $SalesId = $Global->GetSingleFieldValue("SELECT salespersonid FROM  eventdetail WHERE eventid='" . $EventId . "' ");
        if ($SalesId > 0) {
            $SaleName = $Global->GetSingleFieldValue("SELECT `name` AS SalesName FROM salesperson WHERE id='" . $SalesId . "' ");

            echo "assignevent.php?EventId=" . $EventId . "&edit=yes&aSalesId=" . $SalesId;
        } else {
            echo "assignevent.php?EventId=" . $EventId . "&edit=yes";
        }
    } else {
        echo "error";
    }
}
?>