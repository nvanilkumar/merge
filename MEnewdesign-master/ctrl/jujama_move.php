<?php
/******************************************************************************************************************************************
 *	File deatils:
 *	It displays the city list as per the states list.
 *	
 *	Created / Updated on:
 *	1.	Using the MT the file is updated on 22nd Aug 2009
******************************************************************************************************************************************/
	session_start();
		
	include_once("MT/cGlobal.php");
	
	$Global = new cGlobal();
        include 'loginchk.php';	
	$mid=$_REQUEST['mid'];
	if($_POST['Submit'] == "MoveAll")
	{
		$selJujamaMsgs = "SELECT Id,MEventId,JEventId,DPassword,EmailTxt FROM jujama WHERE MEventId =".$mid;
        $dtlJujamaMsgs = $Global->SelectQuery($selJujamaMsgs); 
		$JQuery1 = "SELECT u.Id,u.FirstName,u.UserName,u.Email,u.Company,u.Mobile,u.StateId,u.CityId FROM user as u,EventSignup as s where u.Id=s.UserId and ((s.PaymentModeId=1 and s.PaymentTransId!='A1') or s.PaymentModeId=2 or s.Fees=0) and s.EventId=".$mid;
	$JList1 = $Global->SelectQuery($JQuery1);
	for($i = 0; $i < count($JList1); $i++)
		{
			$userQuery1 = "SELECT u.Email, u.FirstName,u.UserName, u.Company, u.Mobile,u.StateId,u.CityId FROM user AS u WHERE u.Id = '".$JList1[$i]['Id']."'";
$user1 = $Global->SelectQuery($userQuery1);
				
		 $Email = $user1[0]['Email'];
            $FirstName = $user1[0]['FirstName'];
            $Company = $user1[0]['Company'];
            $MobileNo = $user1[0]['Mobile'];
            $UserName = $user1[0]['UserName'];
            $to = $Email;
			$state1=$Global->GetSingleFieldValue("SELECT State FROM States WHERE Id='".$user1[0]['StateId']."'");
			$city1=$Global->GetSingleFieldValue("SELECT City FROM Cities WHERE Id='".$user1[0]['CityId']."'");
		require_once('../Extras/webservice/lib/nusoap.php');
		$client = new nusoap_client('http://www.jujama.com/meraeventswebservice.asmx?WSDL', 'wsdl',$proxyhost, $proxyport, $proxyusername, $proxypassword);
						$err = $client->getError();
if ($err) {
	echo '<h2>Constructor error</h2><pre>' . $err . '</pre>';
}
// Doc/lit parameters get wrapped
$param = array('ConferenceID' => $dtlJujamaMsgs[0]['JEventId'], 'UserName' => $UserName,'Password' => $dtlJujamaMsgs[0]['DPassword'], 'PersonName' => $FirstName, 'CompanyName' => $Company, 'Email' => $Email, 'State' => $state1, 'City' => $city1, 'MobileNo' => $MobileNo);
$result = $client->call('InsertPersonDetails', array('parameters' => $param), '', '', false, true);// Check for a fault

		
		}
		
	}// END IF delete
	
	if($_POST['Submit'] == "MoveSelected")
	{
		include_once("MT/cCities.php");		//include the cCities MT
		$delJujamaIds = $_POST['movejujama'];			//get the list of cities in array of delCityIds
		$selJujamaMsgs = "SELECT Id,MEventId,JEventId,DPassword,EmailTxt FROM jujama WHERE MEventId =".$mid;
        $dtlJujamaMsgs = $Global->SelectQuery($selJujamaMsgs); 
		foreach($delJujamaIds as $JId)			//get the city id one by one to delete
		{
			$Id = $JId;
		
			$userQuery1 = "SELECT u.Email, u.FirstName,u.UserName, u.Company, u.Mobile,u.StateId,u.CityId FROM user AS u WHERE u.Id = '".$Id."'";

		
		$user1 = $Global->SelectQuery($userQuery1);
				
		 $Email = $user1[0]['Email'];
            $FirstName = $user1[0]['FirstName'];
            $Company = $user1[0]['Company'];
            $MobileNo = $user1[0]['Mobile'];
            $UserName = $user1[0]['UserName'];
            $to = $Email;
			$state1=$Global->GetSingleFieldValue("SELECT State FROM States WHERE Id='".$user1[0]['StateId']."'");
			$city1=$Global->GetSingleFieldValue("SELECT City FROM Cities WHERE Id='".$user1[0]['CityId']."'");
		require_once('../../webservice/lib/nusoap.php');
		$client = new nusoap_client('http://moozuplite.com/MeraEventsWebservice.asmx?WSDL', 'wsdl',$proxyhost, $proxyport, $proxyusername, $proxypassword);
						$err = $client->getError();
if ($err) {
	echo '<h2>Constructor error</h2><pre>' . $err . '</pre>';
}
// Doc/lit parameters get wrapped
$param = array('ConferenceID' => $dtlJujamaMsgs[0]['JEventId'], 'UserName' => $UserName,'Password' => $dtlJujamaMsgs[0]['DPassword'], 'PersonName' => $FirstName, 'CompanyName' => $Company, 'Email' => $Email, 'State' => $state1, 'City' => $city1, 'MobileNo' => $MobileNo);
$result = $client->call('InsertPersonDetails', array('parameters' => $param), '', '', false, true);// Check for a fault

		}
	}// END IF delete
		
	//Query For All Cities
	$JQuery = "SELECT u.Id,u.FirstName,u.UserName,u.Email,u.Company,u.Mobile,u.StateId,u.CityId FROM user as u,EventSignup as s where u.Id=s.UserId and ((s.PaymentModeId=1 and s.PaymentTransId!='A1') or s.PaymentModeId=2 or s.Fees=0) and s.EventId=".$mid;
	$JList = $Global->SelectQuery($JQuery);

	include 'templates/jujama_move.tpl.php';
?>