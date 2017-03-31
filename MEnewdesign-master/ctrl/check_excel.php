<?php

session_start();
include_once("MT/cGlobal.php");
error_reporting(1);
$Global = new cGlobal();
include 'loginchk.php';
include_once 'excel_reader.php';


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
    $ebs_captured_data = $excel_reader->read_data($file_name);

    echo '<pre>'; print_r($ebs_captured_data);exit;
    
    foreach ($ebs_captured_data as $captured_data) {
        //echo '--'.is_numeric($captured_data[3]).'--'.'<br>';
        $name = $captured_data[1].' '.$captured_data[2];
        $email = '';
        //$captured_data[0] = strtotime($captured_data[0]);
        //echo $captured_data[0].' '.$captured_data[0].'<br>';
        if($captured_data[4] != '') {
            $email = $captured_data[4];
        } else {
            $email = $captured_data[3];
        }
        if($email != '') {
            $captured_query = "INSERT INTO pminationalconf (memberId,experiation_date,certificateId,Name,Email,Mobile,MembershipStatus) "
                    . "VALUES (0,'" . $captured_data[0] . "',0,'".$name."','".$email."',0,'Member')";
    //    //echo $captured_query;exit;
            $Global->SelectQuery($captured_query);
        }
    }
    echo 'Success';exit;
    $data = meraevents_capturred_refund_records($Global, implode(',', $signup_ids));
//    print_r($data);
    $resumt_array = array();
    $i = 0;
    echo "<table>";
    echo "<tr><td>Signup Id</td><td>Transaction Id</td><td>Checked</td></tr>";
    foreach($data as $value) {
        echo "<tr>";
        echo "<td>".$resumt_array[$i]['Id'] = $value['Id']."</td>";
        echo "<td>".$resumt_array[$i]['PaymentTransId'] = $value['PaymentTransId']."</td>";
        echo "<td>".$resumt_array[$i]['eChecked'] = $value['eChecked']."</td>";
        $i++;
        echo "</tr>";
    }
    echo "</table>";
    //print_r($resumt_array);
    exit;
}//End of the submit form if
//To bring the captured event signup recrods from our db
function meraevents_capturred_refund_records($Global, $event_singup_ids) {
    $captured_query = "select Id,PaymentTransId,eChecked
                     from EventSignup where Id in (" . $event_singup_ids . ") AND PaymentTransId = 'A1' ";
    //echo $captured_query;exit;
    return $Global->SelectQuery($captured_query);
}

include 'templates/check_excel.tpl.php';
?>