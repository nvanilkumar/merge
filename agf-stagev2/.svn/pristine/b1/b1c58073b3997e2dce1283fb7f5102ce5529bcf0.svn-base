<?php
/*
  Template Name: Donation
 */
get_header();
 
if(isset($_GET['status'])){
    $status=$_GET['status'];
    switch ($status) {
    case 1:
   echo $statusMessage = "Payment successful. your transacion details :" . $_GET['transactionId'];
        
        exit;
    
    case 2:
   echo $statusMessage = "There was an error processing Request!";
        exit;
    case 3:
      echo  $statusMessage =  "Thanks for your interest in our donation programe. We will get in touch with you shortly ";
        $_GET['status']=4;
                exit;
    
    default:
    
} 
    
}
 

?>
<script src="<?php bloginfo('template_url'); ?>/js/jquery-1.7.min.js" type="text/javascript"></script>
<script src="<?php bloginfo('template_url'); ?>/js/validator.js" type="text/javascript"></script>
<h1 class="entry-title">Donate</h1><br>
<?php
if (have_posts()) :
    while (have_posts()) :
        the_post();
        the_content();
    endwhile;
endif;
?>
<script type="text/javascript">
   
<!--
    function show_form() {
        jQuery('#d-form-section').slideDown();
    }

    function show_online_section() {
        jQuery('#online-section').slideDown();
        jQuery('#offline-section').slideUp();
    }

    function show_offline_section() {
        jQuery('#offline-section').slideDown();
        jQuery('#online-section').slideUp();
    }
//-->
</script>

