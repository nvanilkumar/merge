<?php
        session_start();
	error_reporting(-1);
	
	include 'loginchk.php';
	
	include_once("MT/cGlobal.php");
        include_once("includes/common_functions.php");
	
	
	
	$Global = new cGlobal();
	$commonFunctions=new functions();
	
	//$base_path = '/home/meraeven/public_html/content';		
	
	$base_path = "../"._HTTP_Content;
	$msgActionStatus = '';
	$EventId=$_REQUEST['EventId'];
	
	$commId=(isset($_GET['commId']))?$_GET['commId']:NULL;
	
	$sqlCommId=$Global->SelectQuery("select Id,ContractDoc from commsion where EventId='".$EventId."'");
	if(count($sqlCommId)>0){ $commId=$sqlCommId[0]['Id']; }
	
	//print_r($ResDocQuery);
	$docQuery = "SELECT * FROM global_commissions"; //using 4/6
			$ResDocQuery = $Global->SelectQuery($docQuery);	
			
			$Card=$ResDocQuery[0]['Card'];
			$Cod=$ResDocQuery[0]['Cod'];
			$Counter=$ResDocQuery[0]['Counter'];
			$Paypal=$ResDocQuery[0]['Paypal'];
			$Mobikwik=$ResDocQuery[0]['Mobikwik'];
			$Paytm=$ResDocQuery[0]['Paytm'];
			$MEeffort=$ResDocQuery[0]['MEeffort'];
	
	/*if(isset($_REQUEST['delete']))
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
	}		*/
	
	if(isset($_REQUEST['Upload']))
	{
		$commId=$_POST['commId'];
		$contractdoc = NULL;
		if($_FILES['contractdoc']['error']==0)			//If file is attached.
		{
			$contractdoc = $_FILES['contractdoc']['name'];
			$contractdoc=$EventId."_".$contractdoc;
			move_uploaded_file($_FILES['contractdoc']['tmp_name'],$base_path."/images/contractdoc/".$contractdoc);
			 // Uploading the doc to S3
                    if ($_SERVER['HTTP_HOST'] != "localhost") {
                        $contract_doc_path = $base_path."/images/contractdoc/".$contractdoc;
                        $uploadToS3ErrorContract =  $commonFunctions->uploadImageToS3($contract_doc_path,_BUCKET);

                        for ($i=0;$i<2;$i++) //trying uploading 2 more times if some problem occured while uploading the first time -pH
                        {
                            if (!empty($uploadToS3ErrorContract)) {
                                $uploadToS3ErrorContract = $commonFunctions->uploadImageToS3($contract_doc_path, _BUCKET);
                            }
                        }
                    }
			$contractdoc = "images/contractdoc/".$contractdoc;
		}	
		
			
		
		$cts=date ("Y-m-d H:i:s", strtotime("now"));
		
		$insSql = "insert into commsion(Id,EventId,ContractDoc,Card,Cod,Counter,Paypal,Mobikwik,Paytm,MEeffort) values ('".$commId."',".$EventId.",'".$contractdoc."','".$Card."','".$Cod."','".$Counter."','".$Paypal."','".$Mobikwik."','".$Paytm."','".$MEeffort."') on duplicate key update  ContractDoc='".$contractdoc."' ";
		if($Global->ExecuteQuery($insSql))
		{
			//new record inserted successfully. need to send the mail to organizer
			
			
				
			$_SESSION['docUploaded'] = true;
			header("Location: contract-doc.php?EventId=".$EventId); exit;
		}
        
	}

	
	
	
	
	function getFileName($filePath)
	{
		$filePathEx=explode("/",$filePath);
		return end($filePathEx);
	}
	
	
	
	
	
	
	
	
    /*$PaymentinfoSql = "SELECT * FROM Paymentinfo where EventId='".$EventId."'"; //using 6/7
	$PaymentinfoRes = $Global->SelectQuery($PaymentinfoSql);*/
	//print_r($PaymentinfoRes);
	
	include 'templates/contract-doc.tpl.php';
?>