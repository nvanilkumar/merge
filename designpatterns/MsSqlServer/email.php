<?php
error_reporting(E_ALL ^ E_NOTICE);
$to      = 'yoursanil22@gmail.com';
$subject = 'Fake sendmail test';
$message = 'If we can read this, it means that our fake Sendmail setup works!';
$headers = 'From: myemail@egmail.com' . "\r\n" .
           'Reply-To: myemail@gmail.com' . "\r\n" .
           'X-Mailer: PHP/' . phpversion();

if(mail($to, $subject, $message, $headers)) {
    echo 'Email sent successfully!';
} else {
    print_r(error_get_last());
    die('Failure: Email was not sent!');
}
?>
