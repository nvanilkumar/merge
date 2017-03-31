<?php

session_start();
include_once("MT/cGlobali.php");
$Global = new cGlobali();
include 'loginchk.php';


$btype = "newyear";
if (isset($_GET['btype'])) {
    if (strlen(trim($_GET['btype'])) > 0) {
        $btype = $_GET['btype'];
    }
}

$nye_disc_Query = "SELECT sp.id,sp.eventid,sp.title,sp.promocode,sp.cts,sp.status,c.name"
        . " FROM specialdiscount as sp left join city as c on c.id=sp.cityid where sp.`type`='" . $btype . "' and sp.deleted=0 order by sp.id desc"; //using all -pH
$nye_disc_list = $Global->SelectQuery($nye_disc_Query);
 

include 'templates/newyeardiscounts.tpl.php';
 