<?php
include_once 'MT/cGlobali.php';
include_once 'loginchk.php';
$eventid=NULL;
if (isset($_REQUEST['eventid']) && $_REQUEST['eventid'] > 0) {
    $eventid=$_REQUEST['eventid'];
}
include 'templates/cancelevent_tpl.php';