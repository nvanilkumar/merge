<?php

include_once("MT/cGlobali.php");
include_once 'includes/common_functions.php';
$commonFunctions = new functions();
$_GET = $commonFunctions->stripData($_GET);


session_start();

$EventId = $_GET['EventId'];
$txtPromoCode = $_GET['txtPromoCode'];
$totQty = $_GET['totQty'];
$tot_amt = $_GET['tot_amt'];
//    $TID=$_GET['TID'];

$arrTicketsId = $_GET['arrTicketsId'];
$txtPromoCode = $_GET['txtPromoCode'];

$reffCode = trim($_GET['reffCode']);
//$reffCode=(!empty($reffCode))?$reffCode:'';



if ($EventId != "" && $totQty != "" && $arrTicketsId != "") {
    //echo rand(20,40);
    $Globali = new cGlobali();
    if ($EventId == 67580) {
        $return = $commonFunctions->bookingCalculations($EventId, $txtPromoCode, $arrTicketsId, $tot_amt, $reffCode, $Globali);
    } else {
        $return = $commonFunctions->applyDiscount($EventId, $txtPromoCode, $arrTicketsId, $tot_amt, $reffCode, $Globali);
    }
    echo $return;
} else {
    $returnArr = array("totalST" => '0.00', "totalET" => '0.00', "totalNormalDiscount" => '0.00', "totalBulkDiscount" => '0.00', "totalReferralDiscount" => '0.00', "purchaseTotal" => '0.00', "discountError" => 0);
    echo json_encode($returnArr, true);
    $totalDiscount = number_format($returnBulkDiscount, 2, '.', '');
}

//$commonFunctions->applyDiscount($EventId, $txtPromoCode, $totQty, $arrTicketsId,$tot_amt)








