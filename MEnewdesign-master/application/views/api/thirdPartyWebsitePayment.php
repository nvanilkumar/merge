<form  method="post" action="<?php echo $formUrl; ?>" name="frmTransaction" id="frmTransaction" >
    <input type="hidden" name="account_id" value="7300">
    <input type="hidden" name="reference_no" value="<?php echo $eventSignupId; ?>">
    <input type="hidden" name="client_reference_no" value="<?php echo $reference_no; ?>">
    <input type="hidden" name="return_url" value="<?php echo $return_url; ?>" /> 
    <input type='hidden' name='amount' value='<?php echo $amount ?>' />
    <input type='hidden' name='description' value='<?php echo $description ?>' />
    <input type="hidden" name="name" value="<?php echo$name; ?>" /> 
    <input type="hidden"  name="address" value="<?php echo$address; ?>"/>
    <input type="hidden" name="city" value="<?php echo $city; ?>"/> 
    <input type="hidden" name="state" value="<?php echo $state; ?>"/> 
    <input type="hidden" name="postal_code" value="<?php echo $postal_code; ?>"/>
    <input type="hidden" name="country" value="IND" />
    <input type="hidden" name="email" value="<?php echo $email; ?>"/>
    <input type="hidden" name="phone"  value="<?php echo $phone; ?>"/>
    <input type="hidden" name="ship_name" value="<?php echo $name; ?>"/>
    <input type="hidden" name="ship_address" value="<?php echo $address; ?>"/>
    <input type="hidden" name="ship_city" value="<?php echo $city; ?>"/>
    <input type="hidden" name="ship_state" value="<?php echo $state; ?>"/> 
    <input type="hidden" name="ship_postal_code" value="<?php echo $postal_code; ?>"/>  
    <input type="hidden" name="ship_country" value="IND" />
    <input type="hidden" name="ship_phone" value="<?php echo $phone; ?>"/>

    <input type="hidden" name="paypal" value="0" />
    <input type="hidden" name="currency" value="INR" />
</form>
<script>
    setTimeout("document.frmTransaction.submit()", 2000);
</script>