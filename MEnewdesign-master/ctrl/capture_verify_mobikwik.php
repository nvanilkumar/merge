<?php

session_start();



include_once("MT/cGlobali.php");
//error_reporting(-1);
$Global = new cGlobali();
include 'loginchk.php';
$page = 'Mobikwik';
include_once 'excel_reader.php';
include_once("includes/common_functions.php");
$common=new functions();

//To Bring the user records



if ($_POST['submit_form'] == "Submit") {

    $file_name = "userfiles/" . $_FILES["mobikwik_doc"]["name"];
    $SDt = $_POST['transaction_date'];
    $SDtExplode = explode("/", $SDt);
    $trasaction_date = $SDtExplode[2] . '-' . $SDtExplode[1] . '-' . $SDtExplode[0] . ' 00:00:00';
    $trasaction_date =$common->convertTime($trasaction_date, DEFAULT_TIMEZONE);

    //Uploads the file 
    move_uploaded_file($_FILES["mobikwik_doc"]["tmp_name"], $file_name);

    //read the excel dat
    $excel_reader = new excel_reader($page);
    $mobikwik_captured_data = $excel_reader->read_data($file_name);
    //Removes the uploaded file
    unlink($file_name);
    $mobikwik_capture_array = $mobikwik_commission_array = array();
    //$mobikwik_refund_array = array();
 
$order_loglist=array();
    foreach ($mobikwik_captured_data as $key => $mobikwik_record) {
        // print_r($mobikwik_record);
        $isvalid=$batch_number=trim(substr($mobikwik_record[1],1));
//        $isvalid=preg_match('/^BATCH\s[0-9]+$/', $batch_number);    
        if (!is_null($batch_number) && !empty($batch_number) 
              && (is_numeric($isvalid) && $isvalid>0) && (is_numeric(substr($mobikwik_record[0],1)))) {
            $mobikwik_capture_array[] = str_replace('"', '', substr($mobikwik_record[0],1));
            $mobikwik_commission_array[substr($mobikwik_record[0],1)]=$mobikwik_record[2];
        }
//        else if ($ebs_record[3] == "REFUNDED") {
//            $ebs_refund_array[] = $ebs_record[2];
//        }
        
        else if(!is_numeric(substr($mobikwik_record[0],1))){
//            $order_loglist[][0]=substr($mobikwik_record[0],1);
            $order_loglist[substr($mobikwik_record[0],1)][1]=substr($mobikwik_record[1],1);
            $order_loglist[substr($mobikwik_record[0],1)][2]=$mobikwik_record[2];
           $order_array[]="'".substr($mobikwik_record[0],1)."'";
        }
    }
    
      if(count($order_array)> 0){
           $orderidList=implode(',',$order_array);
            $signup_array=get_eventids_by_orderlog($Global, $orderidList);
            foreach ($signup_array as $row){
                $mobikwik_capture_array[] = $row['eventsignup'];
                $mobikwik_commission_array[$row['eventsignup']]=$order_loglist[$row['orderid']][2];
            }
      }

    //Process only 200 records at a time which are in  Captured state
    $mobikwik_records_count = count($mobikwik_capture_array);
    $process_records_count = 200; //
    $mobikwik_loop_count = ceil($mobikwik_records_count / $process_records_count);
    $update_event_singup_id_array = array();
    for ($i = 0; $i < $mobikwik_loop_count; $i++) {
        $start_point = $i * $process_records_count;

        $event_singup_ids = implode(',', array_slice($mobikwik_capture_array, $start_point, $process_records_count));
        $captured_list = meraevents_capturred_records($Global, $event_singup_ids);
        $capture_event_singup_ids = "";
        foreach ($captured_list as $list) {

            if ($list['eChecked'] === "Captured") {
                $capture_event_singup_ids.=$list['Id'] . ",";
                //for status message
                $update_event_singup_id_array[] = $list['Id'];
            }/* else if($list['eChecked'] ==="Refunded"){
              $refund_event_singup_ids.=$list['Id'] . ",";
              $meraevents_refund_event_singup_id_array[]=$list['Id'];
              } */
        }
        //Processing the records, which having the status of capture in (Me db & mobikwik side
        $capture_event_singup_ids = substr($capture_event_singup_ids, 0, -1);
        if (strlen($capture_event_singup_ids) > 1) {
            $status = 'Verified';
            change_event_signup_status($Global, $capture_event_singup_ids, $status, $trasaction_date, $mobikwik_commission_array);
        }
        //Processing the records, which having the status of capture in (Ebs) 
        //& In meraevents side as refunded state
        /* $refund_event_singup_ids = substr($refund_event_singup_ids, 0, -1);
          if (strlen($refund_event_singup_ids) > 1) {
          change_event_signup_deposited_date($Global, $refund_event_singup_ids, $trasaction_date);
          } */
    }//end of capture records for loop
    //Processing Refund records
    /* $ebs_refund_records_count = count($ebs_refund_array);
      $ebs_refund_loop_count = ceil($ebs_refund_records_count / $process_records_count);
      $updated_refund_event_singup_id_array = array();
      for ($i = 0; $i < $ebs_refund_loop_count; $i++) {
      $start_point = $i * $process_records_count;

      $event_singup_ids = implode(',', array_slice($ebs_refund_array, $start_point, $process_records_count));
      $refund_list = meraevents_capturred_refund_records($Global, $event_singup_ids);
      $updated_refund_event_singup_id = "";
      foreach ($refund_list as $list) {
      if(($list['eChecked'] === "Refunded") and ($list['SettlementDate'] !=='0000-00-00 00:00:00')
      and ($list['DepositedDate'] !=='0000-00-00 00:00:00')){
      $meraevents_refund_event_singup_id.=$list['Id'] . ",";
      $meraevents_refund_event_singup_id_array[] = $list['Id'];


      }else {
      $updated_refund_event_singup_id.=$list['Id'] . ",";
      $updated_refund_event_singup_id_array[] = $list['Id'];

      }
      }

      //removes the last character
      $updated_refund_event_singup_id = substr($updated_refund_event_singup_id, 0, -1);
      if (strlen($updated_refund_event_singup_id) > 1) {
      $status = 'Refunded';
      change_event_signup_status($Global, $updated_refund_event_singup_id, $status, $trasaction_date);
      }
      //processing the records which status as reunded in me side & change it's SettlementDate
      $meraevents_refund_event_singup_id = substr($meraevents_refund_event_singup_id, 0, -1);
      if (strlen($meraevents_refund_event_singup_id) > 1) {
      $status = 'Refunded';
      change_event_signup_settlement_date($Global, $meraevents_refund_event_singup_id,$trasaction_date);
      }
      }
     */
    $capture_status = print_status_message($mobikwik_capture_array, $update_event_singup_id_array, 'captured', $meraevents_refund_event_singup_id_array, $Global);

    //$refund_status = print_status_message($mobikwik_refund_array, $updated_refund_event_singup_id_array,'refunded',$meraevents_refund_event_singup_id_array);
}//End of the submit form if
//To print the event signup status information
//@ $capture_array excel data 
//@ $update_event_singup_id_array updated event singup ids
function print_status_message($capture_array, $update_event_singup_id_array, $type, $settlement_date_change_array, $Global) {
    $total = count($capture_array);
    $status_message = "";
    if ($total == count($update_event_singup_id_array)) {
        $status_message = " Updated " . $total . " " . $type . " records sucessfully";
    } else {
        if (!is_array($update_event_singup_id_array)) {
            $update_event_singup_id_array = array();
        }
        $result = array_diff($capture_array, $update_event_singup_id_array);
        //$result2 = array_diff($result, $settlement_date_change_array);
        $list_ids = implode(", ", $result);

        $signup_list_ids = '';
        if ($list_ids != '') {
            $signup_list_ids = get_eventids_by_signupids($Global, $list_ids);
        }

        $list_signup_and_event_ids = "";
        if (count($signup_list_ids) > 0) {

            $res = array();
            foreach ($signup_list_ids as $listids => $val) {
                $res[$val['Id']] = $val['EventId'];
            }

            foreach ($result as $rskey => $rsvalue) {
                $ev_signup_ids = isset($res[$rsvalue]) ? $res[$rsvalue] : '-';
                $list_signup_and_event_ids.=$rsvalue . " : " . $ev_signup_ids . ', ';
            }
        } else {
            $list_signup_and_event_ids = $list_ids;
        }
        $list_signup_and_event_ids = rtrim($list_signup_and_event_ids, ', ');


        $status_message = "Not  Updated  this ids " . $list_signup_and_event_ids;
    }
    return $status_message;
}

