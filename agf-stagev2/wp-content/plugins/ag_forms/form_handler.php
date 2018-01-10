<?php

/*
 * To Handle the Order form, Donation form actions
 *  
 */

//Add actions for handle form submits
add_action('wp_ajax_donation_form', 'donation_form_action');
add_action('wp_ajax_nopriv_donation_form', 'donation_form_action');

add_action('wp_ajax_order_form_action', 'order_form_action');
add_action('wp_ajax_nopriv_order_form_action', 'order_form_action');

function donation_form_action() {
    $transactionId = 1;
    if ($_POST['payment_mode'] == 'online') {
       $output= papal_prepare();
       $statusMessage= $output['statusMessage'];
       $transactionId= $output['transactionId'];
    } else {
       insertDonationData();
        $statusMessage = 3;
    }
    wp_redirect(get_site_url() . "/donation?status=" . $statusMessage . "&transactionId=" . $transactionId);
}

//To insert the donation form data
function insertDonationData($transactionId = 0) {
    global $wpdb;
    $table = "wp_form_data";
    $data = array();

    $data['donationfor'] = $_POST['item_name'];
    $data['first_name'] = $_POST['first_name'];
    $data['ip_address'] = $_SERVER['REMOTE_ADDR'];
    $data['last_name'] = $_POST['last_name'];
    $data['phone'] = $_POST['phone'];
    $data['email'] = $_POST['email'];
    $data['address'] = $_POST['address1'] . "" . $_POST['address2'];
    $data['city'] = $_POST['city'];
    $data['state'] = $_POST['state'];
    $data['postalcode'] = $_POST['zip'];
    $data['note'] = $_POST['special-note'];
    $data['amount'] = $_POST['amount'];
    $data['payment_mode'] = $_POST['payment_mode'];
    $data['transactionId'] = $transactionId;
    $data['cardnumber'] = ($_POST['card_number']) ? substr($_POST['card_number'], -4) : 0;


    $tableData['type'] = 'donation';
    $tableData['data'] = serialize($data);
    $format = array('%s', '%s');

    return $success = $wpdb->insert($table, $tableData, $format);
}

//To insert the order data
function insertOrderData($transactionId = 0) {
    global $wpdb;
    $table = "wp_form_data";
    $data = array();
    $data['line1'] = $_POST['line01'];
    $data['line2'] = $_POST['line02'];
    $data['line3'] = $_POST['line03'];
    $data['line4'] = $_POST['line04'];
    $data['line5'] = $_POST['line05'];
    $data['line6'] = $_POST['line06'];
    $data['order_type'] = $_POST['engrave-base-code'];
    $data['amount'] = getOrderAmount($_POST['engrave-base-code']);
    $data['ack_from_name'] = $_POST['ack-from-name'];
    $data['dedication_name'] = $_POST['dedication'] . " " . $_POST['dedication-name'];

    $data['first_name'] = $_POST['first_name'] . " " . $_POST['last_name'];
    $data['phone'] = $_POST['phone'];
    $data['email'] = $_POST['email'];
    $data['address'] = $_POST['address-line1'] . " " . $_POST['address-line2'];
    $data['city'] = $_POST['city'];
    $data['state'] = $_POST['state'];
    $data['postalcode'] = $_POST['zip'];
    $data['paymenttype'] = $_POST['payment_mode'];
    $data['transactionId'] = $transactionId;
    $data['ack_name'] = $_POST['ack-name'];
    $data['ack_phone'] = $_POST['ack-phone'];
    $data['ack_email'] = $_POST['ack-email'];
    $data['ack_address'] = $_POST['ack-address-line1'] . " " . $_POST['ack-address-line2'];
    $data['ack_city'] = $_POST['ack-city'];
    $data['ack_state'] = $_POST['ack-state'];
    $data['ack_postalcode'] = $_POST['ack-zip'];

    $tableData['type'] = 'brick';
    $tableData['data'] = serialize($data);
    $format = array('%s', '%s');

    return $success = $wpdb->insert($table, $tableData, $format);
}

function order_form_action() {
    $transactionId = 1;
    if ($_POST['payment_mode'] === 'Online') {
       $output= papal_prepare('order');
       $statusMessage= $output['statusMessage'];
       $transactionId= $output['transactionId'];
    } else {
        insertOrderData();
        $statusMessage = 3;
    }
    $pageType = array();
    $pageType['brick-red-04x08'] = '/4x8-brick/';
    $pageType['brick-red-08x08'] = '/8x8-brick/';
    $pageType['stone-blue-12x12'] = '/12x12-blue-stone/';
    wp_redirect(get_site_url() . "/" . $pageType[$_POST['engrave-base-code']] . "?status=" . $statusMessage . "&transactionId=" . $transactionId);
}

//To insert the paypal information
function papal_prepare($type =NULL){
       
        $amount=stripslashes($_POST['amount']);
        if(isset($_POST['engrave-base-code'])){
          $amount= getOrderAmount($_POST['engrave-base-code']) ;
        }
     
    
    $paypal_username = get_option('paypal_username', '');  // use your live account info
        $paypal_password = get_option('paypal_password', '');
        $paypal_signature = get_option('paypal_signature', '');
        $paypal_url = get_option('paypal_url', '');
        require_once(ABSPATH . "wp-content/themes/awarenessgarden/PayPal.php");
        $paypal = new phpPayPal();

        $paypal->API_USERNAME = $paypal_username;
        $paypal->API_PASSWORD = $paypal_password;
        $paypal->API_SIGNATURE = $paypal_signature;
        $paypal->API_ENDPOINT = $paypal_url;
        
        $paypal->ip_address = $_SERVER['REMOTE_ADDR'];
        $paypal->amount_total = $amount;
        $paypal->credit_card_number = $_POST['card_number'];
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
        $response = $paypal->Response;

        if (is_array($response) && $response['ACK'] == 'Success') { // Payment successful
            $output['transactionId'] = $response['TRANSACTIONID'];
            if(isset($type)){
                 insertOrderData($output['transactionId']);
            }else{
                insertDonationData($output['transactionId']);
            }
            
            $output['statusMessage'] = 1;
        } else {
             $output['statusMessage']= 2;
        }
        return $output;
}

function getOrderAmount($type) {
    $orderType = array();
    $orderType['brick-red-04x08'] = 75;
    $orderType['brick-red-08x08'] = 125;
    $orderType['stone-blue-12x12'] = 650;
    
    return $orderType[$type];
}
