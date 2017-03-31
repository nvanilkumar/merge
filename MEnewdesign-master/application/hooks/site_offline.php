<?php

if (!defined('BASEPATH'))
exit('No direct script access allowed');

/**
* Description of site_offline
*
* @author admin
*/
class Site_Offline {

	function __construct() {

	}

	public function is_offline() {
		if (file_exists(APPPATH . 'config/config.php')) {
			include(APPPATH . 'config/config.php');

			if (isset($config['is_offline'])) {
				if ($config['is_offline'] == 'COMPLETE') {
					$this->show_site_offline();
					exit;
				}
				else if ($config['is_offline'] == 'PUBLIC') {
					if (isset($config['remote_adder']) && $_SERVER['HTTP_X_FORWARDED_FOR'] != $config['remote_adder'] &&  $_SERVER['HTTP_X_FORWARDED_FOR'] != "183.82.4.87") {
						$this->show_site_offline();
						exit;
					}
				}
			}
		}
	}

	private function show_site_offline() {
		echo '<html>
		<head>
		<title>Site Under Maintenance</title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<link href="https://fonts.googleapis.com/css?family=Raleway:400,500,700" rel="stylesheet" type="text/css">
		<style type="text/css">
			.mainsection {margin:0 auto; width:600px; margin-top:20px; text-align: center; font-family: Raleway, sans-serif;}		
			.topText { font-size:28px; text-align:center; font-weight:700; text-transform:none; color:#f8485e; margin:0px 0 30px 0; } 
			.topText span {font-size: 30px; padding:10px 0; width:100%; float: left; text-align: center;}
			.bottomText { font-size:20px; text-align:center; font-weight:400; line-height: 30px; color:#363636; padding:10px 0px; margin:10px 0; }
			.bottomText span { font-weight: 500; padding-bottom: 10px;} 
			.imgGroup {margin:0 auto; }
			.brandText {text-align: center; padding: 40px 0 10px 0; font-size:20px; color: #5f259f; font-weight: 500;}
			b {font-weight: bold;}
		</style>
		</head>
		<body>

		<div class="mainsection">

			<div class="topText">
				<span>WOW!</span><br> Nice to see you at this hour.
			</div>

			<div class="imgGroup"><img src="http://static.meraevents.com/images/static/owls.png"></div>

			<div class="bottomText">
				<span>Our owls are at work</span><br> wake up to our new site with your <b>Hot Cuppa</b>
			</div>

			<div class="imgGroup"><img src="http://static.meraevents.com/images/static/teacup.png"></div>
			<div class="brandText"><img src="http://static.meraevents.com/images/static/me-logo.svg"></div>

		</div>

		</body>
		</html>';
	}

}

/* End of file site_offline.php */
/* Location: ./application/hooks/site_offline.php */
