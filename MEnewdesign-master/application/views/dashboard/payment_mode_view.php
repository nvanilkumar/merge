<div class="rightArea">

    <?php if (isset($paymentGatewayOutput) && $paymentGatewayOutput['status'] === TRUE) { ?>
        <div class="db-alert db-alert-success flashHide">
            <strong>&nbsp;&nbsp;  <?php echo $paymentGatewayOutput['response']['messages'][0]; ?></strong> 
        </div>
    <?php } ?>
    <?php if (isset($paymentGatewayOutput) && $paymentGatewayOutput['status'] === FALSE) { ?>
        <div id="paymentModeMessage" class="db-alert db-alert-danger flashHide">
            <strong>&nbsp;&nbsp;  <?php echo $paymentGatewayOutput['response']['messages'][0]; ?></strong> 
        </div>
    <?php } ?>

    <div class="heading">
        <h2>Manage Payment Modes: <span><?php echo $eventName; ?></span></h2>
    </div>
    <div class="fs-form fs-form-widget-setting fs-payment-modes">
		<h2 class="fs-box-title">Payment Modes</h2>
	    <div class="information paymentmodes fs-form-content">
        <form name='paymentModeForm' method='post' action='' id='paymentModeForm'>
            <?php if (isset($eventPaymentGateways) && $eventPaymentGateways['response']['total'] == 0) { ?>
                <div> <p class="db-alert db-alert-info">
                        <?php print_r($eventPaymentGateways['response']['messages'][0]); ?>
                    </p>
                </div>
                <?php
            } else if (isset($eventPaymentGateways) && $eventPaymentGateways['response']['total'] > 0) {
                foreach ($eventPaymentGateways['response']['gatewayList'] as $gateway) {
                    ?>
                    <p>
                       <label><input type="checkbox" name="gateways[]" class="paymentOptions" value='<?php echo $gateway['paymentgatewayid'];?>' <?php if ($gateway['status'] == 1) { ?>checked="checked" <?php } ?>>
                        Payment using <?php echo ucwords($gateway['gatewayName']);?> Gateway</label>
                        <span class="mleft"><?php echo $gateway['description'];?></span>
                    </p>                     
                    <?php
                }
            }
            ?>                       
            <div class='error float-left'><p id='paymentErrorMessage'></p></div>
            <input type="submit" name='paymentSubmit' id='paymentSubmit' class="createBtn float-right" value='SAVE'>
        </form>
    </div>
</div>
</div>
