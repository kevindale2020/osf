<?php

require_once('controller.php');

if($_SERVER['REQUEST_METHOD']=='POST') {

	$mobile = new MobileController();

	$username = $mobile->conn->real_escape_string($_POST['username']);
	$password = $mobile->conn->real_escape_string($_POST['password']);
	$password_encrypt = md5($password);

	$mobile->login($username,$password_encrypt);

}
?>