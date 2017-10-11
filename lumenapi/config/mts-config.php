<?php

/*
 * MTS Project related config settings
 * 
 * 
 */
define('STATUS_OK', 200);
define('STATUS_UPDATED', 201);
define('STATUS_CREATED', 201);

define('STATUS_UNAUTHORIZED', 401);
define('STATUS_NO_DATA_FOUND', 404);

define('STATUS_PERMISSION_DENIED', 550);

return [
     "login" => [
        "failed" => "Invalid login details",
        "success" => "Successfully logged in"
    ],
    "devicetoken" => [
        "record_updated" => "",
        "record_created" => "Successfully inserted",
    ],
    "forgot_passowrd" => [
        "failed" => "Username does not exist for : ",
        "email_fail" => "Unable to send the email ",
        "success" => "Successfully sent the email",
    ],
    "change_password" => [
        "user_id_failed" => "Invalid user details or user id not found in our system",
        "password_failed" => "Invalid old password",
        "success" => "Success! Your Password has been changed!",
    ],
    "links" => [
        "zerorecords" => "No data available",
    ],
    "surveys" => [
        "user_id_failed" => "Invalid user id ",
        "zerorecords" => "no data available",
        "user_survey_status" => "Thanks for responding to our survey!"
    ],
    "events" => [
        "statusupdate" => "Successfully updated the user status",
        
    ],
    "topics" => [
        "category_id_fail" => "Invalid category id",
        "topic_id_fail" => "Invalid topic id",
        "comment_status" => "Successfully marked comment for reviewed"
        
    ],
];

