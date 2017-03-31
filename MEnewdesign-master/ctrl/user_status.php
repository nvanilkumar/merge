<?php
session_start();
include_once("MT/cGlobali.php");

$Global = new cGlobali();
include 'loginchk.php';

if($_POST['user_status']==="change"){
     $user_status=$_POST['user_status_value'];
     $user_id =$_POST['user_id'];   
    change_user_status($Global, $user_status, $user_id);
    echo "changed";
    exit;
}

//To Bring the user records
 
if ($_POST['submit_form'] == "Submit") {
    $UserNmae = $_POST['user_email'];
    $user_list_query = "SELECT id,email,name,status
            FROM user WHERE (username like '%" . $UserNmae . "%' or email like '%" . $UserNmae . "%')
            order by id desc limit 100";
    $user_list = $Global->SelectQuery($user_list_query);
}


//To change the user status
function change_user_status($Global, $user_status, $user_id) {

     $sqlauth = "update `user` set `status` = '".$user_status."' WHERE `id`=".$user_id; 
    $Global->ExecuteQuery($sqlauth);
     
}

include 'templates/user_status_tpl.php';
?>