function applyDiscount_old($EventId, $txtPromoCode, $totQty, $arrTicketsId, $tot_amt) {

    include_once("MT/cEvents.php");
    $Globali = new cGlobali();



//$SelDiscounts = "SELECT * FROM discounts WHERE EventId='".$EventId."' AND ActiveFrom < '".date('Y-m-d H:i:s')."' AND ActiveTo > '".date('Y-m-d H:i:s')."' AND PromotionCode='".$txtPromoCode."' AND UsageLimit > DiscountLevel";
    $SelDiscounts = "SELECT id as Id, `usagelimit` AS UsageLimit, `totalused` DiscountLevel, `calculationmode` DiscountType, `value` DiscountAmt "
            . "FROM `discount` "
            . "WHERE eventid='" . $Globali->dbconn->real_escape_string($EventId) . "' "
            . "AND startdatetime < '" . date('Y-m-d H:i:s') . "' AND enddatetime > '" . date('Y-m-d H:i:s') . "' "
            . "AND code='" . $Globali->dbconn->real_escape_string($txtPromoCode) . "' AND "
            . "usagelimit > totalused";
    $ResDiscounts = $Globali->SelectQuery($SelDiscounts);

    $totalDiscount = 0;
    $cnt_tktIdANDtotDiscount = 0;



    // $SelDiscountsApply = "SELECT * FROM discount_applies_to WHERE DiscountsId='".$ResDiscounts[0]['Id']."'";
    // $ResDiscountsApply = $Globali->SelectQuery($SelDiscountsApply); not being used anywhere -Phinny

    foreach ($arrTicketsId as $key => $val) {
        if (count($ResDiscounts) > 0) {
            $availQtyDiff = $ResDiscounts[0]['UsageLimit'] - $ResDiscounts[0]['DiscountLevel'];
            $tckIdandDiscount = isset($_SESSION['tktIdANDtotDiscount']) ? $_SESSION['tktIdANDtotDiscount'] : array();
            if ($availQtyDiff > $val) {
                $tktIdQry = "SELECT ticketid "
                        . "FROM ticketdiscount "
                        . "WHERE ticketid = '" . $Globali->dbconn->real_escape_string($key) . "' "
                        . "AND discountid=" . $Globali->dbconn->real_escape_string($ResDiscounts[0]['Id']);
                $tktId = $Globali->GetSingleFieldValue($tktIdQry);
                $tId = $key;

                if (($val > 0) && ($tId == $tktId)) {

                    if ($ResDiscounts[0]['DiscountType'] == 0)
                        $totalDiscount += $arrTicketsId[$tId] * $ResDiscounts[0]['DiscountAmt'];
                    else {
                        $tktAmt = $Globali->GetSingleFieldValue("SELECT price FROM ticket WHERE id = '" . $Globali->dbconn->real_escape_string($tId) . "'");
                        $totalDiscount = $totalDiscount + (($arrTicketsId[$tId] * $tktAmt * $ResDiscounts[0]['DiscountAmt']) / 100);
                    }

                    //$UpdtDiscounts = "UPDATE discounts SET DiscountLevel = '".($ResDiscounts[0]['DiscountLevel']+$arrTicketsId[$tId])."' WHERE Id = '".$ResDiscounts[0]['Id']."'";

                    $cnt_tktIdANDtotDiscount = count($tckIdandDiscount);

                    if ($cnt_tktIdANDtotDiscount != 0)
                        $cnt_tktIdANDtotDiscount += 1;

                    $_SESSION['tktIdANDtotDiscount'][$cnt_tktIdANDtotDiscount] = array($tId, $totalDiscount);
                    //$upId = $Globali->ExecuteQuery($UpdtDiscounts);
                }
            }
            else {
                $tktId = $Globali->GetSingleFieldValue("SELECT ticketid FROM ticketdiscount  WHERE ticketid = '" . $Globali->dbconn->real_escape_string($key) . "' and discountid=" . $Globali->dbconn->real_escape_string($ResDiscounts[0]['Id']));
                $tId = $key;

                if (($val > 0) && ($tId == $tktId)) {
                    if ($ResDiscounts[0]['DiscountType'] == 0)
                        $totalDiscount = $availQtyDiff * $ResDiscounts[0]['DiscountAmt'];
                    else {
                        $tktAmt = $Globali->GetSingleFieldValue("SELECT price FROM ticket WHERE id = '" . $Globali->dbconn->real_escape_string($tId) . "'");
                        $totalDiscount = $totalDiscount + (($availQtyDiff * $tktAmt * $ResDiscounts[0]['DiscountAmt']) / 100);
                    }

                    //$UpdtDiscounts = "UPDATE discounts SET DiscountLevel = '".($ResDiscounts[0]['DiscountLevel']+$availQtyDiff)."' WHERE Id = '".$ResDiscounts[0]['Id']."'";

                    $cnt_tktIdANDtotDiscount = count($tckIdandDiscount);

                    if ($cnt_tktIdANDtotDiscount != 0)
                        $cnt_tktIdANDtotDiscount += 1;

                    $_SESSION['tktIdANDtotDiscount'][$cnt_tktIdANDtotDiscount] = array($tId, $totalDiscount);
                    //$upId = $Globali->ExecuteQuery($UpdtDiscounts);
                }
            }
        } else {

            //$SelDiscounts1 = "SELECT * FROM discounts_api WHERE  DiscountCode='".$txtPromoCode."' AND Status=0";
            $SelDiscounts1 = "SELECT `DiscountAmt`, `DiscountPer` FROM `discounts_api` WHERE  DiscountCode='" . $Globali->dbconn->real_escape_string($txtPromoCode) . "' AND Status=0";
            $ResDiscounts1 = $Globali->SelectQuery($SelDiscounts1);


            if (count($ResDiscounts1) > 0) {

                if ($arrTicketsId[$key] > 0) {
                    $totamt = $ResDiscounts1[0]['DiscountAmt'];
                    $totperc = $ResDiscounts1[0]['DiscountPer'];



                    $totalDiscount1 = $totamt;

                    $tktAmt = $Globali->GetSingleFieldValue("SELECT price FROM ticket WHERE id = '" . $Globali->dbconn->real_escape_string($key) . "'");
                    $totalDiscount2 = $totalDiscount2 + (($arrTicketsId[$key] * $tktAmt * $totperc) / 100);
                    if ($totalDiscount1 > $totalDiscount2) {
                        $totalDiscount = $totalDiscount2;
                    } else {
                        $totalDiscount = $totalDiscount1;
                    }
                }

                //$UpdtDiscounts = "UPDATE discounts SET DiscountLevel = '".($ResDiscounts[0]['DiscountLevel']+$arrTicketsId[$tId])."' WHERE Id = '".$ResDiscounts[0]['Id']."'";

                $cnt_tktIdANDtotDiscount = count($tckIdandDiscount);

                if ($cnt_tktIdANDtotDiscount != 0)
                    $cnt_tktIdANDtotDiscount += 1;

                $_SESSION['tktIdANDtotDiscount'][$cnt_tktIdANDtotDiscount] = array($tId, $totalDiscount);
                //$upId = $Globali->ExecuteQuery($UpdtDiscounts);
            }
        }
    }
    if (ceil($totalDiscount) > $tot_amt)
        return $tot_amt . '.00';
    else
        return ceil($totalDiscount) . '.00';
}

?>