<form id="donate" action='<?php echo get_site_url(); ?>/wp-admin/admin-ajax.php' method="post" method="post" class="std-frm01"> 
    <div style="margin-top: 30px;">
        <table class="std-tbl01">
            <tbody><tr>
                    <input type='hidden' name='action' value='donation_form' />
                    <td style="border-right: solid 0px #ccc; padding-right: 10px;">
                        <div style="margin-top: 0px;">
                            <table style="width: 100%;">             
                                <tbody>

                                    <tr>
                                        <td><input type="radio" onclick="show_form();" value="donation-education-events" name="item_name" class="rd"></td>
                                        <td>I would like to make a donation to support Community Education events.</td>
                                    </tr>
                                    <tr><td colspan="2">&nbsp;</td></tr>

                                    <tr>
                                        <td><input type="radio" onclick="show_form();" value="donation-education-scholarships" name="item_name" class="rd"></td>
                                        <td>I would like to make donation to contribute to the Education Scholarships.</td>
                                    </tr>
                                    <tr><td colspan="2">&nbsp;</td></tr>

                                    <tr>
                                        <td><input type="radio" onclick="show_form();" value="donation-general" name="item_name" class="rd"></td>
                                        <td>I would like to make general donation to support the Endowment.</td>
                                    </tr>
                                    <tr><td colspan="2">&nbsp;</td></tr>

                                </tbody></table>
                        </div>
                    </td>
                </tr>
            </tbody></table>  

    </div>  


    <div style="margin-top: 30px; display: none;" id="d-form-section"> 
        <div style="margin-top: 30px; ">
            <table class="std-tbl01">
                <tbody><tr>
                        <td style="width: 50%; border-right: solid 1px #ccc; padding-right: 10px;">
                            <div>
                                <div id="user-info">
                                    <div style="margin-bottom: 15px;">
                                        <span class="imp">YOUR INFORMATION</span>
                                    </div>
                                    <div>
                                        <table id="order-form-tb1" class="std-tbl01">
                                            <tbody>
                                                <tr>
                                                    <td style="width: 100px;">First Name</td>
                                                    <td><input value="" name="first_name" id="first_name"></td>
                                                </tr>
                                                <tr>
                                                    <td style="width: 100px;">Last Name</td>
                                                    <td><input value="" name="last_name" id="last_name"></td>
                                                </tr>
                                                <tr>
                                                    <td>Phone</td>
                                                    <td><input value="" name="phone" id="phone"></td>
                                                </tr>

                                                <tr>
                                                    <td>E-mail</td>
                                                    <td><input value="" name="email" id="email"></td>
                                                </tr>

                                                <tr>
                                                    <td>Address 1</td>
                                                    <td><input value="" name="address1" id="address1"></td>
                                                </tr>
                                                <tr>
                                                    <td>Address 2</td>
                                                    <td><input value="" name="address2" id="address2"></td>
                                                </tr>
                                                <tr>
                                                    <td>City</td>
                                                    <td><input value="" name="city" id="city"></td>
                                                </tr>
                                                <tr>
                                                    <td>State</td>
                                                    <td><input style="width: 80px;" value="" name="state" id="state"></td>
                                                </tr>
                                                <tr>
                                                    <td>Postal Code</td>
                                                    <td><input style="width: 80px;" value="" name="zip" id="zip"></td>
                                                </tr>
                                            </tbody></table>
                                    </div>
                                </div>
                            </div>
                            <div style="margin-top: 20px;">
                                <div style="margin-top: 0px;">
                                    <span class="sec-hd">SPECIAL NOTE OR ACKNOWLEDGEMENT</span>
                                    <div>
                                        <textarea style="width: 350px; height: 150px; padding: 2px;" name="special-note"></textarea>
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td style="padding-left: 10px;">
                            <div style="margin-bottom: 15px; padding-right: 5px;">
                                <span class="imp">PAYMENT INFORMATION</span><br>
                                <span style="font-size: 80%;">The Awareness Garden Foundation is a nonprofit organization. Your donation is tax deductible as provided by law. We are truly grateful for your support.</span>
                            </div>

                            <div style="margin-top: 30px; margin-bottom: 15px;">
                                <table>
                                    <tbody><tr>
                                            <td style="vertical-align: middle; padding-right: 10px; font-weight: bold; color: #000; ">Amount, I would like to donate :</td>
                                            <td style="vertical-align: middle; padding-left: 10px;"><input style="width: 80px;" value="0.0" id="amount" name="amount"></td>
                                        </tr>
                                    </tbody></table>
                            </div>

                            <div style="margin-top: 10px; margin-bottom: 15px;">
                                <table style="width: 100%;">
                                    <tbody><tr>
                                            <td style="vertical-align: middle; padding-right: 5px;" colspan="2">I would like to donate by:</td>
                                        </tr>	
                                        
                                        <?php
                                            $paypalstatus= get_option( 'paypal_status', '' ); 
                                            $cardStatus='none';
                                            if($paypalstatus == '1'){ 
                                                $cardStatus='block';
                                     echo'   <tr>
                                            <td style="width: 10px;">&nbsp;</td>
                                            <td style="vertical-align: middle;"><input type="radio" onclick="show_offline_section();" value="offline" name="payment_mode" class="rd">Mail/ Phone/ E-mail</td>
                                        </tr>
                                        <tr>
                                            <td style="width: 10px;">&nbsp;</td>
                                            <td style="vertical-align: middle;"><input type="radio" onclick="show_online_section();" checked="checked" value="online" name="payment_mode" class="rd">Online</td>
                                        </tr>';
                                                
                                             } else {  
                                           echo  '<tr>
                                            <td style="width: 10px;">&nbsp;</td>
                                            <td style="vertical-align: middle;"><input type="radio" onclick="show_offline_section();" value="offline" checked="checked" name="payment_mode" class="rd">Mail/ Phone/ E-mail</td>
                                            </tr>';
                                             }
                                        
                                        ?>
                                        

                                    </tbody></table>
                            </div>

                            <div id="online-section" style="margin-top: 10px; margin-bottom: 15px; display: <?php echo $cardStatus?>;">
                                <table style="width: 100%;">
                                    <tbody>     
                                        <tr>
                                            <td>Credit Card:</td>
                                            <td style="vertical-align: middle;"><input type="text" value="" id="card_number" name="card_number" maxlength="18"></td>
                                        </tr>
                                        <tr>
                                            <td>Card Type</td>
                                            <td style="vertical-align: middle;"><select name="card_type" id="card_type">
                                                    <option value="amex">AMEX</option>
                                                    <option value="discover">Discover</option>
                                                    <option value="mc">MasterCard</option>
                                                    <option value="visa">Visa</option>
                                                </select></td>
                                        </tr>
                                        <tr>
                                            <td>Expiry Date</td>
                                            <td style="vertical-align: middle;">
                                                <select name="expiryMonth" id="expiryMonth">
                                                    <option value="01">01</option>
                                                    <option value="02">02</option>
                                                    <option value="03">03</option>
                                                    <option value="04">04</option>
                                                    <option value="05">05</option>
                                                    <option value="06">06</option>
                                                    <option value="07">07</option>
                                                    <option value="08">08</option>
                                                    <option value="09">09</option>
                                                    <option value="10">10</option>
                                                    <option value="11">11</option>
                                                    <option value="12">12</option>
                                                </select>
                                                <span>/</span>
                                                 <select name="expiryYear" id="expiryYear">
                                                <?php 
                                                
                                                 $currentYear=date("Y"); 
                                                 
                                                 for($i=1;$i<21;$i++){?>
                                                    <option value="<?php echo $currentYear;?>"><?php echo $currentYear;?></option> 
                                                <?php     
                                                    $currentYear= $currentYear+1;
                                                 }
                                                ?>
 
                                                </select>
                                            </td>
                                           
                                        </tr>
                                        <tr>
                                            <td>CVV</td>
                                            <td style="vertical-align: middle;"><input type="text" value="" name="card_cvv" id="card_cvv" size="5" maxlength="5"></td>
                                        </tr>                 	      
                                    </tbody></table>
                            </div>

                            <div class="pm_offline" id="offline-section" style="margin-top: 10px; margin-bottom: 15px; display: none;">
                                Once your request comes to us, we will contact you with the information we would require to make the payment via phone, mail or email.
                            </div>
                        </td>
                    </tr>
                </tbody></table>  
        </div>
        <div style="margin-top: 20px;" id="submit-section">
            <input type="submit" style="padding: 4px; padding-left: 10px; padding-right: 10px; " value="Donate" name="submit" class="bt">
        </div> 
    </div>     