//To bring the captured event signup recrods from our db
function meraevents_capturred_records($Global, $event_singup_ids) {
    $captured_query = "select id as Id, paymentstatus As eChecked, settlementdate AS SettlementDate, depositdate AS DepositedDate
                     FROM eventsignup where id in (" . $event_singup_ids . ") and (paymentstatus='Captured') ";
    return $Global->SelectQuery($captured_query);
}

//To change the event signup  status & deposited date
function change_event_signup_status($Global, $event_singup_id, $status, $date_time, $comm = array()) {
//    $date_time = date('Y-m-d H:i:s');
    $sqlauth = "update eventsignup set paymentstatus='" . $status . "', depositdate='" . $date_time . "'";
    if (count($comm) > 0) {
        $sqlauth .=", gatewaycommission=CASE ";
        foreach ($comm as $key => $value) {
            $sqlauth .=" WHEN id=" . $key . " THEN " . $value;
        }
        $sqlauth .=" END";
    }
    $sqlauth .= " WHERE id in (" . $event_singup_id . ")";
    $Global->ExecuteQuery($sqlauth);
}

//To change the event signup ids  & deposited date
function change_event_signup_deposited_date($Global, $event_singup_id, $date_time) {

    $sqlauth = "update eventsignup set depositdate='" . $date_time . "'  
                WHERE id in (" . $event_singup_id . ")";
    $Global->ExecuteQuery($sqlauth);
}

//To change the event signup ids  & deposited date
function change_event_signup_settlement_date($Global, $event_singup_ids, $date_time) {
    $sqlauth = "update eventsignup set settlementdate='" . $date_time . "'  
                WHERE id in (" . $event_singup_ids . ")";
    $Global->ExecuteQuery($sqlauth);
}

function get_eventids_by_signupids($Global, $eventsingupids) {
    $event_singup_ids_query = "select id as Id,eventid as EventId from eventsignup where id in (" . $eventsingupids . ")";
    return $Global->SelectQuery($event_singup_ids_query);
}

$SDt = date("d/m/Y", mktime(0, 0, 0, date("m"), (date("d") - 1), date("Y")));
$refund_date_change_messages = '';
if (count($meraevents_refund_event_singup_id_array) > 0) {
    $refund_date_change_messages = "Refund Updates for " . implode(",", $meraevents_refund_event_singup_id_array);
}

//to bring the order log related signup id
function get_eventids_by_orderlog($Global, $eventsingupids) {
    //select orderid from orderlog where eventsignup in (520717,520123);
     $event_singup_ids_query = "select eventsignup, orderid from orderlog where orderid in (" . $eventsingupids . ")";
    return $Global->SelectQuery($event_singup_ids_query);
}


include 'templates/capture_verify_mobikwik_tpl.php';
