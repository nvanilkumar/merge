<?php

/* * ****************************************************************************************************************************************
 * 	File deatils:
 * 	It displays the city list as per the states list.
 * 	
 * 	Created / Updated on:
 * 	1.	Using the MT the file is updated on 22nd Aug 2009
 * **************************************************************************************************************************************** */
session_start();

include_once("MT/cGlobali.php");
include 'loginchk.php';
include_once 'eventsignup.php';
include 'includes/common_functions.php';

$commonFunctions = new functions();
$eventSignup = new eventsignup();

if (!$commonFunctions->isSuperAdmin() && !$commonFunctions->isSupportTeam())
    header("Location: admin.php");


$Global = new cGlobali();

//updating eventsignup details
if (isset($_POST['EventSignUpId'])) {
    $esid = $_POST['EventSignUpId'];

    $ucode = $_POST['Ucode'];

    $SignupDt = $_POST['SignupDt'];
    $SDtExplode = explode("/", $SignupDt);
    $SignupDt = $SDtExplode[2] . '-' . $SDtExplode[1] . '-' . $SDtExplode[0] . ' 00:00:00';

    $sqlUpEs = "update `eventsignup` set `signupdate`='" . $SignupDt . "' ,"
            . "`promotercode`='" . $ucode . "' where `id`='" . $esid . "'";
    $queryStatus=$Global->ExecuteQuery($sqlUpEs);
    
    $_SESSION['esUpSucc'] = $esid;
        
        ///Updateing the attendeedetails
        $Name = $_POST['Name'];
        $EMail = $_POST['EMail'];
        $Phone = $_POST['Phone'];
        $Address = $_POST['Address'];
        $Name2 = $_POST['Name2'];
        $EMail2 = $_POST['EMail2'];
        $Phone2 = $_POST['Phone2'];
        $Address2 = $_POST['Address2'];
        $addressUpdate=$phoneUpdate="";
        if(strlen($Phone) >0 && strlen($Phone2) >0){
            $phoneUpdate= " WHEN id =".$Phone2." THEN '".$Phone."'";
            $idsList.=$Phone2.",";
        }
        if(strlen($Address) > 0 && strlen($Address2)>0 ){
             $addressUpdate= "WHEN id =".$Address2." THEN '".$Address."'";
            $idsList.=$Address2.",";
        }else if(strlen($Phone) >0 ){
            $idsList=substr($idsList,0,-1 );
        }
       
        $updateQuery = "UPDATE attendeedetail
                        SET value = 
           CASE 
          WHEN id =".$Name2." THEN '".$Name.
        "' WHEN id =".$EMail2." THEN '".$EMail."'".
         $phoneUpdate.$addressUpdate.
        " END
        where id in (".$Name2.",".$EMail2.",".$idsList.")";
        $Global->ExecuteQuery($updateQuery);
    

}


$data = array();
$esid = NULL;
if (isset($_GET['evtsignupid'])) {
    $esid = $_GET['evtsignupid'];
	$errormsg="NO data available.";

   // if ($commonFunctions->isSuperAdmin() || $commonFunctions->isSupportTeam()) {
        $sqlData = $eventSignup->getPrimaryAttendeDetails($esid);
		if(count($sqlData) > 0){
        $data = $eventSignup->formatEventSignupDetails($sqlData);
		$errormsg="";
		//attendees data for this eventsignupid
     $sqlData1 = $eventSignup->getAttendeeList($esid);
     $dataAtt=$eventSignup->formatAttendeelist($sqlData1);
		}
  //  } 
    /* elseif ($commonFunctions->isAdmin()) {
        $sql = "select `id` 'Id' from `eventsignup` where `id`='" . $esid . "' and deleted = 0";
        $data = $Global->SelectQuery($sql);
		$errormsg="";
    } */
    
     

}

//To remove the signup id entry
//if ((isset($_POST['delete_signup'])) && ($commonFunctions->isSuperAdmin())) {
if ((isset($_POST['delete_signup'])) && ($commonFunctions->isSuperAdmin())) {

    delete_signup_record($Global);
	$errormsg="Successfully deleted";
}
include 'templates/editeventsignup.tpl.php';

/*
  //To remove the signup records & stores the details in bkp table
 * @parm post data delete variable sets
 * @parm event signup id
 * 
 */

function delete_signup_record($Global) {
    $event_signup_id = $_POST['delete_signup_id'];
	
    //Remove entry from even signup table
     $sql_delete = "update  eventsignup set deleted=1 WHERE id = " . $event_signup_id;
    $delres = $Global->ExecuteQuery($sql_delete);
	if($delres){
		
	  //$sqltck="select ticketid,ticketquantity from eventsignupticketdetail where eventsignupid = ". $event_signup_id; 
	  $sqltck="select 
    s.id,es.ticketid, es.ticketquantity
     from
    eventsignupticketdetail as es
        inner join
    eventsignup as s ON es.eventsignupid = s.id
    where
    es.eventsignupid = ".$event_signup_id."
        AND ((s.paymentmodeid = 1 and s.paymenttransactionid != 'A1')
        or s.paymentmodeid = 2
        or s.discount != 'X'
        or  s.paymenttransactionid = 'Offline'
        and s.totalamount != 0)";
       $tckres = $Global->SelectQuery($sqltck);	
       $tckCount=count($tckres);	   
	    for($i=0;$i<$tckCount;$i++) {
			 $uptck="update ticket set totalsoldtickets=(totalsoldtickets-".$tckres[$i][ticketquantity].") where id=".$tckres[$i][ticketid];
			$Global->ExecuteQuery($uptck);
		}
		
	}
    //header("Location: editeventsignup.php");


}

?>