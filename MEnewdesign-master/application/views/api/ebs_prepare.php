<div class="page-container">
    <div class="wrap">
        <div class="container"> 
            
            <form method="post" action="https://secure.ebs.in/pg/ma/sale/pay/" name="frmTransaction" id="frmTransaction" >
                <input name="account_id" value="<?php echo $account_id;?>" type="hidden">
                <input name="mode" value="<?php echo $mode;?>" type="hidden">
                <input name="reference_no" value="<?php echo $reference_no;?>" type="hidden">
       
                <input name="return_url" type="hidden" value="<?php echo $meRedirectURL;?>" /> 
                 
                <input type='hidden' name='amount' value='<?php echo $amount;?>' />
                <input type='hidden' name='description' value='<?php echo $description;?>' />
                
                <input name="name" type="hidden" value="<?php echo $name;?>" /> 
                <input name="address" type="hidden" value="<?php echo ($address) ? $address : 'Hyderabad';?>"/>
                <input name="city" type="hidden" value="<?php echo ($city) ? $city : 'Hyderabad';?>"/> 
                <input name="state" type="hidden" value="<?php echo ($state) ? $state : 'Telangana';?>"/> 
                <input name="postal_code" type="hidden" value="<?php echo ($pincode!='') ? $pincode : '580001';?>"/>
                
                <input name="country" value="<?php echo ($userData['countryCode'] != '') ? $userData['countryCode'] : 'IND';?>" type="hidden" />
                
                <input name="email" type="hidden" value="<?php echo $email;?>"/>
                <input name="phone" type="hidden"  value="<?php echo ($phone != '') ? $phone : '1234567890';?>"/>
                <input name="ship_name" type="hidden" value="<?php echo $name;?>"/>
                <input name="ship_address" type="hidden" value="<?php echo ($address) ? $address : 'Hyderabad';?>"/>
                <input name="ship_city" type="hidden" value="<?php echo ($city) ? $city : 'Hyderabad';?>"/>
                <input name="ship_state" type="hidden" value="<?php echo ($state) ? $state : 'Telangana';?>"/> 
                <input name="ship_postal_code" type="hidden" value="<?php echo ($pincode!='') ? $pincode : '580001';?>"/>  
                <input name="ship_country" value="<?php echo ($userData['countryCode'] != '') ? $userData['countryCode'] : 'IND';?>" type="hidden" />
                <input name="ship_phone" type="hidden" value="<?php echo $phone;?>"/>
                
                <input name="secure_hash" type="hidden" value="<?php echo $secure_hash; ?>" />
            </form>
            
            <div align="center">
            <img src="<?php echo $this->config->item('images_static_path');?>loading_image.gif"></div>
            <div align="center">Please wait while we are redirecting to Payment page...</div>
            <script type="text/javascript">
                setTimeout("document.frmTransaction.submit()",4000);
            </script> 
        </div>
    </div>
</div>