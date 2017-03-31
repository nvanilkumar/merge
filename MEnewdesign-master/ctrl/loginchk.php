<?php

if (!isset($_SESSION)) {
    session_start();
}

include_once("MT/cGlobali.php");
include_once("MT/cUser.php");

$Global = new cGlobali();
$msgLogin = "";

function adminLoginCheck() {
    global $Global;
    // Check if all session variables are set 
    if (isset($_SESSION['uid'], $_SESSION['UserName'], $_SESSION['login_string'])) {

        $user_id = $_SESSION['uid'];
        $login_string = $_SESSION['login_string'];
        $username = $_SESSION['UserName'];

        // Get the user-agent string of the user.
        $user_browser = $_SERVER['HTTP_USER_AGENT'];

        $loginQry = "SELECT `password` FROM user WHERE `id` = '" . $Global->dbconn->real_escape_string($user_id) . "' AND usertype IN ('admin','superadmin') LIMIT 1";
        if ($rec = $Global->SelectQuery($loginQry)) {


            if (count($rec) == 1) {
                // If the user exists get variables from result.
                $password = $rec[0]['password'];
                $login_check = hash('sha512', $password . $user_browser);

                if ($login_check == $login_string) {
                    // Logged In!!!! 
                    return true;
                } else {
                    // Not logged in 
                    return false;
                }
            } else {
                // Not logged in 
                return false;
            }
        } else {
            // Not logged in 
            return false;
        }
    } else {
        // Not logged in 
        return false;
    }
}

if (!adminLoginCheck())
    header("Location: login.php");
?>