<?php if(isset($ebsGateway) && $ebsGateway == 1) { ?>

    <!-- EBS form starts here -->
        <form name="ebs_frm" id="ebs_frm" action="<?php echo site_url('payment/ebsPrepare');?>" method='POST'>
            <input type="hidden" name="eventTitle" value="<?php echo preg_replace("/[^A-Za-z0-9]/","", stripslashes($eventData['title']))?>" />
            <input type="hidden" name="orderId" value="<?php echo $orderLogId; ?>" />
            <input type="hidden" name="paymentGatewayKey" value="<?php echo $ebsKey; ?>" />
            <input type="hidden" name="primaryAddress" class="primaryAddress" id="primaryAddress" value="<?php if(isset($primaryAddress) && $primaryAddress != '') echo $primaryAddress;?>">
        </form>
    <!-- EBS form ends here -->

<?php } if(isset($paytmGateway) && $paytmGateway == 1) { ?>

    <!-- Paytm form starts here -->
        <form name="paytm_frm" id="paytm_frm" action="<?php echo site_url('payment/paytmPrepare');?>" method='POST'>
            <input type="hidden" name="orderId" value="<?php echo $orderLogId; ?>" />
            <input type="hidden" name="paymentGatewayKey" value="<?php echo $paytmKey; ?>" />
            <input type="hidden" name="primaryAddress" class="primaryAddress" id="primaryAddress" value="<?php if(isset($primaryAddress) && $primaryAddress != '') echo $primaryAddress;?>">
        </form>
    <!-- Paytm form ends here -->

<?php } if(isset($mobikwikGateway) && $mobikwikGateway == 1) { ?>

    <!-- Mobikwik form starts here -->
        <form name="mobikwik_frm" id="mobikwik_frm" action="<?php echo site_url('payment/mobikwikPrepare');?>" method='POST'>
            <input type="hidden" name="orderId" value="<?php echo $orderLogId; ?>" />
            <input type="hidden" name="paymentGatewayKey" value="<?php echo $mobikwikKey; ?>" />
            <input type="hidden" name="primaryAddress" class="primaryAddress" id="primaryAddress" value="<?php if(isset($primaryAddress) && $primaryAddress != '') echo $primaryAddress;?>">
        </form>
    <!-- Mobikwik form ends here -->
    
<?php } if(isset($paypalGateway) && $paypalGateway == 1) { ?>
    
    <!-- Paypal form starts here -->
        <form name="paypal_frm" id="paypal_frm" action="<?php echo site_url('payment/paypalPrepare');?>" method='POST'>
            <input type="hidden" name="eventTitle" value="<?php echo preg_replace("/[^A-Za-z0-9]/","", stripslashes($eventData['title']))?>" />
            <input type="hidden" name="orderId" value="<?php echo $orderLogId; ?>" />
            <input type="hidden" name="paymentGatewayKey" value="<?php echo $paypalKey; ?>" />
            <input type="hidden" name="primaryAddress" class="primaryAddress" id="primaryAddress" value="<?php if(isset($primaryAddress) && $primaryAddress != '') echo $primaryAddress;?>">
        </form>
    <!-- Paypal form ends here -->
    
<?php } ?>