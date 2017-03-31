<form name="payNowForm" id="payNowForm" action="<?php echo site_url('payment/ebsPrepare');?>" method='POST'>
            <input type="hidden" name="eventTitle" value="<?php echo preg_replace("/[^A-Za-z0-9]/","", stripslashes($eventTitle))?>" />
            <input type="hidden" name="orderId" value="<?php echo $orderId; ?>" />
            <input type="hidden" name="token" value="<?php echo $accessToken; ?>" />
            <input type="hidden" name="paymentGatewayKey" value="<?php echo $paymentGatewayKey; ?>" />
            <input type="hidden" name="primaryAddress" class="primaryAddress" id="primaryAddress" value="<?php if(isset($primaryAddress) && $primaryAddress != '') echo $primaryAddress;?>">
 </form>
 
<script>
    setTimeout("document.payNowForm.submit()", 2000);
</script>
