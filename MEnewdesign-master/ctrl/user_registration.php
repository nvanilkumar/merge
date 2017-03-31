<?php

session_start();
include_once("MT/cGlobal.php");
include_once("MT/cGlobali.php");
include_once "includes/common_functions.php";
$global = new cGlobal();
include 'loginchk.php';
include_once 'excel_reader.php';
require_once '../Extras/phpexcel/PHPExcel/IOFactory.php';

//To Bring the user records



if ($_POST['submit_form'] == "Submit") {

    $file_name = "userfiles/" . $_FILES["ebs_doc"]["name"];
    $SDt = $_POST['transaction_date'];
    $SDtExplode = explode("/", $SDt);
    $trasaction_date = $SDtExplode[2] . '-' . $SDtExplode[1] . '-' . $SDtExplode[0] . ' 00:00:00';

    //Uploads the file 
    move_uploaded_file($_FILES["ebs_doc"]["tmp_name"], $file_name);

    //read the excel dat
    $excel_reader = new excel_reader();
    $excel_sheet_data = $excel_reader->read_user_data($file_name);
    //Removes the uploaded file
    unlink($file_name);

     
    $user_ids = array();
    foreach ($excel_sheet_data as $key => $details) {
        if ($key == 0) {
            continue;
        }

        $email_id = $details[2];
        if (strlen($email_id) > 0) {
            //Check the user exists
            $user_id = get_user_id($email_id, $global);
            if ($user_id == 0) {
                $user_id = insert_user_details($global, $details);
            }
            $user_ids[] = $user_id;
        }//end of if
        //create event signup entry
        $event_signup_id = insert_event_signup($global, $user_id, $details);
        $attendee_id = insert_attendee_details($global, $event_signup_id, $details);
        insert_event_signup_ticketdetails($global, $event_signup_id, $details);
        insert_custom_fields($global, $event_signup_id, $attendee_id, $user_id, $details);
    }//end of excel records for
    $status_message="Successfully inserted the records";
}//End of if
//insert record in event signup table

function insert_event_signup($global, $user_id, $details) {
    $Globali = new cGlobali();
    $common_functions = new functions();
    $event_id = $details[22];
    $user_email = $details[2];
    $name = $details[1];
    $address=str_replace('"',"'",$details[8]);
    $address =mysql_real_escape_string($address);
    
    $mobile_number = $details[3];
    $country_id = $common_functions->insert_country("India", $Globali);
    $state_id = $common_functions->insert_state($details[9], $country_id, $Globali);
    $city_id = $common_functions->insert_city($details[10], $state_id, $Globali);
    $pin_code = $details[11];
    $promo_code = '1R7T2L7C';
    $trascation_id = "import"; 
//"excelimpo";
    $signup_date = date('Y-m-d H:i:s');
    $payment_status = "Successful Transaction";
    $payment_type = "EBS";
    $payment_trasaction_type = "Verified";
    $qty = "1";
    $fees = "0";
    $damount = "500";
    $currency_id=1;
    $signup_query = "INSERT INTO EventSignup (UserId, EventId, SignupDt, Qty, Fees,  "
        . "DAmount,  Name, EMail, Phone, Address,CountryId,StateId,CityId , PIN,"
        . " PromotionCode, PaymentTransId, PaymentStatus, PaymentGateway,"
        . " eChecked, SettlementDate,  DepositedDate, source,CurrencyId) VALUES "
        . "( '" . $user_id . "','" . $event_id . "', '" . $signup_date . "', '" . $qty . "', '" . $fees . "', '" . $damount . "',  '" . $name . "', '" . $user_email . "', '" . $mobile_number . "', '" . $address . "', '" . $country_id . "', '" . $state_id . "', '" . $city_id . "', '" . $pin_code . "', '" . $promo_code . "','" . $trascation_id . "','" . $payment_status . "','" . $payment_type . "','" . $payment_trasaction_type . "','" . $signup_date . "','" . $signup_date . "','" . $trascation_id . "',".$currency_id.");";
    $insert_id = $global->ExecuteQueryId($signup_query);
    return $insert_id;
}

//To add entry in event signup ticket details
function insert_event_signup_ticketdetails($global, $event_signup_id, $details) {

    $qty = "1";
    $fees = $details[21];
    $ticket_id = $details[23];
    $promo_code = '1R7T2L7C';
    $discount_amount = $details[21];

    $event_ticket_query = "INSERT INTO eventsignupticketdetails (EventSignupId, TicketId,"
        . " NumOfTickets, TicketAmt, promoCode, Discount) VALUES ('" . $event_signup_id . "', '" . $ticket_id . "', '" . $qty . "', '" . $fees . "', '" . $promo_code . "', '" . $discount_amount . "')";
    $insert_id = $global->ExecuteQueryId($event_ticket_query);
    return $insert_id;
}

