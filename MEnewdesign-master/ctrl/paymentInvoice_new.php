<?php

session_start();
$uid = $_SESSION['uid'];

include 'loginchk.php';


include_once("MT/cGlobali.php");

$Global = new cGlobali();

include 'includes/common_functions.php';
include 'includes/PaymentAdviceFunctions.php';
$commonFunctions = new functions();
$advice = new PaymentAdviceFunctions();
$event_id_custom = $_REQUEST['event_id_custom'];
if ($event_id_custom != '') {
    $_REQUEST['EventId'] = $event_id_custom;
}
	if(!empty($_REQUEST['EventId'])){
           $query="SELECT id FROM event WHERE id=".$_REQUEST['EventId']." and deleted=1";
           $outputPaymentInvoice=$Global->SelectQuery($query);
           if(!$outputPaymentInvoice){
//getting global commission values
$sqlComm = "select `type`,`value` from `commission` where global = 1";
$recComm = $Global->SelectQuery($sqlComm);
for ($i = 0; $i < count($recComm); $i++) {
    if ($recComm[$i]['type'] == 1) {
        $EbsComm = $recComm[$i]['value'];
    }
    if ($recComm[$i]['type'] == 2) {
        $CodComm = $recComm[$i]['value'];
    }
    if ($recComm[$i]['type'] == 3) {
        $CounterComm = $recComm[$i]['value'];
    }
    if ($recComm[$i]['type'] == 4) {
        $PaypalComm = $recComm[$i]['value'];
    }
    if ($recComm[$i]['type'] == 11) {
        $final_MEeffortComm = $MEeffortComm = $recComm[$i]['value'];
    }
    if ($recComm[$i]['type'] == 12) {
        $ServiceTaxComm = $recComm[$i]['value'];
    }
    /*  if($recComm[$i]['type']==5){
      $MobikwikComm=$recComm[$i]['value'];
      }
      if($recComm[$i]['type']==6){
      $PaytmComm=$recComm[$i]['value'];
      } */
}

//	}
$MobikwikComm = $PaytmComm = $EbsComm;
//$MobikwikComm=$recComm[0]['Mobikwik'];
//$ServiceTaxComm = 14; //$recComm[0]['ServiceTax'];
//$final_MEeffortComm = 10; // $MEeffortComm=$recComm[0]['MEeffort'];
//getting global commission values

$event_id_custom = $_REQUEST['event_id_custom'];
if ($event_id_custom != '') {
    $_REQUEST['EventId'] = $event_id_custom;
}

if (isset($_REQUEST['applyzero'])) {
    $MEeffortComm = 0;
}


$eventOverAllPerc = $perc = $Global->GetSingleFieldValue("select percentage from eventsetting where eventid='" . $_REQUEST[EventId] . "'");


//echo $_REQUEST['add_update_bankdetails'];exit;

if ($_REQUEST['export'] == "ExportPaymentAdvice" || $_REQUEST['download'] == "EmailPaymentAdvice") {
    if ($_REQUEST['EventId'] != "") {
        $sql = "select e.title AS Title,u.company AS Company,u.email AS Email "
                . "FROM event e, user u where e.ownerid=u.id and e.id=" . $_REQUEST['EventId'];
        $QueryRES = $Global->SelectQuery($sql);
        $Organiser = $QueryRES[0]['Company'];
        $Title = stripslashes($QueryRES[0]['Title']);
        $Email = $QueryRES[0]['Email'];


        $EventLevelEbsComm = $EventLevelPaypalComm = $EventLevelMobikwikComm = $EventLevelCodComm = $EventLevelCounterComm = $EventLevelMEeffortComm = $EventLevelPaytmComm = 0;  // event level default values 

        $sqlComm1 = "select `type`,`value` from `commission`  where eventid='" . $_REQUEST['EventId'] . "'  AND deleted = 0 AND global = 0";
        $ELCommmCard = $Global->SelectQuery($sqlComm1);
        for ($i = 0; $i < count($ELCommmCard); $i++) {
            if ($ELCommmCard[$i]['type'] == 1) {
                $EventLevelEbsComm = $ELCommmCard[$i]['value'];
            }

            if ($ELCommmCard[$i]['type'] == 3) {
                $EventLevelSpotRegistrationCounter = $ELCommmCard[$i]['value'];
            }
            if ($ELCommmCard[$i]['type'] == 4) {
                $EventLevelPaypalComm = $ELCommmCard[$i]['value'];
            }
            if ($ELCommmCard[$i]['type'] == 11) {
                $EventLevelMEeffortComm = $ELCommmCard[$i]['value'];
            }
        }

        $Commm = $EventLevelEbsComm;
        if ($Commm == "" || $Commm == 0) {
            $Commm = $_REQUEST[Commm];
        }


        $Chqno = $_REQUEST['Chqno'];
        $Chqdt = $_REQUEST['Chqdt'];
        $AccName = $_REQUEST['AccName'];
        $Accno = $_REQUEST['Accno'];
        $BnkName = $_REQUEST['BnkName'];
        $BnkBranch = $_REQUEST['BnkBranch'];
        $Acctype = $_REQUEST['Acctype'];
        $IFCS = $_REQUEST['IFCS'];

        $bank_details_action = 'Add';



        $UserID = $Global->GetSingleFieldValue("select ownerid as 'UserID' from event where deleted=0 and id='" . $_REQUEST['EventId'] . "'");
        $BankQuery = "select * from organizerbankdetail  where userid=" . $UserID; //using 6/8 -pH
        $BankQueryRES = $Global->SelectQuery($BankQuery);
        //print_r($BankQueryRES);exit;
        if (count($BankQueryRES) > 0) {

            $AccName = $BankQueryRES[0]['accountname'];
            $Accno = $BankQueryRES[0]['accountnumber'];
            $BnkName = $BankQueryRES[0]['bankname'];
            $BnkBranch = $BankQueryRES[0]['branch'];
            $Acctype = $BankQueryRES[0]['accounttype'];
            $IFCS = $BankQueryRES[0]['ifsccode'];
        }


        if ($_REQUEST['txtSDt'] != "" && $_REQUEST['txtEDt'] != "") {
            $SDt = $_REQUEST['txtSDt'];
            $SDtExplode = explode("/", $SDt);
            $yesterdaySDate = $SDtExplode[2] . '-' . $SDtExplode[1] . '-' . $SDtExplode[0] . ' 00:00:00';
            $yesterdaySDate = $commonFunctions->convertTime($yesterdaySDate, DEFAULT_TIMEZONE);


            $EDt = $_REQUEST['txtEDt'];
            $EDtExplode = explode("/", $EDt);
            $yesterdayEDate = $EDtExplode[2] . '-' . $EDtExplode[1] . '-' . $EDtExplode[0] . ' 23:59:59';
            $yesterdayEDate = $commonFunctions->convertTime($yesterdayEDate, DEFAULT_TIMEZONE);
            $SqDate = " and s.signupdate between '" . $yesterdaySDate . "' and '" . $yesterdayEDate . "'";
        } else {
            $SqDate = "";
        }
        $paymentModeIds = array(1, 5);
        if (isset($_REQUEST['offline']) && $_REQUEST['offline'] != '') {
            array_push($paymentModeIds, 4);
        }
        if (isset($_REQUEST['exclude_spot']) && $_REQUEST['exclude_spot'] == "1") {
            if (($key = array_search(5, $paymentModeIds)) !== false) {
                unset($paymentModeIds[$key]);
            }
        }
		if(isset($_REQUEST['show_extra']) && $_REQUEST['show_extra'] == "1"){
			$show_extra=1;
		}else{
			$show_extra=0;
		}
        $offline_mode = 's.paymentmodeid in ('.  implode(',', $paymentModeIds).')';

        $transqlAll = "SELECT s.convertedamount 'paypal_converted_amount',s.conversionrate 'conversionRate',"
                . "(s.totalamount*s.conversionrate) 'AMOUNT',estd.ticketquantity 'NumOfTickets',estd.amount 'TicketAmt',s.eventid 'EventId',"
                . "s.id 'Id', s.signupdate 'SignupDt', e.title 'Details', s.quantity 'Qty', (s.totalamount/s.quantity) 'Fees',"
                . "s.paymenttransactionid 'PaymentTransId',s.discount 'PromotionCode',s.paymentmodeid 'PaymentModeId',"
                . "s.paymentstatus 'eChecked',s.eventextrachargeamount 'Ccharge',s.promotercode 'ucode',c1.code 'fromcurrencyCode',c2.code 'tocurrencyCode',"
                . "p.`name` 'PaymentGateway', s.referraldiscountamount 'referralDAmount' FROM eventsignup AS s"
                . " INNER JOIN eventsignupticketdetail estd ON estd.eventsignupid=s.id"
                . " INNER JOIN event AS e ON s.eventid = e.id"
                . " left JOIN currency c1 on s.fromcurrencyid=c1.id"
                . " left JOIN currency c2 on s.tocurrencyid=c2.id"
                . " INNER JOIN paymentgateway p on s.paymentgatewayid = p.id"
                . " WHERE 1 AND ($offline_mode and s.paymenttransactionid != 'A1')"
                . " And s.eventid=$_REQUEST[EventId] $SqDate"
                . " And s.paymentstatus!='Refunded'"
                . " And s.paymentstatus!='Canceled'"
                . " Order by s.signupdate";
  
        $TranRESAll = $Global->SelectQuery($transqlAll);
        $transByGateway = $advice->getCardTransByGateway($TranRESAll);

        $transqlrAll = "SELECT s.convertedamount 'paypal_converted_amount',s.conversionrate 'conversionRate',"
                . "(s.totalamount*s.conversionrate) 'AMOUNT',estd.ticketquantity 'NumOfTickets',estd.amount 'TicketAmt',s.eventid 'EventId',"
                . "s.id 'Id', s.signupdate 'SignupDt', e.title 'Details', s.quantity 'Qty', (s.totalamount/s.quantity) 'Fees',"
                . "s.paymenttransactionid 'PaymentTransId',s.discount 'PromotionCode',s.paymentmodeid 'PaymentModeId',"
                . "s.paymentstatus 'eChecked',s.eventextrachargeamount 'Ccharge',s.promotercode 'ucode',c1.code 'fromcurrencyCode',c2.code 'tocurrencyCode',"
                . "p.`name` 'PaymentGateway', s.referraldiscountamount 'referralDAmount' FROM eventsignup AS s"
                . " INNER JOIN eventsignupticketdetail estd ON estd.eventsignupid=s.id"
                . " INNER JOIN event AS e ON s.eventid = e.id"
                . " left JOIN currency c1 on s.fromcurrencyid=c1.id"
                . " left JOIN currency c2 on s.tocurrencyid=c2.id"
                . " INNER JOIN paymentgateway p on s.paymentgatewayid = p.id"
                . " WHERE 1 AND ($offline_mode and s.paymenttransactionid != 'A1')"
                . " And s.eventid=$_REQUEST[EventId] $SqDate"
                . " And s.paymentstatus='Refunded' Order by s.signupdate";
        $TranRESrAll = $Global->SelectQuery($transqlrAll);
        //echo '<pre>';print_r($TranRESAll);exit;
        $transrByGateway = $advice->getCardTransByGateway($TranRESrAll);


        include("includes/mpdf/mpdf.php");
        $mpdf = new mPDF();

        $data = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>MeraEvents - Signup Events</title>
<link href="<?=_HTTP_CF_ROOT;?>/ctrl/css/style.min.css.gz" rel="stylesheet" type="text/css" />
</head>
<body>
<table width="850" align="center" border="10" cellpadding="5" cellspacing="0" bordercolor="#CCCCCC" style="font-family:Arial, Helvetica, sans-serif; font-size:18px;">
  <tr>
    <td><table width="100%" border="0" cellspacing="0" cellpadding="2">
      <tr>

        <td width="21%"><!--img  src="http://content.meraevents.com/images/VOS-logo.jpg" alt="Versant Online Solutions Private Limited" /--></td>
        <td width="63%" align="center"><div align="center">PAYMENT    ADVISE<br />
                <strong>MeraEvents Collections    &amp; Payment details</strong></div></td>
        <td width="16%"><img width="144" height="60" src="images/garland_dropmenu_logo (1).png" alt="Meraevents Logo" /></td>
      </tr>
    </table></td>
  </tr>
  <tr></tr>
  <tr>
    <td><table width="100%" border="1" cellpadding="2" cellspacing="0">
      <tr height="21">

        <td width="500" height="30">Versant Online Solutions Private Limited</td>
        <td width="152" height="30"><strong>Name of the Organizer:</strong></td>
        <td width="274" height="30" colspan="2">' . $Organiser . '</td>
      </tr>
      <tr height="20">
        <td height="30">3Cube    Towers, 2nd Floor, White Field Road, Kondapur, Hyderabad-500084</td>
        <td height="30"><strong>Name of the Event&nbsp; :</strong></td>
        <td height="30" colspan="2">' . $Title . '</td>
      </tr>
      <tr height="20">
        <td height="30" colspan="4"><strong>Service    Tax No# : </strong> AABCV8766GST002</td>
      </tr>
 <tr height="20">
        <td height="30" colspan="4"><strong>EventId# : </strong>' . $_REQUEST['EventId'] . '</td>
      </tr>

    </table></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>';
        $grandTotalamt = NULL;

        // print_r($transByGateway);
//echo '<pre>';

        $data.='<tr>
    <td><table width="100%" border="1" cellspacing="0" cellpadding="2">
      <tr height="35">
        <td height="25" width="112" align="left" nowrap="nowrap" bordercolor="#000000"><strong>Receipt No.</strong></td>
        <td height="25" width="150" align="left" nowrap="nowrap" bordercolor="#000000"><strong>Mode</strong></td>
        <td height="25" width="150" align="left" nowrap="nowrap" bordercolor="#000000"><strong>DateofTxn</strong></td>
        <td height="25" width="80" align="left" nowrap="nowrap" bordercolor="#000000"><strong>Qty</strong></td>';
		if($show_extra==1){
		 $data.='<td height="25" width="92" align="left" nowrap="nowrap" bordercolor="#000000"><strong>Amount Paid' . $currencyType . '</strong></td>
        <td height="25" width="90" align="left" nowrap="nowrap" bordercolor="#000000"><strong>Service/<br/>Gateway Fee</strong></td>';	
		}
         $data.='<td height="25" width="92" align="left" nowrap="nowrap" bordercolor="#000000"><strong>Amount' . $currencyType . '</strong></td>
        <td height="25" width="90" align="left" nowrap="nowrap" bordercolor="#000000"><strong>Commission</strong></td>
        <td height="25" width="90" align="left" nowrap="nowrap" bordercolor="#000000"><strong>ServiceTax<br/>@' . $ServiceTaxComm . '%' . $currencyType . ' </strong></td>
        <td height="25" width="90" align="left" nowrap="nowrap" bordercolor="#000000"><strong>NetAmount' . $currencyType . '</strong></td>
		 </tr>';

        $section_total_qty = $refund_total_qty = 0;
        $section_totamt =  $section_totincamt =  $section_totfee = $section_totcom = $section_totst = $section_totna = NULL;


        foreach ($transByGateway as $Gatewayk => $Gatewayv) {
            $cardCommisionType = 0;
            if (strtolower($Gatewayk) == 'ebs' || strtolower($Gatewayk) == 'mobikwik' || strtolower($Gatewayk) == 'paytm') {
                $cardCommisionType = 1;
            }

            if (strtolower($Gatewayk) != 'paypal') {
                $TranRES = $Gatewayv;
                if (count($ELCommmCard) > 0) {
                    // $final_MEeffortComm = $EventLevelMEeffortComm = 10; 
                    // $EventLevelEbsComm = $EventLevelMobikwikComm = $EventLevelPaytmComm ;           
                    //EBS commission
                    if ($cardCommisionType == 1) {
                        if ($EventLevelEbsComm == "" ) {  //if event level value is Zero then default value || $EventLevelEbsComm == 0
                            if (!isset($_REQUEST['applyzero'])) {
                                if ($eventOverAllPerc == 0) {
                                    $CommmCard = $EbsComm;
                                } else {
                                    $CommmCard = $eventOverAllPerc;
                                }
                            } else {
                                $CommmCard = 0;
                            }
                        } else {
                            if (!isset($_REQUEST['applyzero'])) {
                                if ($eventOverAllPerc == 0) {
                                    $CommmCard = $EventLevelEbsComm;
                                } else {
                                    $CommmCard = $eventOverAllPerc;
                                }
                            } else {
                                $CommmCard = 0;
                            }
                        }  //else,  event level value
                    }

                    //Spot Registration
                    if ((strtolower($Gatewayk) == 'spotcash') || ((strtolower($Gatewayk) == 'spotcard'))) {
                        if ($EventLevelSpotRegistrationCounter == "" || $EventLevelSpotRegistrationCounter == 0) {
                            if (!isset($_REQUEST['applyzero'])) {
                                if ($eventOverAllPerc == 0) {
                                    $CommmCard = $CounterComm;
                                } else {
                                    $CommmCard = $eventOverAllPerc;
                                }
                            } else {
                                $CommmCard = 0;
                            }
                        } else {
                            if (!isset($_REQUEST['applyzero'])) {
                                if ($eventOverAllPerc == 0) {
                                    $CommmCard = $EventLevelSpotRegistrationCounter;
                                } else {
                                    $CommmCard = $eventOverAllPerc;
                                }
                            } else {
                                $CommmCard = 0;
                            }
                        }
                    }


                    //MEeffort commission
                    if ($EventLevelMEeffortComm == "" ) {  //if event level value is Zero then default value || $EventLevelMEeffortComm == 0
                        if (!isset($_REQUEST['applyzero'])) {
                            if ($eventOverAllPerc == 0) {
                                $MEeffortComm = $MEeffortComm;
                            } else {
                                $MEeffortComm = $eventOverAllPerc;
                            }
                        } else {
                            $CommmCard = 0;
                        }
                    } else {
                        if (!isset($_REQUEST['applyzero'])) {
                            if ($eventOverAllPerc == 0) {
                                $MEeffortComm = $EventLevelMEeffortComm;
                            } else {
                                $MEeffortComm = $eventOverAllPerc;
                            }
                        } else {
                            $CommmCard = 0;
                        }
                    }  //else,  event level value
                    //$final_MEeffortComm = $MEeffortComm;		  
                } else { //when Event level value not set
                    //if(strtolower($Gatewayk)== 'ebs' || strtolower($Gatewayk)== 'mobikwik' || strtolower($Gatewayk)== 'paytm'){ $CommmCard=$EbsComm; } 
                    if ($cardCommisionType == 1) {
                        $CommmCard = $EbsComm;
                    }
                    if (strtolower($Gatewayk) == 'paypal') {
                        $CommmCard = $PaypalComm;
                    }
                    //if(strtolower($Gatewayk)== 'mobikwik'){ $CommmCard=$MobikwikComm; } 
                    //if(strtolower($Gatewayk)== 'paytm'){ $CommmCard=$PaytmComm; }
                    if ((strtolower($Gatewayk) == 'spotcash') || ((strtolower($Gatewayk) == 'spotcard'))) {
                        $CommmCard = $CounterComm;
                    }
                    if (isset($_REQUEST['applyzero'])) {
                        $CommmCard = 0;
                    }
                    //if(strtolower($Gatewayk)== 'meeffort'){ $MEeffortComm=$MEeffortComm; } 
                }





                $currencyType = '';
                if (strcmp($Gatewayk, 'paypal') != 0) {
                    $currencyType = '(INR)';
                }


                $calComm = NULL;
				$IncAmt = NULL;
				$fee = NULL;
                $serTax = NULL;
                $netAmt = NULL;
                $totqty = 0;
                $totamt = NULL;
				$totincamt = NULL;
				$totfee = NULL;
                $totcom = NULL;
                $totst = NULL;
                //$totna=NULL;
                $totna = array('INR' => 0, 'USD' => 0);
                $netToPay = NULL;
                $coTran = count($TranRES);

                if ($coTran > 0) {
                    if (count($ELCommmCard) > 0) {
                        if ($cardCommisionType == 1) {
                            if ($EventLevelEbsComm == "" ) {  //if event level value is Zero then default value || $EventLevelEbsComm == 0
                                if ($eventOverAllPerc == 0) {
                                    $tst_CommmCard = $EbsComm;
                                } else {
                                    $tst_CommmCard = $eventOverAllPerc;
                                }
                            } else {
                                if ($eventOverAllPerc == 0) {
                                    $tst_CommmCard = $EventLevelEbsComm;
                                } else {
                                    $tst_CommmCard = $eventOverAllPerc;
                                }
                            }  //else,  event level value
                        }
//                       
                        //Spot Registration
                        if ((strtolower($Gatewayk) == 'spotcash') || ((strtolower($Gatewayk) == 'spotcard'))) {
                            if ($EventLevelSpotRegistrationCounter == "" || $EventLevelSpotRegistrationCounter == 0) {
                                if ($eventOverAllPerc == 0) {
                                    $tst_CommmCard = $CounterComm;
                                } else {
                                    $tst_CommmCard = $eventOverAllPerc;
                                }
                            } else {
                                if ($eventOverAllPerc == 0) {
                                    $tst_CommmCard = $EventLevelSpotRegistrationCounter;
                                } else {
                                    $tst_CommmCard = $eventOverAllPerc;
                                }
                            }
                        }
                    } else {
                        if ($cardCommisionType == 1) {
                            $tst_CommmCard = $EbsComm;
                        }
                        if (strtolower($Gatewayk) == 'paypal') {
                            $tst_CommmCard = $PaypalComm;
                        }
                        if ((strtolower($Gatewayk) == 'spotcash') || ((strtolower($Gatewayk) == 'spotcard'))) {
                            $tst_CommmCard = $CounterComm;
                        }
                    }
                    $commision_gateways[$Gatewayk] = $tst_CommmCard;
                }


                for ($i = 0; $i < $coTran; $i++) {
                    $sopt_status = ((strtolower($Gatewayk) == 'spotcash') || ((strtolower($Gatewayk) == 'spotcard'))) ? false : true;
                    if (count($ELCommmCard) > 0) {

                        //  $final_MEeffortComm = $EventLevelMEeffortComm = 10; //$ELCommmCard[0]['MEeffort']; //MEeffort commission
                        // $EventLevelMobikwikComm = $EventLevelPaytmComm = $EventLevelEbsComm;
                        //EBS commission
                        //if(strtolower($Gatewayk)== 'ebs' || strtolower($Gatewayk)== 'mobikwik' || strtolower($Gatewayk)== 'paytm'){
                        if ($cardCommisionType == 1) {
                            if ($EventLevelEbsComm == "" ) {  //if event level value is Zero then default value || $EventLevelEbsComm == 0
                                if (!isset($_REQUEST['applyzero'])) {
                                    if ($eventOverAllPerc == 0) {
                                        $CommmCard = $EbsComm;
                                    } else {
                                        $CommmCard = $eventOverAllPerc;
                                    }
                                } else {
                                    $CommmCard = 0;
                                }
                            } else {
                                if (!isset($_REQUEST['applyzero'])) {
                                    if ($eventOverAllPerc == 0) {
                                        $CommmCard = $EventLevelEbsComm;
                                    } else {
                                        $CommmCard = $eventOverAllPerc;
                                    }
                                } else {
                                    $CommmCard = 0;
                                }
                            }  //else,  event level value
                        }
                        //Spot Registration
                        if ((strtolower($Gatewayk) == 'spotcash') ||
                                ((strtolower($Gatewayk) == 'spotcard'))) {
                            if ($EventLevelSpotRegistrationCounter == "" || $EventLevelSpotRegistrationCounter == 0) {
                                if (!isset($_REQUEST['applyzero'])) {
                                    if ($eventOverAllPerc == 0) {
                                        $CommmCard = $CounterComm;
                                    } else {
                                        $CommmCard = $eventOverAllPerc;
                                    }
                                } else {
                                    $CommmCard = 0;
                                }
                            } else {
                                if (!isset($_REQUEST['applyzero'])) {
                                    if ($eventOverAllPerc == 0) {
                                        $CommmCard = $EventLevelSpotRegistrationCounter;
                                    } else {
                                        $CommmCard = $eventOverAllPerc;
                                    }
                                } else {
                                    $CommmCard = 0;
                                }
                            }
                        }

                        //MEeffort commission
                        if (($TranRES[$i]['ucode'] == '' || $TranRES[$i]['ucode'] === 0) && $TranRES[$i]['referralDAmount'] == 0 && $sopt_status) {
                            if ($EventLevelMEeffortComm == "" ) {  //if event level value is Zero then default value || $EventLevelMEeffortComm == 0
                                if (!isset($_REQUEST['applyzero'])) {
                                    if ($eventOverAllPerc == 0) {
                                        $final_MEeffortComm = $CommmCard = $MEeffortComm;
                                    } else {
                                        $final_MEeffortComm = $CommmCard = $eventOverAllPerc;
                                    }
                                } else {
                                    $CommmCard = 0;
                                }
                            } else {
                                if (!isset($_REQUEST['applyzero'])) {
                                    if ($eventOverAllPerc == 0) {
                                        $final_MEeffortComm = $CommmCard = $EventLevelMEeffortComm;
                                    } else {
                                        $final_MEeffortComm = $CommmCard = $eventOverAllPerc;
                                    }
                                } else {
                                    $CommmCard = 0;
                                }
                            }  //else,  event level value		  
                        }
                    } else { //when Event level value not set
                        if (($TranRES[$i]['ucode'] == '' || $TranRES[$i]['ucode'] === 0) && $TranRES[$i]['referralDAmount'] == 0) { //means, ME sales effort
                            $final_MEeffortComm = $CommmCard = $MEeffortComm;
                            //spot booking comming
                            if ((strtolower($Gatewayk) == 'spotcash') || ((strtolower($Gatewayk) == 'spotcard'))) {
                                $CommmCard = $CounterComm;
                            }
                        } else { // default value
                            //if(strtolower($Gatewayk)== 'ebs' || strtolower($Gatewayk)== 'mobikwik' || strtolower($Gatewayk)== 'paytm'){ $CommmCard=$EbsComm; } 
                            if ($cardCommisionType == 1) {
                                $CommmCard = $EbsComm;
                            }
                            if (strtolower($Gatewayk) == 'paypal') {
                                $CommmCard = $PaypalComm;
                            }
                            //if(strtolower($Gatewayk)== 'mobikwik'){ $CommmCard=$MobikwikComm; } 
                            // if(strtolower($Gatewayk)== 'paytm'){ $CommmCard=$PaytmComm; } 
                            if ((strtolower($Gatewayk) == 'spotcash') || ((strtolower($Gatewayk) == 'spotcard'))) {
                                $CommmCard = $EventLevelSpotRegistrationCounter;
                            }
                            if (isset($_REQUEST['applyzero'])) {
                                $CommmCard = 0;
                            }
                        }
                    }


                    
                    $calComm = (($TranRES[$i][Qty] * $TranRES[$i][Fees]) - $TranRES[$i][Ccharge]) * ($CommmCard / 100);
                     $serTax = $calComm * ($commonFunctions->getServiceTax($Global,$TranRES[$i][SignupDt]) / 100);              
					
                    $netAmt = (($TranRES[$i][Qty] * $TranRES[$i][Fees]) - $TranRES[$i][Ccharge]) - $calComm - $serTax;
                    
                    $transid = "TR:" . $TranRES[$i][PaymentTransId];


                    $thisTransAmt = (($TranRES[$i][Qty] * $TranRES[$i][Fees]) - $TranRES[$i][Ccharge]);
					$IncAmt = ($TranRES[$i][Qty] * $TranRES[$i][Fees]);
                    $fee =  $TranRES[$i][Ccharge];
                    if ($thisTransAmt > 0) {
                        $data.=' <tr>
        <td height="20" align="left" bordercolor="#000000">' . $TranRES[$i][Id] . '</td>';
                        if (strtolower($TranRES[$i]['PaymentTransId']) == 'offline') {
                            $data .= '<td align="left" bordercolor="#000000">Offline</td>';
                        } else {
                            $data .= '<td align="left" bordercolor="#000000">' . $Gatewayk . '</td>';
                        }
                        $data .= '<td align="left" bordercolor="#000000">' . 
                                  $commonFunctions->convertTime($TranRES[$i][SignupDt], DEFAULT_TIMEZONE,TRUE)
                                . '</td>
        <td align="left" bordercolor="#000000">' . $TranRES[$i][qty_paid] . '</td>';
          if($show_extra==1){
		 $data.=' <td align="left" bordercolor="#000000">' . $TranRES[$i]['currencyCode'] . " " . $IncAmt . '</td>
		<td align="left" bordercolor="#000000">' . $TranRES[$i]['currencyCode'] . " " . $fee . '</td>';
		  }
         $data.=' <td align="left" bordercolor="#000000">' . $TranRES[$i]['currencyCode'] . " " . $thisTransAmt . '</td>';


                        if (($TranRES[$i]['ucode'] == '' || $TranRES[$i]['ucode'] === 0) && $sopt_status) {
                            $data.='<td align="left" bordercolor="#000000">' . round($calComm, 2) . ' (ME)</td>';
                        } else {
                            $data.='<td align="left" bordercolor="#000000">' . round($calComm, 2) . ' </td>';
                        }

                        $data.='<td align="left" bordercolor="#000000">' . round($serTax, 2) . '</td>
        <td align="left" bordercolor="#000000">' . round($netAmt, 2) . '</td>
		
      </tr>';
                    }

                    $totqty = $totqty + $TranRES[$i]['qty_paid'];
                    $totamt[$TranRES[$i]['currencyCode']]+=(($TranRES[$i][Qty] * $TranRES[$i][Fees]) - $TranRES[$i][Ccharge]);
					$totincamt[$TranRES[$i]['currencyCode']]+=($TranRES[$i][Qty] * $TranRES[$i][Fees]);
					$totfee[$TranRES[$i]['currencyCode']]+= $TranRES[$i][Ccharge];
                    $totcom[$TranRES[$i]['currencyCode']]+=$calComm;
                    $totst[$TranRES[$i]['currencyCode']]+=$serTax;
                    $totna[$TranRES[$i]['currencyCode']]+=$netAmt;

                    $section_total_qty += $TranRES[$i]['qty_paid'];
                    $section_totamt[$TranRES[$i]['currencyCode']]+=(($TranRES[$i][Qty] * $TranRES[$i][Fees]) - $TranRES[$i][Ccharge]);
					$section_totincamt[$TranRES[$i]['currencyCode']]+=($TranRES[$i][Qty] * $TranRES[$i][Fees]);
					$section_totfee[$TranRES[$i]['currencyCode']]+=$TranRES[$i][Ccharge];
                    $section_totcom[$TranRES[$i]['currencyCode']]+=$calComm;
                    $section_totst[$TranRES[$i]['currencyCode']]+=$serTax;
                    $section_totna[$TranRES[$i]['currencyCode']]+=$netAmt;
                }
                $TranRESr = NULL;
                if (strcmp($Gatewayk, 'ebs') == 0) {
                    $TranRESr = $transrByGateway['ebs'];
                } else if (strcmp($Gatewayk, 'mobikwik') == 0) {
                    $TranRESr = $transrByGateway['mobikwik'];
                } else if (strcmp($Gatewayk, 'paytm') == 0) {
                    $TranRESr = $transrByGateway['paytm'];
                }
                //echo '<pre>';print_r($TranRESr);


                $calCommr = NULL;
                $serTaxr = NULL;
                $netAmtr = NULL;
                $totqtyr = NULL;
                $totamtr = NULL;
				$totincamtr = NULL;
				$totfeer = NULL;
                $totcomr = NULL;
                $totstr = NULL;
                $totnar = NULL;
                $coTranr = count($TranRESr);
                if ($coTranr > 0) {
                    if (count($ELCommmCard) > 0) {
                        // if(strtolower($Gatewayk)== 'ebs' || strtolower($Gatewayk)== 'mobikwik' || strtolower($Gatewayk)== 'paytm'){
                        if ($cardCommisionType == 1) {
                            if ($EventLevelEbsComm == "" ) {  //if event level value is Zero then default value || $EventLevelEbsComm == 0
                                if ($eventOverAllPerc == 0) {
                                    $tst_CommmCard = $EbsComm;
                                } else {
                                    $tst_CommmCard = $eventOverAllPerc;
                                }
                            } else {
                                if ($eventOverAllPerc == 0) {
                                    $tst_CommmCard = $EventLevelEbsComm;
                                } else {
                                    $tst_CommmCard = $eventOverAllPerc;
                                }
                            }  //else,  event level value
                        }

                        //Spot Registration
                        if ((strtolower($Gatewayk) == 'spotcash') ||
                                ((strtolower($Gatewayk) == 'spotcard'))) {
                            if ($EventLevelSpotRegistrationCounter == "" || $EventLevelSpotRegistrationCounter == 0) {
                                if ($eventOverAllPerc == 0) {
                                    $tst_CommmCard = $CounterComm;
                                } else {
                                    $tst_CommmCard = $eventOverAllPerc;
                                }
                            } else {
                                if ($eventOverAllPerc == 0) {
                                    $tst_CommmCard = $EventLevelSpotRegistrationCounter;
                                } else {
                                    $tst_CommmCard = $eventOverAllPerc;
                                }
                            }
                        }
                    } else {
                        // if(strtolower($Gatewayk)== 'ebs' || strtolower($Gatewayk)== 'mobikwik' || strtolower($Gatewayk)== 'paytm'){ $tst_CommmCard=$EbsComm; } 
                        if ($cardCommisionType == 1) {
                            $tst_CommmCard = $EbsComm;
                        }
                        if (strtolower($Gatewayk) == 'paypal') {
                            $tst_CommmCard = $PaypalComm;
                        }
                        // if(strtolower($Gatewayk)== 'mobikwik'){ $tst_CommmCard=$MobikwikComm; } 
                        // if(strtolower($Gatewayk)== 'paytm'){ $tst_CommmCard=$PaytmComm; }
                        if ((strtolower($Gatewayk) == 'spotcash') || ((strtolower($Gatewayk) == 'spotcard'))) {
                            $tst_CommmCard = $CounterComm;
                        }
                    }
                    $commision_gateways[$Gatewayk] = $tst_CommmCard;
                }
                for ($i = 0; $i < $coTranr; $i++) {

                    if (count($ELCommmCard) > 0) {
                        //$EventLevelMEeffortComm = 10; //$ELCommmCard[0]['MEeffort']; //MEeffort commission
                        // $EventLevelMobikwikComm = $EventLevelPaytmComm = $EventLevelEbsComm;
                        //EBS commission
                        //if(strtolower($Gatewayk)== 'ebs' || strtolower($Gatewayk)== 'mobikwik' || strtolower($Gatewayk)== 'paytm'){
                        if ($cardCommisionType == 1) {
                            if ($EventLevelEbsComm == "" ) {  //if event level value is Zero then default value || $EventLevelEbsComm == 0
                                if (!isset($_REQUEST['applyzero'])) {
                                    if ($eventOverAllPerc == 0) {
                                        $CommmCard = $EbsComm;
                                    } else {
                                        $CommmCard = $eventOverAllPerc;
                                    }
                                } else {
                                    $CommmCard = 0;
                                }
                            } else {
                                if (!isset($_REQUEST['applyzero'])) {
                                    if ($eventOverAllPerc == 0) {
                                        $CommmCard = $EventLevelEbsComm;
                                    } else {
                                        $CommmCard = $eventOverAllPerc;
                                    }
                                } else {
                                    $CommmCard = 0;
                                }
                            }  //else,  event level value
                        }

                        //MEeffort commission
                        if (($TranRESr[$i]['ucode'] == '' || $TranRESr[$i]['ucode'] == 0) && $TranRESr[$i]['referralDAmount'] == 0 && $sopt_status) {
                            if ($EventLevelMEeffortComm == "" ) {  //if event level value is Zero then default value || $EventLevelMEeffortComm == 0
                                if (!isset($_REQUEST['applyzero'])) {
                                    if ($eventOverAllPerc == 0) {
                                        $final_MEeffortComm = $CommmCard = $MEeffortComm;
                                    } else {
                                        $final_MEeffortComm = $CommmCard = $eventOverAllPerc;
                                    }
                                } else {
                                    $CommmCard = 0;
                                }
                            } else {
                                if (!isset($_REQUEST['applyzero'])) {
                                    if ($eventOverAllPerc == 0) {
                                        $final_MEeffortComm = $CommmCard = $EventLevelMEeffortComm;
                                    } else {
                                        $final_MEeffortComm = $CommmCard = $eventOverAllPerc;
                                    }
                                } else {
                                    $CommmCard = 0;
                                }
                            }  //else,  event level value		  
                        }
                    } else { //when Event level value not set
                        if (($TranRESr[$i]['ucode'] == '' || $TranRESr[$i]['ucode'] == 0) && $TranRESr[$i]['referralDAmount'] == 0) { //means, ME sales effort
                            $final_MEeffortComm = $CommmCard = $MEeffortComm;
                            //spot booking comming
                            if ((strtolower($Gatewayk) == 'spotcash') || ((strtolower($Gatewayk) == 'spotcard'))) {
                                $CommmCard = $CounterComm;
                            }
                            if (isset($_REQUEST['applyzero'])) {
                                $CommmCard = 0;
                            }
                        } else { // default value
                            // if(strtolower($Gatewayk)== 'ebs' || strtolower($Gatewayk)== 'mobikwik' || strtolower($Gatewayk)== 'paytm'){ $CommmCard=$EbsComm; } 
                            if ($cardCommisionType == 1) {
                                $CommmCard = $EbsComm;
                            }
                            if (strtolower($Gatewayk) == 'paypal') {
                                $CommmCard = $PaypalComm;
                            }
                            if ((strtolower($Gatewayk) == 'spotcash') || ((strtolower($Gatewayk) == 'spotcard'))) {
                                $CommmCard = $EventLevelSpotRegistrationCounter;
                            }
                            //if(strtolower($Gatewayk)== 'mobikwik'){ $CommmCard=$MobikwikComm; } 
                            //if(strtolower($Gatewayk)== 'paytm'){ $CommmCard=$PaytmComm; } 
                            if (isset($_REQUEST['applyzero'])) {
                                $CommmCard = 0;
                            }
                        }
                    }


                    $calCommr = (($TranRESr[$i][Qty] * $TranRESr[$i][Fees]) - $TranRESr[$i][Ccharge]) * ($CommmCard / 100);

                     $serTaxr = $calCommr * ($commonFunctions->getServiceTax($Global,$TranRESr[$i][SignupDt]) / 100);
                   
                    $netAmtr = (($TranRESr[$i][Qty] * $TranRESr[$i][Fees]) - $TranRESr[$i][Ccharge]) - $calCommr - $serTaxr;
                    $totqtyr = $totqtyr + $TranRESr[$i]['qty_paid'];
                    $refund_total_qty += $TranRESr[$i]['qty_paid'];
                    $totamtr[$TranRESr[$i]['currencyCode']]+=(($TranRESr[$i][Qty] * $TranRESr[$i][Fees]) - $TranRESr[$i][Ccharge]);
					$totincamtr[$TranRESr[$i]['currencyCode']]+=($TranRESr[$i][Qty] * $TranRESr[$i][Fees]);
					$totfeer[$TranRESr[$i]['currencyCode']]+=$TranRESr[$i][Ccharge];
                    $totcomr[$TranRESr[$i]['currencyCode']]+=$calCommr;
                    $totstr[$TranRESr[$i]['currencyCode']]+=$serTaxr;
                    $totnar[$TranRESr[$i]['currencyCode']]+=$netAmtr;

                    $refund_totamtr[$TranRESr[$i]['currencyCode']]+=(($TranRESr[$i][Qty] * $TranRESr[$i][Fees]) - $TranRESr[$i][Ccharge]);
					$refund_totincamtr[$TranRESr[$i]['currencyCode']]+=$TranRESr[$i][Qty] * $TranRESr[$i][Fees];
					$refund_totfeer[$TranRESr[$i]['currencyCode']]+= $TranRESr[$i][Ccharge];
                    $refund_totcomr[$TranRESr[$i]['currencyCode']]+=$calCommr;
                    $refund_totstr[$TranRESr[$i]['currencyCode']]+=$serTaxr;
                    $refund_totnar[$TranRESr[$i]['currencyCode']]+=$netAmtr;
                }
                //round($totna-$totcomr-$totstr,2)
                foreach ($totna as $code => $value) {
                    $subTotal = round($value - ($totcomr[$code] + $totstr[$code]), 2);
                    $netToPay[$code]+=$subTotal;

                    $grandTotalamt1[$code]+=$subTotal;
                }

                $totqtyr = ($totqtyr != '') ? $totqtyr : '&nbsp;';
            }
        }

        $data.=' 
      <tr height="20">
        <td height="25" bordercolor="#000000">&nbsp;</td>
        <td height="25" bordercolor="#000000">&nbsp;</td>
        <td height="25" bordercolor="#000000">&nbsp;</td>
        <td height="25" bordercolor="#000000">' . $section_total_qty . '</td>';
		if($show_extra==1){
			 $data.='<td height="25" bordercolor="#000000">' . $advice->totalStrWithCurrencies($section_totincamt) . '</td>
			<td height="25" bordercolor="#000000">' . $advice->totalStrWithCurrencies($section_totfee) . '</td>';
		}
         $data.='<td height="25" bordercolor="#000000">' . $advice->totalStrWithCurrencies($section_totamt) . '</td>
        <td height="25" bordercolor="#000000">' . $advice->totalStrWithCurrencies($section_totcom) . '</td>
        <td height="25" bordercolor="#000000">' . $advice->totalStrWithCurrencies($section_totst) . '</td>
        <td height="25" bordercolor="#000000">' . $advice->totalStrWithCurrencies($section_totna) . '</td>
      </tr>';

        $data.=' <tr height="20">
        <td height="25" bordercolor="#000000" colspan=2>Refunds</td>
        <td height="25" bordercolor="#000000">&nbsp;</td>
       <td height="25" bordercolor="#000000">' . $refund_total_qty . '</td>';
	   if($show_extra==1){
		 $data.='<td height="25" bordercolor="#000000">' . $advice->totalStrWithCurrencies($refund_totincamtr) . '</td>
          <td height="25" bordercolor="#000000">' . $advice->totalStrWithCurrencies($refund_totfeer) . '</td>';		 
	   }
        $data.='<td height="25" bordercolor="#000000">' . $advice->totalStrWithCurrencies($refund_totamtr) . '</td>
        <td height="25" bordercolor="#000000">' . $advice->totalStrWithCurrencies($refund_totcomr) . '</td>
        <td height="25" bordercolor="#000000">' . $advice->totalStrWithCurrencies($refund_totstr) . '</td>
        <td height="25" bordercolor="#000000">' . $advice->totalStrWithCurrencies($refund_totnar) . '</td>
      </tr>
	    <tr height="20">
        <td height="25" bordercolor="#000000" colspan=3>Net to Pay (Net Amount-Refund Charges)</td>
        <td height="25" bordercolor="#000000">&nbsp;</td>';
		if($show_extra==1){
		    $data.='<td height="25" bordercolor="#000000">&nbsp;</td> 
			<td height="25" bordercolor="#000000">&nbsp;</td>';
		   }
         $data.='<td height="25" bordercolor="#000000">&nbsp;</td>
        <td height="25" bordercolor="#000000">&nbsp;</td>
        <td height="25" bordercolor="#000000">&nbsp;</td>
        <td height="25" bordercolor="#000000">' . $advice->totalStrWithCurrencies($grandTotalamt1) . '</td>
      </tr>';

        $data .= '<tr><td colspan="8">Commissions :';
        foreach ($commision_gateways as $gwk => $cmval) {

//            $exclude_gateway = array('paytm', 'mobikwik');
//            if (in_array(strtolower($gwk), $exclude_gateway)) {
//                continue;
//            }
            if ($gwk == 'ebs' ||$gwk == 'paytm'||$gwk == 'mobikwik') {
//                $gwk = 'Card';
                $tmp_data['Card']=$cmval;
            }
            if ($gwk == "spotcash" || $gwk == "spotcard") {
//                $tmp_data .= ' Counter - ' . $cmval . '%,';
                 $tmp_data['Counter']=$cmval;
            } 
//            else if ($gwk != "spotcard") {
//                $tmp_data .= ' ' . $gwk . ' - ' . $cmval . '%,';
//            }
        }
        //$tmp_data .= 'Card - '.$cmval.'%,';
//        $data .= rtrim($tmp_data, ',');
//        $data .= ', ME Commission - ' . $MEeffortComm . '%</td></tr>';
         $CommLables = NULL;
   if(count($tmp_data) > 0)
   {
 	  foreach($tmp_data as $CommLable => $CommVal)
 	  {
 		  $CommLables.=$CommLable." ".$CommVal."%, ";
 	  }
   }
    $data .= $CommLables.'ME Commission - '.$MEeffortComm.'%</td></tr>';

        $data .= '</table></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>';




        //  My Code starts here
        //print_r($transByGateway);exit;
        $totna = array('INR' => 0, 'USD' => 0);

        //calculating the paypal commision need to take the maximum commision value
        // from(Global paypal,Event level overall,paypal,Mecommision values) 6-5-2015
        //$CommmCard

        $CommmCard = 0;
        $MEeffortComm = 0;
        if (count($ELCommmCard) > 0 && !isset($_REQUEST['applyzero'])) {//event level commision applying
            $me_commisons_array = array(
                "global_paypal" => $PaypalComm,
                "global_me" => $final_MEeffortComm, //$recComm[0]['MEeffort'],
                "event_paypal_value" => $EventLevelPaypalComm, //$ELCommmCard[0]['Paypal'],
                "event_over_all" => $eventOverAllPerc,
                "event_me_value" => $final_MEeffortComm //$ELCommmCard[0]['MEeffort']
            );
//        print_r($me_commisons_array);
            $MEeffortComm = max($me_commisons_array);

            $all_commisons_array = array(
                "global_paypal" => $PaypalComm,
                "event_paypal_value" => $EventLevelPaypalComm, //$ELCommmCard[0]['Paypal'],
                "event_over_all" => $eventOverAllPerc,
            );
            $org_commision = max($all_commisons_array);
        } else if (!isset($_REQUEST['applyzero'])) { //global commision applying
            $me_commisons_array = array(
                "global_paypal" => $PaypalComm,
                "global_me" => $final_MEeffortComm
            );
            $org_commision = $PaypalComm;
            $MEeffortComm = max($me_commisons_array);
        }
//    echo $MEeffortComm .":".$CommmCard;exit;
        foreach ($transByGateway as $Gatewayk => $Gatewayv) {
            $cardCommisionType = 0;
            if (strtolower($Gatewayk) == 'ebs' || strtolower($Gatewayk) == 'mobikwik' || strtolower($Gatewayk) == 'paytm') {
                $cardCommisionType = 1;
            }
            if (strtolower($Gatewayk) == 'paypal' && (count($transByGateway['paypal']) > 0 || count($transrByGateway['paypal']) > 0)) {

                $TranRES = $Gatewayv;
                $coTran = count($TranRES);
                $conversionRate = ($TranRES[0]['conversionRate'] != '' || $TranRES[0]['conversionRate'] != 0) ? $TranRES[0]['conversionRate'] : 1;
                $conversion_currency = ($conversionRate == 1) ? $TranRES[0]['currencyCode'] : 'INR';
                $data.='<tr>
      <td><b>Card Transactions(PayPal)</b></td>
    </tr>
    <tr>
      <td><table width="100%" border="1" cellspacing="0" cellpadding="2">
        <tr height="35">
          <td height="25" width="112" align="left" nowrap="nowrap" bordercolor="#000000"><strong>Receipt No.</strong></td>
          <td height="25" width="150" align="left" nowrap="nowrap" bordercolor="#000000"><strong>DateofTxn</strong></td>
          <td height="25" width="80" align="left" nowrap="nowrap" bordercolor="#000000"><strong>Qty</strong></td>';
		  if($show_extra==1){
		 $data.='<td height="25" width="92" align="left" nowrap="nowrap" bordercolor="#000000"><strong>Amount Paid</strong></td>
        <td height="25" width="90" align="left" nowrap="nowrap" bordercolor="#000000"><strong>Service/<br/>Gateway Fee</strong></td>';	
		}
           $data.='<td height="25" width="92" align="left" nowrap="nowrap" bordercolor="#000000"><strong>Amount</strong></td>
          <td height="25" width="92" align="left" nowrap="nowrap" bordercolor="#000000"><strong>Converted Amount</strong></td>
          <td height="25" width="90" align="left" nowrap="nowrap" bordercolor="#000000"><strong>Commission </strong></td>
          <td height="25" width="90" align="left" nowrap="nowrap" bordercolor="#000000"><strong>ServiceTax<br/>@' . $ServiceTaxComm . '% </strong></td>
          <td height="25" width="90" align="left" nowrap="nowrap" bordercolor="#000000"><strong>NetAmount</strong></td>
                   </tr>';
                $netToPay = NULL;
                if ($coTran > 0) {
                    $currencyType = '';
                    $calComm = NULL;
                    $serTax = NULL;
                    $netAmt = NULL;
                    $totqty = 0;
                    $totamt = NULL;
					$totincamt = NULL;
					$totfee = NULL;
                    $totcom = NULL;
                    $converArr = NULL;
                    $totst = NULL;
                    $totna = NULL;

                    $coTran = count($TranRES);

                    $conversionTxt = $conversionAmount = 0;

                    for ($i = 0; $i < $coTran; $i++) {


//                        if (false && ($TranRES[$i]['ucode'] == '' || $TranRES[$i]['ucode'] === 0) && $TranRES[$i]['referralDAmount'] == 0) { //means, ME sales effort
//                            $CommmCard = $MEeffortComm;
//                            $display_text = ' (ME)';
//                        } else {
//                            $CommmCard = $org_commision;
//                            $display_text = '';
//                        }

                        $CommmCard = $org_commision;
                            $display_text = '';
                        $transid = "TR:" . $TranRES[$i][PaymentTransId];

                        $paypal_converted_amount = $TranRES[$i]['paypal_converted_amount'] != 0 ? $TranRES[$i]['paypal_converted_amount'] : $TranRES[$i][Fees];
                        $y1 = $TranRES[$i][Ccharge];
                        if ($TranRES[$i][paypal_converted_amount] > 0) {
                            $x = $TranRES[$i][Qty] * $paypal_converted_amount;
                            $x1 = $TranRES[$i]['Fees'] * $TranRES[$i]['Qty'];
                            $y = $TranRES[$i][Ccharge];
                            $TranRES[$i]['fromcurrencyCode']='USD';
                            $y1 = ($y * $x) / $x1;
                        }

                        $thisTransAmt = (($TranRES[$i][Qty] * $paypal_converted_amount) - $y1);
						$IncAmt = ($TranRES[$i][Qty] * $paypal_converted_amount);

                        //$conversionAmount = $conversionRate*$thisTransAmt;

                        if ($TranRES[$i]['conversionRate'] > 1) {
                            $conversion_currency = 'INR';
                            if ($TranRES[$i]['paypal_converted_amount'] > 0) {
                                $conversionAmount = round(($paypal_converted_amount * $TranRES[$i]['Qty'] - $y1) * $TranRES[$i]['conversionRate'], 2);
                                $conversionTxt = $conversion_currency . " " . $conversionAmount;
                            } else {

                                $conversionAmount = round($thisTransAmt * $TranRES[$i]['conversionRate'], 2);
                                $conversionTxt = $conversion_currency . " " . $conversionAmount;
                            }
                        } else {
                            if ($TranRES[$i]['paypal_converted_amount'] > 0) {
                                $conversion_currency = 'USD';
                                $conversionAmount = round($paypal_converted_amount * $TranRES[$i]['Qty'] - $y1, 2);
                                $conversionTxt = $conversion_currency . " " . $conversionAmount;
                            } else {
                                $conversion_currency = $TranRES[$i]['currencyCode'];
                                $conversionAmount = $thisTransAmt;
                                $conversionTxt = $conversion_currency . " " . $conversionAmount;
                            }
                        }


                        $calComm = ($conversionAmount) * ($CommmCard / 100);
                        $serTax = $calComm * ($commonFunctions->getServiceTax($Global,$TranRES[$i][SignupDt]) / 100);
                        $netAmt = ($conversionAmount) - $calComm - $serTax;
                        // changed currency code as usd for paypal convertd amount 
                        if ($TranRES[$i]['paypal_converted_amount'] > 0) {
                          //  $thisTransAmt = (($TranRES[$i][Qty] * $TranRES[$i]['Fees']) - $TranRES[$i]['Ccharge'] );
                            // $TranRES[$i]['fromcurrencyCode'] ='INR';
                        }
                        if ($thisTransAmt > 0) {
                            $data.=' <tr>
          <td height="20" align="left" bordercolor="#000000">' . $TranRES[$i][Id] . '</td>
          <td align="left" bordercolor="#000000">' . $commonFunctions->convertTime($TranRES[$i][SignupDt], DEFAULT_TIMEZONE,TRUE) . '</td>
          <td align="left" bordercolor="#000000">' . $TranRES[$i][qty_paid] . '</td>';
		  if($show_extra==1){
			   $data.='<td align="left" bordercolor="#000000">' . $TranRES[$i]['fromcurrencyCode'] . " " . round($IncAmt, 2) . '</td>
			    <td align="left" bordercolor="#000000">' . $TranRES[$i]['fromcurrencyCode'] . " " . round($y1, 2) . '</td>';
		  }
            $data.='<td align="left" bordercolor="#000000">' . $TranRES[$i]['fromcurrencyCode'] . " " . round($thisTransAmt, 2) . '</td>'
                                    . '<td align="left" bordercolor="#000000">' . $conversionTxt . '</td>';

//                  if($TranRES[$i]['ucode']=='' ){ $data.='<td align="left" bordercolor="#000000">'.$conversion_currency.' '.round($calComm,2).' (ME)</td>'; }	
//                  else{  }
                            $data.='<td align="left" bordercolor="#000000">' . $conversion_currency . ' ' . round($calComm, 2) . $display_text . ' </td>';

                            $data.='<td align="left" bordercolor="#000000">' . $conversion_currency . ' ' . round($serTax, 2) . '</td>
          <td align="left" bordercolor="#000000">' . $conversion_currency . ' ' . round($netAmt, 2) . '</td>

        </tr>';
                        }

                        $totqty = $totqty + $TranRES[$i]['qty_paid'];
                        $totamt[$TranRES[$i]['fromcurrencyCode']]+=$thisTransAmt;
						 $totincamt[$TranRES[$i]['fromcurrencyCode']]+=$IncAmt;
						  $totfee[$TranRES[$i]['fromcurrencyCode']]+=$y1;

                        $TranRES[$i]['currencyCode'] = $conversion_currency;

                        $totcom[$TranRES[$i]['currencyCode']]+=$calComm;
                        $totst[$TranRES[$i]['currencyCode']]+=$serTax;
                        $totna[$TranRES[$i]['currencyCode']]+=$netAmt;
                        $converArr[$TranRES[$i]['currencyCode']] += $conversionAmount;
                    }
                    $data.=' 
        <tr height="20">
          <td height="25" bordercolor="#000000">&nbsp;</td>
          <td height="25" bordercolor="#000000">&nbsp;</td>
          <td height="25" bordercolor="#000000">' . $totqty . '</td>';
		  if($show_extra==1){
			   $data.='<td height="25" bordercolor="#000000">' . $advice->totalStrWithCurrencies($totincamt) . '</td>
          <td height="25" bordercolor="#000000">' . $advice->totalStrWithCurrencies($totfee) . '</td>';
		  }
          $data.='<td height="25" bordercolor="#000000">' . $advice->totalStrWithCurrencies($totamt) . '</td>
          <td height="25" bordercolor="#000000">' . $advice->totalStrWithCurrencies($converArr) . '</td>
          <td height="25" bordercolor="#000000">' . $advice->totalStrWithCurrencies($totcom) . '</td>
          <td height="25" bordercolor="#000000">' . $advice->totalStrWithCurrencies($totst) . '</td>
          <td height="25" bordercolor="#000000">' . $advice->totalStrWithCurrencies($totna) . '</td>
        </tr>';
                }

                if (strcmp($Gatewayk, 'paypal') == 0) {
                    $TranRESr = $transrByGateway['paypal'];
                }
                $conversionRater = $TranRESr[0]['conversionRate'];
                $conversion_currency_r = ($conversionRater == 1) ? $TranRESr[0]['currencyCode'] : 'INR';

                $calCommr = NULL;
                $serTaxr = NULL;
                $netAmtr = NULL;
                $totqtyr = NULL;
                $totamtr = NULL;
				$totincamtr = NULL;
				$totfeer = NULL;
                $totcomr = NULL;
                $totstr = NULL;
                $totnar = NULL;
                $coTranr = count($TranRESr);

                $conversionTxtr = 0;
                $conversionAmounter = 0;
                $conversionTOT = array();
                for ($i = 0; $i < $coTranr; $i++) {


                    //$totAmntr = ($TranRESr[$i][Qty]*$TranRESr[$i][Fees])-$TranRESr[$i][Ccharge];

                    if (($TranRESr[$i]['ucode'] == '' || $TranRESr[$i]['ucode'] == 0) && $TranRESr[$i]['referralDAmount'] == 0) { //means, ME sales effort
                        $CommmCard = $MEeffortComm;
                        $display_text = ' (ME)';
                    } else {
                        $CommmCard = $org_commision;
                        $display_text = '';
                    }
                    $paypal_converted_amount = $TranRESr[$i]['paypal_converted_amount'] != 0 ? $TranRESr[$i]['paypal_converted_amount'] : $TranRESr[$i][Fees];
                    $y1 = $TranRESr[$i]['Ccharge'];
                    if ($TranRESr[$i]['paypal_converted_amount'] > 0) {
                        $x = $TranRESr[$i][Qty] * $paypal_converted_amount;
                        $x1 = $TranRESr[$i]['Fees'] * $TranRES[$i]['Qty'];
                        $y = $TranRESr[$i][Ccharge];
                        $y1 = round(($y * $x) / $x1, 2);
                    }
                    $TranRESr[$i]['currencyCode'] = $TranRESr[$i]['paypal_converted_amount'] != 0 ? "USD" : $TranRESr[$i]['currencyCode'];
                    //$conversionAmounter = $totAmntr*$conversionRater;
                    if ($TranRESr[$i]['paypal_converted_amount'] > 0) {
                        $totAmntr = ($TranRESr[$i][Qty] * $paypal_converted_amount) - $y1;
						$totincAmntr = $TranRESr[$i][Qty] * $paypal_converted_amount;
                    } else {
                        $totAmntr = ($TranRESr[$i][Qty] * $TranRESr[$i]['Fees']) - $y1;
						$totincAmntr = $TranRESr[$i][Qty] * $TranRESr[$i]['Fees'];
                    }

                    if ($TranRESr[$i]['conversionRate'] > 1) {
                        $conversion_currency_r = 'INR';
                        if ($TranRESr[$i]['paypal_converted_amount'] > 0) {
                            $conversionAmounter = round(($paypal_converted_amount * $TranRESr[$i]['Qty'] - $y1) * $TranRESr[$i]['conversionRate'], 2);
                            $conversionTOT[$conversion_currency_r] += $conversionAmounter;
                        } else {
                            $conversionAmounter = round($TranRESr[$i]['AMOUNT'] - $y1, 2);
                            $conversionTOT[$conversion_currency_r] += $conversionAmounter;
                        }
                    } else {
                        if ($TranRESr[$i]['paypal_converted_amount'] > 0) {
                            $conversion_currency_r = 'USD';
                            $conversionAmounter = round($paypal_converted_amount * $TranRESr[$i]['Qty'] - $y1, 2);
                            $conversionTOT[$conversion_currency_r] += $conversionAmounter;
                        } else {
                            $conversion_currency_r = $TranRESr[$i]['currencyCode'];
                            $conversionAmounter = $TranRESr[$i]['AMOUNT'] - $y1;
                            $conversionTOT[$conversion_currency_r] += $conversionAmounter;
                        }
                    }
                    $calCommr = ($conversionAmounter) * ($CommmCard / 100);
                    $serTaxr = $calCommr * ($commonFunctions->getServiceTax($Global,$TranRESr[$i][SignupDt]) / 100); 
                    $netAmtr = ($conversionAmounter) - $calCommr - $serTaxr;
                    $totqtyr = $totqtyr + $TranRESr[$i]['qty_paid'];
                    $totamtr[$TranRESr[$i]['currencyCode']] += $totAmntr;
					$totincamtr[$TranRESr[$i]['currencyCode']] += $totincAmntr;
					$totfeer[$TranRESr[$i]['currencyCode']] += $y1;

                    $TranRESr[$i]['currencyCode'] = $conversion_currency_r;

                    $totcomr[$TranRESr[$i]['currencyCode']]+=$calCommr;
                    $totstr[$TranRESr[$i]['currencyCode']]+=$serTaxr;
                    $totnar[$TranRESr[$i]['currencyCode']]+=$netAmtr;
                }
                //round($totna-$totcomr-$totstr,2)
                foreach ($totna as $code => $value) {
                    $subTotal = round($value - ($totcomr[$code] + $totstr[$code]), 2);
                    $netToPay[$code]+=$subTotal;
                    $grandTotalamt2[$code]+=$subTotal;
                }
                $data.=' <tr height="20">
          <td height="25" bordercolor="#000000" colspan=2>Refunds</td>
         <td height="25" bordercolor="#000000">' . $totqtyr . '</td>';
		 if($show_extra==1){
			  $data.='<td height="25" bordercolor="#000000">' . $advice->totalStrWithCurrencies($totincamtr) . '</td>
			  <td height="25" bordercolor="#000000">' . $advice->totalStrWithCurrencies($totfeer) . '</td>';
		 }
           $data.='<td height="25" bordercolor="#000000">' . $advice->totalStrWithCurrencies($totamtr) . '</td>
          <td height="25" bordercolor="#000000">' . $advice->totalStrWithCurrencies($conversionTOT) . '</td>
          <td height="25" bordercolor="#000000">' . $advice->totalStrWithCurrencies($totcomr) . '</td>
          <td height="25" bordercolor="#000000">' . $advice->totalStrWithCurrencies($totstr) . '</td>
          <td height="25" bordercolor="#000000">' . $advice->totalStrWithCurrencies($totnar) . '</td>
        </tr>
              <tr height="20">
          <td height="25" bordercolor="#000000" colspan=3>Net to Pay (Net Amount-Refund Charges)</td>
          <td height="25" bordercolor="#000000">&nbsp;</td>';
		   if($show_extra==1){
		    $data.='<td height="25" bordercolor="#000000">&nbsp;</td> 
			<td height="25" bordercolor="#000000">&nbsp;</td>';
		   }
		  
           $data.='<td height="25" bordercolor="#000000">&nbsp;</td>
          <td height="25" bordercolor="#000000">&nbsp;</td>
          <td height="25" bordercolor="#000000">&nbsp;</td>
          <td height="25" bordercolor="#000000">' . $advice->totalStrWithCurrencies($grandTotalamt2) . '</td>
        </tr>';


                $data .= '<tr><td colspan="8">Commissions : ME Commission - ' . $MEeffortComm . '%, Paypal -' . $org_commision . '% </td> </tr>';

                $data.='</table></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
    </tr>';
            }
        }


        $complete_grand_total = array();

        foreach ($grandTotalamt1 as $tkey1 => $tval1) {
            $complete_grand_total[$tkey1] += $tval1;
        }

        foreach ($grandTotalamt2 as $tkey2 => $tval2) {
            $complete_grand_total[$tkey2] += $tval2;
        }

        // My Code ends here
        if ($_REQUEST['COD'] == 1) {

            $CommmCOD = $CodComm;
            if (count($ELCommmCard) > 0) {
                for ($i = 0; $i < count($ELCommmCard); $i++) {
                    if ($ELCommmCard[$i]['type'] == 2) {
                        $EventLevelCodComm = $ELCommmCard[$i]['value'];
                    }
                }
                //$EventLevelCodComm = $ELCommmCard[0]['Cod']; //Cod commission

                if (!isset($_REQUEST['applyzero'])) {
                    if ($eventOverAllPerc == 0) {
                        if ($EventLevelCodComm > 0) {
                            $CommmCOD = $EventLevelCodComm;
                        }
                    } else {
                        $CommmCOD = $eventOverAllPerc;
                    }
                } else {
                    $CommmCOD = 0;
                }



                $EventLevelMEeffortComm = $ELCommmCard[0]['MEeffort']; //MEeffortComm commission
                if ($EventLevelMEeffortComm == 0 || $EventLevelMEeffortComm == "") {
                    if (!isset($_REQUEST['applyzero'])) {
                        if ($eventOverAllPerc == 0) {
                            $MEeffortComm = $MEeffortComm;
                        } else {
                            $MEeffortComm = $eventOverAllPerc;
                        }
                    } else {
                        $MEeffortComm = 0;
                    }
                } else {
                    if (!isset($_REQUEST['applyzero'])) {
                        if ($eventOverAllPerc == 0) {
                            $MEeffortComm = $EventLevelMEeffortComm;
                        } else {
                            $MEeffortComm = $eventOverAllPerc;
                        }
                    } else {
                        $MEeffortComm = 0;
                    }
                }
            }

            $COD = "SELECT estd.ticketquantity as NumOfTickets,estd.amount as TicketAmt,s.eventid as EventId, s.id as Id, s.signupdate as SignupDt, e.title AS Details, s.quantity as Qty,(s.totalamount/s.quantity) as Fees, s.paymenttransactionid as PaymentTransId,s.discount as PromotionCode,s.paymentmodeid as PaymentModeId,s.paymentstatus as eChecked,s.eventextrachargeamount as Ccharge,s.promotercode as ucode,s.referraldiscountamount as referralDAmount FROM eventsignup AS s INNER JOIN eventsignupticketdetail estd ON estd.eventsignupid=s.id INNER JOIN event AS e ON s.eventid = e.id WHERE s.paymentgatewayid=2 and s.eventid=$_REQUEST[EventId]  $SqDate $offline_Sql and s.paymentstatus ='Verified' order by s.id,s.signupdate";
            $TranCODRESt = $Global->SelectQuery($COD);
            $TranCODRES = $advice->getCODPaidTrans($TranCODRESt);

            $CODrefund = "SELECT estd.ticketquantity as NumOfTickets,estd.amount as TicketAmt,s.eventid as EventId, s.id as Id, s.signupdate as SignupDt, e.title AS Details, s.quantity as Qty, (s.totalamount/s.quantity) as Fees, s.paymenttransactionid as PaymentTransId,s.discount as PromotionCode,s.paymentmodeid as PaymentModeId,s.paymentstatus as eChecked,s.eventextrachargeamount as Ccharge,s.promotercode as ucode,s.referraldiscountamount as referralDAmount FROM eventsignup AS s INNER JOIN event AS e ON s.eventid = e.id  INNER JOIN eventsignupticketdetail estd ON estd.eventsignupid=s.id WHERE s.paymentgatewayid=2  and s.eventid=$_REQUEST[EventId]  $SqDate $offline_Sql and s.paymentstatus='Refunded' order by s.id,s.signupdate";
            $TranCODrefundRESt = $Global->SelectQuery($CODrefund);
            $TranCODrefundRES = $advice->getCODPaidTrans($TranCODrefundRESt);

            $data.=' <tr>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td><b>COD Transactions</b></td>
  </tr>
  <tr>
    <td><table width="100%" border="1" cellspacing="0" cellpadding="2">
      <tr height="35">
        <td height="25" width="112" align="left" nowrap="nowrap" bordercolor="#000000"><strong>ReceiptNo.</strong></td>
        <td height="25" width="150" align="left" nowrap="nowrap" bordercolor="#000000"><strong>PGIRef.No.</strong></td>
        <td height="25" width="150" align="left" nowrap="nowrap" bordercolor="#000000"><strong>DateofTxn</strong></td>
        <td height="25" width="80" align="left" nowrap="nowrap" bordercolor="#000000"><strong>Qty</strong></td>
        <td height="25" width="112" align="left" nowrap="nowrap" bordercolor="#000000"><strong>Amount<br/>(Rs)</strong></td>
        <td height="25" width="120" align="left" nowrap="nowrap" bordercolor="#000000"><strong>Commission(' . $CommmCOD . '%) <br/>ME Commission ' . $MEeffortComm . ' %</strong></td>
        <td height="25" width="120" align="left" nowrap="nowrap" bordercolor="#000000"><strong>ServiceTax<br/>@' . $ServiceTaxComm . '%</strong></td>
        <td height="25" width="112" align="left" nowrap="nowrap" bordercolor="#000000"><strong>NetAmount</strong></td>
		</tr>';
            $calComm3 = 0;
            $serTax3 = 0;
            $netAmt3 = 0;
            $totqty3 = 0;
            $totamt3 = 0;
            $totcom3 = 0;
            $totst3 = 0;


            $totna3 = 0;
            $coTran3 = count($TranCODRES);
            for ($i = 0; $i < $coTran3; $i++) {
                if (count($ELCommmCard) > 0) {
                    $EventLevelCodComm = $ELCommmCard[0]['Cod']; //COD commission
                    //Paypal commission
                    if ($EventLevelCodComm == "" || $EventLevelCodComm == 0) { //if event level value is Zero then default value
                        if (!isset($_REQUEST['applyzero'])) {
                            if ($eventOverAllPerc == 0) {
                                $CommmCOD = $CodComm;
                            } else {
                                $CommmCOD = $eventOverAllPerc;
                            }
                        } else {
                            $CommmCOD = 0;
                        }
                    } else {
                        if (!isset($_REQUEST['applyzero'])) {
                            if ($eventOverAllPerc == 0) {
                                $CommmCOD = $EventLevelCodComm;
                            } else {
                                $CommmCOD = $eventOverAllPerc;
                            }
                        } else {
                            $CommmCOD = 0;
                        }
                    }
                    //else,  event level value
                    //MEeffort commission
                    if (($TranCODRES[$i]['ucode'] == '' || $TranCODRES[$i]['ucode'] == 0) && $TranCODRES[$i]['referralDAmount'] == 0) {
                        $EventLevelMEeffortComm = $ELCommmCard[0]['MEeffort']; //MEeffortComm commission
                        if ($EventLevelMEeffortComm == 0 || $EventLevelMEeffortComm == "") {
                            if (!isset($_REQUEST['applyzero'])) {
                                if ($eventOverAllPerc == 0) {
                                    $CommmCOD = $MEeffortComm;
                                } else {
                                    $CommmCOD = $eventOverAllPerc;
                                }
                            } else {
                                $CommmCOD = 0;
                            }
                        } else {
                            if (!isset($_REQUEST['applyzero'])) {
                                if ($eventOverAllPerc == 0) {
                                    $CommmCOD = $EventLevelMEeffortComm;
                                } else {
                                    $CommmCOD = $eventOverAllPerc;
                                }
                            } else {
                                $CommmCOD = 0;
                            }
                        }
                    }
                } else {
                    if (($TranCODRES[$i]['ucode'] == '' || $TranCODRES[$i]['ucode'] == 0) && $TranCODRES[$i]['referralDAmount'] == 0) { //means, ME sales effort
                        $CommmCOD = $MEeffortComm;
                        if (isset($_REQUEST['applyzero'])) {
                            $CommmCOD = 0;
                        }
                    } else {
                        $CommmCOD = $CodComm;
                        if (isset($_REQUEST['applyzero'])) {
                            $CommmCOD = 0;
                        }
                    }
                }
                //echo $CommmCOD;
                $calComm3 = (($TranCODRES[$i][Qty] * $TranCODRES[$i][Fees]) - $TranCODRES[$i][Ccharge]) * ($CommmCOD / 100);

                //$calComm3=(($TranCODRES[$i][Qty]*$TranCODRES[$i][Fees])-$TranCODRES[$i][Ccharge])*($Commm3/100);
				$serTax3 = $calComm3 * ($commonFunctions->getServiceTax($Global,$TranCODRES[$i][SignupDt]) / 100);
                $netAmt3 = (($TranCODRES[$i][Qty] * $TranCODRES[$i][Fees]) - $TranCODRES[$i][Ccharge]
                        ) - $calComm3 - $serTax3;



                $transid3 = "CashonDelivery";

                $data.=' <tr>
        <td height="20" align="left" bordercolor="#000000">' . $TranCODRES[$i][Id] . '</td>
        <td align="left" bordercolor="#000000">' . $transid3 . '</td>
        <td align="left" bordercolor="#000000">' . date("d/m/Y H:i", strtotime($TranCODRES[$i][SignupDt])) . '</td>
        <td align="left" bordercolor="#000000">' . $TranCODRES[$i]['qty_paid'] . '</td>
        <td align="left" bordercolor="#000000">' . (($TranCODRES[$i][Qty] * $TranCODRES[$i][Fees]) - $TranCODRES[$i][Ccharge]
                        ) . '</td>';

                if (($TranCODRES[$i]['ucode'] == '' || $TranCODRES[$i]['ucode'] == 0) && $TranCODRES[$i]['referralDAmount'] == 0) {
                    $data.=' <td align="left" bordercolor="#000000">' . round($calComm3, 2) . ' (ME)</td>';
                } else {
                    $data.=' <td align="left" bordercolor="#000000">' . round($calComm3, 2) . '</td>';
                }


                $data.='<td align="left" bordercolor="#000000">' . round($serTax3, 2) . '</td>
        <td align="left" bordercolor="#000000">' . round($netAmt3, 2) . '</td>
		
      </tr>';
                $totqty3 = $totqty3 + $TranCODRES[$i]['qty_paid'];
                $totamt3 = $totamt3 + (($TranCODRES[$i][Qty] * $TranCODRES[$i][Fees]) - $TranCODRES[$i][Ccharge]
                        );
                $totcom3 = $totcom3 + $calComm3;
                $totst3 = $totst3 + $serTax3;
                $totna3 = $totna3 + $netAmt3;
            }


            $data.=' 
      <tr height="20">
        <td height="25" bordercolor="#000000">&nbsp;</td>
        <td height="25" bordercolor="#000000">&nbsp;</td>
        <td height="25" bordercolor="#000000">&nbsp;</td>
        <td height="25" bordercolor="#000000">' . $totqty3 . '</td>
        <td height="25" bordercolor="#000000">' . $totamt3 . '</td>
        <td height="25" bordercolor="#000000">' . round($totcom3, 2) . '</td>
        <td height="25" bordercolor="#000000">' . round($totst3, 2) . '</td>
        <td height="25" bordercolor="#000000">' . round($totna3, 2) . '</td>
      </tr>';
            $calrComm3 = 0;
            $serrTax3 = 0;
            $netrAmt3 = 0;
            $totrqty3 = 0;
            $totramt3 = 0;
            $totrcom3 = 0;
            $totrst3 = 0;
            $totrna3 = 0;
            $corTran3 = count($TranCODrefundRES);
            for ($i = 0; $i < $corTran3; $i++) {

                if (count($ELCommmCard) > 0) {
                    $EventLevelCodComm = $ELCommmCard[0]['Cod']; //COD commission
                    //Paypal commission
                    if ($EventLevelCodComm == "" || $EventLevelCodComm == 0) { //if event level value is Zero then default value
                        if (!isset($_REQUEST['applyzero'])) {
                            if ($eventOverAllPerc == 0) {
                                $CommmCOD = $CodComm;
                            } else {
                                $CommmCOD = $eventOverAllPerc;
                            }
                        } else {
                            $CommmCOD = 0;
                        }
                    } else {
                        if (!isset($_REQUEST['applyzero'])) {
                            if ($eventOverAllPerc == 0) {
                                $CommmCOD = $EventLevelCodComm;
                            } else {
                                $CommmCOD = $eventOverAllPerc;
                            }
                        } else {
                            $CommmCOD = 0;
                        }
                    }
                    //else,  event level value
                    //MEeffort commission
                    if (($TranCODrefundRES[$i]['ucode'] == '' || $TranCODrefundRES[$i]['ucode'] == 0) && $TranCODrefundRES[$i]['referralDAmount'] == 0) {
                        //$EventLevelMEeffortComm = 10; //$ELCommmCard[0]['MEeffort']; //MEeffortComm commission
                        if ($EventLevelMEeffortComm == 0 || $EventLevelMEeffortComm == "") {
                            if (!isset($_REQUEST['applyzero'])) {
                                if ($eventOverAllPerc == 0) {
                                    $CommmCOD = $MEeffortComm;
                                } else {
                                    $CommmCOD = $eventOverAllPerc;
                                }
                            } else {
                                $CommmCOD = 0;
                            }
                        } else {
                            if (!isset($_REQUEST['applyzero'])) {
                                if ($eventOverAllPerc == 0) {
                                    $CommmCOD = $EventLevelMEeffortComm;
                                } else {
                                    $CommmCOD = $eventOverAllPerc;
                                }
                            } else {
                                $CommmCOD = 0;
                            }
                        }
                    }
                } else {
                    if (($TranCODrefundRES[$i]['ucode'] == '' || $TranCODrefundRES[$i]['ucode'] == 0) && $TranCODrefundRES[$i]['referralDAmount'] == 0) { //means, ME sales effort
                        $CommmCOD = $MEeffortComm;
                        if (isset($_REQUEST['applyzero'])) {
                            $CommmCOD = 0;
                        }
                    } else {
                        $CommmCOD = $CodComm;
                        if (isset($_REQUEST['applyzero'])) {
                            $CommmCOD = 0;
                        }
                    }
                }

                $calrComm3 = ($TranCODrefundRES[$i][Qty] * $TranCODrefundRES[$i][Fees]) * ($CommmCOD / 100);
                $serrTax3 = $calrComm3 * ($commonFunctions->getServiceTax($Global,$TranCODrefundRES[$i][SignupDt]) / 100);
				$netrAmt3 = ($TranCODrefundRES[$i][Qty] * $TranCODrefundRES[$i][Fees]) - $calrComm3 - $serrTax3;
                $totrqty3 = $totrqty3 + $TranCODrefundRES[$i]['qty_paid'];
                $totramt3 = $totramt3 + ($TranCODrefundRES[$i][Qty] * $TranCODrefundRES[$i][Fees]);
                $totrcom3 = $totrcom3 + $calrComm3;
                $totrst3 = $totrst3 + $serrTax3;
                $totrna3 = $totrna3 + $netrAmt3;
            }


            $data.=' <tr height="20">
        <td height="25" bordercolor="#000000" colspan=2>Refunds</td>
        <td height="25" bordercolor="#000000">&nbsp;</td>
         <td height="25" bordercolor="#000000">' . $totrqty3 . '</td>
        <td height="25" bordercolor="#000000">' . $totramt3 . '</td>
        <td height="25" bordercolor="#000000">' . round($totrcom3, 2) . '</td>
        <td height="25" bordercolor="#000000">' . round($totrst3, 2) . '</td>
        <td height="25" bordercolor="#000000">' . round($totrna3, 2) . '</td>
      </tr>
	   <tr height="20">
        <td height="25" bordercolor="#000000" colspan=3>Net to Pay (Net Amount-Refund Charges)</td>
        <td height="25" bordercolor="#000000">&nbsp;</td>
        <td height="25" bordercolor="#000000">&nbsp;</td>
        <td height="25" bordercolor="#000000">&nbsp;</td>
        <td height="25" bordercolor="#000000">&nbsp;</td>
        <td height="25" bordercolor="#000000">' . round(($totna3 - ($corTran3 * 40) - ($totrcom3 + $totrst3)), 2) . '</td>
      </tr>

	  </table></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>';
        }
        $listingFee = isset($_POST['Cheque']) ? 1124 : 0;

        if ($_REQUEST['payatcounter'] == 1) {
            $CommmCounter = $CounterComm;
            if (count($ELCommmCard) > 0) {
                $EventLevelCounterComm = $ELCommmCard[0]['Counter']; //Counter commission
                for ($i = 0; $i < count($ELCommmCard); $i++) {
                    if ($ELCommmCard[$i]['type'] == 3) {
                        $EventLevelCounterComm = $ELCommmCard[$i]['value'];
                    }
                }
                if (!isset($_REQUEST['applyzero'])) {
                    if ($eventOverAllPerc == 0) {
                        if ($EventLevelCounterComm > 0) {
                            $CommmCounter = $EventLevelCounterComm;
                        }
                    } else {
                        $CommmCounter = $eventOverAllPerc;
                    }
                } else {
                    $CommmCounter = 0;
                }


                //$EventLevelMEeffortComm = 10 ;// $ELCommmCard[0]['MEeffort']; //MEeffortComm commission
                if ($EventLevelMEeffortComm == 0 || $EventLevelMEeffortComm == "") {
                    if (!isset($_REQUEST['applyzero'])) {
                        if ($eventOverAllPerc == 0) {
                            $CommMEeffort = $MEeffortComm;
                        } else {
                            $CommMEeffort = $eventOverAllPerc;
                        }
                    } else {
                        $CommMEeffort = 0;
                    }
                } else {
                    if (!isset($_REQUEST['applyzero'])) {
                        if ($eventOverAllPerc == 0) {
                            $CommMEeffort = $EventLevelMEeffortComm;
                        } else {
                            $CommMEeffort = $eventOverAllPerc;
                        }
                    } else {
                        $CommMEeffort = 0;
                    }
                }
            }




            $Counter = "SELECT s.eventid as EventId, s.id as Id, s.signupdate as SignupDt, e.title as Details, s.quantity as Qty, (s.totalamount/s.quantity) as Fees, s.paymenttransactionid as PaymentTransId,s.discount as PromotionCode,s.paymentmodeid as PaymentModeId,s.paymentstatus as eChecked,s.eventextrachargeamount as Ccharge,s.promotercode as ucode,s.referraldiscountamount as referralDAmount FROM eventsignup AS s, event AS e where s.eventid = e.id AND s.discount='PayatCounter' and s.eventid=$_REQUEST[EventId]  $SqDate $offline_Sql and s.paymentstatus!='Refunded' and s.paymentstatus!='Canceled'  order by s.signupdate";
            $TranCounterRES = $Global->SelectQuery($Counter);


            $data.=' <tr>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td><b>Counter Transactions</b></td>
  </tr>
  <tr>
    <td><table width="100%" border="1" cellspacing="0" cellpadding="2">
      <tr height="35">
        <td height="25" width="112" align="left" nowrap="nowrap" bordercolor="#000000"><strong>Receipt No.</strong></td>
        <td height="25" width="150" align="left" nowrap="nowrap" bordercolor="#000000"><strong>PGIRef. No.</strong></td>
        <td height="25" width="150" align="left" nowrap="nowrap" bordercolor="#000000"><strong>DateofTxn</strong></td>
        <td height="25" width="80" align="left" nowrap="nowrap" bordercolor="#000000"><strong>Qty</strong></td>
        <td height="25" width="92" align="left" nowrap="nowrap" bordercolor="#000000"><strong>Amount<br/>(Rs)</strong></td>
        <td height="25" width="90" align="left" nowrap="nowrap" bordercolor="#000000"><strong>Commission(' . $CommmCounter . '%) <br/>ME Commission ' . $CommMEeffort . ' %</strong></td>
        <td height="25" width="90" align="left" nowrap="nowrap" bordercolor="#000000"><strong>ServiceTax<br/>@' . $ServiceTaxComm . '%</strong></td>
        <td height="25" width="90" align="left" nowrap="nowrap" bordercolor="#000000"><strong>NetAmount</strong></td>
		
      </tr>';
            $calComm2 = 0;
            $serTax2 = 0;
            $netAmt2 = 0;
            $totqty2 = 0;
            $totamt2 = 0;
            $totcom2 = 0;
            $totst2 = 0;
            $totna2 = 0;
            $coTran2 = count($TranCounterRES);
            for ($i = 0; $i < $coTran2; $i++) {
                if (count($ELCommmCard) > 0) {
                    $EventLevelCounterComm = $ELCommmCard[0]['Counter']; //COD commission
                    //Counter commission
                    if ($EventLevelCounterComm == "" || $EventLevelCounterComm == 0) { //if event level value is Zero then default value
                        if (!isset($_REQUEST['applyzero'])) {
                            if ($eventOverAllPerc == 0) {
                                $CommmCounter = $CounterComm;
                            } else {
                                $CommmCounter = $eventOverAllPerc;
                            }
                        } else {
                            $CommmCounter = 0;
                        }
                    } else {
                        if (!isset($_REQUEST['applyzero'])) {
                            if ($eventOverAllPerc == 0) {
                                $CommmCounter = $EventLevelCounterComm;
                            } else {
                                $CommmCounter = $eventOverAllPerc;
                            }
                        } else {
                            $CommmCounter = 0;
                        }
                    }  //else,  event level value
                    //MEeffort commission
                    $EventLevelMEeffortComm = $ELCommmCard[0]['MEeffort']; //MEeffort commission
                    if (($TranCounterRES[$i]['ucode'] == '' || $TranCounterRES[$i]['ucode'] == 0) && $TranCounterRES[$i]['referralDAmount'] == 0) {
                        if ($EventLevelMEeffortComm == "" || $EventLevelMEeffortComm == 0) { //if event level value is Zero then default value
                            if (!isset($_REQUEST['applyzero'])) {
                                if ($eventOverAllPerc == 0) {
                                    $CommmCounter = $MEeffortComm;
                                } else {
                                    $CommmCounter = $eventOverAllPerc;
                                }
                            } else {
                                $CommmCounter = 0;
                            }
                        } else {
                            if (!isset($_REQUEST['applyzero'])) {
                                if ($eventOverAllPerc == 0) {
                                    $CommmCounter = $EventLevelMEeffortComm;
                                } else {
                                    $CommmCounter = $eventOverAllPerc;
                                }
                            } else {
                                $CommmCounter = 0;
                            }
                        }  //else,  event level value  
                    }
                } else {
                    if (($TranCounterRES[$i]['ucode'] == '' || $TranCounterRES[$i]['ucode'] == 0) && $TranCounterRES[$i]['referralDAmount'] == 0) { //means, ME sales effort
                        $CommmCounter = $MEeffortComm;
                        if (isset($_REQUEST['applyzero'])) {
                            $CommmCounter = 0;
                        }
                    } else {
                        $CommmCounter = $CounterComm;
                        if (isset($_REQUEST['applyzero'])) {
                            $CommmCounter = 0;
                        }
                    }
                }

                $calComm2 = (($TranCounterRES[$i][Qty] * $TranCounterRES[$i][Fees]) - $TranCounterRES[$i][Ccharge]) * ($CommmCounter / 100);
                 $serTax2 = $calComm2 * ($commonFunctions->getServiceTax($Global,$TranCounterRES[$i][SignupDt]) / 100);    
                $netAmt2 = (($TranCounterRES[$i][Qty] * $TranCounterRES[$i][Fees]) - $TranCounterRES[$i][Ccharge]) - $calComm2 - $serTax2;



                $transid2 = "PayatCounter";

                $data.=' <tr>
        <td height="20" align="left" bordercolor="#000000">' . $TranCounterRES[$i][Id] . '</td>
        <td align="left" bordercolor="#000000">' . $transid2 . '</td>
        <td align="left" bordercolor="#000000">' . date("d/m/Y H:i", strtotime($TranCounterRES[$i][SignupDt])) . '</td>
        <td align="left" bordercolor="#000000">' . $TranCounterRES[$i][Qty] . '</td>
        <td align="left" bordercolor="#000000">' . (($TranCounterRES[$i][Qty] * $TranCounterRES[$i][Fees]) - $TranCounterRES[$i][Ccharge]) . '</td>';

                if (($TranCounterRES[$i]['ucode'] == '' || $TranCounterRES[$i]['ucode'] == 0) && $TranCounterRES[$i]['referralDAmount'] == 0) {
                    $data.=' <td align="left" bordercolor="#000000">' . round($calComm2, 2) . ' (ME)</td>';
                } else {
                    $data.=' <td align="left" bordercolor="#000000">' . round($calComm2, 2) . '</td>';
                }

                $data.='<td align="left" bordercolor="#000000">' . round($serTax2, 2) . '</td>
        <td align="left" bordercolor="#000000">' . round($netAmt2, 2) . '</td>
		 
      </tr>';
                $totqty2 = $totqty2 + $TranCounterRES[$i][Qty];
                $totamt2 = $totamt2 + (($TranCounterRES[$i][Qty] * $TranCounterRES[$i][Fees]) - $TranCounterRES[$i][Ccharge]);
                $totcom2 = $totcom2 + $calComm2;
                $totst2 = $totst2 + $serTax2;
                $totna2 = $totna2 + $netAmt2;
            }


            $data.=' 
      <tr height="20">
        <td height="25" bordercolor="#000000">&nbsp;</td>
        <td height="25" bordercolor="#000000">&nbsp;</td>

        <td height="25" bordercolor="#000000">&nbsp;</td>
        <td height="25" bordercolor="#000000">' . $totqty2 . '</td>
        <td height="25" bordercolor="#000000">' . $totamt2 . '</td>
        <td height="25" bordercolor="#000000">' . round($totcom2, 2) . '</td>
        <td height="25" bordercolor="#000000">' . round($totst2, 2) . '</td>
        <td height="25" bordercolor="#000000">' . round($totna2, 2) . '</td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>';
        }


        if (isset($_POST['Cheque'])) {
            $data.='
            <tr>
                <td ><table width="100%" border="1" cellspacing="0" cellpadding="2">';
            //$netTotal=round($totna2+$totna1+($totna3-($corTran3*40)-($totrcom3+$totrst3))+($grandTotalamt),2);
            $grandTotalamt['INR']+=round($totna2 + $totna1 + ($totna3 - ($corTran3 * 40) - ($totrcom3 + $totrst3)), 2);

            $data.= '<tr height="35">
                    <td height="25" width="112" colspan=3 align="left" nowrap="nowrap" bordercolor="#000000"><strong>Listing Fee to Pay (inclusive of serive tax @' . $ServiceTaxComm . '%)</strong></td>
                    <td height="25" width="80" align="right" colspan=3 nowrap="nowrap" bordercolor="#000000"><strong>' . $listingFee . '</strong></td>
                </tr>';
            //$netTotal-=$listingFee;  
            $grandTotalamt['INR']-=$listingFee;
            $data .= '</table></td></tr>';
        }



        $totPaidAmt = 0;
        $dataHTML = '';
        $selAllPayments = "SELECT paymentdate as date,amountpaid as AmountP,paymenttype as PType FROM settlement WHERE eventid='" . $_REQUEST['EventId'] . "' and status=1 and paymenttype in ('Partial Payment', 'Done') ORDER BY paymentdate ASC";
        $resAllPayments = $Global->SelectQuery($selAllPayments);
        if (count($resAllPayments) > 0) {
            foreach ($resAllPayments as $k => $v) {
                if (strtolower($v['PType']) == 'done') {
                    $pType = " (Full Payment)";
                    $pDate = $v['date'];
                } else {
                    $pType = "";
                    $pDate = $v['date'];
                }
                $pDate=$commonFunctions->convertTime($pDate, DEFAULT_TIMEZONE,TRUE);
                $pDate= date('Y-m-d',strtotime($pDate));
                $dataHTML.='<tr height="20">
                                                <td  width="116" height="25" colspan="1">' . $v['AmountP'] . '</td>
                                                <td width="200" height="25" colspan="3">' . $pDate . $pType . '</td>
                                            </tr>';
                $totPaidAmt += $v['AmountP'];
            }
        }

        if ($dataHTML != '') {

            $dataHTML .= '<tr height="20">
                            <td height="25" colspan=2>Total Net Amount</td>
                            <td>' . $advice->totalStrWithCurrencies($complete_grand_total) . '</td>
                        </tr>
                        <tr height="20">
                            <td height="25" colspan=2>Partially paid</td>
                            <td>' . $totPaidAmt . '</td>
                        </tr>'; //$grandTotalamt1
            $complete_grand_total['INR'] = $complete_grand_total['INR'] - $totPaidAmt;

            $data.='
                <tr>
                    <td>
                        <table width="100%" cellspacing="0" cellpadding="2">
                            <tr height="35">
                                <td height="25" colspan=2 align="left" nowrap="nowrap" bordercolor="#000000">
                                    <table width="100%" border="1" cellpadding="2" cellspacing="0">
                                        <col width="79" />
                                        <col width="128" />
                                        <tr height="20">
                                            <td height="25" colspan="4"><strong>Partial Payments :</strong></td>
                                        </tr>
                                        <tr height="20">
                                            <td width="116" height="25" colspan="1"><strong>Partial Payment Amt' . $currencyType . '</strong></td>
                                            <td width="200" height="25" colspan="3"><strong>Partial Payment Date</strong></td>
                                        </tr>
                                        ' . $dataHTML . '
                                    </table>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td>&nbsp;</td>
                </tr>';
        }
        $complete_grand_total['INR']-=$listingFee;
        $data.='
            <tr>
                <td ><table width="100%" border="1" cellspacing="0" cellpadding="2">';


        $data.='<tr height="35">
        <td height="25" width="112" colspan=3 align="left" nowrap="nowrap" bordercolor="#000000"><strong>Total Net Amount to Pay</strong></td>
        
        <td height="25" width="80" align="right" colspan=3 nowrap="nowrap" bordercolor="#000000"><strong>' . $advice->totalStrWithCurrencies($complete_grand_total) . '</strong></td>
       </tr></table></td>
      </tr>
   <tr>
    <td><table width="100%" border="0" cellspacing="0" cellpadding="2">
      <tr>
        <td><table width="100%" border="1" cellpadding="2" cellspacing="0">
            <col width="79" />
            <col width="128" />
            <tr height="20">
              <td height="25" colspan="2"><strong>Payment    Details:</strong></td>
              </tr>
            <tr height="20">
              <td width="116" height="25"><strong>Cheque No&nbsp;&nbsp;</strong></td>
              <td width="200" height="25">' . $Chqno . '</td>
            </tr>
            <tr height="20">
              <td height="25"><strong>Cheque Date</strong></td>
              <td height="25">' . $Chqdt . '</td>
            </tr>
            <tr height="20">
              <td height="25"><strong>Bank Name</strong></td>
              <td height="25">ICICI</td>
            </tr>
            <tr height="20">
              <td height="25"><strong>Branch</strong></td>
              <td height="25">Madhapur</td>
            </tr>
        </table></td>
       <td width="65%" align="center" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="2">
          <tr>
            <td align="right"><strong>Verified and Confirmed By</strong></td>
          </tr>
		   <tr>
            <td width="71%" height="50" align="right">&nbsp;</td>
            <td width="29%" align="right">&nbsp;</td>
          </tr>
		    <tr>
            <td width="71%" height="19" align="right"><strong>Signatory</strong></td>
            <td width="29%" align="right">&nbsp;</td>
          </tr>
		    <tr>
            <td width="90%" height="19" align="right"><strong>Please Process the Payment</strong></td>
            <td width="10%" align="right">&nbsp;</td>
          </tr>
        </table></td>      </tr>
     
      <tr>
        <td height="45" colspan="2" ><table width="100%" border="1" cellpadding="2" cellspacing="0">
        
          <tr height="20">
            <td height="25" colspan="2"><strong>Organizer Account   Details:</strong></td>
            </tr>
          <tr height="20">
            <td height="25" width="200"><strong>Beneficiary  Name</strong></td>
            <td width="900" height="25">' . $AccName . '</td>
            </tr>
          <tr height="20">
            <td height="25" width="200"><strong>Beneficiary Ac/No</strong></td>
            <td height="25" width="900">' . $Accno . '</td>
            </tr>
			<tr height="20">
            <td height="25" width="200"><strong>Account Type(SB/CA)</strong></td>
            <td height="25" width="900">' . $Acctype . '</td>
            </tr>
          <tr height="20" >
            <td width="200" height="25"><strong>Bank Name&nbsp;</strong></td>
            <td height="25" width="900">' . $BnkName . '</td>
            </tr>
          <tr height="20" >
            <td height="25" width="200"><strong>Bank Branch & Address</strong></td>
            <td height="25" width="900">' . $BnkBranch . '</td>
            </tr>
			 <tr height="20" >
            <td height="25" width="200"><strong>IFSC Code</strong></td>
            <td height="25" width="900">' . $IFCS . '</td>
            </tr>
        </table></td>
      </tr>
    </table></td>
  </tr>
  <tr></tr>
</table>
</body>
</html>
';
        $msg = $data;
        $data = utf8_encode($data);

        if ($_REQUEST['export'] == "ExportPaymentAdvice") {
            echo $data;
            exit;
        } elseif ($_REQUEST['download'] == "EmailPaymentAdvice") {
//$mpdf->SetDisplayMode('fullpage');
//$mpdf->useOnlyCoreFonts = true; 
            //$mpdf->WriteHTML($data);
            //$pdf=$mpdf->Output();
            //$mpdf->Output($Title.'Advice.pdf','D');
            $subject = "Payment Advice for the event : " . stripslashes($Title);


            $cc = $content = $filename = $replyto = NULL;

            $from = 'MeraEvents<admin@meraevents.com>';
            $bcc = 'qison@meraevents.com';
            $folder = 'ctrl';
            $commonFunctions->sendEmail('sales@meraevents.com', $cc, $bcc, $from, $replyto, $subject, $msg, $content, $filename, $folder);
            $_SESSION['PaymentAdviceMailSent'] = true;

            //mail('sales@meraevents.com', $subject, $msg, $headers);         
        }
    }
} /* else if ($_REQUEST['EventId'] != "" && $_REQUEST['add_update_bankdetails'] == 'Add/UpdateBankDetails') {

  $AccName = $_REQUEST['AccName'];
  $Accno = $_REQUEST['Accno'];
  $BnkName = $_REQUEST['BnkName'];
  $BnkBranch = $_REQUEST['BnkBranch'];
  $Acctype = $_REQUEST['Acctype'];
  $IFCS = $_REQUEST['IFCS'];

  $bank_details_action = 'Add';

  if ($AccName != '' && $BnkName != '' && $Accno != '' && $Acctype != '' && $BnkBranch != '' && $IFCS != '') {
  $UserID = $Global->GetSingleFieldValue("select UserID from events where Id='" . $_REQUEST['EventId'] . "'");
  $BankQuery = "select * from orgbank  where UserID=" . $UserID; //using 6/8 -pH
  $BankQueryRES = $Global->SelectQuery($BankQuery);
  //print_r($BankQueryRES);exit;
  if (count($BankQueryRES) > 0) {

  $bankupdate = "update orgbank set AccName='" . $AccName . "',BankName='" . $BnkName . "',AccNo='" . $Accno . "',AccType='" . $Acctype . "',Branch='" . $BnkBranch . "',IFCSCode='" . $IFCS . "' where UserID=" . $UserID;
  $Global->ExecuteQuery($bankupdate);
  $bank_details_action = 'Update';
  } else {
  $bankupdate = "insert into orgbank (UserID,AccName,BankName,AccNo,AccType,Branch,IFCSCode) values('" . $UserID . "','" . $AccName . "','" . $BnkName . "','" . $Accno . "','" . $Acctype . "','" . $BnkBranch . "','" . $IFCS . "')";
  $Global->ExecuteQuery($bankupdate);
  $bank_details_action = 'Add';
  }
  $_SESSION['BankDetailsAdded'] = true;
  $_SESSION['bank_details_action'] = $bank_details_action;
  }
  } */



if ($_REQUEST[EventId] != "") {
    $BankQuery = "select     b . *, e.title
                from organizerbankdetail as b,event as e
                where  e.ownerid = b.userid and e.id =" . $_REQUEST[EventId];
    $BankQueryRES = $Global->SelectQuery($BankQuery);
}

/* $EventQuery = "SELECT distinct
  (s.eventid), e.title AS Details
  FROM
  eventsignup AS s
  INNER JOIN
  event AS e ON s.eventid = e.id
  where
  1
  and ((s.paymentmodeid = 1
  and s.paymenttransactionid != 'A1')
  or s.paymentmodeid = 2)
  ORDER BY e.id DESC";
  $EventQueryRES = $Global->SelectQuery($EventQuery);
 */
           }
        }
mysql_close();
include 'templates/paymentInvoice_new.tpl.php';
?>
