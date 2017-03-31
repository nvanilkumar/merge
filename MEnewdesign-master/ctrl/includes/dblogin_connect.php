<?php
	$connect = mysql_connect(DB_SERVER,DB_SERVER_USERNAME,DB_SERVER_PASSWORD) or die("Could not connect to databse!");
	$database = mysql_select_db(DB_DATABASE,$connect);
?>