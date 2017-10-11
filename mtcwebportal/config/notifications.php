<?php

return [
    //Firebase Cloud Messaging Required Fields
    'fcm' => [
                'url' => 'https://fcm.googleapis.com/fcm/send',
                'app_key' => 'AIzaSyAG-5CEfh7nTzoej1V_EUwE6K-d-VnDGQw'
    ],
    //Apple Push Notifications Required Fields
    'apn' =>[
        'host' => 'gateway.sandbox.push.apple.com',	//Sandbox URL to send to
	'port' => 2195,	
        'cert' => storage_path() .  DIRECTORY_SEPARATOR .'cert'. DIRECTORY_SEPARATOR .'apns-dev-cert.pem',//Absolute path to dev cert file	
        'cert_pass' => '' //Password for certificate file
    ]
];

