<?php
include("commondbdetails.php");

    //include('../ctrl/includes/commondbdetails.php');
    include_once '../ctrl/includes/common_functions.php';
    include("../ctrl/MT/cGlobali.php");
    $cGlobali=new cGlobali();
    $commonFunctions=new functions();
    $_GET=$commonFunctions->stripData($_GET);
    $_POST=$commonFunctions->stripData($_POST);
    $_REQUEST=$commonFunctions->stripData($_REQUEST);
	
if($_GET['runNow']==1) 
{
    $modifiedUser=",modifiedby=".$commonFunctions->getCronUserDetails();
    
	$db_conn = mysqli_connect($DBServerName,$DBUserName,$DBPassword,$DBIniCatalog);
        $yesterdaySDate=  date('Y-m-d',  strtotime('-1 days')); //test on stage
         $TransactionQuery = "SELECT s.id,s.totalamount 'AMOUNT',s.paymenttransactionid,ifnull(epd.paymentid,0) as PaymentId,s.paymentstatus 'eChecked' 
	FROM eventsignup AS s 
	INNER JOIN event AS e ON s.eventid = e.id 
	LEFT OUTER JOIN ebspaymentdetail epd ON epd.eventsignupid=s.id AND epd.paymentid=s.paymenttransactionid 
	WHERE (s.totalamount != 0 AND (s.paymenttransactionid != 'A1'))  and s.paymentgatewayid =1 AND s.paymentstatus ='NotVerified' and s.signupdate>='".$yesterdaySDate."' 
	ORDER BY s.signupdate DESC";  
//        $TransactionRES=  mysqli_query($db_conn,$TransactionQuery);
         $TransactionRES=$cGlobali->justSelectQuery($TransactionQuery);
        $sessData=array();
        while($transByRow= mysqli_fetch_assoc($TransactionRES)){
            if($transByRow['PaymentId']>0 && $transByRow['eChecked']!='Captured'){
                        $ch=curl_init();
                        $data=array('Action'=>'capture','AccountID'=>'7300','SecretKey'=>'67624ee7bb021090f9c0ef1bb3dcd53f','Amount'=>$transByRow['AMOUNT'],'PaymentID'=>$transByRow['PaymentId']);
                        curl_setopt($ch,CURLOPT_URL,'https://secure.ebs.in/api/1_0');
                        curl_setopt($ch,CURLOPT_POST,true);
                        curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,false);
                        curl_setopt($ch,CURLOPT_POSTFIELDS,$data);
                        curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
                        $res=curl_exec($ch);
                        $resObj=simplexml_load_string($res);
                        if(!$res){
                                $sessData['failed1'].=$transByRow['id']." transaction failed because ".curl_error($ch)."<br>";
                        }else{
                                if(isset($resObj['error']) && !empty($resObj['error'])){
                                        $sessData['failed2'].=$transByRow['id']." failed because ".$resObj['error']."<br>";
                                }else{
                                        $settlementDate=$resObj['dateTime'];
                                        $UpQuery="update eventsignup set paymentstatus='Captured',settlementdate='".$settlementDate."'".$modifiedUser." where id=".$transByRow['id'];
                                        $ResUp= $cGlobali->ExecuteQuery($UpQuery);
                                       
                                        if($ResUp){
                                                $sessData['success'].=$transByRow['id'].",";
                                                $transId=$resObj['transactionId'];
                                                $statusCode=1;
                                                $statusMsg=$resObj['status'];
                                                
//                                                $insCaptureStausStmt=  mysqli_query($db_conn,"INSERT INTO ebspaymentdetail(eventsignupid,paymentid,transactionid,statuscode,statusmessage) VALUES(".$transByRow['id'].",".$transByRow['PaymentId'].",".$transId.",".$statusCode.",'".$statusMsg."')");
                                         
                                                $insertCaptureQuery="INSERT INTO ebspaymentdetail(eventsignupid,paymentid,transactionid,statuscode,statusmessage) VALUES(".$transByRow['id'].",".$transByRow['PaymentId'].",".$transId.",".$statusCode.",'".$statusMsg."')";
                                                $cGlobali->ExecuteQuery($insertCaptureQuery);
                                        }
                                }
                        }
                        curl_close($ch);
                }else{
                        $sessData['failed3'].=$transByRow['id'].',';
                }
        }
       /* if(strlen($sessData['failed3'])>0){
            $sessData['failed3'].= ' has failed as no payment id.Capture manually.<br>';
        }*/
        /*if(strlen($sessData['success'])>0){
            $sessData['success'].='Updation successfull';
        }*/
        $date=  date('Y-m-d H:i:s');
	$message='Dear Sir/Madam,<br>Reports of capture transactions done at '.$date.'<br/>';
        if(isset($sessData['failed1']) || isset($sessData['failed2']) || isset($sessData['failed3'])){
		$message.=$sessData['failed1'].'<br>'.$sessData['failed2'].'<br>'.'The following transactions did not get captured, please capture them Manually.<br>'.$sessData['failed3'].'<br>';

                $to = 'support@meraevents.com';
                //$to='ms.jagadish1@gmail.com';
                $bcc=$replyto=$content=$filename=NULL;
                $cc="sreekanthp@meraevents.com";
                $subject = '[MeraEvents] Reports of capture transactions done at '.$date;
                $from='MeraEvents<admin@meraevents.com>';
                $commonFunctions->sendEmail($to,$cc,$bcc,$from,$replyto,$subject,$message,$content,$filename);
        }
        
 mysqli_close($db_conn);
}
?>

