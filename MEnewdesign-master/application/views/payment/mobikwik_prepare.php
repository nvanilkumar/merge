<div class="page-container">
    <div class="wrap">
        <div class="container"> 
            <form name="mobiwikform" id="demo_form" action="<?php echo $actionUrl;?>" method="POST">
                <input type="hidden" name="email" value="<?php echo $email;?>">
                <input type="hidden" name="amount" value="<?php echo $amount;?>">
                <input type="hidden" name="cell" value="<?php echo $mobileNo;?>">
                <input type="hidden" name="orderid" value="<?php echo $orderId;?>">
                <input type="hidden" name="merchantname"  value="<?php echo $merchantName;?>">
                <input type="hidden" name="mid" value="<?php echo $mobikwikMerchantId;?>">
                <input type="hidden" name="redirecturl" value="<?php echo $redirectUrl;?>">
                <input type="hidden" name="checksum" value="<?php echo $checksum;?>" />
                
            </form>
            <div class="clear"></div>
            <div align="center"><img src="<?php echo $this->config->item('images_static_path')?>loading_image.gif"></div>
            <div align="center">Please wait while we are redirecting to Mobikwik...</div>
            <div align="center"><img src="<?php echo $this->config->item('images_static_path')?>mobikwik_wallet.png"></div>
            
            <script type="text/javascript">
                setTimeout("document.mobiwikform.submit()",2000);
            </script>
        </div>
    </div>
</div>