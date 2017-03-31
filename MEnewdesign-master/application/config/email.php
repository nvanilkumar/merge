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

// mandrial smtp settings 
$config['protocol'] = 'smtp';
$config['smtp_host'] = 'smtp.mandrillapp.com';
$config['smtp_port'] = '587';
$config['smtp_timeout'] = '7';
/*$config['smtp_user'] = 'admin@meraevents.com';
$config['smtp_pass'] = 'fUJS6m2L5FfJL0-O0kzOiA';*/
//MND PROD DETAILS
$config['smtp_user']='admin@meraevents.com';
$config['smtp_pass'] ='ryjKRcD0lIxIEM20X0N-OA';
$config['charset'] = 'utf-8';
$config['newline'] = "\r\n";
$config['crlf'] = "\r\n";
$config['mailtype'] = 'html'; // or html
$config['validation'] = TRUE; // bool whether to validate email or not 
?>

