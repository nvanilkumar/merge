<?php
/******************************************************************************************************************************************
 *	File deatils:
 *	Display Events of the Month
 *	
 *	Created / Updated on:
 *	1.	Using the MT the file is updated on 26th Aug 2009
 *	2.	Added the new filed IsFamous in db which is used to display the Famous Events on the front end.
 * 		The check box property checked shows the event is famous, visible on front end and vice versa.
******************************************************************************************************************************************/
	
	session_start();
	$uid =	$_SESSION['uid'];
	
 include 'loginchk.php';

	include_once("MT/cGlobali.php");
        include_once("includes/common_functions.php");
        $common=new functions();
	
	$Global = new cGlobali();
	
	if($_REQUEST['submit'] == 'Show Events')
	{
		$SDt = $_REQUEST['txtSDt']; 
                $EDt = $_REQUEST['txtEDt'];
                $date_query = '';
                
                if($SDt != '' && $EDt != '') {
                    $SDtExplode = explode("/", $SDt);
                    $SDtYMD = $SDtExplode[2].'-'.$SDtExplode[1].'-'.$SDtExplode[0].' 00:00:00';
                    $SDtYMD =$common->convertTime($SDtYMD, DEFAULT_TIMEZONE);

                    $EDtExplode = explode("/", $EDt);
                    $EDtYMD = $EDtExplode[2].'-'.$EDtExplode[1].'-'.$EDtExplode[0].' 23:59:59';
                    $EDtYMD =$common->convertTime($EDtYMD, DEFAULT_TIMEZONE);
                    
                    $date_query = " AND (e.startdatetime >= '".$SDtYMD."' AND e.enddatetime <='".$EDtYMD."') ";
                }
                $eventId = (isset($_REQUEST['EventId'])) ? $_REQUEST['EventId'] : '';
                $event_query = '';
                if($eventId != '') {
                    $event_query = " and e.id = ".$eventId;
                }
		
		/*$EventsQuery = "SELECT e.Id, e.Title, e.StartDt,e.EndDt,e.perc, ex.Id 'exId', ex.amount 'exAmount', ex.Type FROM events e
			Left join extracharges ex on e.Id=ex.EventId 
			WHERE 1 $date_query $event_query and e.Free=0 and e.Title!='' ORDER BY e.StartDt, e.Title ASC";*/
                
                $EventsQuery = "SELECT e.id AS Id, e.title AS Title, e.startdatetime AS StartDt,e.enddatetime AS EndDt,es.percentage AS perc,sal.id AS SalesId,sal.name AS SalesName 
                        FROM eventsetting es
                        JOIN event e ON e.id = es.eventid
                        LEFT JOIN eventsalespersonmapping ae ON ae.eventid = e.id
                        LEFT JOIN salesperson sal ON sal.id = ae.salesid
			WHERE 1 and e.deleted=0 $date_query $event_query and e.registrationtype=2 and e.title!='' ORDER BY e.startdatetime, e.title ASC";
                
                
   		$EventsOfMonth = $Global->SelectQuery($EventsQuery);
	}
	
	//echo $EventsQuery;
	
	
	
	$ContractDocDB=NULL;
	//code to get default global commission values
	$sqlComm="SELECT id, type, countryid, value FROM commission WHERE `global` = 1";
	$recComm=$Global->SelectQuery($sqlComm);
	
	//$cardperc=$codperc=$counterperc=$paypalperc=$mobikwikperc=0;
	/*echo "<pre>";
	print_r($recComm);
        echo "</pre>";
        $Globalcardperc="";
	$Globalcodperc="";
	$Globalcounterperc="";
	$Globalpaypalperc="";
	$Globalmobikwikperc="";
	$Globalpaytmperc="";
	//$ServiceTax="";
	$Globalmeeffortperc="";*/
    foreach ($recComm as $gGommission) {
        switch ($gGommission['type']) {
            case "1":
                $Globalcardperc=$gGommission['value'];
                break;
            case "2":
                $Globalcodperc=$gGommission['value'];
                break;
            case "3":
                $Globalcounterperc=$gGommission['value'];
                break;
            case "4":
                $Globalpaypalperc=$gGommission['value'];
                break;
            case "5":
                $Globalmobikwikperc=$gGommission['value'];
                break;
            case "6":
                $Globalpaytmperc=$gGommission['value'];
                break;
            case "11":
                $Globalmeeffortperc=$gGommission['value'];
                break;
           
        }
    }
	//$ServiceTax=$recComm[0]['ServiceTax'];
        /*echo "Commissions: <br>";
	echo $Globalcardperc;
        echo "<br>";
	echo $Globalcodperc;
        echo "<br>";
	echo $Globalcounterperc;
        echo "<br>";
	echo $Globalpaypalperc;
        echo "<br>";
	echo $Globalmobikwikperc;
        echo "<br>";
	echo $Globalpaytmperc;
        echo "<br>";
	echo $Globalmeeffortperc;
        echo "<br>";*/
     
	
	include 'templates/events_commision.tpl.php';	
?>