//To insert into attendes table
function insert_attendee_details($global, $event_signup_id, $details) {
    $user_email = $details[2];
    $name = $details[1];
    $company_name = $details[13];
    $mobile_number = $details[3];
    $ticket_id = $details[23];

    $attendee_query = "INSERT INTO Attendees (EventSIgnupId, Name, Company, Email, Phone, PaidBit,  checked_in,  ticketid) VALUES 
('" . $event_signup_id . "', '" . $name . "', '" . $company_name . "', '" . $user_email . "', '" . $mobile_number . "', '1',  '0',  '" . $ticket_id . "')";
    $insert_id = $global->ExecuteQueryId($attendee_query);
    return $insert_id;
}

//to bring the event custom fields ids
function event_custom_field_ids($event_id, $global) {
    $query = "SELECT Id,EventCustomFieldName FROM eventcustomfields WHERE EventId='" . $event_id . "'";
    $list = $global->SelectQuery($query);
    return $list;
}

//Insert the event related custom fields values 
function insert_custom_fields($global, $event_signup_id, $attendee_id, $user_id, $details) {
    $Globali = new cGlobali();
    $common_functions = new functions();
    $event_id = $details[22];
    $custom_fields=array();
    $custom_fields['name'] = $details[1];
    $custom_fields['user_name'] = $details[2];
//    $custom_fields['company_name'] = $details[13];

    $address=str_replace('"',"'",$details[8]);
    $custom_fields['address'] =mysql_real_escape_string($address);
    $custom_fields['mobile_number'] = $details[3];
    $country_id= $common_functions->insert_country("India", $Globali);
//    $custom_fields['state_id'] = $common_functions->insert_state($details[9], $country_id, $Globali);
//    $custom_fields['city_id'] = $common_functions->insert_city($details[10], $custom_fields['state_id'], $Globali);
    $custom_fields['state_id'] = $details[9];
    $custom_fields['city_id'] =$details[10];
    $custom_fields['pin_code'] = $details[11];
    $custom_fields['gender'] = $details[4];
    $custom_fields['dob'] = $details[5];
    $custom_fields['nationality']= $details[7];
    
    //event related custom fileds ids
    //local values
//    $custom_fields_ids=array("171980","171981","171983","171982","171984","171985","171986","171990","171981","171992");
    //stage values
//    $custom_fields_ids=array("345195","345196","345198","345197","345199","345200","345201","345204","345205","345206");
// $custom_fields_ids=array("345207","345208","345210","345209","345211","345212","345213","345218","345217","345216");
// 
// $custom_fields_ids=array("345179","345180","345182","345181","345183","345184","345185","345221","345222","345220"); 
 //Prod values
 $custom_fields_ids=array("401760","401761","401763","401762","401764","401765","401766","404164","404165","404166");
 
    $insert_string="";
 
    $index = 0;
    foreach($custom_fields as $value ){
        
        $insert_string.= " ('".$event_id."','".$event_signup_id."', '".$custom_fields_ids[$index]."', '".$value."', '".$user_id."', '".$attendee_id."'),";
        $index++;
    }

    $insert_string=substr($insert_string,0,-1);


//    $custom_fields_list = event_custom_field_ids($event_id, $global);

    $custom_field_query = "INSERT INTO  eventsignupcustomfields (EventId, EventSignupId,  EventCustomFieldsId, EventSignupFieldValue,UserId, attendeeId) VALUES ".$insert_string;
    $global->ExecuteQuery($custom_field_query);
}

//Check the user id exist are not
function get_user_id($email_id, $global) {

    $checkuser = "SELECT Id FROM user WHERE  "
        . "Email='" . $email_id . "'";
    return $global->GetSingleFieldValue($checkuser);
}

//insert the user
function insert_user_details($global, $details) {


    $common_functions = new functions();
    $Globali = new cGlobali();



    $user_name = $details[2];
    $password = md5(createPassword(8));
    $company_name = $details[13];
    $name = explode(" ", $details[1]);
//    $address =mysql_real_escape_string($details[8]);
    $address=str_replace('"',"'",$details[8]);
    $address =mysql_real_escape_string($address);
    $mobile_number = $details[3];
    $country_id = $common_functions->insert_country("India", $Globali);
    $state_id = $common_functions->insert_state($details[9], $country_id, $Globali);
    $city_id = $common_functions->insert_city($details[10], $state_id, $Globali);
    $pin_code = $details[11];
    $registration_date = date('Y-m-d H:i:s');
  

  $user_entry = "INSERT INTO user(UserName,Password,Email,Company,FirstName,LastName,Mobile,CountryId,StateId,CityId,Address,PIN,RegnDt) 
                                    VALUES('" . $user_name . "','" . $password . "','" . $user_name . "','" . $company_name . "','" . $name[0] . "','" . $name[1] . "','" . $mobile_number . "'," . $country_id . "," . $state_id . "," . $city_id . ",'" . $address . "','" . $pin_code . "','" . $registration_date . "')";
    $insert_id = $global->ExecuteQueryId($user_entry);
    return $insert_id;
}

function createPassword($length) {
    $chars = "234567890abcdefghijkmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
    $i = 0;
    $password = "";
    while ($i <= $length) {
        $password .= $chars{mt_rand(0, strlen($chars))};
        $i++;
    }
    return $password;
}

include 'templates/user_registration_tpl.php';
?>