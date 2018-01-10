<?php        
/*
Template Name: Products
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
      echo  $statusMessage = "Thanks for your interest in our Order programe. We will get in touch with you shortly ";
        $_GET['status']=4;
                exit;
    
    default:
    
} 
    
}



if($_POST['submit']=='Process my Order'){
    
    if ($_POST['payment_mode'] === 'Online') {
        $paypal_username = "upendrakumar-facilitator_api1.cestechservices.com";  // use your live account info
        $paypal_password = "WQLSETZK7YWXNQ3T";
        $paypal_signature = "AH5b0-f6iXjMfl17Z54HDh9s3IaDApWTaPhNGzMNBhFRM4ZH5ymnkQxC";
        $paypal_url = "https://api-3t.sandbox.paypal.com/nvp";
        include("PayPal.php");
        
        $orderType=array();
        $orderType['brick-red-04x08']=75;
        $orderType['brick-red-08x08']=125;
        $orderType['stone-blue-12x12']=650;
//        $_POST['engrave-base-code']
        $paypal = new phpPayPal();

        $paypal->API_USERNAME = $paypal_username;
        $paypal->API_PASSWORD = $paypal_password;
        $paypal->API_SIGNATURE = $paypal_signature;
        $paypal->API_ENDPOINT = $paypal_url;
        // (required)
        $paypal->ip_address = $_SERVER['REMOTE_ADDR'];
        $paypal->amount_total = $orderType[$_POST['engrave-base-code']];
        $paypal->credit_card_number = $_POST['card_number'];
        $paypal->credit_card_type = $_POST['card_type'];
        $paypal->cvv2_code = $_POST['card_cvv'];
        $paypal->expire_date = $_POST['expiryMonth'] . $_POST['expiryYear'];
        $paypal->country_code = 'US';
        //predefined value
        $paypal->currency_code = 'USD';
        $paypal->cmd = '_donation';
        $paypal->bn = 'Donate';
        $paypal->no_shipping = '0';
        $paypal->no_note = '1';
        $paypal->tax = '0';

        // Billing Details (required)
        $paypal->first_name = $_POST['first_name'];
        $paypal->last_name = $_POST['last_name'];
        $paypal->address1 = $_POST['address1'];
        $paypal->address2 = $_POST['address2'];
        $paypal->city = $_POST['city'];
        $paypal->state = $_POST['state'];
        $paypal->postal_code = $_POST['zip'];
        $paypal->phone_number = $_POST['phone'];
        $paypal->expire_date_month = $_POST['expiryMonth'] . $_POST['expiryYear'];

// Add Order Items (NOT required) - Name, Number, Qty, Tax, Amt
//$paypal->addItem('Item Name', 'Item Number 012', 1, 0, '50.49');
// Perform the payment
       $paypal->DoDirectPayment();
       $response= $paypal->Response;

        if (is_array($response) && $response['ACK'] == 'Success') { // Payment successful
            // We'll fetch the transaction ID for internal bookkeeping
            //insert the form data
            $status=insertOrderData();
            $transactionId = $response['TRANSACTIONID'];
            $statusMessage = "Payment successful. your transacion details :" . $transactionId;
        } else {
            $statusMessage = "There was an error processing Request!";
        }

       
    }else{
        $status=insertOrderData();
        $statusMessage =  "Thanks for your interest in our Order programe. We will get in touch with you shortly ";
    }
    echo $statusMessage;
//   print_r($wpdb->last_query) ;

    
        exit;

    
    
}
$pagename = basename(get_permalink());  
switch ($pagename) {
    case "12x12-blue-stone":
        $defaultSelect = "stone-blue-12x12";
        break;
    case "8x8-brick":
        $defaultSelect = "brick-red-08x08";
        break;
    default:
    case "4x8-brick":
        $defaultSelect = "brick-red-04x08";
        break;
}
                  
?>
<script src="<?php bloginfo('template_url'); ?>/js/jquery-1.7.min.js" type="text/javascript"></script>
<script src="<?php bloginfo('template_url'); ?>/js/validator.js" type="text/javascript"></script>
<div>
<div id="contentMiddleContent" style="min-height: 200px;">
<style>
<!--
.brick-line-input{font-family:"Courier New", monospace;}
.preview-line{
  font-family:"Courier New", monospace;
  font-weight:bold;
  font-size: 30px;
  font-effect: engrave;
  line-height:50px;
  color: #000;      
}

#epb{
  width: 400px;
  color: #000;
  text-align: center;
}
.epb4x8brickred{
  background: url("<?php bloginfo('template_url'); ?>/images/blank_sample_brick_04x08_W400.jpg") no-repeat center top ;
  height: 200px;
}
.epb8x8brickred{
  background: url("<?php bloginfo('template_url'); ?>/images/blank_sample_brick_08x08_W400.jpg") no-repeat center top ;
  height: 400px;
}
.epb12x12stoneblue{
  background: url("<?php bloginfo('template_url'); ?>/images/blank_sample_bluestone_12x12_W400.jpg") no-repeat center top ;
  height: 400px;
}
.epb-tdl{vertical-align: middle; padding-right: 15px;}
.epb-tdd{}

#brick-order-form-tb1 input{width: 250px;} 
.brick-price{color: #333; font-size: 115%;}
-->
</style>


<script type="text/javascript">
<!--
make_request('<?php echo $defaultSelect;?>');

function make_request(brick){  
  jQuery.ajax({
      url:"<?php echo get_site_url(); ?>/wp-admin/admin-ajax.php",
      type:'POST',
      data:'action=my_special_action&bricksize=' + brick,        
      success:function(data){
          jQuery("#preview").empty();   			
          jQuery("#preview").append(data);
		}
 });  
}

function update_preview(linenum, ip){
     var input_id   = "#line0"+linenum;
     var preview_id = "#preview-line"+linenum;

     var v = $(ip).val();
     if(v==null || v=='' ) v = '&nbsp;';

     $(preview_id).html( v );
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

<div>
 <img src="<?php bloginfo('template_url'); ?>/images/order-brink-page-headings-image-300.png"><br>
<?php 
if (have_posts()) : 
 while (have_posts()) : 
 the_post();
 the_content(); 
 endwhile; 
endif; 
?>

 <form id="product" class="std-frm01" method="post" action="<?php echo get_site_url(); ?>/wp-admin/admin-ajax.php"> 
     <input type='hidden' name='action' value='order_form_action' />
      
  <div style="margin-top: 20px;">
    <table class="std-tbl01">
      <tbody><tr>
        <td style="width: 33%;">
                <table class="std-tbl01">
                  <tbody><tr>
                    <td style="width: 30px;">
                      <input class="rd" type="radio" name="engrave-base-code" value="brick-red-04x08" <?php if($defaultSelect=='brick-red-04x08'){echo 'checked';}?> onclick="make_request(this.value);">                     
                    </td>
                    <td>
                      <span class="brick-price">$75</span> - 4" X 8" Engraved brick paver<br>
                      Up to 3 lines - 16 characters per line<br>
                      <span class="subtle">Total - 48 characters.</span>
                    </td>
                  </tr>
                </tbody></table>
        </td>
        <td style="width: 33%;">
                <table>
                  <tbody><tr>
                    <td style="width: 30px;">
                      <input class="rd" type="radio" name="engrave-base-code" <?php if($defaultSelect=='brick-red-08x08'){echo 'checked';}?> value="brick-red-08x08" onclick="make_request(this.value);">
                    </td>
                    <td>
                      <span class="brick-price">$125</span> - 8" X 8" Engraved brick paver<br>
                      Up to 5 lines - 16 characters per line<br>
                      <span class="subtle">Total - 80 characters.</span>
                    </td>
                  </tr>
                </tbody></table>
        </td>
        <td style="width: 33%;">
                <table>
                  <tbody><tr>
                    <td style="width: 30px;">
                      <input class="rd" type="radio" name="engrave-base-code" <?php if($defaultSelect=='stone-blue-12x12'){echo 'checked';}?> value="stone-blue-12x12" value="stone-blue-12x12" onclick="make_request(this.value);">
                    </td>
                    <td>
                      <span class="brick-price">$650</span> - 12" X 12" Bluestone<br>
                      Up to 6 lines - 20 characters per line<br>
                      <span class="subtle">Total - 120 characters.</span>
                    </td>
                  </tr>
                </tbody></table>
        </td>
      </tr>
    </tbody></table>
  </div>
  
 
  <div id="preview" style="margin-top: 20px; padding: 5px; background-color: rgb(238, 238, 238); border-top-style: solid; border-top-width: 1px; border-top-color: rgb(187, 187, 187); border-bottom-style: solid; border-bottom-width: 1px; border-bottom-color: rgb(187, 187, 187); background-position: initial initial; background-repeat: initial initial; "> </div>
  
  
  <div style="margin-top: 20px;" id="personal-info-section">
    <table class="std-tbl01">
      <tbody><tr>
        <td style="width: 50%; border-right: solid 1px #ccc; padding-right: 10px;">
          <div>
            <div style="margin-bottom: 15px;">
              <span class="imp">YOUR INFORMATION</span>
            </div>
              <div>
                <table id="brick-order-form-tb1">
                  <tbody>
                  <tr>
                    <td style="width: 100px;">First Name</td>
                    <td><input value="" id="first_name" name="first_name"></td>
                  </tr>
                  <tr>
                    <td style="width: 100px;">Last Name</td>
                    <td><input value="" id="last_name" name="last_name"></td>
                  </tr>
                  <tr>
                    <td>Phone</td>
                    <td><input value="" id="phone" name="phone"></td>
                  </tr>
                  <tr>
                    <td>E-mail</td>
                    <td><input value="" id="email" name="email"></td>
                  </tr>           
                  <tr>
                    <td>Address 1</td>
                    <td><input value="" id="address1" name="address1"></td>
                  </tr>
                  <tr>
                    <td>Address 2</td>
                    <td><input value="" id="address2" name="address2"></td>
                  </tr>
                  <tr>
                    <td>City</td>
                    <td><input value="" id="city" name="city"></td>
                  </tr>
                  <tr>
                    <td>State</td>
                    <td><input style="width: 80px;" value="" id="state" name="state"></td>
                  </tr>
                  <tr>
                    <td>Postal Code</td>
                    <td><input style="width: 80px;" value="" id="zip" name="zip"></td>
                  </tr>
                </tbody></table>
             </div>
          </div>
          <div style="margin-top: 40px;" id="payment-section">
              <div style="margin-bottom: 15px;">
                <span class="imp">PAYMENT INFORMATION</span><br>
                <span style="font-size: 80%;">The Awareness Garden Foundation is a nonprofit organization. Your donation is tax deductible as provided by law. We are truly grateful for your support.</span>
              </div>
            
             <div style="margin-top: 10px; margin-bottom: 15px;">
               <table style="width: 100%;">
                 <tbody><tr>
                   <td style="vertical-align: middle; padding-right: 5px;" colspan="2">I would like to pay by:</td>
                 </tr> 
                 
                  <?php
                                            $paypalstatus= get_option( 'paypal_status', '' ); 
                                            $cardStatus='none';
                                            if($paypalstatus == '1'){ 
                                                $cardStatus='block';
                                     echo'   <tr>
                   <td style="width: 10px;">&nbsp;</td>
                   <td style="vertical-align: middle;"><input type="radio" checked="true" value="Offline" name="payment_mode" onclick="show_offline_section();" class="rd">Mail/ Phone/ E-mail</td>
                 </tr>
                 <tr>
                   <td style="width: 10px;">&nbsp;</td>
                   <td style="vertical-align: middle;"><input type="radio" checked="" value="Online" name="payment_mode" onclick="show_online_section();" checked="checked" class="rd">Credit Card/Paypal</td>
                 </tr> ';
                                                
                                             } else {  
                                           echo  ' <tr>
                   <td style="width: 10px;">&nbsp;</td>
                   <td style="vertical-align: middle;"><input type="radio" checked="true" value="Offline" name="payment_mode" onclick="show_offline_section();" checked="checked" class="rd">Mail/ Phone/ E-mail</td>
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
                                      $currentYear = date("Y");

                                      for ($i = 1; $i < 21; $i++) {
                                          ?>
                                          <option value="<?php echo $currentYear; ?>"><?php echo $currentYear; ?></option> 
                                          <?php
                                          $currentYear = $currentYear + 1;
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
              
         
        <div class="pm_offline" id="offline-section" style=" display: none;">
          Once your request comes to us, we will contact you with the information we would require to make the payment via phone, mail or email.
        </div>
      </div>
        </td>
        <td style="padding-left: 10px;">
          <div style="margin-bottom: 15px;">
            <span class="imp">ACKNOWLEDGEMENT FOR</span>
          </div>
            <div>
              <table style="margin-top: 10px; margin-left: 10px;" id="brick-order-form-tb1" class="std-tbl01">
                <tbody><tr><td style="width: 80px;" colspan="2">The card should read</td></tr>
    
                <tr>
                  <td style="width: 80px;">From</td>
                  <td><input value="" name="ack-from-name"></td>
                </tr>
                <tr><td colspan="2">&nbsp;</td></tr>            
    
                <tr>
                  <td style="width: 80px;" colspan="2">Please send acknowledgement</td>
                </tr>
    
                <tr>
                  <td style="" colspan="2">
                    <input type="radio" style="width: 18px; padding:0; margin: 0;" class="rd" value="In Honor" name="dedication"> In Honor of
                    <span style="padding-right: 35px;">&nbsp;</span>
                    <input type="radio" style="width: 18px;" class="rd" value="In Memory" name="dedication"> In Memory of
                  </td>
                </tr>
    
                <tr>
                  <td style="width: 80px;">Name</td>
                  <td><input value="" name="dedication-name"></td>
                </tr>
                <tr><td colspan="2">&nbsp;</td></tr>
                <tr><td colspan="2">&nbsp;</td></tr>
    
                <tr>
                  <td style="width: 80px;" colspan="2">
                    Kindly send the acknowledgement to
                  </td>
                </tr>
                
                <tr>
                  <td style="width: 80px;">Name</td>
                  <td><input value="" id="ack-name" name="ack-name"></td>
                </tr>
                
                <tr>
                  <td>Phone</td>
                  <td><input value="" id="ack-phone" name="ack-phone"></td>
                </tr>
                
                <tr>
                  <td>E-mail</td>
                  <td><input value="" id="ack-email" name="ack-email"></td>
                </tr>
                
                <tr>
                  <td>Address 1</td>
                  <td><input value="" id="ack-address-line1" name="ack-address-line1"></td>
                </tr>
                
                <tr>
                  <td>Address 2</td>
                  <td><input value="" id="ack-address-line2" name="ack-address-line2"></td>
                </tr>
                
                <tr>
                  <td>City</td>
                  <td><input value="" id="ack-city" name="ack-city"></td>
                </tr>
                
                <tr>
                  <td>State</td>
                  <td><input style="width: 80px;" value="" id="ack-state" name="ack-state"></td>
                </tr>
                
                <tr>
                  <td>Postal Code</td>
                  <td><input style="width: 80px;" value="" id="ack-zip" name="ack-zip"></td>
                </tr>
              </tbody>
            </table>
        </div>
        </td>
      </tr>
    </tbody></table>
  </div>
      <input type="hidden" name="cmd" value="_donation">
      <input type="hidden" name="bn" value="Donate">
      <input type="hidden" name="no_shipping" value="0">
      <input type="hidden" name="no_note" value="1">
      <input type="hidden" name="currency_code" value="USD">
      <input type="hidden" name="tax" value="0">
      
    <div id="submit-section" style="margin-top: 20px;">  
      <input class="bt" type="submit" name="submit" value="Process my Order" style="padding: 4px; padding-left: 10px; padding-right: 10px; ">    
    </div>  
  </form>
</div>
</div>
</div>
<script  type="text/javascript"> 
  var frmvalidator = new Validator("product"); 
  frmvalidator.addValidation("first_name","req","Please enter your first name"); 
  frmvalidator.addValidation("first_name","maxlen=20", "Max length for Name is 20");  
  frmvalidator.addValidation("last_name","req","Please enter your last name"); 
  frmvalidator.addValidation("last_name","maxlen=20", "Max length for Name is 20");  
  
  frmvalidator.addValidation("email","maxlen=70", "Email should not be more than 70"); 
  frmvalidator.addValidation("email","req", "Please enter email"); 
  frmvalidator.addValidation("email","email", "Please enter proper email");  
  frmvalidator.addValidation("phone","numeric", "Phone number should be numeric only");
  
  frmvalidator.addValidation("address1","req", "Please enter address"); 
  frmvalidator.addValidation("city","req", "Please enter city");
  frmvalidator.addValidation("state","req", "Please enter state");
  frmvalidator.addValidation("zip","req", "Please enter postal code");
  
  frmvalidator.addValidation("donation-amount","req", "Please enter donation amount"); 
  frmvalidator.addValidation("donation-amount","numeric", "Donation amount should be numeric");
  frmvalidator.addValidation("donation-amount","gt=0.0", "Please enter donation amount");
</script> 


<?php get_footer(); ?>