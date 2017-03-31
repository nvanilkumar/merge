<?php 
class PaymentAdviceFunctions{
    function getCardTransByGateway($TranRES){
     $countRes=  count($TranRES);
    $incEBS=$incPayPal=$incMobi=$inc=$incPaytm=$incspotcash=$incspotcard=0;
    $TranRESEBS=$TranRESPayPal=$TranRESMobikwik=$TranRESPaytm=$TranRESCash=$TranRESCard=array();$tckCnt=0;
    for($k=0;$k<$countRes;$k++){
		if($TranRES[$k]['TicketAmt']>0){
                $tckCnt+=$TranRES[$k]['NumOfTickets'];
        }
       
        if($TranRES[$k]['Id']!=$TranRES[$k+1]['Id']){
			 
            switch ($TranRES[$k]['PaymentGateway']){
                case 'ebs':$resArray='TranRESEBS';$inc=$incEBS++;break;
                case 'paypal':$resArray='TranRESPayPal';$inc=$incPayPal++;break;
                case 'mobikwik':$resArray='TranRESMobikwik';$inc=$incMobi++;break;
                case 'paytm':$resArray='TranRESPaytm';$inc=$incPaytm++;break;
                case 'spotcash':$resArray='TranRESCash';$inc=$incspotcash++;break;
                case 'spotcard':$resArray='TranRESCard';$inc=$incspotcard++;break;
                default : echo 'no gateway';$resArray='';break;
            } 
            if(strlen($resArray)>0){
                 ${$resArray}[$inc]['EventId']=$TranRES[$k]['EventId'];
                 ${$resArray}[$inc]['Id']=$TranRES[$k]['Id'];
                 ${$resArray}[$inc]['SignupDt']=$TranRES[$k]['SignupDt'];
                 ${$resArray}[$inc]['Details']=$TranRES[$k]['Details'];
                 ${$resArray}[$inc]['Qty']=$TranRES[$k]['Qty'];
                 ${$resArray}[$inc]['qty_paid']=$tckCnt;
                 ${$resArray}[$inc]['Fees']=$TranRES[$k]['Fees'];
                 ${$resArray}[$inc]['PaymentTransId']=$TranRES[$k]['PaymentTransId'];
                 ${$resArray}[$inc]['PromotionCode']=$TranRES[$k]['PromotionCode'];
                 ${$resArray}[$inc]['PaymentModeId']=$TranRES[$k]['PaymentModeId'];
                 ${$resArray}[$inc]['eChecked']=$TranRES[$k]['eChecked'];
                 ${$resArray}[$inc]['Ccharge']=$TranRES[$k]['Ccharge'];
                 ${$resArray}[$inc]['currencyCode']=$TranRES[$k]['tocurrencyCode'];
                 ${$resArray}[$inc]['fromcurrencyCode']=$TranRES[$k]['fromcurrencyCode'];
                 ${$resArray}[$inc]['conversionRate']=$TranRES[$k]['conversionRate'];
                 ${$resArray}[$inc]['PaymentGateway']=$TranRES[$k]['PaymentGateway'];
                 ${$resArray}[$inc]['ucode']=$TranRES[$k]['ucode'];
                 ${$resArray}[$inc]['referralDAmount']=$TranRES[$k]['referralDAmount'];
                 ${$resArray}[$inc]['DAmount']=$TranRES[$k]['DAmount'];
                 ${$resArray}[$inc]['AMOUNT']=$TranRES[$k]['AMOUNT'];
                 ${$resArray}[$inc]['paypal_converted_amount']=$TranRES[$k]['paypal_converted_amount'];
            }
            $tckCnt=0;
        }
    }
    $transByGateway=array('ebs'=>$TranRESEBS,'paypal'=>$TranRESPayPal,'mobikwik'=>$TranRESMobikwik,'paytm'=>$TranRESPaytm,"spotcash"=>$TranRESCash,"spotcard"=>$TranRESCard);
    return $transByGateway;
}
function getCODPaidTrans($TranCODRES){
    $total=count($TranCODRES);
    $res=NULL;$tckCnt=0;$inc=0;
    //print_r($TranCODRES);
    for($k=0;$k<$total;$k++){
        //estd.NumOfTickets,estd.TicketAmt,s.EventId, s.Id, s.SignupDt, e.Title AS Details, s.Qty, s.Fees, s.PaymentTransId,s.PromotionCode,s.PaymentModeId,s.eChecked,s.Ccharge,s.ucode,s.referralDAmount
        if($TranCODRES[$k]['TicketAmt']>0){
            $tckCnt+=$TranCODRES[$k]['NumOfTickets'];
        }
        if($TranCODRES[$k]['Id']!=$TranCODRES[$k+1]['Id']){
            $res[$inc]['EventId']=$TranCODRES[$k]['EventId'];
            $res[$inc]['Id']=$TranCODRES[$k]['Id'];
            $res[$inc]['SignupDt']=$TranCODRES[$k]['SignupDt'];
            $res[$inc]['Details']=$TranCODRES[$k]['Details'];
            $res[$inc]['Qty']=$TranCODRES[$k]['Qty'];
            $res[$inc]['qty_paid']=$tckCnt;
            $res[$inc]['Fees']=$TranCODRES[$k]['Fees'];
            $res[$inc]['PaymentTransId']=$TranCODRES[$k]['PaymentTransId'];
            $res[$inc]['PromotionCode']=$TranCODRES[$k]['PromotionCode'];
            $res[$inc]['PaymentModeId']=$TranCODRES[$k]['PaymentModeId'];
            $res[$inc]['eChecked']=$TranCODRES[$k]['eChecked'];
            $res[$inc]['Ccharge']=$TranCODRES[$k]['Ccharge'];
            $res[$inc]['ucode']=$TranCODRES[$k]['ucode'];
            $res[$inc]['referralDAmount']=$TranCODRES[$k]['referralDAmount'];
            $inc++;
            $tckCnt=0;
        }
    }
    //print_r($res);
    return $res;
}
	  
function totalStrWithCurrencies($totalArray){
    $totalArrayStr='';
    if(count($totalArray)>0){
       ksort($totalArray);
       $tot=NULL;
       foreach($totalArray as $k=>$v){
           if($v!=0){
               $tot.=$k." ".round($v,2)."<br>";
           }

       }
           $totalArrayStr=$tot;
    }
    return strlen($totalArrayStr)>0?$totalArrayStr:0;
}
}
?>