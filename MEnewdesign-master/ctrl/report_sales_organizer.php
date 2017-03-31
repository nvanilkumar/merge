<?php
	
	include_once("MT/cGlobali.php");
	include 'loginchk.php';
	
	$Global = new cGlobali();
	include("MT/cCtrl.php");
$cCtrl = new cCtrl();	
include_once("includes/common_functions.php");
$common=new functions();
		
    //sales person info
$salesPersons=$cCtrl->getSalesPersonData();		
$organizerCityQry="select c.id,c.name from organizer o INNER JOIN city c on c.id=o.cityid where c.status=1 and c.deleted=0 and c.name not in('Other','-- Not From India --') group by c.name";
$resOrganizerCity=$Global->SelectQuery($organizerCityQry,MYSQLI_ASSOC);
		$MsgCountryExist = '';
		
		//CRETAE THE YESTERDAYS START / END DATE
$yesterdayDate=date ("Y-m-d", strtotime("-1 day"));
$SDt=$EDt=date ("d-M-Y", strtotime("-1 day"));
$fromDt = $yesterdayDate.' 00:00:01';
$fromDt =$common->convertTime($fromDt, DEFAULT_TIMEZONE);
$toDt = $yesterdayDate.' 23:59:59';
$toDt =$common->convertTime($toDt, DEFAULT_TIMEZONE);

$citySql=$SalesId=NULL;
$ExtraCharge=false;
	$organizerCity='allcities';
        $organizerQry='';
	if(isset($_POST['formSubmit']))
{
	//print_r($_POST); exit;
	 //dates
	 $SDt = str_replace('/', '-', $_POST['txtSDt']);
	 $EDt= str_replace('/', '-', $_POST['txtEDt']);
	if(strlen($SDt)>0)
	{
		$fromDt = date ("Y-m-d", strtotime($SDt)).' 00:00:01';
                $fromDt =$common->convertTime($fromDt, DEFAULT_TIMEZONE);
	}else{
            $fromDt=date('Y-m-d').' 00:00:01';
        }
	
	if(strlen($EDt)>0)
	{
		$toDt = date ("Y-m-d", strtotime($EDt)).' 23:59:59';
                
	}
	else
	{
		$EDt=date ("d-M-Y", strtotime("now"));
		$toDt = date ("Y-m-d h:i:s", strtotime("now"));
                
	}
        $toDt =$common->convertTime($toDt, DEFAULT_TIMEZONE);
	//dates
	
	
	
	//ExtraCharge
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
	
	$organizerCity=$_REQUEST['organizerCity'];
}




	$TotalAmount = 0;
	$cntTransactionRES = 1;	
	
	
	
	//Display list of Successful Transactions
	 $cardTrCond=" and s.signupdate >= '".$fromDt."' AND s.signupdate <= '".$toDt."' ".$citySql." ".$SalesId;
	$TransactionRES=$cCtrl->getTransactionsByEventAndOrgWise($ExtraCharge,$cardTrCond,$organizerCity);
	//print_r($TransactionRES);exit;
        if(isset($_POST['exportReports'])){
            $finalArr=$userEventsArr=array();
            $totalQuanity=0;
            if($TransactionRES->num_rows>0){
            while($value=$TransactionRES->fetch_assoc()){
              //  print_r($value);
               $userEventsArr[$value['UserID']][$value['EventId']]['title']=$value['Title'];
               $finalArr[$value['UserID']]['events']=$userEventsArr[$value['UserID']];
               $finalArr[$value['UserID']]['totalqty']+=$value['tktQty'];
               $finalArr[$value['UserID']]['company']=$value['Company'];
               $finalArr[$value['UserID']]['username']=$value['UserName'];
               $finalArr[$value['UserID']]['cityname']=  isset($value['cityname'])?$value['cityname']:'';
               $finalArr[$value['UserID']]['totalAmount'][$value['code']]+=$value['totalAmount'];
            }
            //print_r($finalArr);exit;
            $file='userfiles/'.$common->appendTimeStamp('sales_by_organizer').'csv';
            $handle = fopen($file, 'wa+');
            $header=array('S.No','User Id','Event Organizer');
            if($organizerCity=='other' || $organizerCity=='allcities'){
                array_push($header, 'User City');
            }
            $header[]='No. of Events';
            $header[]=  str_pad("Event Name & ID", 20, " ", STR_PAD_BOTH);
            $header[]="Ticket Qty";
            $header[]="Amount (Rs.)";
            fwrite($handle, implode(',', $header).PHP_EOL);
            $sno=1;
            $rowData='';
            //print_r($finalArr);
            foreach ($finalArr as $key => $value) {
                $rowData.='"'.($sno++).'","'.$key.'","'.$value['company'].' ('.$value['username'].')"';
                if($organizerCity=='other' || $organizerCity=='allcities'){
                    $rowData.=',"'.$value['cityname'].'"';
                }
                $rowData.=',"'.count($value['events']).'"';
                $addedEvent=0;
                 $userEvents=count($value['events']);
                foreach ($value['events'] as $id => $eventData) {
                    $addedEvent++;
                    $rowData.=',"=>'.$eventData['title'].'"';
                    if($userEvents!=$addedEvent){
                        $rowData.=PHP_EOL.',,,';
                        if($organizerCity=='other' || $organizerCity=='allcities'){
                            $rowData.=',';
                        }
                    }else{
                        $totalQuanity+=$value['totalqty'];
                        $rowData.=',"'.$value['totalqty'].'","';
                        foreach ($value['totalAmount'] as $key => $value) {
                            $rowData.=($key.' '.$value)."  ";
                        }
                        $rowData.='"'.PHP_EOL;
                    }
                }
            }
            //print_r($rowData);exit;
            fwrite($handle, $rowData);
            //ob_end_clean();
            header('Content-Description: File Transfer');
            header("Content-Type:'application/force-download'");
            header('Content-Disposition: attachment; filename=' . basename($file));
            header('Content-Transfer-Encoding: binary');
            header('Expires: 0');
            header('Pragma: public');
            header('Content-Length: ' . filesize($file));
            readfile($file);exit;
            }else{
                $_SESSION['nodata']=true;
            }
        }

include 'templates/report_sales_organizer.tpl.php';
?>