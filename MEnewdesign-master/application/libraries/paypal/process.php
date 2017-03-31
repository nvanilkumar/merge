<?php
session_start();
include_once("../MT/cGlobali.php"); 
include_once("config.php");
include_once("paypal.class.php");
$Globali = new cGlobali();
include_once("../MT/cEventSignup.php");

$paypalmode = ($PayPalMode=='sandbox') ? '.sandbox' : '';

if (!isset($_GET['oid']) && !isset($_GET['EventSignupId']))
    header("Location: " . _HTTP_SITE_ROOT . "/event/" . $_SESSION['event_url'] . "");

//if the oid (uniqueid) record not available in our DB, will redirect to event preview page
$oid = $_GET['oid'];
$eventSignupId=$_GET['EventSignupId'];
if($_POST) //Post Data received from product list page.
{
	 

	$ItemName 		= $_POST["EventTitle"]; //Item Name
	$ItemTotalPrice=$ItemPrice 		= $_POST["txnAmount"]; //Item Price
	$ItemNumber 	= $_POST["EventSignupId"]; //Item Number
	$ItemDesc 		= $_POST["EventTitle"]; //Item Number
	$ItemQty 		= $_POST["itemQty"]; // Item Quantity
        $PayPalReturnURL.="?oid=".$oid."&EventSignupId=".$eventSignupId;
	//Grand total including all tax, insurance, shipping cost and discount
	$GrandTotal = $ItemTotalPrice;//($ItemTotalPrice + $TotalTaxAmount + $HandalingCost + $InsuranceCost + $ShippinCost + $ShippinDiscount);
	$TotalTaxAmount=0;
	//Parameters for SetExpressCheckout, which will be sent to PayPal
	$padata = 	'&METHOD=SetExpressCheckout'.
				'&RETURNURL='.urlencode($PayPalReturnURL ).
				'&CANCELURL='.urlencode($PayPalReturnURL).
				'&PAYMENTREQUEST_0_PAYMENTACTION='.urlencode("SALE").
				
				'&L_PAYMENTREQUEST_0_NAME0='.urlencode($ItemName).
				'&L_PAYMENTREQUEST_0_NUMBER0='.urlencode($ItemNumber).
				'&L_PAYMENTREQUEST_0_DESC0='.urlencode($ItemDesc).
				'&L_PAYMENTREQUEST_0_AMT0='.urlencode($ItemPrice).
				'&L_PAYMENTREQUEST_0_QTY0='. urlencode($ItemQty).
				
			 
				 
				

				
				'&NOSHIPPING=1'. //set 1 to hide buyer's shipping address, in-case products that does not require shipping
				
				'&PAYMENTREQUEST_0_ITEMAMT='.urlencode($ItemTotalPrice).
				'&PAYMENTREQUEST_0_TAXAMT='.urlencode($TotalTaxAmount).
//				'&PAYMENTREQUEST_0_SHIPPINGAMT='.urlencode($ShippinCost).
//				'&PAYMENTREQUEST_0_HANDLINGAMT='.urlencode($HandalingCost).
//				'&PAYMENTREQUEST_0_SHIPDISCAMT='.urlencode($ShippinDiscount).
//				'&PAYMENTREQUEST_0_INSURANCEAMT='.urlencode($InsuranceCost).
				'&PAYMENTREQUEST_0_AMT='.urlencode($GrandTotal).
				'&PAYMENTREQUEST_0_CURRENCYCODE='.urlencode($PayPalCurrencyCode).
				'&LOCALECODE=GB'. //PayPal pages to match the language on your website.
				//'&LOGOIMG=http://www.sanwebe.com/wp-content/themes/sanwebe/img/logo.png'. //site logo
				'&CARTBORDERCOLOR=FFFFFF'. //border color of cart
				'&ALLOWNOTE=1';
				
 


		//We need to execute the "SetExpressCheckOut" method to obtain paypal token
		$paypal= new MyPayPal();
		$httpParsedResponseAr = $paypal->PPHttpPost('SetExpressCheckout', $padata, $PayPalApiUsername, $PayPalApiPassword, $PayPalApiSignature, $PayPalMode);
		
		//Respond according to message we receive from Paypal
		if("SUCCESS" == strtoupper($httpParsedResponseAr["ACK"]) || "SUCCESSWITHWARNING" == strtoupper($httpParsedResponseAr["ACK"]))
		{

				//Redirect user to PayPal store with Token received.
			 	$paypalurl ='https://www'.$paypalmode.'.paypal.com/cgi-bin/webscr?cmd=_express-checkout&token='.$httpParsedResponseAr["TOKEN"].'';
				header('Location: '.$paypalurl);
			 
		}else{
			//Show error message
			echo '<div style="color:red"><b>Error : </b>'.urldecode($httpParsedResponseAr["L_LONGMESSAGE0"]).'</div>';
			echo '<pre>';
			print_r($httpParsedResponseAr);
			echo '</pre>';
		}

}

