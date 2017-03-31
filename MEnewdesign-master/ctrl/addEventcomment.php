<?php
	session_start();
	include 'loginchk.php';
	include_once("MT/cGlobali.php");
	/*error_reporting(-1);
        ini_set('display_errors',1);*/
	
	$Global = new cGlobali();

      


     if($_REQUEST['AddComment']=="Add Comment")
	 {
	 $comment=$_REQUEST['comment'];
	 $qname=$_REQUEST['qname'];
	 $EventId=$_REQUEST['EventId'];
	 
	 $EventsUpQuery = "INSERT INTO eventqualitycomment (eventid,qpid,comment) values ('".$EventId."','".$qname."','".$comment."') ";  
   	 $Global->ExecuteQuery($EventsUpQuery);
	 }

        if($_REQUEST['EventId']!=""){
            //$sqlTransComments="select * from EventInfoComments where EventId=".$_REQUEST['EventId'];
            $sqlTransComments="SELECT `comment` AS Comment, `cts` AS PostedDt, `qpid` AS QPid "
                . "FROM eventqualitycomment "
                . "WHERE eventid=".$_REQUEST['EventId'];

            $indId = $Global->SelectQuery($sqlTransComments); 

            $sqlEvent="SELECT ownerid AS UserID, title AS Title FROM event WHERE deleted = 0 AND id=".$_REQUEST['EventId'];
            $ResEvent = $Global->SelectQuery($sqlEvent);
	}

		$SalesQuery = "SELECT `id` AS SalesId,`name` AS SalesName FROM  salesperson ORDER BY `name`" ; 
		$SalesQueryRES = $Global->SelectQuery($SalesQuery);

	include 'templates/addEventcomment.tpl.php';
?>