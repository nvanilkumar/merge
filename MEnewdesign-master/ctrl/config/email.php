<?php
 // gmail smtp settings

//$config['protocol'] = 'smtp';
//$config['smtp_host'] = 'ssl://smtp.gmail.com';
//$config['smtp_port'] = '465';
//$config['smtp_timeout'] = '7';
//$config['smtp_user'] = 'email.qison@gmail.com';
//$config['smtp_pass'] = 'qison123';
//$config['charset'] = 'utf-8';
//$config['newline'] = "\r\n";
//$config['mailtype'] = 'text'; // or html
//$config['validation'] = TRUE; // bool whether to validate email or not 

// mandril smtp settings 

$config['protocol'] = 'smtp';
$config['smtp_host'] = 'smtp.mandrillapp.com';
$config['smtp_port'] = '587';
$config['smtp_timeout'] = '7';
$config['smtp_user'] = 'admin@meraevents.com';
$config['smtp_pass'] = '514cc489-5519-40d1-8f15-dc0721d083f2';
$config['charset'] = 'utf-8';
$config['newline'] = "\r\n";
$config['crlf'] = "\r\n";
$config['mailtype'] = 'html'; // or html
$config['validation'] = TRUE; // bool whether to validate email or not 
?>

