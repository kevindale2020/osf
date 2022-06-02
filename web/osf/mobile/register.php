<?php

require_once('controller.php');

if($_SERVER['REQUEST_METHOD']=='POST') {

	$mobile = new MobileController();

	$fname = $mobile->conn->real_escape_string($_POST['fname']);
	$lname = $mobile->conn->real_escape_string($_POST['lname']);
	$address = $mobile->conn->real_escape_string($_POST['address']);
	$email = $mobile->conn->real_escape_string($_POST['email']);
	$contact = $mobile->conn->real_escape_string($_POST['contact']);
	$username = $mobile->conn->real_escape_string($_POST['username']);
	$password = $mobile->conn->real_escape_string($_POST['password']);
	$password_encrypted = md5($password);
	$created_date = date('Ymd');

	$mobile->register($fname,$lname,$address,$email,$contact,$username,$password_encrypted,$created_date);

}
?>