</form>
<script  type="text/javascript">
    var pethod = jQuery('input:radio[name=payment_mode]:checked').val();
    var frmvalidator = new Validator("donate");

    frmvalidator.addValidation("first_name", "req", "Please enter your first name");
    frmvalidator.addValidation("first_name", "maxlen=20", "Max length for first name is 20");
    frmvalidator.addValidation("last_name", "req", "Please enter your last Name");
    frmvalidator.addValidation("last_name", "maxlen=20", "Max length for last name is 20");

    frmvalidator.addValidation("email", "maxlen=70", "Email should not be more than 70");
    frmvalidator.addValidation("email", "req", "Please enter email");
    frmvalidator.addValidation("email", "email", "Please enter proper email");
    frmvalidator.addValidation("phone", "numeric", "Phone number should be numeric only");

    frmvalidator.addValidation("address-line1", "req", "Please enter address");
    frmvalidator.addValidation("city", "req", "Please enter city");
    frmvalidator.addValidation("state", "req", "Please enter state");
    frmvalidator.addValidation("zip", "req", "Please enter postal code");

    frmvalidator.addValidation("amount", "req", "Please enter donation amount");
    frmvalidator.addValidation("amount", "numeric", "Donation amount should be numeric");
    frmvalidator.addValidation("amount", "gt=0.0", "Please enter donation amount");

    if (pethod == 'online') {
        frmvalidator.addValidation("card_number", "req", "Please enter credit card number");
        frmvalidator.addValidation("card_number", "numeric", "Credit card should be numeric");
        frmvalidator.addValidation("card_number", "regexp=^[0-9]{15,20}$", "Please enter proper credit card number");
        frmvalidator.addValidation("card_cvv", "req", "Please enter cvv code");
        frmvalidator.addValidation("expiryMonth", "req", "Please select card expiry month");
        frmvalidator.addValidation("expiryYear", "req", "Please select card expiry year");
    }

</script> 

<?php get_footer(); ?>