<?php
	session_start();
	ini_set('max_execution_time', 180);
	include_once("MT/cGlobali.php");
	include 'loginchk.php';
	include_once("includes/common_functions.php");
	$Global = new cGlobali();
	$common=new functions();
	$MsgCountryExist = '';

//error_reporting(-1);
include("MT/cCtrl.php");
$cCtrl = new cCtrl();	

$finalArr=array();
foreach($ctrlCities as $cityData)
{
	$finalArr[$cityData[1]]=array();
	$finalArr[$cityData[1]]['totalCardTrAmt']=$finalArr[$cityData[1]]['totalCardTrQty']=$finalArr[$cityData[1]]['totalPACTrAmt']=$finalArr[$cityData[1]]['totalPACTrQty']=$finalArr[$cityData[1]]['totalCODTrAmt']=$finalArr[$cityData[1]]['totalCODTrQty']=$finalArr[$cityData[1]]['totalChqTrAmt']=$finalArr[$cityData[1]]['totalChqTrQty']=$finalArr[$cityData[1]]['totalUsers']=$finalArr[$cityData[1]]['totalPaidUsers']=$finalArr[$cityData[1]]['totalFreeUsers']=$finalArr[$cityData[1]]['totalSignedUpUsers']=$finalArr[$cityData[1]]['totalOrgs']=$finalArr[$cityData[1]]['totalEvents']=$finalArr[$cityData[1]]['totalFreeEvents']=$finalArr[$cityData[1]]['totalPaidEvents']=$finalArr[$cityData[1]]['totalNoRegEvents']=$finalArr[$cityData[1]]['totalUnqEvents']=$finalArr[$cityData[1]]['totalUnqUsers']=0;
}
	
  $finalArr['Other']=array();
$finalArr['Other']['totalCardTrAmt']=$finalArr['Other']['totalCardTrQty']=$finalArr['Other']['totalPACTrAmt']=$finalArr['Other']['totalPACTrQty']=$finalArr['Other']['totalCODTrAmt']=$finalArr['Other']['totalCODTrQty']=$finalArr['Other']['totalChqTrAmt']=$finalArr['Other']['totalChqTrQty']=$finalArr['Other']['totalUsers']=$finalArr['Other']['totalPaidUsers']=$finalArr['Other']['totalFreeUsers']=$finalArr['Other']['totalSignedUpUsers']=$finalArr['Other']['totalOrgs']=$finalArr['Other']['totalEvents']=$finalArr['Other']['totalFreeEvents']=$finalArr['Other']['totalPaidEvents']=$finalArr['Other']['totalNoRegEvents']=$finalArr['Other']['totalUnqEvents']=$finalArr['Other']['totalUnqUsers']=0;
//print_r($finalArr);


$HydCts=array('cities'=>array(47, 448),'states'=>array());
$ChennaiCts=array('cities'=>array(39),'states'=>array());
$BengaluruCts=array('cities'=>array(37),'states'=>array());
$DelhiCts=array('cities'=>array(330,331,383,408,484),'states'=>array(53));
$PuneCts=array('cities'=>array(77),'states'=>array());
$MumbaiCts=array('cities'=>array(14, 393),'states'=>array());




	
$salesPersons=$cCtrl->getSalesPersonData();
//CRETAE THE YESTERDAYS START / END DATE
$yesterdayDate=date ("Y-m-d", strtotime("-1 day"));
$SDt=$EDt=date ("d-M-Y", strtotime("-1 day"));
$fromDt = $common->convertTime($yesterdayDate.' 00:00:01', DEFAULT_TIMEZONE);
$toDt = $common->convertTime($yesterdayDate.' 23:59:59', DEFAULT_TIMEZONE);


/*$fromDt = '2014-09-01 00:00:01';
$toDt = '2015-04-20 23:59:59';*/
	
