<?php
session_start();

if (!session_is_registered("login_log_id") ) {
	session_register("login_log_id");
}
///Session start for Backend User//
if (!session_is_registered("backend_id") ) {
	session_register("backend_id");
}
/////////////////////////////////
if (!session_is_registered("login_user_id") ) {
	session_register("login_user_id");
}

if (!session_is_registered("login_poc_name") ) {
	session_register("login_poc_name");
}

if (!session_is_registered("login_user_type") ) {
	session_register("login_user_type");
}

if (!session_is_registered("login_user_name") ) {
	session_register("login_user_name");
}

if (!session_is_registered("login_partner_name") ) {
	session_register("login_partner_name");
}
if (!session_is_registered("login_pwd") ) {
	session_register("login_pwd");
}
?>