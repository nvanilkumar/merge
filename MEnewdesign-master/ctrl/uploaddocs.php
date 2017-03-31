<?php
	session_start();
	error_reporting(-1);
	
	include 'loginchk.php';
	
	include_once("MT/cGlobali.php");
	include_once 'includes/common_functions.php';
        $commonFunctions = new functions();
	
	
        
	$Global = new cGlobali();
	
	//$base_path = '/home/meraeven/public_html/content';		
	
	$base_path = "../"._HTTP_Content;
	$msgActionStatus = '';
	$EventId=$_REQUEST['EventId'];


        
	if(isset($_REQUEST['delete']))
	{
		$Id = $_REQUEST['delete'];
		
		try
		{	
			$objBanner = new cBanners($Id);
			if($objBanner->Delete())
			{	
				//delete successful statement
				$msgActionStatus = "Banner Deleted Successfully.";
			}
		}
		catch (Exception $Ex)
		{
			echo $Ex->getMessage();
		}
	}		
	
	if(isset($_REQUEST['addDocs']))
	{
		$padvice = $pdone = $cyber = NULL;
		if($_FILES['padvice']['error']==0)			//If file is attached.
		{
                   
                     $adId = $commonFunctions->fileUpload($Global, $_FILES['padvice'], array('png', 'jpg', 'jpeg', 'gif'), "images/paymentdocs",'settlementpaymentadvice');
					 if ($adId !== false) 
                    $padviceId = $adId;                     
                     else
                     $padviceId = 0;          
                    
                        
                  
		}		
			
		if($_FILES['pdone']['error']==0)			//If file is attached.
		{
            
               $pdId = $commonFunctions->fileUpload($Global, $_FILES['pdone'], array('png', 'jpg', 'jpeg', 'gif'), "images/paymentdocs",'settlementcheque');
					 if ($pdId !== false) 
                    $pdoneId = $pdId;                     
                     else
                     $pdoneId = 0;     			
                    // End of uploading the doc to S3
		}
		
		if($_FILES['cyber']['error']==0)			//If file is attached.
		{
          $cbId = $commonFunctions->fileUpload($Global, $_FILES['cyber'], array('png', 'jpg', 'jpeg', 'gif'), "images/paymentdocs",'settlementcyberreciept');
					 if ($cbId !== false) 
                    $cyberId = $cbId;                     
                     else
                     $cyberId = 0;               
                    // End of uploading the doc to S3
		}
		
		$ccode = $_REQUEST['ccode'];
		$pmode=$_REQUEST['pmode'];
		$NetAmount=$_REQUEST['NetAmount'];
		$AmountP=$_REQUEST['AmountP'];
                $PDate_data=$_REQUEST['PDate'];
                $PDateExplode=  explode("/", $PDate_data);
                $PDate=$PDateExplode[2]."-".$PDateExplode[1]."-".$PDateExplode[0];
                $PType=$_REQUEST['PType'];
		$cts=date ("Y-m-d H:i:s", strtotime("now"));
		
		$insSql = "insert into settlement(eventid,paymentadvicefileid,chequefileid,cyberrecieptfileid,note,netamount,amountpaid,paymentdate,paymenttype,currencyid,status,createdby) values (".$EventId.",'".$padviceId."','".$pdoneId."','".$cyberId."','".$pmode."','".$NetAmount."','".$AmountP."','".$PDate."','".$PType."','".$ccode."','1','".$_SESSION['uid']."') ";
		if($Global->ExecuteQuery($insSql))
		{
			//new record inserted successfully. need to send the mail to organizer
			
			if($pdoneId>0 ||  $cyberId>0)
			{
				//sending the mail to organizer, if Admin uploads either Cheque or Cyber Reciept 
				// not required for Payment Advice
			
				$orgMailIdSql="select u.email,u.name,e.title,s.email 'salesPersonEmail',e.url from `user` as u 
				Inner Join event as e on e.ownerid=u.id
				inner join eventdetail ed on ed.eventid = e.id
				Left Join salesperson as s on ed.salespersonid = s.id
				left join eventsalespersonmapping ae on  ae.salesid = s.id
				where e.id='".$EventId."'";
				
				$orgDetails=$Global->SelectQuery($orgMailIdSql);
				
				if(count($orgDetails)>0){
					if(strlen($orgDetails[0]['email']))
					{
            $templateInputs['type'] = 'successUploadDoc';
            $templateInputs['mode'] = 'email';           
            //Sending promoter invitation mail
            $sqlQuery1="select * from messagetemplate where type='successUploadDoc';";
            $promoterTemplate=$Global->SelectQuery($sqlQuery1);
            $templateId = $promoterTemplate[0]['id'];
            $from = $promoterTemplate[0]['fromemailid'];
            $to = $inputArray[0]['email'];
            $templateMessage = $promoterTemplate[0]['template'];
            $templateMessage=str_replace('{userName}',ucfirst($orgDetails[0]['name']),$templateMessage);
            $templateMessage=str_replace('{title}',$orgDetails[0]['title'],$templateMessage);
            $templateMessage=str_replace('{year}',date('Y'),$templateMessage);
            $templateMessage=str_replace('{supportLink}',_HTTP_SITE_ROOT.'/support',$templateMessage);
            $message=str_replace('{paymentRecieptsLink}',_HTTP_SITE_ROOT.'/dashboard/payment/receipts/'.$EventId,$templateMessage);

						$subject="Payment Document for your event: ".$orgDetails[0]['title'];
						
						$mailto=$orgDetails[0]['email'];
						//$mailto='shashi.enjapuri@gmail.com';
						$cc=$bcc=$replyto=$content=$filename=NULL;
						
						$cc="delivery@meraevents.com";
						if(strlen($orgDetails[0]['salesPersonEmail'])>0){ $cc=",".$orgDetails[0]['salesPersonEmail']; }						
						$from='MeraEvents<admin@meraevents.com>';						
						$commonFunctions->sendEmail($mailto,$cc,$bcc,$from,$replyto,$subject,$message,$content,$filename,'ctrl');
						
					}
				}
				
				}
				
				$_SESSION['docUploaded'] = true;
				header("Location: paymentdocs.php?EventId=".$EventId); exit;
		}
        
	}
        
        //not allowing updates -msJ
	/*if($_REQUEST['Submit'] == "Update")
	{
        $Id=$_REQUEST['Id'];
		if($_FILES['padvice']['error']==0)			//If file is attached.
		{
			$padvice = $_FILES['padvice']['name'];
			$padvice=$EventId."_".$padvice;
			move_uploaded_file($_FILES['padvice']['tmp_name'],$base_path."/images/paymentdocs/".$padvice);
			
			$padvice = "/images/paymentdocs/".$padvice;
            $insSql = "update Paymentinfo set PAdvice='".$padvice."' where Id=".$Id; 
			$insRes = $Global->ExecuteQuery($insSql);

		}		
			
		if($_FILES['pdone']['error']==0)			//If file is attached.
		{
			$pdone = $_FILES['pdone']['name'];
			$pdone=$EventId."_".$pdone;
			move_uploaded_file($_FILES['pdone']['tmp_name'],$base_path."/images/paymentdocs/".$pdone);
			$pdone = "/images/paymentdocs/".$pdone;		
			$insSql = "update Paymentinfo set PDone='".$pdone."' where Id=".$Id; 
	        $insRes = $Global->ExecuteQuery($insSql);
		}
		
		if($_FILES['cyber']['error']==0)			//If file is attached.
		{
			$cyber = $_FILES['cyber']['name'];
			$cyber=$EventId."_".$cyber;
			move_uploaded_file($_FILES['cyber']['tmp_name'],$base_path."/images/paymentdocs/".$cyber);
			$cyber = "/images/paymentdocs/".$cyber;	
			$insSql = "update Paymentinfo set cyberreciept='".$cyber."' where Id=".$Id; 	
	        $insRes = $Global->ExecuteQuery($insSql);
		}
		
		
		
		 $pmode=$_REQUEST['pmode'];
		 $NetAmount=$_REQUEST['NetAmount'];
		 $AmountP=$_REQUEST['AmountP']; 
	     
		if($Id!=""){
			$insSql = "update Paymentinfo set Mode='".$pmode."',NetAmount='".$NetAmount."',AmountP='".$AmountP."' where Id='".$Id."'"; 
			$insRes = $Global->ExecuteQuery($insSql);
		}
                 $msgActionStatus = "Data updated sucessfully.";
	}*/
	
	
	
	
	
	function getFileName($filePath)
	{
		$filePathEx=explode("/",$filePath);
		return end($filePathEx);
	}
	
	
	
	
	
    /*$PaymentinfoSql = "SELECT * FROM Paymentinfo where EventId='".$EventId."'"; //using 6/7
	$PaymentinfoRes = $Global->SelectQuery($PaymentinfoSql);*/
	//print_r($PaymentinfoRes);
	
	 $sqlcurrency = "select id,code from currency where status =1 and deleted=0 and code != ''";
	$currencyRes = $Global->SelectQuery($sqlcurrency);
	
	include 'templates/uploaddocs.tpl.php';
?>