if(isset($_POST['formSubmit']))
{
	//dates
	 $SDt = str_replace('/', '-', $_POST['txtSDt']);
	  $EDt= str_replace('/', '-', $_POST['txtEDt']);
	
	if(strlen($SDt)>0)
	{
		 $fromDt = date ("Y-m-d", strtotime($SDt)).' 00:00:01';
		 $fromDt = $common->convertTime($fromDt, DEFAULT_TIMEZONE);
	}
	
	if(strlen($EDt)>0)
	{
		 $toDt = date ("Y-m-d", strtotime($EDt)).' 23:59:59'; 
		 $toDt = $common->convertTime($toDt, DEFAULT_TIMEZONE);
	}
	else
	{
		$EDt=date ("d-M-Y", strtotime("now"));
		$toDt = date ("Y-m-d h:i:s", strtotime("now"));
		$toDt = $common->convertTime($toDt, DEFAULT_TIMEZONE);
	}
	//dates
	
	
	
	//new year
	if(isset($_POST['NewYear'])){
	    $sqlcat = "SELECT id FROM category where name='NewYear'"; 
        $dtsqlcat = $Globali->GetSingleFieldValue($sqlcat);            
        $catSql .=" AND e.categoryid = ".$dtsqlcat ;                
	}
	//new year
	
	
	
	//ExtraCharge
	$ExtraCharge=false;
	if(isset($_POST['ExtraCharge']))
	{
		$ExtraCharge=true;
	}
	//ExtraCharge
	
	
	
	//city
	if($_POST['selCity']!="")
	{
		$currentCity=$_POST['selCity'];	
	}
	//city
	
	//sales id
	if($_POST['SalesId']!="")
	{
		$SalesId=" AND ed.salespersonid=".$_POST['SalesId'];
	}
	//sales id
	
	
}
	
	
	//list of Successful CARD Transactions by citywise
	$cardTrCond=" and s.signupdate >= '".$fromDt."' AND s.signupdate <= '".$toDt."' ".$SalesId;
	$TransactionRES=$cCtrl->getEventCardTrsByCity($ExtraCharge,$cardTrCond);
	
	while($cardRes=$TransactionRES->fetch_assoc())
	{
		if(in_array($cardRes['CityId'],$HydCts['cities']))
		{ 
			$finalArr['Hyderabad']['totalCardTrAmt']+=$cardRes['totalAmount']; 
			$finalArr['Hyderabad']['totalCardTrQty']+=$cardRes['count']; 
		}
		
		elseif(in_array($cardRes['CityId'],$ChennaiCts['cities']))
		{ 
			$finalArr['Chennai']['totalCardTrAmt']+=$cardRes['totalAmount']; 
			$finalArr['Chennai']['totalCardTrQty']+=$cardRes['count'];
		}
		
		elseif(in_array($cardRes['CityId'],$BengaluruCts['cities']))
		{ 
			$finalArr['Bengaluru']['totalCardTrAmt']+=$cardRes['totalAmount']; 
			$finalArr['Bengaluru']['totalCardTrQty']+=$cardRes['count'];
		}
		
		elseif(in_array($cardRes['CityId'],$DelhiCts['cities']) || in_array($cardRes['StateId'],$DelhiCts['states']))
		{ 
			$finalArr['Delhi-NCR']['totalCardTrAmt']+=$cardRes['totalAmount']; 
			$finalArr['Delhi-NCR']['totalCardTrQty']+=$cardRes['count'];
		}
		
		elseif(in_array($cardRes['CityId'],$PuneCts['cities']))
		{ 
			$finalArr['Pune']['totalCardTrAmt']+=$cardRes['totalAmount']; 
			$finalArr['Pune']['totalCardTrQty']+=$cardRes['count'];
		}
		
		elseif(in_array($cardRes['CityId'],$MumbaiCts['cities']))
		{ 
			$finalArr['Mumbai']['totalCardTrAmt']+=$cardRes['totalAmount']; 
			$finalArr['Mumbai']['totalCardTrQty']+=$cardRes['count'];
		}
		
		else
		{ 
		 	$finalArr['Other']['totalCardTrAmt']+=$cardRes['totalAmount']; 
		 	$finalArr['Other']['totalCardTrQty']+=$cardRes['count'];
		}
	}
	
	
	//list of Successful PayAtCounter Transactions by citywise
	$PACTrCond=" and s.signupdate >= '".$fromDt."' AND s.signupdate <= '".$toDt."' ".$SalesId;
	$PACTransactionRES=$cCtrl->getEventPACTrsByCity($ExtraCharge,$PACTrCond);
	
	while($PACRes=$PACTransactionRES->fetch_assoc())
	{
		if(in_array($PACRes['CityId'],$HydCts['cities']))
		{ 
			$finalArr['Hyderabad']['totalPACTrAmt']+=$PACRes['totalAmount'];
			$finalArr['Hyderabad']['totalPACTrQty']+=$PACRes['count']; 
		}
		
		elseif(in_array($PACRes['CityId'],$ChennaiCts['cities']))
		{ 
			$finalArr['Chennai']['totalPACTrAmt']+=$PACRes['totalAmount'];
			$finalArr['Chennai']['totalPACTrQty']+=$PACRes['count'];  
		}
		
		elseif(in_array($PACRes['CityId'],$BengaluruCts['cities']))
		{ 
			$finalArr['Bengaluru']['totalPACTrAmt']+=$PACRes['totalAmount'];
			$finalArr['Bengaluru']['totalPACTrQty']+=$PACRes['count'];  
		}
		
		elseif(in_array($PACRes['CityId'],$DelhiCts['cities']) || in_array($PACRes['StateId'],$DelhiCts['states']))
		{ 
			$finalArr['Delhi-NCR']['totalPACTrAmt']+=$PACRes['totalAmount']; 
			$finalArr['Delhi-NCR']['totalPACTrQty']+=$PACRes['count']; 
		}
		
		elseif(in_array($PACRes['CityId'],$PuneCts['cities']))
		{ 
			$finalArr['Pune']['totalPACTrAmt']+=$PACRes['totalAmount'];
			$finalArr['Pune']['totalPACTrQty']+=$PACRes['count'];  
		}
		
		elseif(in_array($PACRes['CityId'],$MumbaiCts['cities']))
		{ 
			$finalArr['Mumbai']['totalPACTrAmt']+=$PACRes['totalAmount']; 
			$finalArr['Mumbai']['totalPACTrQty']+=$PACRes['count']; 
		}
		
		else
		{ 
			$finalArr['Other']['totalPACTrAmt']+=$PACRes['totalAmount']; 
			$finalArr['Other']['totalPACTrQty']+=$PACRes['count'];  
		}
	}
	
	
	//list of Successful COD Transactions by citywise
	$CODTrCond=" and s.signupdate >= '".$fromDt."' AND s.signupdate <= '".$toDt."' ".$SalesId;
	$CODTransactionRES=$cCtrl->getEventCODTrsByCity($ExtraCharge,$CODTrCond);
	
	while($CODRes=$CODTransactionRES->fetch_assoc())
	{
		if(in_array($CODRes['CityId'],$HydCts['cities']))
		{ 
			$finalArr['Hyderabad']['totalCODTrAmt']+=$CODRes['totalAmount']; 
			$finalArr['Hyderabad']['totalCODTrQty']+=$CODRes['count']; 
		}
		
		elseif(in_array($CODRes['CityId'],$ChennaiCts['cities']))
		{ 
			$finalArr['Chennai']['totalCODTrAmt']+=$CODRes['totalAmount']; 
			$finalArr['Chennai']['totalCODTrQty']+=$CODRes['count']; 
		}
		
		elseif(in_array($CODRes['CityId'],$BengaluruCts['cities']))
		{ 
			$finalArr['Bengaluru']['totalCODTrAmt']+=$CODRes['totalAmount']; 
			$finalArr['Bengaluru']['totalCODTrQty']+=$CODRes['count']; 
		}
		
		elseif(in_array($CODRes['CityId'],$DelhiCts['cities']) || in_array($CODRes['StateId'],$DelhiCts['states']))
		{ 
			$finalArr['Delhi-NCR']['totalCODTrAmt']+=$CODRes['totalAmount']; 
			$finalArr['Delhi-NCR']['totalCODTrQty']+=$CODRes['count']; 
		}
		
		elseif(in_array($CODRes['CityId'],$PuneCts['cities']))
		{ 
			$finalArr['Pune']['totalCODTrAmt']+=$CODRes['totalAmount'];
			$finalArr['Pune']['totalCODTrQty']+=$CODRes['count'];  
		}
		
		elseif(in_array($CODRes['CityId'],$MumbaiCts['cities']))
		{ 
			$finalArr['Mumbai']['totalCODTrAmt']+=$CODRes['totalAmount'];
			$finalArr['Mumbai']['totalCODTrQty']+=$CODRes['count'];  
		}
		
		else
		{ 
			$finalArr['Other']['totalCODTrAmt']+=$CODRes['totalAmount'];
			$finalArr['Other']['totalCODTrQty']+=$CODRes['count']; 
		}
	}
	 
	
	//list of Successful Cheque Transactions by citywise
	$ChqTrCond=" and s.signupdate >= '".$fromDt."' AND s.signupdate <= '".$toDt."' ".$SalesId;
	$ChqTransactionRES=$cCtrl->getEventChqTrsByCity($ExtraCharge,$ChqTrCond);
	
	while($ChqRes=$ChqTransactionRES->fetch_assoc())
	{
		if(in_array($ChqRes['CityId'],$HydCts['cities']))
		{ 
			$finalArr['Hyderabad']['totalChqTrAmt']+=$ChqRes['totalAmount']; 
			$finalArr['Hyderabad']['totalChqTrQty']+=$ChqRes['count']; 
		}
		
		elseif(in_array($ChqRes['CityId'],$ChennaiCts['cities']))
		{ 
			$finalArr['Chennai']['totalChqTrAmt']+=$ChqRes['totalAmount'];
			$finalArr['Chennai']['totalChqTrQty']+=$ChqRes['count']; 
		}
		
		elseif(in_array($ChqRes['CityId'],$BengaluruCts['cities']))
		{ 
			$finalArr['Bengaluru']['totalChqTrAmt']+=$ChqRes['totalAmount']; 
			$finalArr['Bengaluru']['totalChqTrQty']+=$ChqRes['count'];
		}
		
		elseif(in_array($ChqRes['CityId'],$DelhiCts['cities']) || in_array($ChqRes['StateId'],$DelhiCts['states']))
		{ 
			$finalArr['Delhi-NCR']['totalChqTrAmt']+=$ChqRes['totalAmount'];
			$finalArr['Delhi-NCR']['totalChqTrQty']+=$ChqRes['count']; 
		}
		
		elseif(in_array($ChqRes['CityId'],$PuneCts['cities']))
		{ 
			$finalArr['Pune']['totalChqTrAmt']+=$ChqRes['totalAmount'];
			$finalArr['Pune']['totalChqTrQty']+=$ChqRes['count']; 
		}
		
		elseif(in_array($ChqRes['CityId'],$MumbaiCts['cities']))
		{ 
			$finalArr['Mumbai']['totalChqTrAmt']+=$ChqRes['totalAmount'];
			$finalArr['Mumbai']['totalChqTrQty']+=$ChqRes['count']; 
		}
		
		else
		{ 
			$finalArr['Other']['totalChqTrAmt']+=$ChqRes['totalAmount']; 
			$finalArr['Other']['totalChqTrQty']+=$ChqRes['count']; 
		}
	}
	
	
	//list of Total Users by citywise
	$TotSignedUpUsersCond=" and s.signupdate >= '".$fromDt."' AND s.signupdate <= '".$toDt."' ".$SalesId;
	$totalSignedUpUsersRES=$cCtrl->getTotalUsersSignedUpByCity($TotSignedUpUsersCond);
	
	while($totSignedUpUsersRes=$totalSignedUpUsersRES->fetch_assoc())
	{
		if(in_array($totSignedUpUsersRes['CityId'],$HydCts['cities'])){ $finalArr['Hyderabad']['totalSignedUpUsers']+=$totSignedUpUsersRes['totalSignedUpUsers']; }
		
		elseif(in_array($totSignedUpUsersRes['CityId'],$ChennaiCts['cities'])){ $finalArr['Chennai']['totalSignedUpUsers']+=$totSignedUpUsersRes['totalSignedUpUsers']; }
		
		elseif(in_array($totSignedUpUsersRes['CityId'],$BengaluruCts['cities'])){ $finalArr['Bengaluru']['totalSignedUpUsers']+=$totSignedUpUsersRes['totalSignedUpUsers']; }
		
		elseif(in_array($totSignedUpUsersRes['CityId'],$DelhiCts['cities']) || in_array($totSignedUpUsersRes['StateId'],$DelhiCts['states'])){ $finalArr['Delhi-NCR']['totalSignedUpUsers']+=$totSignedUpUsersRes['totalSignedUpUsers']; }
		
		elseif(in_array($totSignedUpUsersRes['CityId'],$PuneCts['cities'])){ $finalArr['Pune']['totalSignedUpUsers']+=$totSignedUpUsersRes['totalSignedUpUsers']; }
		
		elseif(in_array($totSignedUpUsersRes['CityId'],$MumbaiCts['cities'])){ $finalArr['Mumbai']['totalSignedUpUsers']+=$totSignedUpUsersRes['totalSignedUpUsers']; }
		
		else{ $finalArr['Other']['totalSignedUpUsers']+=$totSignedUpUsersRes['totalSignedUpUsers']; }
	}
	
	
	//list of Total Free Users by citywise
	$TotFreeUsersCond=" and s.signupdate >= '".$fromDt."' AND s.signupdate <= '".$toDt."' ".$SalesId;
	$totalFreeUsersRES=$cCtrl->getTotalFreeUsersSignedUpByCity($TotFreeUsersCond);
	
	while($totFreeUsersRes=$totalFreeUsersRES->fetch_assoc())
	{
		if(in_array($totFreeUsersRes['CityId'],$HydCts['cities'])){ $finalArr['Hyderabad']['totalFreeUsers']+=$totFreeUsersRes['totalFreeUsers']; }
		
		elseif(in_array($totFreeUsersRes['CityId'],$ChennaiCts['cities'])){ $finalArr['Chennai']['totalFreeUsers']+=$totFreeUsersRes['totalFreeUsers']; }
		
		elseif(in_array($totFreeUsersRes['CityId'],$BengaluruCts['cities'])){ $finalArr['Bengaluru']['totalFreeUsers']+=$totFreeUsersRes['totalFreeUsers']; }
		
		elseif(in_array($totFreeUsersRes['CityId'],$DelhiCts['cities']) || in_array($totFreeUsersRes['StateId'],$DelhiCts['states'])){ $finalArr['Delhi-NCR']['totalFreeUsers']+=$totFreeUsersRes['totalFreeUsers']; }
		
		elseif(in_array($totFreeUsersRes['CityId'],$PuneCts['cities'])){ $finalArr['Pune']['totalFreeUsers']+=$totFreeUsersRes['totalFreeUsers']; }
		
		elseif(in_array($totFreeUsersRes['CityId'],$MumbaiCts['cities'])){ $finalArr['Mumbai']['totalFreeUsers']+=$totFreeUsersRes['totalFreeUsers']; }
		
		else{ $finalArr['Other']['totalFreeUsers']+=$totFreeUsersRes['totalFreeUsers']; }
	}
	
	
	//list of Total Paid Users by citywise
	$TotPaidUsersCond=" and s.signupdate >= '".$fromDt."' AND s.signupdate <= '".$toDt."' ";
	$totalPaidUsersRES=$cCtrl->getTotalPaidUsersSignedUpByCity($TotPaidUsersCond);
	
	while($totPaidUsersRes=$totalPaidUsersRES->fetch_assoc())
	{
		if(in_array($totPaidUsersRes['CityId'],$HydCts['cities'])){ $finalArr['Hyderabad']['totalPaidUsers']+=$totPaidUsersRes['totalPaidUsers']; }
		
		elseif(in_array($totPaidUsersRes['CityId'],$ChennaiCts['cities'])){ $finalArr['Chennai']['totalPaidUsers']+=$totPaidUsersRes['totalPaidUsers']; }
		
		elseif(in_array($totPaidUsersRes['CityId'],$BengaluruCts['cities'])){ $finalArr['Bengaluru']['totalPaidUsers']+=$totPaidUsersRes['totalPaidUsers']; }
		
		elseif(in_array($totPaidUsersRes['CityId'],$DelhiCts['cities']) || in_array($totPaidUsersRes['StateId'],$DelhiCts['states'])){ $finalArr['Delhi-NCR']['totalPaidUsers']+=$totPaidUsersRes['totalPaidUsers']; }
		
		elseif(in_array($totPaidUsersRes['CityId'],$PuneCts['cities'])){ $finalArr['Pune']['totalPaidUsers']+=$totPaidUsersRes['totalPaidUsers']; }
		
		elseif(in_array($totPaidUsersRes['CityId'],$MumbaiCts['cities'])){ $finalArr['Mumbai']['totalPaidUsers']+=$totPaidUsersRes['totalPaidUsers']; }
		
		else{ $finalArr['Other']['totalPaidUsers']+=$totPaidUsersRes['totalPaidUsers']; }
	}
	
	
	//list of Total Users by citywise
	$TotUsersCond=" and u.signupdate between '".$fromDt."' AND '".$toDt."' ";
	$totalUsersRES=$cCtrl->getTotalUsersByCity($TotUsersCond);
	
	while($totUsersRes=$totalUsersRES->fetch_assoc())
	{
		if(in_array($totUsersRes['CityId'],$HydCts['cities'])){ $finalArr['Hyderabad']['totalUsers']+=$totUsersRes['totalUsers']; }
		
		elseif(in_array($totUsersRes['CityId'],$ChennaiCts['cities'])){ $finalArr['Chennai']['totalUsers']+=$totUsersRes['totalUsers']; }
		
		elseif(in_array($totUsersRes['CityId'],$BengaluruCts['cities'])){ $finalArr['Bengaluru']['totalUsers']+=$totUsersRes['totalUsers']; }
		
		elseif(in_array($totUsersRes['CityId'],$DelhiCts['cities']) || in_array($totUsersRes['StateId'],$DelhiCts['states'])){ $finalArr['Delhi-NCR']['totalUsers']+=$totUsersRes['totalUsers']; }
		
		elseif(in_array($totUsersRes['CityId'],$PuneCts['cities'])){ $finalArr['Pune']['totalUsers']+=$totUsersRes['totalUsers']; }
		
		elseif(in_array($totUsersRes['CityId'],$MumbaiCts['cities'])){ $finalArr['Mumbai']['totalUsers']+=$totUsersRes['totalUsers']; }
		
		else{ $finalArr['Other']['totalUsers']+=$totUsersRes['totalUsers']; }
	}
	
	
	//list of Total Users by citywise
	$TotOrgsCond=" and u.signupdate between '".$fromDt."' AND '".$toDt."' ";
	$totalOrgsRES=$cCtrl->getTotalOrgsByCity($TotOrgsCond);
	
	while($totOrgsRes=$totalOrgsRES->fetch_assoc())
	{
		if(in_array($totOrgsRes['CityId'],$HydCts['cities'])){ $finalArr['Hyderabad']['totalOrgs']+=$totOrgsRes['totalOrgs']; }
		
		elseif(in_array($totOrgsRes['CityId'],$ChennaiCts['cities'])){ $finalArr['Chennai']['totalOrgs']+=$totOrgsRes['totalOrgs']; }
		
		elseif(in_array($totOrgsRes['CityId'],$BengaluruCts['cities'])){ $finalArr['Bengaluru']['totalOrgs']+=$totOrgsRes['totalOrgs']; }
		
		elseif(in_array($totOrgsRes['CityId'],$DelhiCts['cities']) || in_array($totOrgsRes['StateId'],$DelhiCts['states'])){ $finalArr['Delhi-NCR']['totalOrgs']+=$totOrgsRes['totalOrgs']; }
		
		elseif(in_array($totOrgsRes['CityId'],$PuneCts['cities'])){ $finalArr['Pune']['totalOrgs']+=$totOrgsRes['totalOrgs']; }
		
		elseif(in_array($totOrgsRes['CityId'],$MumbaiCts['cities'])){ $finalArr['Mumbai']['totalOrgs']+=$totOrgsRes['totalOrgs']; }
		
		else{ $finalArr['Other']['totalOrgs']+=$totOrgsRes['totalOrgs']; }
	}
	
	
	
	//list of Total events by citywise
	$TotEventsCond=" and e.registrationdate between '".$fromDt."' AND '".$toDt."' ";
	$totalEventsRES=$cCtrl->getTotalEventsByCity($TotEventsCond);
	
	while($totEventsRes=$totalEventsRES->fetch_assoc())
	{
		if(in_array($totEventsRes['CityId'],$HydCts['cities'])){ $finalArr['Hyderabad']['totalEvents']+=$totEventsRes['totalEvents']; }
		
		elseif(in_array($totEventsRes['CityId'],$ChennaiCts['cities'])){ $finalArr['Chennai']['totalEvents']+=$totEventsRes['totalEvents']; }
		
		elseif(in_array($totEventsRes['CityId'],$BengaluruCts['cities'])){ $finalArr['Bengaluru']['totalEvents']+=$totEventsRes['totalEvents']; }
		
		elseif(in_array($totEventsRes['CityId'],$DelhiCts['cities']) || in_array($totEventsRes['StateId'],$DelhiCts['states'])){ $finalArr['Delhi-NCR']['totalEvents']+=$totEventsRes['totalEvents']; }
		
		elseif(in_array($totEventsRes['CityId'],$PuneCts['cities'])){ $finalArr['Pune']['totalEvents']+=$totEventsRes['totalEvents']; }
		
		elseif(in_array($totEventsRes['CityId'],$MumbaiCts['cities'])){ $finalArr['Mumbai']['totalEvents']+=$totEventsRes['totalEvents']; }
		
		else{ $finalArr['Other']['totalEvents']+=$totEventsRes['totalEvents']; }
	}
	
	
	//list of Total free events by citywise
	$TotFreeEventsCond=" and e.registrationdate between '".$fromDt."' AND '".$toDt."' ";
	$totalFreeEventsRES=$cCtrl->getTotalFreeEventsByCity($TotFreeEventsCond);
	
	while($totFreeEventsRes=$totalFreeEventsRES->fetch_assoc())
	{
		if(in_array($totFreeEventsRes['CityId'],$HydCts['cities'])){ $finalArr['Hyderabad']['totalFreeEvents']+=$totFreeEventsRes['totalFreeEvents']; }
		
		elseif(in_array($totFreeEventsRes['CityId'],$ChennaiCts['cities'])){ $finalArr['Chennai']['totalFreeEvents']+=$totFreeEventsRes['totalFreeEvents']; }
		
		elseif(in_array($totFreeEventsRes['CityId'],$BengaluruCts['cities'])){ $finalArr['Bengaluru']['totalFreeEvents']+=$totFreeEventsRes['totalFreeEvents']; }
		
		elseif(in_array($totFreeEventsRes['CityId'],$DelhiCts['cities']) || in_array($totFreeEventsRes['StateId'],$DelhiCts['states'])){ $finalArr['Delhi-NCR']['totalFreeEvents']+=$totFreeEventsRes['totalFreeEvents']; }
		
		elseif(in_array($totFreeEventsRes['CityId'],$PuneCts['cities'])){ $finalArr['Pune']['totalFreeEvents']+=$totFreeEventsRes['totalFreeEvents']; }
		
		elseif(in_array($totFreeEventsRes['CityId'],$MumbaiCts['cities'])){ $finalArr['Mumbai']['totalFreeEvents']+=$totFreeEventsRes['totalFreeEvents']; }
		
		else{ $finalArr['Other']['totalFreeEvents']+=$totFreeEventsRes['totalFreeEvents']; }
	}
	
	
	//list of Total paid events by citywise
	$TotPaidEventsCond=" and e.registrationdate between '".$fromDt."' AND '".$toDt."' ";
	$totalPaidEventsRES=$cCtrl->getTotalPaidEventsByCity($TotPaidEventsCond);
	
	while($totPaidEventsRes=$totalPaidEventsRES->fetch_assoc())
	{
		if(in_array($totPaidEventsRes['CityId'],$HydCts['cities'])){ $finalArr['Hyderabad']['totalPaidEvents']+=$totPaidEventsRes['totalPaidEvents']; }
		
		elseif(in_array($totPaidEventsRes['CityId'],$ChennaiCts['cities'])){ $finalArr['Chennai']['totalPaidEvents']+=$totPaidEventsRes['totalPaidEvents']; }
		
		elseif(in_array($totPaidEventsRes['CityId'],$BengaluruCts['cities'])){ $finalArr['Bengaluru']['totalPaidEvents']+=$totPaidEventsRes['totalPaidEvents']; }
		
		elseif(in_array($totPaidEventsRes['CityId'],$DelhiCts['cities']) || in_array($totPaidEventsRes['StateId'],$DelhiCts['states'])){ $finalArr['Delhi-NCR']['totalPaidEvents']+=$totPaidEventsRes['totalPaidEvents']; }
		
		elseif(in_array($totPaidEventsRes['CityId'],$PuneCts['cities'])){ $finalArr['Pune']['totalPaidEvents']+=$totPaidEventsRes['totalPaidEvents']; }
		
		elseif(in_array($totPaidEventsRes['CityId'],$MumbaiCts['cities'])){ $finalArr['Mumbai']['totalPaidEvents']+=$totPaidEventsRes['totalPaidEvents']; }
		
		else{ $finalArr['Other']['totalPaidEvents']+=$totPaidEventsRes['totalPaidEvents']; }
	}
	
	
	//list of Total no reg events by citywise
	$TotNoRegEventsCond=" and e.registrationdate between '".$fromDt."' AND '".$toDt."' ";
	$totalNoRegEventsRES=$cCtrl->getTotalNoRegEventsByCity($TotNoRegEventsCond);
	
	while($totNoRegEventsRes=$totalNoRegEventsRES->fetch_assoc())
	{
		if(in_array($totNoRegEventsRes['CityId'],$HydCts['cities'])){ $finalArr['Hyderabad']['totalNoRegEvents']+=$totNoRegEventsRes['totalNoRegEvents']; }
		
		elseif(in_array($totNoRegEventsRes['CityId'],$ChennaiCts['cities'])){ $finalArr['Chennai']['totalNoRegEvents']+=$totNoRegEventsRes['totalNoRegEvents']; }
		
		elseif(in_array($totNoRegEventsRes['CityId'],$BengaluruCts['cities'])){ $finalArr['Bengaluru']['totalNoRegEvents']+=$totNoRegEventsRes['totalNoRegEvents']; }
		
		elseif(in_array($totNoRegEventsRes['CityId'],$DelhiCts['cities']) || in_array($totNoRegEventsRes['StateId'],$DelhiCts['states'])){ $finalArr['Delhi-NCR']['totalNoRegEvents']+=$totNoRegEventsRes['totalNoRegEvents']; }
		
		elseif(in_array($totNoRegEventsRes['CityId'],$PuneCts['cities'])){ $finalArr['Pune']['totalNoRegEvents']+=$totNoRegEventsRes['totalNoRegEvents']; }
		
		elseif(in_array($totNoRegEventsRes['CityId'],$MumbaiCts['cities'])){ $finalArr['Mumbai']['totalNoRegEvents']+=$totNoRegEventsRes['totalNoRegEvents']; }
		
		else{ $finalArr['Other']['totalNoRegEvents']+=$totNoRegEventsRes['totalNoRegEvents']; }
	}
	
	
	//list of Total Unique events by citywise
	$TotUnqEventsCond=" and e.registrationdate between '".$fromDt."' AND '".$toDt."' ";
	$totalUnqEventsRES=$cCtrl->getTotalUnqEventsByCity($TotUnqEventsCond);
	
	while($totUnqEventsRes=$totalUnqEventsRES->fetch_assoc())
	{
		if(in_array($totUnqEventsRes['CityId'],$HydCts['cities'])){ $finalArr['Hyderabad']['totalUnqEvents']+=$totUnqEventsRes['totalUnqEvents']; }
		
		elseif(in_array($totUnqEventsRes['CityId'],$ChennaiCts['cities'])){ $finalArr['Chennai']['totalUnqEvents']+=$totUnqEventsRes['totalUnqEvents']; }
		
		elseif(in_array($totUnqEventsRes['CityId'],$BengaluruCts['cities'])){ $finalArr['Bengaluru']['totalUnqEvents']+=$totUnqEventsRes['totalUnqEvents']; }
		
		elseif(in_array($totUnqEventsRes['CityId'],$DelhiCts['cities']) || in_array($totUnqEventsRes['StateId'],$DelhiCts['states'])){ $finalArr['Delhi-NCR']['totalUnqEvents']+=$totUnqEventsRes['totalUnqEvents']; }
		
		elseif(in_array($totUnqEventsRes['CityId'],$PuneCts['cities'])){ $finalArr['Pune']['totalUnqEvents']+=$totUnqEventsRes['totalUnqEvents']; }
		
		elseif(in_array($totUnqEventsRes['CityId'],$MumbaiCts['cities'])){ $finalArr['Mumbai']['totalUnqEvents']+=$totUnqEventsRes['totalUnqEvents']; }
		
		else{ $finalArr['Other']['totalUnqEvents']+=$totUnqEventsRes['totalUnqEvents']; }
	}
	
	
	//list of Total Unique users by citywise
	$TotUnqUsersCond=" and s.signupdate between '".$fromDt."' AND '".$toDt."' ";
	$totalUnqUsersRES=$cCtrl->getTotalUnqUsersByCity($TotUnqUsersCond);
	
	while($totUnqUsersRes=$totalUnqUsersRES->fetch_assoc())
	{
		if(in_array($totUnqUsersRes['CityId'],$HydCts['cities'])){ $finalArr['Hyderabad']['totalUnqUsers']+=$totUnqUsersRes['totalUnqUsers']; }
		
		elseif(in_array($totUnqUsersRes['CityId'],$ChennaiCts['cities'])){ $finalArr['Chennai']['totalUnqUsers']+=$totUnqUsersRes['totalUnqUsers']; }
		
		elseif(in_array($totUnqUsersRes['CityId'],$BengaluruCts['cities'])){ $finalArr['Bengaluru']['totalUnqUsers']+=$totUnqUsersRes['totalUnqUsers']; }
		
		elseif(in_array($totUnqUsersRes['CityId'],$DelhiCts['cities']) || in_array($totUnqUsersRes['StateId'],$DelhiCts['states'])){ $finalArr['Delhi-NCR']['totalUnqUsers']+=$totUnqUsersRes['totalUnqUsers']; }
		
		elseif(in_array($totUnqUsersRes['CityId'],$PuneCts['cities'])){ $finalArr['Pune']['totalUnqUsers']+=$totUnqUsersRes['totalUnqUsers']; }
		
		elseif(in_array($totUnqUsersRes['CityId'],$MumbaiCts['cities'])){ $finalArr['Mumbai']['totalUnqUsers']+=$totUnqUsersRes['totalUnqUsers']; }
		
		else{ $finalArr['Other']['totalUnqUsers']+=$totUnqUsersRes['totalUnqUsers']; }
	}
	
	
	
	/*echo "<pre>";
	print_r($finalArr);
	echo "</pre>";*/
	
	
	$tableData=NULL;
	
	$tableData.='<table  border="1" cellpadding="2" cellspacing="0" width="90%"><thead>';
	$tableData.='<tr bgcolor="#94D2F3"><td></td>';
	foreach($ctrlCities as $cityData)
	{
		$tableData.='<th>'.$cityData[1].'</th>';
	}
	$tableData.='<th>Other</th><th>All</th></tr></thead>';
	$tableData.='</tr></thead>';
	
	$tableData.='<tbody>';
	
	
	$totalAmt=$totalQty=$grandTotalAmt=$grandTotalQty=0;
	
	
	/*transactions amount*/
	$tableData.='<tr>
					<td>Total Card Transactions Amount :</td>';
					foreach($finalArr as $cityReport)
					{
						 
						$tableData.='<td>'.$cityReport['totalCardTrAmt'].'</td>';
						$totalAmt+=$cityReport['totalCardTrAmt'];
						$grandTotalAmt+=$cityReport['totalCardTrAmt'];
					}
					
	$tableData.='<td>'.$totalAmt.'</td></tr>';
	
	$totalAmt=0;
	$tableData.='<tr>
					<td>Total Cheque Transactions Amount :</td>';
					foreach($finalArr as $cityReport)
					{
						 
						$tableData.='<td>'.$cityReport['totalChqTrAmt'].'</td>';
						$totalAmt+=$cityReport['totalChqTrAmt'];
						$grandTotalAmt+=$cityReport['totalChqTrAmt'];
					}
					
	$tableData.='<td>'.$totalAmt.'</td></tr>';
	
	$totalAmt=0;
	$tableData.='<tr>
					<td>Total PayatCounter Amount :</td>';
					foreach($finalArr as $cityReport)
					{
						 
						$tableData.='<td>'.$cityReport['totalPACTrAmt'].'</td>';
						$totalAmt+=$cityReport['totalPACTrAmt'];
						$grandTotalAmt+=$cityReport['totalPACTrAmt'];
					}
	$tableData.='<td>'.$totalAmt.'</td></tr>';
	
	
	$totalAmt=0;
	$tableData.='<tr>
					<td>Total CashonDelivery Amount :</td>';
					foreach($finalArr as $cityReport)
					{
						 
						$tableData.='<td>'.$cityReport['totalCODTrAmt'].'</td>';
						$totalAmt+=$cityReport['totalCODTrAmt'];
						$grandTotalAmt+=$cityReport['totalCODTrAmt'];
					}
	$tableData.='<td>'.$totalAmt.'</td></tr>';
	
	$totalAmt=$grandTotalAmt=0;
	$tableData.='<tr bgcolor="#00CCCC">
					<td><b>Total Transaction Amount:</b></td>';
					foreach($finalArr as $cityReport)
					{
						$totalAmt=$cityReport['totalCardTrAmt']+$cityReport['totalChqTrAmt']+$cityReport['totalPACTrAmt']+$cityReport['totalCODTrAmt'];
						$tableData.='<td><b>'.$totalAmt.'</b></td>';
						$grandTotalAmt+=$cityReport['totalCardTrAmt']+$cityReport['totalChqTrAmt']+$cityReport['totalPACTrAmt']+$cityReport['totalCODTrAmt'];
					}
	
	$tableData.='<td><b>'.$grandTotalAmt.'</b></td></tr>';
	
	/*transactions amount*/
	
	
	
	/*transactions count*/
	$tableData.='<tr>
					<td>Total Card Transactions :</td>';
					foreach($finalArr as $cityReport)
					{
						 
						$tableData.='<td>'.$cityReport['totalCardTrQty'].'</td>';
						$totalQty+=$cityReport['totalCardTrQty'];
						$grandTotalQty+=$cityReport['totalCardTrQty'];
					}
	$tableData.='<td>'.$totalQty.'</td></tr>';
	
	$totalQty=0;
	$tableData.='<tr>
					<td>Total Pay at Counter :</td>';
					foreach($finalArr as $cityReport)
					{
						 
						$tableData.='<td>'.$cityReport['totalPACTrQty'].'</td>';
						$totalQty+=$cityReport['totalPACTrQty'];
						$grandTotalQty+=$cityReport['totalPACTrQty'];
					}
	$tableData.='<td>'.$totalQty.'</td></tr>';
	
	$totalQty=0;
	$tableData.='<tr>
					<td>Total COD Transactions :</td>';
					foreach($finalArr as $cityReport)
					{
						 
						$tableData.='<td>'.$cityReport['totalCODTrQty'].'</td>';
						$totalQty+=$cityReport['totalCODTrQty'];
						$grandTotalQty+=$cityReport['totalCODTrQty'];
					}
	$tableData.='<td>'.$totalQty.'</td></tr>';
	
	$totalQty=0;
	$tableData.='<tr>
					<td>Total Cheque Transaction :</td>';
					foreach($finalArr as $cityReport)
					{
						 
						$tableData.='<td>'.$cityReport['totalChqTrQty'].'</td>';
						$totalQty+=$cityReport['totalChqTrQty'];
						$grandTotalQty+=$cityReport['totalChqTrQty'];
					}
	$tableData.='<td>'.$totalQty.'</td></tr>';
	/*transactions count*/
	
	
	$totalQty=0;
	$tableData.='<tr>
					<td>Total Delegates Signed up for Tickets :</td>';
					foreach($finalArr as $cityReport)
					{ 
						$tableData.='<td>'.$cityReport['totalSignedUpUsers'].'</td>';
						$totalQty+=$cityReport['totalSignedUpUsers'];
					}
	$tableData.='<td>'.$totalQty.'</td></tr>';
	
	
	$totalQty=0;
	$tableData.='<tr>
					<td>Delegates Signed up for Free Tickets :</td>';
					foreach($finalArr as $cityReport)
					{ 
						$tableData.='<td>'.$cityReport['totalFreeUsers'].'</td>';
						$totalQty+=$cityReport['totalFreeUsers'];
					}
	$tableData.='<td>'.$totalQty.'</td></tr>';
	
	
	$totalQty=0;
	$tableData.='<tr>
					<td>Delegates Signed up for Paid Tickets :</td>';
					foreach($finalArr as $cityReport)
					{ 
						$tableData.='<td>'.$cityReport['totalPaidUsers'].'</td>';
						$totalQty+=$cityReport['totalPaidUsers'];
					}
	$tableData.='<td>'.$totalQty.'</td></tr>';
	
	
	$totalQty=0;
	$tableData.='<tr>
					<td>Total Users Signed up :</td>';
					foreach($finalArr as $cityReport)
					{ 
						$tableData.='<td>'.$cityReport['totalUsers'].'</td>';
						$totalQty+=$cityReport['totalUsers'];
					}
	$tableData.='<td>'.$totalQty.'</td></tr>';
	
	
	$totalQty=0;
	$tableData.='<tr>
					<td>Total Organizers Registred :</td>';
					foreach($finalArr as $cityReport)
					{ 
						$tableData.='<td>'.$cityReport['totalOrgs'].'</td>';
						$totalQty+=$cityReport['totalOrgs'];
					}
	$tableData.='<td>'.$totalQty.'</td></tr>';
	
	
	
	$totalQty=0;
	$tableData.='<tr>
					<td>Total Events Added :</td>';
					foreach($finalArr as $cityReport)
					{ 
						$tableData.='<td>'.$cityReport['totalEvents'].'</td>';
						$totalQty+=$cityReport['totalEvents'];
					}
	$tableData.='<td>'.$totalQty.'</td></tr>';
	
	
	$totalQty=0;
	$tableData.='<tr>
					<td>Paid Events :</td>';
					foreach($finalArr as $cityReport)
					{ 
						$tableData.='<td>'.$cityReport['totalPaidEvents'].'</td>';
						$totalQty+=$cityReport['totalPaidEvents'];
					}
	$tableData.='<td>'.$totalQty.'</td></tr>';
	
	
	$totalQty=0;
	$tableData.='<tr>
					<td>Free Events :</td>';
					foreach($finalArr as $cityReport)
					{ 
						$tableData.='<td>'.$cityReport['totalFreeEvents'].'</td>';
						$totalQty+=$cityReport['totalFreeEvents'];
					}
	$tableData.='<td>'.$totalQty.'</td></tr>';
	
	
	$totalQty=0;
	$tableData.='<tr>
					<td>No Registration Events :</td>';
					foreach($finalArr as $cityReport)
					{ 
						$tableData.='<td>'.$cityReport['totalNoRegEvents'].'</td>';
						$totalQty+=$cityReport['totalNoRegEvents'];
					}
	$tableData.='<td>'.$totalQty.'</td></tr>';
	
	
	$totalQty=0;
	$tableData.='<tr>
					<td>No Unique Events :</td>';
					foreach($finalArr as $cityReport)
					{ 
						$tableData.='<td>'.$cityReport['totalUnqEvents'].'</td>';
						$totalQty+=$cityReport['totalUnqEvents'];
					}
	$tableData.='<td>'.$totalQty.'</td></tr>';
	
	
	$totalQty=0;
	$tableData.='<tr>
					<td>No Unique Organizers :</td>';
					foreach($finalArr as $cityReport)
					{ 
						$tableData.='<td>'.$cityReport['totalUnqUsers'].'</td>';
						$totalQty+=$cityReport['totalUnqUsers'];
					}
	$tableData.='<td>'.$totalQty.'</td></tr>';
	
	
	
	
	
	
	
	$tableData.='</tbody></table>';
	
	//echo $tableData."<br><br>";
	
	
	
	if(isset($_GET['runNow']))
	{
		
		include("../includes/functions.php");
		$commonFunctions= new functions();
		
		//echo $currentCity."<br>".$Msg."<br><hr>";
		
		//$to = 'shashi.enjapuri@gmail.com,sudhera99@gmail.com,durgeshmishra2525@gmail.com';
		//$to = 'shashi.enjapuri@gmail.com';
		$subject = '[MeraEvents] Sales Report Details by City From: '.$fromDt.' To: '.$toDt;
		$message = 'Dear Team,<br /><br />Sales Report Details by City From: '.$fromDt.' To: '.$toDt.'<br /><br /><br />'.$tableData.'<br /><br />Regards,<br>Meraevents Team';
		
		
		$cc=$content=$filename=$bcc=$replyto=NULL;
		//$to='sales@meraevents.com';
		$cc='shashi.enjapuri@gmail.com,sudhera99@gmail.com,durgeshmishra2525@gmail.com';
		$bcc='qison@meraevents.com';
		//$to = 'sudhera99@gmail.com,shashi.enjapuri@gmail.com,durgeshmishra2525@gmail.com';
		//$cc='shashidhar.enjapuri@qison.com,sudhera.bagineni@qison.com,durgesh.mishra@qison.com';
		$from='MeraEvents<admin@meraevents.com>';
		$commonFunctions->sendEmail($to,$cc,$bcc,$from,$replyto,$subject,$message,$content,$filename);
		exit;
		
	}
	
	
	
	include 'templates/report_sales_city.tpl.php';
?>