//Paypal redirects back to this page using ReturnURL, We should receive TOKEN and Payer ID
if(isset($_GET["token"]) && isset($_GET["PayerID"]))
{ 
	//we will be using these two variables to execute the "DoExpressCheckoutPayment"
	//Note: we haven't received any payment yet.
	
        
	$token = $_GET["token"];
	$payer_id = $_GET["PayerID"];
        $uid=$_SESSION['uid'];
        //Bring the use order related session details
        $sqlSess="select `sessiondata` ,`eventsignupid`,`status`,`referralCode` from `orderlogs` where `oid`='".$oid."' and `uid`='".$uid."'";
        $sqlSessRes=  $Globali->SelectQuery($sqlSess);
        $dbSessData=$sqlSessRes[0]['sessiondata'];
        $dbSessData=unserialize($dbSessData);
        $eventSignupId=$sqlSessRes[0]['eventsignupid'];
        
        
        //Bring the trasaction related event details
        if($eventSignupId !='' && $eventSignupId > 0)
        {  
            $EventSignUpDtl = @new cEventSignup($eventSignupId);
            $EventSignUpDtl -> Load();
            
            $EventId = $EventSignUpDtl -> EventId;
            $Qty = $EventSignUpDtl -> Qty;    
            $Fees = $EventSignUpDtl ->Fees;     
            $total_amount= round($Fees*$Qty);  
            
            //bring the event title
            $event_sql="select Title from events where id=".$EventId;
            $event_sql_res=  $Globali->SelectQuery($event_sql);
            $event_title=$event_sql_res[0]['Title'];
            
        }

	
	//get session variables
	$ItemName 			= $event_title; //Item Name
	$ItemPrice 			=$total_amount; //Item Price
	$ItemNumber 		= $eventSignupId; //Item Number
	$ItemDesc 			= $event_title; //Item Number
	$ItemQty 			= $Qty; // Item Quantity
	$ItemTotalPrice 	= $total_amount; //(Item Price x Quantity = Total) Get total amount of product; 
	$TotalTaxAmount 	= 0;  //Sum of tax for all items in this order. 
//	$HandalingCost 		= $_SESSION['HandalingCost'];  //Handling cost for this order.
//	$InsuranceCost 		= $_SESSION['InsuranceCost'];  //shipping insurance cost for this order.
//	$ShippinDiscount 	= $_SESSION['ShippinDiscount']; //Shipping discount for this order. Specify this as negative number.
//	$ShippinCost 		= $_SESSION['ShippinCost']; //Although you may change the value later, try to pass in a shipping amount that is reasonably accurate.
	$GrandTotal 		= $total_amount;

	$padata = 	'&TOKEN='.urlencode($token).
				'&PAYERID='.urlencode($payer_id).
				'&PAYMENTREQUEST_0_PAYMENTACTION='.urlencode("SALE").
				
				//set item info here, otherwise we won't see product details later	
				'&L_PAYMENTREQUEST_0_NAME0='.urlencode($ItemName).
				'&L_PAYMENTREQUEST_0_NUMBER0='.urlencode($ItemNumber).
				'&L_PAYMENTREQUEST_0_DESC0='.urlencode($ItemDesc).
				'&L_PAYMENTREQUEST_0_AMT0='.urlencode($ItemPrice).
				'&L_PAYMENTREQUEST_0_QTY0='. urlencode($ItemQty).

				/* 
				//Additional products (L_PAYMENTREQUEST_0_NAME0 becomes L_PAYMENTREQUEST_0_NAME1 and so on)
				'&L_PAYMENTREQUEST_0_NAME1='.urlencode($ItemName2).
				'&L_PAYMENTREQUEST_0_NUMBER1='.urlencode($ItemNumber2).
				'&L_PAYMENTREQUEST_0_DESC1=Description text'.
				'&L_PAYMENTREQUEST_0_AMT1='.urlencode($ItemPrice2).
				'&L_PAYMENTREQUEST_0_QTY1='. urlencode($ItemQty2).
				*/

				'&PAYMENTREQUEST_0_ITEMAMT='.urlencode($ItemTotalPrice).
				'&PAYMENTREQUEST_0_TAXAMT='.urlencode($TotalTaxAmount).
				'&PAYMENTREQUEST_0_SHIPPINGAMT='.urlencode($ShippinCost).
				'&PAYMENTREQUEST_0_HANDLINGAMT='.urlencode($HandalingCost).
				'&PAYMENTREQUEST_0_SHIPDISCAMT='.urlencode($ShippinDiscount).
				'&PAYMENTREQUEST_0_INSURANCEAMT='.urlencode($InsuranceCost).
				'&PAYMENTREQUEST_0_AMT='.urlencode($GrandTotal).
				'&PAYMENTREQUEST_0_CURRENCYCODE='.urlencode($PayPalCurrencyCode);
	
	//We need to execute the "DoExpressCheckoutPayment" at this point to Receive payment from user.
	$paypal= new MyPayPal();
	$httpParsedResponseAr = $paypal->PPHttpPost('DoExpressCheckoutPayment', $padata, $PayPalApiUsername, $PayPalApiPassword, $PayPalApiSignature, $PayPalMode);
	
	//Check if everything went ok..
	if("SUCCESS" == strtoupper($httpParsedResponseAr["ACK"]) || "SUCCESSWITHWARNING" == strtoupper($httpParsedResponseAr["ACK"])) 
	{
            $paypal_transaction_id=urldecode($httpParsedResponseAr["PAYMENTINFO_0_TRANSACTIONID"]);
 
            /*
            //Sometimes Payment are kept pending even when transaction is complete. 
            //hence we need to notify user about it and ask him manually approve the transiction
            */

            if('Completed' == $httpParsedResponseAr["PAYMENTINFO_0_PAYMENTSTATUS"])
            {
                    echo '<div style="color:green">Payment Received! Your product will be sent to you very soon!</div>';
            }
            elseif('Pending' == $httpParsedResponseAr["PAYMENTINFO_0_PAYMENTSTATUS"])
            {
                    echo '<div style="color:red">Transaction Complete, but payment is still pending! '.
                    'You need to manually authorize this payment in your <a target="_new" href="http://www.paypal.com">Paypal Account</a></div>';
            }

            // we can retrive transection details using either GetTransactionDetails or GetExpressCheckoutDetails
            // GetTransactionDetails requires a Transaction ID, and GetExpressCheckoutDetails requires Token returned by SetExpressCheckOut
            $padata = 	'&TOKEN='.urlencode($token);
            $paypal= new MyPayPal();
            $httpParsedResponseAr = $paypal->PPHttpPost('GetExpressCheckoutDetails', $padata, $PayPalApiUsername, $PayPalApiPassword, $PayPalApiSignature, $PayPalMode);

            if("SUCCESS" == strtoupper($httpParsedResponseAr["ACK"]) || "SUCCESSWITHWARNING" == strtoupper($httpParsedResponseAr["ACK"])) 
            {
                 
                $paypal_res_arr['MerchantRefNo']=$eventSignupId;
                $paypal_res_arr['TransactionID']=$paypal_transaction_id;
                $paypal_res_arr['ResponseCode']=0; //means success transaction
                $paypal_res_arr['PaymentStatus']='Successful Transaction';
                $paypal_res_arr['Amount']=$total_amount;

            } else  {
                $paypal_res_arr['MerchantRefNo']=$eventSignupId;
                $paypal_res_arr['TransactionID']=$paypal_transaction_id;
                $paypal_res_arr['ResponseCode']=1; //means success transaction
                $paypal_res_arr['PaymentStatus']=urldecode($httpParsedResponseAr["L_LONGMESSAGE0"]);
                $paypal_res_arr['Amount']=0;

            }
	
	}else{
             
            $paypal_res_arr['MerchantRefNo']=$eventSignupId;
            $paypal_res_arr['TransactionID']=$paypal_transaction_id;
            $paypal_res_arr['ResponseCode']=1; // 
            $paypal_res_arr['PaymentStatus']=urldecode($httpParsedResponseAr["L_LONGMESSAGE0"]);
            $paypal_res_arr['Amount']=0;
	}
        
    $dbSessData['paypal']=$paypal_res_arr;

    $dbSessData2=serialize($dbSessData);


    //updating orderlogs table, sessiondata for Mobikwik
    $sqlUpOL="update `orderlogs` set `sessiondata`=? where `oid`=? and `eventsignupid`=?";
    $OlStmt=$Globali->dbconn->prepare($sqlUpOL);
    $OlStmt->bind_param("ssd",$dbSessData2,$oid,$eventSignupId);
    $OlStmt->execute();
    $OlStmt->close();




    header("Location: "._HTTP_SITE_ROOT."/ebsresponse.php?oid=".$oid."&EventId=".$EventId."&EventSignupId=".$eventSignupId."&PAYPAL=1&DR=0");
    exit;
}else if(isset($_GET["token"])){
    
    $token = $_GET["token"];
    $uid=$_SESSION['uid'];
    //Bring the use order related session details
    $sqlSess="select `sessiondata` ,`eventsignupid`,`status`,`referralCode` from `orderlogs` where `oid`='".$oid."' and `uid`='".$uid."'";
    $sqlSessRes=  $Globali->SelectQuery($sqlSess);
    $dbSessData=$sqlSessRes[0]['sessiondata'];
    $dbSessData=unserialize($dbSessData);
    $eventSignupId=$sqlSessRes[0]['eventsignupid'];
    
    $paypal_res_arr['MerchantRefNo']=$eventSignupId;
    $paypal_res_arr['TransactionID']='';
    $paypal_res_arr['ResponseCode']=1;  
    $paypal_res_arr['PaymentStatus']='Papayl cancel transaction';
    $paypal_res_arr['Amount']=0;
    
    $dbSessData['paypal']=$paypal_res_arr;

    $dbSessData2=serialize($dbSessData);


    //updating orderlogs table, sessiondata for Mobikwik
    $sqlUpOL="update `orderlogs` set `sessiondata`=? where `oid`=? and `eventsignupid`=?";
    $OlStmt=$Globali->dbconn->prepare($sqlUpOL);
    $OlStmt->bind_param("ssd",$dbSessData2,$oid,$eventSignupId);
    $OlStmt->execute();
    $OlStmt->close();




    header("Location: "._HTTP_SITE_ROOT."/ebsresponse.php?oid=".$oid."&EventId=".$EventId."&EventSignupId=".$eventSignupId."&PAYPAL=1&DR=0");
    exit;
    
}
?>
