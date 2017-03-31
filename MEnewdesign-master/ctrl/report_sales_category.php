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

$sqlCat="select id,name from category where status =1 and featured =1 and deleted =0";
$resCat = $Globali->SelectQuery($sqlCat);

$finalArr=array();
foreach($resCat as $catData)
{
	$finalArr[$catData[1]]=array();
	$finalArr[$catData[1]]['totalCardTrAmt']=$finalArr[$catData[1]]['totalCardTrQty']=0;
    
	}
	
 
//print_r($finalArr); exit;






	
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
	
		if($currentCity=="Hyderabad"){ $citySql=" and e.cityid in (47, 448) "; }
		elseif($currentCity=="Chennai"){ $citySql=" and e.cityid=39 "; }
		elseif($currentCity=="Bengaluru"){ $citySql=" and e.cityid=37 "; }
		elseif($currentCity=="NewDelhi"){ $citySql=" and (e.stateid=53 or e.cityid in (330,331,383,408,484)) "; }
		elseif($currentCity=="Pune"){ $citySql=" and e.cityid=77 "; }
		elseif($currentCity=="Mumbai"){ $citySql=" and e.cityid in (14,393) "; }
		elseif($currentCity=="Other"){ $citySql=" and e.cityid not in (47, 448,14,393,39,37,330,331,383,408,484,77) and e.stateid NOT IN(53)"; }
			
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
	$cardTrCond=" and s.signupdate >= '".$fromDt."' AND s.signupdate <= '".$toDt."' ".$SalesId." ".$citySql;
	$TransactionRES=$cCtrl->getEventCardTrsByCategory($ExtraCharge,$cardTrCond);
	
	while($cardRes=$TransactionRES->fetch_assoc())
	{
		
		
		if($cardRes['categoryid']==1)
		{ 
			$finalArr['Entertainment']['totalCardTrAmt']+=$cardRes['totalAmount']; 
			$finalArr['Entertainment']['totalCardTrQty']+=$cardRes['count']; 
		}
		
		elseif($cardRes['categoryid']==2)
		{ 
			$finalArr['Professional']['totalCardTrAmt']+=$cardRes['totalAmount']; 
			$finalArr['Professional']['totalCardTrQty']+=$cardRes['count'];
		}
		
		elseif($cardRes['categoryid']==3)
		{ 
			$finalArr['Training']['totalCardTrAmt']+=$cardRes['totalAmount']; 
			$finalArr['Training']['totalCardTrQty']+=$cardRes['count'];
		}
		
		elseif($cardRes['categoryid']==4)
		{ 
			$finalArr['Campus']['totalCardTrAmt']+=$cardRes['totalAmount']; 
			$finalArr['Campus']['totalCardTrQty']+=$cardRes['count'];
		}
		
		elseif($cardRes['categoryid']==5)
		{ 
			$finalArr['Spiritual']['totalCardTrAmt']+=$cardRes['totalAmount']; 
			$finalArr['Spiritual']['totalCardTrQty']+=$cardRes['count'];
		}
		
		elseif($cardRes['categoryid']==6)
		{ 
			$finalArr['Trade Shows']['totalCardTrAmt']+=$cardRes['totalAmount']; 
			$finalArr['Trade Shows']['totalCardTrQty']+=$cardRes['count'];
		}
		
		elseif($cardRes['categoryid']==9)
		{ 
		 	$finalArr['Sports']['totalCardTrAmt']+=$cardRes['totalAmount']; 
		 	$finalArr['Sports']['totalCardTrQty']+=$cardRes['count'];
		}
	}
	
	
	
	
	
	
	
	
	
	
	/*echo "<pre>";
	print_r($finalArr);
	echo "</pre>";
	*/
	
	$tableData=NULL;
	
	$tableData.='<table  border="1" cellpadding="2" cellspacing="0" width="90%"><thead>';
	$tableData.='<tr bgcolor="#94D2F3"><td></td>';
	foreach($resCat as $catData)
	{
		$tableData.='<th>'.$catData[1].'</th>';
	}
	$tableData.='<th>All</th></tr></thead>';
	//$tableData.='</tr></thead>';
	
	$tableData.='<tbody>';
	
	
	$totalAmt=$totalQty=$grandTotalAmt=$grandTotalQty=0;
	
	
	/*transactions amount*/
	$tableData.='<tr>
					<td>Total Card Transactions Amount :</td>';
					foreach($finalArr as $catReport)
					{
						 
						$tableData.='<td>'.$catReport['totalCardTrAmt'].'</td>';
						$totalAmt+=$catReport['totalCardTrAmt'];
						$grandTotalAmt+=$catReport['totalCardTrAmt'];
					}
					
	$tableData.='<td>'.$totalAmt.'</td></tr>';
	
	$totalAmt=0;

	
	/*transactions count*/
	$tableData.='<tr>
					<td>Total Card Transactions :</td>';
					foreach($finalArr as $catReport)
					{
						 
						$tableData.='<td>'.$catReport['totalCardTrQty'].'</td>';
						$totalQty+=$catReport['totalCardTrQty'];
						$grandTotalQty+=$catReport['totalCardTrQty'];
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
	
	
	
	include 'templates/report_sales_category.tpl.php';
?>